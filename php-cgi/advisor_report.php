<?php
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
                unset($temp["session_id"]);
                unset($temp["session_substr"]);
                unset($temp["name"]);

                // Check if we've seen the project before
                if (!(isset($advisor_report["$project_name"]))) { 
                    $advisor_report["$project_name"] = []; // New array for scores
                    $advisor_report["$project_name"]["members"] = $score_array["members"];
                    $advisor_report["$project_name"]["advisors"] = $score_array["advisors"];
                    $advisor_report["$project_name"]["scores"]["$judge_name"] =  $temp;

                }
                else {
                    array_push($advisor_report["$project_name"]["scores"], $judge_name);
                    $advisor_report["$project_name"]["scores"]["$judge_name"] = $temp;
                }
            }
        }
    }

    $temp_project = [];
    $score_totals = [""];
    $num_judges = 0;
    $judges = [""];

    $fp = fopen("advisor_report.csv","w");

    foreach ($advisor_report as $project_name => $values) {
    $judges = ["Judges"];
    $score_totals = ["Totals"];
    unset($cat1);
    unset($cat2);
    unset($cat3);
    unset($cat4);
    unset($cat5);
    unset($cat6);
    unset($cat7);
    unset($cat8);
    unset($cat9);
    unset($cat10);
    unset($cat11);
    unset($cat12);
    $cat1 = ["Technical Accuracy"];
    $cat2 = ["Creativity and Innovation"];
    $cat3 = ["Supporting Analytical Work"];
    $cat4 = ["Methodical Design Process Demonstrated"];
    $cat5 = ["Addresses Project Complexity Appropriately"];
    $cat6 = ["Expectation of Completion (by term's end)"];
    $cat7 = ["Design & Analysis of Tests"];
    $cat8 = ["Quality of Response during Q&A"];
    $cat9 = ["Organization"];
    $cat10 = ["Use of Alloted Time"];
    $cat11 = ["Visual Aids"];
    $cat12 = ["Confidence and Poise"];
        array_push($temp_project, $project_name);
        fputcsv($fp, $temp_project);
        $temp_project = [];
        fputcsv($fp, [""]);
        foreach ($values["scores"] as $judge => $judges_scores) {
            if(!array_key_exists($judge,$judges)) {
                array_push($judges, $judge);
            }
            foreach ($judges_scores["grades"] as $category => $grade) {
                //echo $category ."=" . $grade ."\n";
                //Make rows of scores
                switch ($category) {
                    case $cat1[0]:
                        array_push($cat1, $grade);
                    case $cat2[0]:
                        array_push($cat2, $grade);
                    case $cat3[0]:
                        array_push($cat3, $grade);
                    case $cat4[0]:
                        array_push($cat4, $grade);
                    case $cat5[0]:
                        array_push($cat5, $grade);
                    case $cat6[0]:
                        array_push($cat6, $grade);
                    case $cat7[0]:
                        array_push($cat7, $grade);
                    case $cat8[0]:
                        array_push($cat8, $grade);
                    case $cat9[0]:
                        array_push($cat9, $grade);
                    case $cat10[0]:
                        array_push($cat10, $grade);
                    case $cat11[0]:
                        array_push($cat11, $grade);
                    case $cat12[0]:
                        array_push($cat12, $grade);
                }
            }
        }
        fputcsv($fp,$judges);
        fputcsv($fp,$cat1);
        fputcsv($fp,$cat2);
        fputcsv($fp,$cat3);
        fputcsv($fp,$cat4);
        fputcsv($fp,$cat5);
        fputcsv($fp,$cat6);
        fputcsv($fp,$cat7);
        fputcsv($fp,$cat8);
        fputcsv($fp,$cat9);
        fputcsv($fp,$cat10);
        fputcsv($fp,$cat11);
        fputcsv($fp,$cat12);
        for ($i = 1; $i <= count($judges); $i++) {
            $score_totals[$i] += $cat1[$i];
            $score_totals[$i] += $cat2[$i];
            $score_totals[$i] += $cat3[$i];
            $score_totals[$i] += $cat4[$i];
            $score_totals[$i] += $cat5[$i];
            $score_totals[$i] += $cat6[$i];
            $score_totals[$i] += $cat7[$i];
            $score_totals[$i] += $cat8[$i];
            $score_totals[$i] += $cat9[$i];
            $score_totals[$i] += $cat10[$i];
            $score_totals[$i] += $cat11[$i];
            $score_totals[$i] += $cat12[$i];
        }
        fputcsv($fp,$score_totals);
        fputcsv($fp,[""]);
    }
    fclose($fp);
    $advisor_report["download_link"] = "advisor_report.csv";
    $return_data = json_encode($advisor_report);
    echo $return_data;
	http_response_code(200);

?>
