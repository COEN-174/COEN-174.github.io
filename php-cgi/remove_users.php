<?php

/*
    this script removes users from users file
*/
//Data should be array of UIDs
$data = file_get_contents("php://input");
$data = json_decode($data, true);

if(!isset($data)) {
    echo "No UIDs defined...";
    http_response_code(403);
    exit;
}
if(!file_exists("./data/users.json")) {
    $oldmask = umask(0);
    $fp = fopen("./data/users.json", "w");
    fwrite($fp, "[]");
    fclose($fp);
    umask($oldmask);
    echo "No users to delete";
    http_response_code(403);
    exit;
}

//Sanitize data
for($i = 0; $i < count($data); $i++) {
    $data[$i] = trim($data[$i]);
}

//Remove matching user from user list
$user_list = file_get_contents("./data/users.json");
$user_list = json_decode($user_list, true);
//echo var_dump($user_list) . "\n";
foreach ($data as $id_to_delete) {
    for ($i = 0; $i < count($user_list); $i++) {
        if ($id_to_delete == $user_list[$i]["id"]) {
            unset($user_list[$i]);
        }
    }
}
/*
foreach ($data as $judge) {
    recursive_unset($new_list, $judge);
}
function recursive_unset(&$arr, $judge_to_delete) {
    if(is_array($arr)) {
        foreach($arr as $key => &$arrVal) {
            if(is_array($arrVal)) {
                recursive_unset($arrVal, $judge_to_delete);
            }
            else {
                if($arrVal == $judge_to_delete) {
                    unset($arr[$key]);
                    echo "Removed someone";
                }
            }
        }
    }
}
*/
//echo var_dump($new_list) . "\n";
$user_list = json_encode($user_list);
if(file_put_contents("./data/users.json", $user_list)) {
    echo $user_list;
}
else {
    echo "Could not write users\n";
    http_response_code(400);
}

?>
