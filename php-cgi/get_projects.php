<?php
/**
 * Created by IntelliJ IDEA.
 * project: blaketsuzaki
 * Date: 11/15/16
 * Time: 5:06 AM
 */
$projects_file = file_get_contents('./data/projects.json');
$projects = json_decode($projects_file, true);
$advisors = array();
$project_id = "";
if (isset($_GET["id"])) {
    $project_id = $_GET["id"];
    $found = False;

    foreach($projects as &$project) {
        if ($project_id == $project["project"]) {
            $returnobj["project"] = $project;
            echo json_encode($returnobj);
            $found = True;
            break;
        }
    }
    if (!$found) {
        $returnobj["found"] = False;
        echo json_encode($returnobj);
    }
    http_response_code(200);
} else if (isset($_GET["user_id"])) {
    $users_file = file_get_contents('./data/users.json');
    $users = json_decode($users_file, true);
    $user_id = $_GET["user_id"];
    $curr_user;
    foreach($users as &$user) {
        if ($user_id == $user["id"]) {
            $curr_user = $user;
            break;
        }
    }
    $returnarr = array();
    if ($curr_user["type"] == "advisor") {
        foreach ($curr_user["projects"] as &$project_id) {
            foreach($projects as &$project) {
                if ($project_id == $project["id"]) {
                    array_push($returnarr, $project);
                    echo json_encode($returnobj);
                    break;
                }
            }
        }
    } else if ($curr_user["type"] == "judge") {
        $session_file = file_get_contents('./data/sessions.json');
        $sessions = json_decode($session_file, true);
        foreach ($sessions as &$session) {
            if ($session["id"] == $curr_user["session"]) {
                $curr_session = $session;
                break;
            }
        }
        foreach ($curr_session["projects"] as &$project_id) {
            foreach($projects as &$project) {
                if ($project_id == $project["id"]) {
                    array_push($returnarr, $project);
                    break;
                }
            }
        }
    } else if ($curr_user["type"] == "admin") {
        foreach($projects as &$project) {
            array_push($returnarr, $project);
        }
    }
    $returnobj["projects"] = $returnarr;
    echo json_encode($returnobj);
    http_response_code(200);
} else {
    foreach($projects as &$project) {
        $projectObj["name"] = $project["project"];
        array_push($advisors, $projectObj);
    }
    $returnobj["projects"] = $advisors;
    echo json_encode($returnobj);
    http_response_code(200);
}
?>