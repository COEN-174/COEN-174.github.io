<?php
/*
    This login script checks inputted credentials
    against list of users and assigns a temporary
    hex ID used for validating POSTs
*/

// Get list of saved users
$users_file = file_get_contents('./users');
$users = json_decode($users_file, true);

// Get username & password
$form_login = file_get_contents("php://input");
$form_login = json_decode($form_login,true);
$form_username = $form_login["username"];
$form_password = $form_login["password"];

// Check if username & password are valid
$this_user = [];
$found = False;
for($i = 0; $i < count($users); $i++) {
    $user = (Array)$users[$i]; //Treat each index in the array as an array(json obj)
    if ($form_username == $user["username"]) {
        if ($form_password == $user["password"]) {
            $this_user = $user; // Copy user credentials from file
            unset($this_user["password"]); // Never return passwords
            $auths_dir = "auths/";
            // Check if there is an auths directory
            if (!(file_exists($auths_dir))) {
                $oldmask = umask(0);
                mkdir($auths_dir, 0777, true);
                umask($oldmask);
            }
            $fp = fopen($auths_dir . $this_user["username"], "w");
            // This person's auth
            $auth = bin4hex(random_bytes(5));
            fwrite($fp, (string)$auth);
            $this_user["rest_auth"] = $auth;
            // Return user's credentials
            echo json_encode($this_user);
            fclose($fp);
            $found = True;
            break;
        }
    }
}

$found ? http_response_code(200) : http_response_code(403);
?>
