<?php
require_once 'Connection.php';

class ContentController
{
    private $mysqli;
    private $db;
    function __construct()
    {
        $this->db = Connection::getInstance();
        $this->mysqli = $this->db->getConnection();
    }

    public function getContent()
    {
        $result = $this->queryContent();
        $resultArray = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                \array_push($resultArray, $row);
            }
        }
        if (!$result) {
            return "Error: " . mysqli_error($this->mysqli);
        }
        return $resultArray;
    }

    private function queryContent ()
    {
        return $this->mysqli->query(
            'SELECT * FROM CONTENT');
    }
}