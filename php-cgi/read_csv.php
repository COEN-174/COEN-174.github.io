<?php
function stripWhiteSpace($strang){
    $strang = preg_replace('/\s+/', '', $strang);
    return $strang;
}
ini_set('display_errors', 'On');
error_reporting(E_ALL);
$data = file_get_contents("php://input");

// date/judges/teams_associated_with_judges.csv
// date/advisors/teams_associated_with_advisors.csv (indv scores)
// date/shane.csv <-- appended to every time
$root_dir = date("m-d-y");
$file_path_judges = $root_dir . "/" . "judges";
$file_path_advisors = $root_dir . "/" . "advisors";
$file_path_shane = $root_dir;

$csv = array_map('str_getcsv', file($file_path_shane));
array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
});
array_shift($csv);
echo $csv;
?>
