<?php

/*

    Include to check auth of a user

*/

function check_auth($username, $rest_auth) {
    $check_auth = "";
    if (file_exists("auths/" . $username)) {
        $check_auth = file_get_contents("auths/" . $username);
        if ($check_auth == $rest_auth) {
            return True;
        }
    }
    else {
        return False;
    }
}


?>
