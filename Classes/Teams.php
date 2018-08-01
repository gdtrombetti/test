<?php
require_once 'Connection.php';
require_once 'Response.php';

class Teams
{
    private $mysqli;
    private $db;
    private $response;
    private $ACCECTABLE_COLUMNS = ["NAME", "COLOR", "CITY"];
    function __construct()
    {
        $this->db = Connection::getInstance();
        $this->mysqli = $this->db->getConnection();
        $this->response = new Response();
    }

    public function getTeams()
    {
        $result = $this->queryTeams();
        $resultArray = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                \array_push($resultArray, $row);
            }
        }
        if (!$result) {
            $this->response->set('ERROR', "Error: " . mysqli_error($this->mysqli), []);
            return $this->response;
        }
        $this->response->set('SUCCESS', null, $resultArray);
        return $this->response;
    }

    public function addTeams(Array $request)
    {
        $resultArray = array();
        $headerRow = $request[0];
        foreach($headerRow as $header) {
            if (!\in_array($header, $this->ACCECTABLE_COLUMNS)) {
                \array_push($resultArray, "Error: Supplied Header {$header} not an acceptable table column.");
            }
        }
        if (count($resultArray) > 0) {
            header('HTTP/1.1 500 Internal Server');
            $this->response->set('ERROR', null, $resultArray);
            return $this->response;
        }
        try {
            mysqli_autocommit($this->mysqli,FALSE);

            $colorColumn = null;
            $newInsertArray = array();
            foreach ($request as $key => $row) {
                foreach ($row as $col => $columns) {
                    if ($columns === 'COLOR') {
                        $row[$col] = 'TEAM_ID';
                        $colorColumn = $col;
                        break;
                    }
                    if ($colorColumn === $col) {
                        $row[$colorColumn] = $this->fillInfoTableWithColor($row[$colorColumn]);
                    }
                }
                \array_push($newInsertArray, $row);
            }
            $columns = $newInsertArray[0];
            $updatedColumns = \count($columns);
            $values =  str_repeat('?, ', $updatedColumns - 1) . '?';
            unset($newInsertArray[0]);
            $dataArray = array_values($newInsertArray);
            if ($updatedColumns > 0) {
                foreach ($dataArray as $key => $data) {
                    $sql = "INSERT INTO TEAMS( ". implode(', ',$columns) . ") VALUES ({$values})";
                    $statement = $this->mysqli->prepare($sql);
                    $bindTypes = '';
                    foreach($data as $rowKey => $row) {
                        if (gettype($row) === 'string') {
                            $bindTypes .= 's';
                        } elseif (gettype($row) === 'integer') {
                            $bindTypes .= 'i';
                        }
                    }
                    $statement->bind_param($bindTypes, ...$data);
                    $statement->execute();
                    $statement->close();
                }
            }
            mysqli_autocommit($this->mysqli,TRUE);
        } catch (Exception $e) {
            mysqli_rollback($this->mysqli);
        }
        $result = $this->queryTeams();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                \array_push($resultArray, $row);
            }
        }
        if (!$result) {
            header('HTTP/1.1 500 Internal Server');
            $this->response->set('ERROR', "Error: " . mysqli_error($this->mysqli), []);
        }
        $this->response->set('SUCCESS', null, $resultArray);
        return $this->response;
    }

    private function queryTeams () {
        return $this->mysqli->query(
            'SELECT T.ID as ID, T.NAME as NAME, T.CITY as CITY, I.COLOR as COLOR
FROM TEAMS T INNER JOIN INFO I ON T.TEAM_ID = I.ID');
    }

    private function fillInfoTableWithColor($color): int
    {
        $column = 'COLOR';
        $teamId = null;
        try {
            // Not Sure if this is best practice for transactions
            mysqli_autocommit($this->mysqli,FALSE);
            $statement = $this->mysqli->prepare("INSERT INTO INFO ({$column}) VALUES (?)");
            $statement->bind_param('s', $color);
            $statement->execute();
            if ($statement->affected_rows > 0) {
                $teamId = $statement->insert_id;
            }
            $statement->close();
            mysqli_autocommit($this->mysqli,TRUE);

        }  catch (Exception $e) {
            mysqli_rollback($this->mysqli);
        }
        return $teamId;
    }

    public function deleteTeam(int $id)
    {
        $resultArray = array();
        if ($id === null) {
            $this->response->set('ERROR', "Error:  Team ID not provided.", []);
            header('HTTP/1.1 500 Internal Server');
            return $this->response;
        }
        $team = $this->getSingleTeamById($id);
        if ($team === null) {
            $this->response->set('ERROR', "Error:  Team ID not found.", []);
            header('HTTP/1.1 500 Internal Server');
            return $this->response;
        } else {
          if ($team['TEAM_ID'] !== null) {
              $this->deleteTeamInfo($team['TEAM_ID']);
          }
            $team = null;
            $statement = $this->mysqli->prepare("DELETE FROM INFO WHERE ID = ?");
            $statement->bind_param('i', $team['ID']);
            $statement->execute();
            if (!$statement) {
                $this->response->set('ERROR', "Error: " . mysqli_error($this->mysqli), []);
                header('HTTP/1.1 500 Internal Server');
                return $this->response;
            } else {
                $result = $this->queryTeams();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        \array_push($resultArray, $row);
                    }
                }
            }
            $statement->close();
        }
        $this->response->set('SUCCESS', null, $resultArray);
        return $this->response;
    }

    private function getSingleTeamById(int $id)
    {
        $team = null;
        $statement = $this->mysqli->prepare("SELECT * FROM TEAMS WHERE ID = ?");
        $statement->bind_param('i', $id);
        $statement->execute();
        if (!$statement) {
            $this->response->set('ERROR', "Error: " . mysqli_error($this->mysqli), []);
            header('HTTP/1.1 500 Internal Server');
            return $this->response;
        }
        $result = $statement->get_result();
        $team = $result->fetch_array(MYSQLI_ASSOC);
        $statement->close();
        return $team;
    }

    private function deleteTeamInfo(int $teamId) {
        $team = null;
        $statement = $this->mysqli->prepare("DELETE FROM INFO WHERE ID = ?");
        $statement->bind_param('i', $teamId);
        $statement->execute();
        if (!$statement) {
            $this->response->set('ERROR', "Error: " . mysqli_error($this->mysqli), []);
            header('HTTP/1.1 500 Internal Server');
            return $this->response;
        }
        $statement->close();
    }
}