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

$data = $_POST["data"];
$data = html_entity_decode($data);
// ^^ expects {"Team": "B,C,P","Score": 100}
$data = json_decode($data, true); //Converts json to array
$file_name = $_SERVER["HTTP_USER_IS"];
$fp = fopen("$file_name" + ".csv", "a");

foreach ($data as $line) {
   fputcsv($fp, $line);
}

fclose($fp);

?>
