<?php
require_once 'Classes/Teams.php';
require_once 'Classes/Content.php';
require_once 'Controllers/ContentController.php';
$method = $_GET['method'] ?? $_POST['method'] ?? null;
$request = $_GET['request'] ?? $_POST['request'] ?? null;
switch ($method) {
    case 'getTeams':
        $teams = new Teams();
        echo json_encode($teams->getTeams());
        break;
    case 'addTeams':
        $teams = new Teams();
        echo json_encode($teams->addTeams($request));
        break;
    case 'deleteTeam':
        $teams = new Teams();
        $id = $_POST['id'] ?? null;
        echo json_encode($teams->deleteTeam((int) $id));
        break;
    default:
        echo json_encode("ERERERER");
        break;

}

