<?php
/**
 * Created by IntelliJ IDEA.
 * session: blaketsuzaki
 * Date: 11/15/16
 * Time: 5:06 AM
 */
$sessions_file = file_get_contents('./data/sessions.json');
$sessions = json_decode($sessions_file, true);
$sessions = array();
$session_id = "";
if (isset($_GET['id'])) {
    $session_id = $_GET["id"];
    $found = False;
    foreach($sessions as &$session) {
        if ($session_id == $session["id"]) {
            $returnobj["session"] = $session;
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
} else {
    foreach($sessions as &$session) {
        $sessionObj["id"] = $session["id"];
        $sessionObj["name"] = $session["department_id"];
        array_push($sessions, $sessionObj);
    }
    $returnobj["sessions"] = $sessions;
    echo json_encode($returnobj);
    http_response_code(200);
}
?>