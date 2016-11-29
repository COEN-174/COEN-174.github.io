<?php
/*
    This script adds sessions to sessions.json
*/
$data = [];
$data['session_id'] = $_GET['session_id'];
$data['session_substr'] = $_GET['session_substr'];
$data['projects'] = $_GET['projects'];

$check_keys = ["session_id","session_substr","projects"];
foreach ($data as $session) {
    foreach ($check_keys as $key) {
        if (!array_key_exists($key, $session)) {
            echo "Invalid fields\n";
            http_response_code(403);
            exit;
        }
    }
}

if (!file_exists("./data/sessions.json")) {
    $oldmask = umask(0);
    $fp = fopen("./data/sessions.json", "w");
    fwrite($fp, "[]");
    fclose($fp);
    umask($oldmask);
}
$new_sessions = $data; //array of sessions

for ($i = 0; $i < count($new_sessions); $i++) {
    if(!isset($new_sessions["id"])) {
        $new_sessions["id"] = bin2hex(openssl_random_pseudo_bytes(6));
    }
}

$sessions_list = file_get_contents("./data/sessions.json");
$sessions_list = json_decode($sessions_list, true);

//update projects
$project_list = file_get_contents("./data/projects.json");
$project_list = json_decode($project_list, true);

for ($i = 0; $i < count($project_list); $i++) {
    if ($project_list[$i]["id"]
}

array_push($sessions_list, $new_sessions);

file_put_contents("./data/sessions.json", json_encode($sessions_list));
echo json_encode($sessions_list);
http_response_code(200);
?>
