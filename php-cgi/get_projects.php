<?php
/**
 * Created by IntelliJ IDEA.
 * project: blaketsuzaki
 * Date: 11/15/16
 * Time: 5:06 AM
 */
$projects_file = file_get_contents('./data/projects.json');
$projects = json_decode($projects_file, true);
$advisors = array();
$project_id = "";
if (isset($_GET["id"])) {
    $project_id = $_GET["id"];
    $found = False;

    foreach($projects as &$project) {
        if ($project_id == $project["project"]) {
            $returnobj["project"] = $project;
            echo json_encode($returnobj);
            $found = True;
            break;
        }
    }
    if (!$found) {
        $returnobj["found"] = False;
        echo json_encode($returnobj);
    }
    http_response_code(200);
} else if (isset($_GET["user_id"])) {
    $user_id = $_GET["user_id"];
    for($i = 0; $i < count($projects); $i++) {
        $project = $projects[$i];
        if (in_array($project["members"], $user_id)) {
            $projectObj["name"] = $project["project"];
            array_push($advisors, $projectObj);
        }
        $returnobj["projects"] = $advisors;
        echo json_encode($returnobj);
        http_response_code(200);
    }
} else {
    foreach($projects as &$project) {
        $projectObj["name"] = $project["project"];
        array_push($advisors, $projectObj);
    }
    $returnobj["projects"] = $advisors;
    echo json_encode($returnobj);
    http_response_code(200);
}
?>