<?php
/*
    This script removes projects by UID
*/

// Takes an array of UIDs
$data = file_get_contents("php://input");
$data = json_decode($data, true);

if(!isset($data)) {
    echo "No projects defined...\n";
    http_response_code(403);
    exit;
}
if(!file_exists("./data/projects.json")) {
    $oldmask = umask(0);
    $fp = fopen("./data/projects.json", "w");
    fwrite($fp, "[]");
    fclose($fp);
    umask($oldmask);
}
//Sanitization is important
for($i = 0; $i < count($data); $i++) {
    $data = trim($data[$i]);
}

$project_list = file_get_contents("./data/projects.json");
$project_list = json_decode($project_list, true);
//echo var_dump($project_list) . "\n";
foreach($data as $id_to_delete) {
    for($i = 0; $i < count($project_list); $i++) {
        if($id_to_delete == $project_list[$i]["id"]) {
            unset($project_list[$i]);
        }
    }
}
//echo var_dump($project_list) . "\n";
if (file_put_contents("./data/projects.json", $project_list)) {
    echo json_encode($project_list);
}
else {
    echo "Could not write projects\n";
    http_response_code(400);
}

?>
