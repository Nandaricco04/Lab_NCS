<?php

function get_pg_connection(): PgSql\Connection {
    static $conn = null;
    if ($conn instanceof PgSql\Connection) {
        return $conn;
    }

    $connStr = "host=localhost port=5432 dbname=Ncs user=postgres password=cerr2407 options='--client_encoding=UTF8'";
    $conn = @pg_connect($connStr);

    if (!$conn) {
         $err = pg_last_error() ?: 'Unknown error from pg_connect';
        throw new RuntimeException("Koneksi PostgreSQL gagal. Periksa host/port/db/user/pass & ekstensi pgsql.");
        throw new RuntimeException("Koneksi PostgreSQL gagal: " . $err);
    }
    return $conn;
}

function qparams(string $sql, array $params) {
    $conn = get_pg_connection();
    $res = @pg_query_params($conn, $sql, $params);
    if ($res === false) {
        throw new RuntimeException("Query gagal: " . pg_last_error($conn));
        
    }
    return $res;
}

function q(string $sql) {
    $conn = get_pg_connection();
    $res = @pg_query($conn, $sql);
    if ($res === false) {
        throw new RuntimeException("Query gagal: " . pg_last_error($conn));
    }
    return $res;
}

?>