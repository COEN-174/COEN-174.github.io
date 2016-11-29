<?php
/**
 * Created by IntelliJ IDEA.
 * User: blaketsuzaki
 * Date: 11/15/16
 * Time: 5:06 AM
 */
$users_file = file_get_contents('./data/users.json');
$users = json_decode($users_file, true);
$judges = array();
$user_id = "";
if (isset($_GET['id'])) {
    $user_id = $_GET["id"];
    $found = False;
    foreach($users as &$user) {
        if ($user_id == $user["id"]) {
            $returnobj["judge"] = $user;
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
    foreach($users as &$user) {
        if ($user["type"] == "judge") {
            $userObj["id"] = $user["id"];
            $userObj["name"] = $user["name"];
            array_push($judges, $userObj);
        }
    }
    $returnobj["judges"] = $judges;
    echo json_encode($returnobj);
    http_response_code(200);
}
?>