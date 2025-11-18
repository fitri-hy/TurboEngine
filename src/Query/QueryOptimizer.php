<?php

namespace TurboEngine\Query;

use Illuminate\Support\Facades\DB;

class QueryOptimizer
{
    public function optimize(string $sql): string
    {
        // Optimasi nyata: tambahkan SQL_NO_CACHE, limit default jika tidak ada, dan gunakan index hint jika ada
        if (!preg_match('/LIMIT\s+\d+/i', $sql)) {
            $sql .= ' LIMIT 1000';
        }

        $sql = preg_replace('/SELECT \*/i', 'SELECT SQL_NO_CACHE *', $sql);

        // Contoh index hint otomatis
        if (preg_match('/FROM\s+(\w+)/i', $sql, $matches)) {
            $table = $matches[1];
            $sql .= " USE INDEX (PRIMARY)";
        }

        return $sql;
    }

    public function execute(string $sql)
    {
        $sql = $this->optimize($sql);
        return DB::select($sql);
    }
}
