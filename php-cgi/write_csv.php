<?php
/*
 This script is called when 'Score Submit'
 button is pressed. It takes an array of
 scores in JSON and writes a .csv with them. ~pmiller

Example usage:
<script>
    var UID = login_ID;
    var score = "data=" + JSON.stringify(json_encoded_scores);
    var http = new XMLHttpRequest();
    var post_scores = "write_csv.php";

    http.open("POST", post_scores, true);
    http.setRequestHeader("user-is", UID, false);
    http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

    http.send(scores);
</script>

*/
    
function stripWhiteSpace($strang){
    $strang = preg_replace('/\s+/', '', $strang);
    return $strang;
}
ini_set('display_errors', 'On');
error_reporting(E_ALL);
//Get post data
$data = file_get_contents("php://input");

//testing
$temp = fopen("test","w");
fwrite($temp, $data);

$data = json_decode($data,true);

$keys = array_keys($data);//Array of keys
$values = array_values($data);//Array of values
$category_score = array_combine($keys,$values);
$category_score = array($keys,$values);
$judge = $data["Judge"];
$advisor = $data["Advisor"];
$judge = stripWhiteSpace($judge);
$advisor = stripWhiteSpace($advisor);
$team = $data["Team"];

// date/judges/teams_associated_with_judges.csv
// date/advisors/teams_associated_with_advisors.csv (indv scores)
// date/shane.csv <-- appended to every time
$root_dir = date("m-d-y");
$file_path_judges = $root_dir . "/" . "judges";
$file_path_advisors = $root_dir . "/" . "advisors";
$file_path_shane = $root_dir;
if (!file_exists($file_path_judges)) {
    $oldmask = umask(0);
    mkdir($file_path_judges, 0777,true);
    umask($oldmask);
}
if (!file_exists($file_path_advisors)) {
    $oldmask = umask(0);
    mkdir($file_path_advisors, 0777,true);
    umask($oldmask);
}

$fjudge = fopen($file_path_judges . "/" . $judge . ".csv","a");
$fadvisor = fopen($file_path_advisors . "/" . $advisor . ".csv","a");
$fshane = fopen($file_path_shane . "/" .  "shane.csv","a");
foreach($category_score as $line) {
    fputcsv($fjudge,$line);
    fputcsv($fadvisor,$line);
    fputcsv($fshane,$line);
}
fclose($fjudge);
fclose($fadvisor);
fclose($fshane);
fclose($temp);
?>
