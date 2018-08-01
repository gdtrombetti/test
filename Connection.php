<?php

class Connection
{
    private $conn;
    private static $_instance;

    public function __construct()
    {
        $env = json_decode(file_get_contents('config.json'), true);
        $user = $env['DB']['USER'];
        $pass = $env['DB']['PASS'];
        $end_pt = $env['DB']['HOST'];

        $conn = mysqli_connect($end_pt, $user, $pass, $env['DB']['NAME']);

        if (!$conn) {
            trigger_error("Failed to connect to MySQL: ",
                E_USER_ERROR);
        }

        $this->conn = $conn;
    }

    public static function getInstance() {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function getConnection() {
        return $this->conn;
    }
}