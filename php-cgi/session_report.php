<?php
include 'check_auth.php';
/*

    This script returns all projects=>scores in a session

*/

$request = file_get_contents("php://input");
$request = json_decode($request, true);
$allow_get = False;
$check_keys = ["session","rest_auth","username"];

$request_keys = array_keys($request);

if (count($check_keys) == count($request_keys)) {
    echo "Passed key count check\n\n";
    for ($i = 0; $i < count($request_keys); $i++) {
        if (!(isset($request["$check_keys[$i]"]))) {
            invalid_form("Unknown key");
        }
    }
    $allow_get = check_auth($request["username"], $request["rest_auth"]);

    if ($allow_get == False) {
        invalid_form("Invalid credentials");
    }

    echo "Passed allow_get\n\n";

    //Passed authentication. Lets deliver some reports
    $session_name = $request["session"][0];
    $session_number = $request["session"][1];
    $session_report = [];
    $judges_path = "scores/json/judges/*";

    foreach (glob($judges_path) as $scores_path) {
        $scores = file_get_contents($scores_path);
        $scores = json_decode($scores);

        for ($i = 0; $i < count($scores); $i++) {
            $score_array = (Array) $scores[$i];
            if (($score_array["session"][0] == $session_name) && ($score_array["session"][1] == $session_number)) {
                $project_name = $score_array["project"];
                $judges_name = $score_array["name"];
                $temp = $score_array;
                unset($temp["project"]);
                unset($temp["members"]);
                unset($temp["advisors"]);
                unset($temp["session"]);
                unset($temp["name"]);

                if (!(isset($session_report["$project_name"]))) {
                    $session_report["$project_name"] = [];
                    $session_report["$project_name"]["members"] = $score_array["members"];
                    $session_report["$project_name"]["advisors"] = $score_array["advisors"];
                    $session_report["$project_name"]["scores"]["$judges_name"] = $temp;
                    
                }
                else {
                    array_push($session_report["$project_name"]["scores"], $judges_name);
                    $session_report["$project_name"]["scores"]["$judges_name"] = $temp;
                }
            }
        }
    }
    $return_data = json_encode($session_report);
    echo $return_data;
}
else {
    invalid_form("Failed key length check");
}

function invalid_form($msg) {
    echo "Invalid form ";
    if (isset($msg)) {
        echo ": message:" . $msg;
    }
    http_response_code(403);
    exit();
}

?>
