<?php

/*
    This script takes an array of users
    and adds them to ./data/users.json
*/

$data = file_get_contents("php://input");
//echo var_dump($data);
$data = json_decode($data, true);
$check_keys = ["username","name","acl","session_id","session_substr"];
foreach ($data as $new_user) {
    for ($i = 0; $i < count($check_keys); $i++) {
        if (!isset($new_user["$check_keys[$i]"])) {
            echo "Missing fields\n";
            http_response_code(403);
            return 0;
        }
    }
}

//Sanitize data (strim trail/leading whitespace)
for ($i = 0; $i < count($data); $i++) {
    for ($j = 0; $j < count($data[$i]); $j++) {
        $data[$i][$j] = trim($data[$i][$j]);
    }
}

if (!file_exists("./data/users.json")) {
    //echo "judge file DNE";
    $oldmask = umask(0);
    $fp = fopen("./data/users.json", "w");
    fwrite($fp, "[]");
    fclose($fp);
    umask($oldmask);
}

$new_users = $data; // Array of users
//Give each user a UID
for ($i = 0; $i < count($new_users); $i++) {
    $new_users[$i]["id"] = bin2hex(random_bytes(6));
}
//  Judges are stored in lists keyed by session
$user_list = file_get_contents("./data/users.json");
$user_list = json_decode($user_list, true);
//Add new users to current users
array_push($user_list, $new_users);
//echo $judge_list;

file_put_contents("./data/users.json", json_encode($user_list));
echo json_encode($user_list);

?>
