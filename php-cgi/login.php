<?php
/*
    This login script checks inputted credentials
    against list of users and assigns a temporary
    hex ID used for validating POSTs
*/

// Get list of saved users
$users_file = file_get_contents('./data/users.json');
$users = json_decode($users_file, true);

// Get username
$form_username = $_GET["username"];

// Check if username is valid
$this_user = [];
$found = False;
foreach($users as &$user) {
    if ($form_username == $user["username"]) {
        $this_user = $user; // Copy user credentials from file
        $auths_dir = "auths/";
        // Check if there is an auths directory
        if (!(file_exists($auths_dir))) {
            $oldmask = umask(0);
            mkdir($auths_dir, 0777, true);
            umask($oldmask);
        }
        $fp = fopen($auths_dir . $this_user["username"], "w");
        // This person's auth
        $auth = hash('ripemd160', $this_user["name"]);
        fwrite($fp, (string)$auth);
        $this_user["name"] = $user["name"];
        // Return user's credentials
        /*   Note that only judges are given sessions
            {
                "id":"datkinson",
                "name":"Darren Atkinson",
                "type":["judge","advisor"],
                "rest_auth":"23409dsad",
                "session":["Computer Engineering","1"]
            }
        */
        $returnobj["user"] = $this_user;
        $returnobj["found"] = True;
        echo json_encode($returnobj);
        fclose($fp);
        $found = True;
        break;
    }
}
if (!$found) {
    $returnobj["found"] = False;
    echo json_encode($returnobj);
}

http_response_code(200);
?>
