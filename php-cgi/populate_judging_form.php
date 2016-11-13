<?php
/*

    This script is used to populate the 
    list of projects associated with a judge's
    session on the left column of the judging form

*/

//Expects a session, username, and REST auth
$session = file_get_contents("php://input");
$session = json_decode($session, true);
$allow_get = False;
$check_keys = ["session","rest_auth"];
$session_keys = array_keys($session);
if (count($check_keys) == count($session_keys)) {
    for ($i = 0; $i < count($session_keys); $i++) {
        if (!(isset($session["check_keys[$i]"]))) {
            invalid_form();
        }
    }

    $check_auth = "";
    if (file_exists("auths/" . $session["username"])) {
        $check_auth = file_get_contents("auths/" . $session["username"]);
        if ($session["rest_auth"] == $check_auth) {
            $allow_get = True;
            echo "Allow get: TRUE\n";
        }
    }
    else {
        invalid_form("No auths to be checked. Are you logged in?");
    }

    if ($allow_get == False) {
        invalid_form();
    }

    $projects_path = "projects";
    $projects = file_get_contents($projects_path);
    $projects = json_decode($projects);

    // Match judge's session with projects' sessions
    $judges_projects = [];
    for ($i = 0; $i < count($projects); $i++) {
        $project = (Array)$projects[$i];
        if ($project["session"][0] == $session["session"][0] && $project["session"][1] == $session["session"][1]) {
            array_push($judges_projects, $project);
        }
    }
    $projects_to_string = json_encode($judges_projects);
    echo $projects_to_string;
}

function invalid_form($msg) {
    echo "Invalid form ";
    if (isset($msg)) {
        echo ": message:" . $msg;
    }
    http_response_code(403);
    exit();
}

?>
