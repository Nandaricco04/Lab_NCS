<?php

function get_pg_connection(): PgSql\Connection {
    static $conn = null;
    if ($conn instanceof PgSql\Connection) {
        return $conn;
    }

    $connStr = "host=localhost port=5432 dbname=Ncs user=postgres password=bakmi1 options='--client_encoding=UTF8'";
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

function jumlahUsers() {
    $sql = 'SELECT COUNT(*) AS total FROM users';
    $res = q($sql);
    return pg_fetch_result($res, 0, 'total');
}

function jumlahPengabdian() {
    $sql = 'SELECT COUNT(*) AS total FROM pengabdian';
    $res = q($sql);
    return pg_fetch_result($res, 0, 'total');
}

function jumlahGaleri() {
    $sql = 'SELECT COUNT(*) AS total FROM agenda_kegiatan';
    $res = q($sql);
    return pg_fetch_result($res, 0, 'total');
}

function kurangiStok($id_sarana, $jumlah) {
    $sql = "UPDATE sarana_prasarana 
            SET stok = stok - $1 
            WHERE id_sarana = $2";

    qparams($sql, [$jumlah, $id_sarana]);
}

function tambahStok($id_sarana, $jumlah) {
    $sql = "UPDATE sarana_prasarana 
            SET stok = stok + $1 
            WHERE id_sarana = $2";

    qparams($sql, [$jumlah, $id_sarana]);
}

function getStok($id_sarana) {
    $sql = "SELECT stok FROM sarana_prasarana WHERE id_sarana = $1";
    $res = qparams($sql, [$id_sarana]);
    $row = pg_fetch_assoc($res);
    return $row['stok'] ?? 0;
}

function jumlahPeminjaman() {
    $res = q('SELECT COUNT(*) AS total FROM peminjaman');
    $row = pg_fetch_assoc($res);
    return $row['total'] ?? 0;
}


