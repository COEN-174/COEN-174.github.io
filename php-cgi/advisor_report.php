<?php
include 'check_auth.php';
/*

    This script returns all graded projects 
    associated with an advisor. 
    Expected output form:

    [
        "Project's Name":[
            "members":["member1","member2",...,"memberN"],
            "advisors":["advisorsss"],
            "session":["Session name","Session #"],
            "scores":[
                "Some judge":[
                    "grades":["Being awesome":"5","Not being awesome":"0"],
                    "considerations":["Social justice","Economic Benefit"],
                    "commends":"This project was the greatest thing ever"
                ]
                "Some other judge":[
                    "grades":["Being awesome":"5","Not being awesome":"0"],
                    "considerations":["Social justice","Economic Benefit"],
                    "commends":"This project was the greatest thing ever"
                ]
            ]
        ],
        "Another projects Name":[
            "members":["member1","member2",...,"memberN"],
            "advisors":["advisorsss"],
            "session":["Session name","Session #"],
            "scores":[
                "Some judge":[
                    "grades":["Being awesome":"5","Not being awesome":"0"],
                    "considerations":["Social justice","Economic Benefit"],
                    "commends":"This project was the greatest thing ever"
                ]
                "Some other judge":[
                    "grades":["Being awesome":"5","Not being awesome":"0"],
                    "considerations":["Social justice","Economic Benefit"],
                    "commends":"This project was the greatest thing ever"
                ]
            ]
        ]
    ]

*/

$request = file_get_contents("php://input");
$request = json_decode($request, true);
$allow_get = False;
$check_keys = ["name","username","rest_auth"];
$request_keys = array_keys($request);
if (count($check_keys) == count($request_keys)) {
    echo "Passed key count check\n\n";
    for ($i = 0; $i < count($request_keys); $i++) {
        if (!(isset($request["$check_keys[$i]"]))) {
            invalid_form("Bad keys");
        }
    }

    $allow_get = check_auth($request["username"], $request["rest_auth"]);

    if ($allow_get == False) {
        invalid_form("Invalid credentials");
    }
    echo "Passed allow_get\n\n";

    // Passed authentication. Lets deliver some reports
    // Gather up all json from scores/json/judges/*
    $advisor_name = $request["name"];
    $advisors_report = [];
    $judges_path = "scores/json/judges/*";
    foreach (glob($judges_path) as $scores_path) { // each judge's list of scores
        $scores = file_get_contents($scores_path);
        $scores = json_decode($scores); // Array of scores

        for ($i = 0; $i < count($scores); $i++) { // Loop through each judge's scores
            $score_array = (Array) $scores[$i];
            if (in_array($advisor_name, $score_array["advisors"])) {
                $project_name = $score_array["project"]; // Set name of project
                $judge_name = $score_array["name"]; // Set who graded this project
                $temp = $score_array;
                unset($temp["project"]);
                unset($temp["members"]);
                unset($temp["advisors"]);
                unset($temp["session"]);
                unset($temp["name"]);

                // Check if we've seen the project before
                if (!(isset($advisor_report["$project_name"]))) { 
                    $advisor_report["$project_name"] = []; // New array for scores
                    $advisor_report["$project_name"]["members"] = $score_array["members"];
                    $advisor_report["$project_name"]["advisors"] = $score_array["advisors"];
                    $advisor_report["$project_name"]["session"] = $score_array["session"];
                    $advisor_report["$project_name"]["scores"]["$judge_name"] =  $temp;

                }
                else {
                    array_push($advisor_report["$project_name"]["scores"], $judge_name);
                    $advisor_report["$project_name"]["scores"]["$judge_name"] = $temp;
                }
            }
        }
    }

    $return_data = json_encode($advisor_report);
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
