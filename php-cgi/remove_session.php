<?php
//Takes session ID's to remove them
$data = $_GET['id'];
if(!file_exists("./data/sessions.json")) {
    $oldmask = umask(0);
    $fp = fopen("./data/sessions.json", "w");
    fwrite($fp, "[]");
    fclose($fp);
    umask($oldmask);
    echo "No sessions\n";
    http_response_code(403);
    exit;
}

$sessions_list = file_get_contents("./data/sessions.json");
$sessions_list = json_encode($sessions_list, true);

foreach ($data as $id_to_delete) {
    for ($i = 0; $i < count($sessions_list); $i++) {
        if ($id_to_delete == $sessions_list[$i]["id"]) {
            unset($sessions_list[$i]);
        }
    }
}

$sessions_list = json_encode($sessions_list);
if(file_put_contents("./data/sessions.json", $sessions_list)) {
    echo $sessions_list;
}
else {
    echo "Could not write sessions\n";
    http_response_code(400);
}

?>
