<?php

namespace TurboEngine\Core;

use TurboEngine\Query\QueryEngine;
use TurboEngine\Query\QueryProfiler;
use TurboEngine\Query\QueryPredictor;
use TurboEngine\Query\QueryOptimizer;
use TurboEngine\Response\ResponseEngine;
use TurboEngine\Response\FragmentCache;
use TurboEngine\Response\Compressor;
use TurboEngine\Response\Streamer;
use TurboEngine\Response\PredictiveRenderer;
use TurboEngine\Async\AsyncEngine;
use TurboEngine\Async\WorkerPool;
use TurboEngine\Async\LazyLoader;
use TurboEngine\Async\JobOptimizer;
use TurboEngine\Optimization\Profiler;
use TurboEngine\Optimization\AutoTuner;
use TurboEngine\Optimization\TrafficAnalyzer;
use TurboEngine\Optimization\HotMemoryManager;
use TurboEngine\ML\TrafficPredictor;
use TurboEngine\ML\QueryPatternAnalyzer;
use TurboEngine\ML\ResponsePatternAnalyzer;
use TurboEngine\Helpers\Logger;
use TurboEngine\Helpers\MetricsHelper;
use TurboEngine\Helpers\CacheHelper;

class Engine
{
    public ConfigManager $config;
    public MemoryManager $memory;
    public EventManager $events;

    public QueryEngine $query;
    public QueryProfiler $queryProfiler;
    public QueryPredictor $queryPredictor;
    public QueryOptimizer $queryOptimizer;

    public ResponseEngine $response;
    public FragmentCache $fragmentCache;
    public Compressor $compressor;
    public Streamer $streamer;
    public PredictiveRenderer $predictiveRenderer;

    public AsyncEngine $async;
    public WorkerPool $workerPool;
    public LazyLoader $lazyLoader;
    public JobOptimizer $jobOptimizer;

    public Profiler $profiler;
    public AutoTuner $autoTuner;
    public TrafficAnalyzer $trafficAnalyzer;
    public HotMemoryManager $hotMemoryManager;

    public TrafficPredictor $trafficPredictor;
    public QueryPatternAnalyzer $queryPatternAnalyzer;
    public ResponsePatternAnalyzer $responsePatternAnalyzer;

    public MetricsHelper $metrics;

    public function __construct(array $config = [])
    {
        $this->config = new ConfigManager($config);
        $this->memory = new MemoryManager($this->config);
        $this->events = new EventManager($this->memory);

        // Query modules
        $this->query = new QueryEngine($this->memory, $this->events);
        $this->queryProfiler = new QueryProfiler($this->memory);
        $this->queryPredictor = new QueryPredictor($this->memory);
        $this->queryOptimizer = new QueryOptimizer();

        // Response modules
        $this->response = new ResponseEngine($this->memory, $this->events);
        $this->fragmentCache = new FragmentCache($this->memory);
        $this->compressor = new Compressor();
        $this->streamer = new Streamer();
        $this->predictiveRenderer = new PredictiveRenderer($this->memory);

        // Async modules
        $this->async = new AsyncEngine($this->memory, $this->events);
        $this->workerPool = new WorkerPool($this->config->get('workers.pool_size', 16));
        $this->lazyLoader = new LazyLoader($this->memory);
        $this->jobOptimizer = new JobOptimizer($this->events);

        // Optimization modules
        $this->profiler = new Profiler($this->memory);
        $this->autoTuner = new AutoTuner($this->memory);
        $this->trafficAnalyzer = new TrafficAnalyzer($this->memory);
        $this->hotMemoryManager = new HotMemoryManager($this->memory);

        // ML modules
        $this->trafficPredictor = new TrafficPredictor($this->memory);
        $this->queryPatternAnalyzer = new QueryPatternAnalyzer($this->memory);
        $this->responsePatternAnalyzer = new ResponsePatternAnalyzer($this->memory);

        // Helpers
        $this->metrics = new MetricsHelper($this->memory);

        // Start engine
        $this->events->dispatch('engine.start');
        Logger::log('info', 'TurboEngine fully initialized and all modules bootstrapped.');
    }

    public function run(): void
    {
        // Auto-tune on startup
        $this->autoTuner->tune();

        // Predictive preloading
        if ($this->config->get('predictive.query_prefetch', true)) {
            $recentQueries = $this->queryPatternAnalyzer->analyze($this->memory->get('recent_queries', []));
            $this->queryPredictor->prefetch(array_keys($recentQueries));
        }

        if ($this->config->get('predictive.response_preload', true)) {
            $fragments = $this->memory->get('hot_views', []);
            $this->predictiveRenderer->preload($fragments);
        }

        // Analyze traffic
        if ($this->config->get('predictive.traffic_prediction', true)) {
            $traffic = $this->memory->get('recent_hits', []);
            $trend = $this->trafficAnalyzer->analyze($traffic);
            $this->trafficPredictor->predict($trend);
        }

        Logger::log('info', 'TurboEngine run complete with predictive preloading and auto-tuning.');
    }
}
