<?php
include 'check_auth.php';
/*
    This script returns json and download link
    to all project scores neatly sorted
*/

$request = file_get_contents("php://input");
$request = json_decode($request, true);
$allow_get = False;
$check_keys = ["username","rest_auth"];
$request_keys = array_keys($request);
/*
if (count($check_keys) == count($request_keys)) {
    echo "Passed key count check\n\n";
    for ($i = 0; $i < count($request_keys); $i++) {
        if (!(isset($request["$check_keys[$i]"]))) {
            invalid_form("Unknown key");
        }
    }
    echo var_dump($request);
    $allow_get = check_auth($request["username"], $request["rest_auth"]);

    if ($allow_get == False) {
        invalid_form("Invalid credentials");
    }

    echo "Passed check_auth\n\n";
*/
    $shanes_report = [];
    $judges_path = "scores/json/judges/*";

    foreach (glob($judges_path) as $scores_path) {
        $scores = file_get_contents($scores_path);
        $scores = json_decode($scores);

        for ($i = 0; $i < count($scores); $i++) {
            $score_array = (Array) $scores[$i];
            $project_name = $score_array["project"];
            $judges_name = $score_array["name"];
            $temp = $score_array;
            unset($temp["project"]);
            unset($temp["members"]);
            unset($temp["advisors"]);
            unset($temp["session"]);
            unset($temp["name"]);

            if (!(isset($shanes_report["$project_name"]))) {
                $shanes_report["$project_name"] = [];
                $shanes_report["$project_name"]["members"] = $score_array["members"];
                $shanes_report["$project_name"]["advisors"] = $score_array["advisors"];
                $shanes_report["$project_name"]["scores"]["$judges_name"] = $temp;
            }
            else {
                array_push($shanes_report["$project_name"]["scores"], $judges_name);
                $shanes_report["$project_name"]["scores"]["$judges_name"] = $temp;
            }
        }
    }

$shane_csv = $shanes_report;
$projects_keys = array_keys($shane_csv);
$project_scores = [];

foreach($shane_csv as $project) {
    foreach($project["scores"] as $judge) {
        array_push($project_scores, $judge["final_score"]);
    }
}

$csv = array($project_keys, $project_scores);
$fp = fopen("shane.csv","w+");
foreach($csv as $line) {
    fputcsv($fp,$line);
}
fclose($fp);
$shanes_report["download_link"] = "http://students.engr.scu.edu/~pmiller/website/shane.csv";

    $return_data = json_encode($shanes_report);
    echo $return_data;
    /*
}
else {
    invalid_form("Failed key length check");
}
*/

function invalid_form($msg) {
    echo "Invalid form ";
    if (isset($msg)) {
        echo ": message:" . $msg;
    }
    http_response_code(403);
    exit();
}
?>
