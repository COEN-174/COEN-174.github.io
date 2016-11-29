<?php
/*
    This script adds sessions to sessions.json
*/
$data = [];
$data['session_id'] = $_GET['session_id'];
$data['session_substr'] = $_GET['session_substr'];
$data['projects'] = $_GET['projects'];

//$check_keys = ["session_id","session_substr","projects"];
//foreach ($data as $session) {
//    foreach ($check_keys as $key) {
//        if (!array_key_exists($key, $session)) {
//            echo "Invalid fields\n";
//            http_response_code(403);
//            exit;
//        }
//    }
//}

if (!file_exists("./data/sessions.json")) {
    $oldmask = umask(0);
    $fp = fopen("./data/sessions.json", "w");
    fwrite($fp, "[]");
    fclose($fp);
    umask($oldmask);
}

$sessions_list = file_get_contents("./data/sessions.json");
$sessions_list = json_decode($sessions_list, true);

//update projects
$project_list = file_get_contents("./data/projects.json");
$project_list = json_decode($project_list, true);

if(!strlen($_GET['id'])) {
    $data["id"] = bin2hex(openssl_random_pseudo_bytes(6));
    $new_session = $data;
} else {
    for ($i = 0; $i < count($project_list); $i++) {
        if ($project_list[$i]["id"] == $_GET['id']) {
            $new_session = $project_list[$i];
            unset($project_list[$i]);
            $new_session['session_id'] = $_GET['session_id'];
            $new_session['session_substr'] = $_GET['session_substr'];
            $new_session['projects'] = $_GET['projects'];
        }
    }
}

array_push($sessions_list, $new_session);

file_put_contents("./data/sessions.json", json_encode($sessions_list));
echo json_encode($sessions_list);
http_response_code(200);
?>
