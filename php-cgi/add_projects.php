<?php
//$data = file_get_contents("php://input");
//$data = json_decode($data, true);
/*
$check_keys = ["advisors","session_id","session_substr","project","members"];
for ($i = 0; $i < count($data); $i++) {
foreach ($check_keys as $key) {
    if (!isset($data[$i]["$key"])) {
        echo "Missing Field" . "\n";
        http_response_code(403);
        exit;
    }
}
}
*/
$data = [];
$data['advisors'] = $_GET['advisors'];
$data['session_id'] = $_GET['session_id'];
$data['session_substr'] = $_GET['session_substr'];
$data['project'] = $_GET['project'];
$data['members'] = $_GET['members'];
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
$check_ids = []; //Used to update sessions
for ($i = 0; $i < count($new_projects); $i++) {
    if(!isset($new_projects[$i]["id"])) {
        $new_projects[$i]["id"] = bin2hex(openssl_random_pseudo_bytes(6));
        array_push($check_ids, $new_projects[$i]["id"]);
    }
}
//Get list of projects
$project_list = file_get_contents("./data/projects.json");
$project_list = json_decode($project_list, true);

array_push($project_list, $new_projects);

//Update sessions.json aligns with new projects
$session_list = file_get_contents("./data/sessions.json");
$session_list = json_decode($session_list, true);

for ($i = 0; $i < count($session_list); $i++) {
       for ($k = 0; $k < count($new_projects); $k++) {
           if(!array_key_exists($new_projects[$k]["id"], $session_list[$i]["projects"])) {
                array_push($session_list[$i]["projects"], $new_projects[$k]["id"]);
           }
       }
   }
}

file_put_contents("./data/projects.json", json_encode($project_list));

echo json_encode($project_list);
http_response_code(200);

?>
