<?php
/**
 * Created by IntelliJ IDEA.
 * User: blaketsuzaki
 * Date: 11/15/16
 * Time: 6:00 AM
 */
$users_file_path = './data/users';
$users_file = file_get_contents($users_file_path);
$users = json_decode($users_file, true);
$user_object_new = $_POST["user"];
for ($i = 0; $i < count($users); $i++) {
    $user = $users[$i];
    if ($user_object_new["id"] == $user["id"]) {
        $users[$i]["name"] = $user_object_new["name"];
        $users[$i]["type"] = $user_object_new["type"];
        $users[$i]["session"] = $user_object_new["session"];
    } else {
        $newuser["id"] = hash('ripemd160', $user_object_new["name"]);
        $newuser["name"] = $user_object_new["name"];
        $newuser["type"] = $user_object_new["type"];
        $newuser["session"] = $user_object_new["session"];
        array_push($users, $newuser);
    }
}
file_put_contents($users_file_path, json_encode($users));
http_response_code(200);
?>