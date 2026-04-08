<?php
require_once 'db_connect.php';

$tables_query = $conn->query("SHOW TABLES");
$schema = [];

while($table_row = $tables_query->fetch_array()) {
    $table = $table_row[0];
    $columns_query = $conn->query("SHOW COLUMNS FROM `$table`");
    $columns = [];
    while($col_row = $columns_query->fetch_assoc()) {
        $columns[] = $col_row;
    }
    $schema[$table] = $columns;
}

echo json_encode($schema, JSON_PRETTY_PRINT);
?>
