<?php

require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = mysqli_init();
$db->real_connect(
    $config['db_host'],
    $config['db_user'],
    $config['db_pass'],
    $config['db_name'],
    3306,
    null,
    MYSQLI_CLIENT_SSL
);

$db->set_charset('utf8mb4');
