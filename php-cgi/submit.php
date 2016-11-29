<?php
include 'check_auth.php';
/*
    This script takes a json scoring object like
    {
        "username":"datkinson",
        "name":"Darren Atkinson",
        "advisors":["Einstein","Betty Young"],
        "session":["Computer Engineering":"1"];
        "project":"Ballooning Across North Pole",
        "members":["Patrick Miller","Blake Tsuzaki","Christina Ciardella"],
        "grades":{"Being awesome":"5","Not Being awesome":"0"},
        "considerations":["Global Justice","Economic Benefit"],
        "comments":"The best darn project I've ever seen",
    }
    and will save it to the judges own judging directory
*/


$submission = file_get_contents("php://input");
$submission = json_decode($submission, true);
// Checking for legitimate data
$allow_post = False;
$check_keys = ["name","username","advisors","session","project","members","grades","considerations","comments"];

// Checking for legitimate keys
$submission_keys = array_keys($submission);
    // Set judges file structure scores/json/judges/judgeid
    $judges_path =  "scores/json/judges/";
    $judges_file = $judges_path . $submission["username"];
    if (!(file_exists($judges_path))) {
        echo "Judge's path does not exist, creating...\n";
        $oldmask = umask(0);
        mkdir($judges_path, 0777, true);
        umask($oldmask);
    }
    if (!(file_exists($judges_file))) {
        echo "Judge's grading file does not exist, creating...\n";
        $fp = fopen($judges_file, "w+");
        fwrite($fp, "[]");
        fclose($fp);
    }
    $submissions_to_json = file_get_contents($judges_file);
    $submissions_to_json = json_decode($submissions_to_json, true);
    echo var_dump($submissions_to_json);
    $score = $submission;
    unset($score["username"]);
    array_push($submissions_to_json, $score);
    echo var_dump($submissions_to_json);
    $submissions_to_string = json_encode($submissions_to_json); // Score is now a json string
    echo var_dump($submissions_to_string);
    file_put_contents($judges_file, $submissions_to_string); // Write to file
    echo "Score submitted\n";
/*
}
// Failed size comparison
else {
    invalid_form("No auths to be checked. Are you logged in?");
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
