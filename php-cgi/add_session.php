<?php
/*
    This script adds sessions to sessions.json
*/
$data = file_get_contents("php://input");
$data = json_decode($data, true);

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
    $new_sessions["id"] = bin2hex(random_bytes(6));
}

$sessions_list = file_get_contents("./data/sessions.json");
$sessions_list = json_decode($sessions_list, true);

array_push($sessions_list, $new_sessions);

file_put_contents("./data/sessions.json", json_encode($sessions_list));
echo json_encode($sessions_list);

?>
