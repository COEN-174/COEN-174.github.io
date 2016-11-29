<?php
$data = file_get_contents("php://input");
$data = json_decode($data, true);

$check_keys = ["advisors","session","project","members"];
for ($i = 0; $i < count($data); $i++) {
foreach ($check_keys as $key) {
    if (!isset($data[$i]["$key"])) {
        echo "Missing Field" . "\n";
        http_response_code(403);
        exit;
    }
}
}

//echo var_dump($data);
if (!file_exists("./data/projects.json")) {
    //echo "Making projects file\n";
    $oldmask = umask(0);
    $fp = fopen("./data/projects.json", "w");
    fwrite($fp, "[]");
    fclose($fp);
    umask($oldmask);
}
//Add UIDs to new projects
$new_projects = $data;
for ($i = 0; $i < count($new_projects); $i++) {
    $new_projects[$i]["id"] = bin2hex(random_bytes(6));
}
//Get list of projects
$project_list = file_get_contents("./data/projects.json");
$project_list = json_decode($project_list, true);

array_push($project_list, $new_projects);

file_put_contents("./data/projects.json", json_encode($project_list));

echo json_encode($project_list);

?>
