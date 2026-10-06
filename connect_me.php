<?php 

class DB {
public static function &conn() {
    $cfg = json_decode(file_get_contents('dbconfig.json', true));
    $conn = NULL;
    if ($conn == NULL) {
        try {
            $conn = new PDO('mysql:host=' . $cfg->server . ';dbname=' . $cfg->dbname, $cfg->user, $cfg->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            echo 'PDO Error: ' . $e->getMessage();
        }
    }
    return $conn;
}

}