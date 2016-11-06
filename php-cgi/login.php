<?php

// This login script expects
// {"username": "nifty username", "password": "hard2guess"}
// and will check these values against a user/pass file.
// This script returns HTTP code 200 + credentials on success and 403 if not


    //Get associative array of all users and their attributes
    //Example user file:
    // {"id":"sballmer","auth_id":"developers","type":["judge"],"name":"Steve Ballmer"},
    // {"id":"datkinson","auth_id":"asdf","type":["judge","advisor"],"name":"Darren Atkinson"},
    // {"id":"rdavis","auth_id":"qwerty","type":["advisor"],"name":"Ruth Davis"},
    // {"id":"shane","auth_id":"theman","type":["admin"],"name":"Shane Wibeto"}
    $users_file = file_get_contents("../json/users.json");
    $users = json_decode($users_file, true);
    $this_user = [];
    //Get login POST data
    $form_login = file_get_contents("php://input");
    $form_login = json_decode($data, true);
    if (!array_key_exists("username", $login) or !array_key_exists("password", $login)) {
        http_response_code(400);
        echo "Bad response";
        exit("Malformed POST");
    }
    $form_username = $login["username"];
    $form_password = $login["password"];

    //Check POST'd user->pass against our user->password
    $found = 0;
    foreach $users as $user {
        if (in_array($form_username, $user)) {
            if ($form_password == $user["auth_id"]) {
                $this_user = $user;
                unset($this_user["auth_id"]);//Don't ever return password! >:(
                $found = 1;
                break;
            }
        }
    }

    if ($found == 1) {
        http_response_code(200);
        echo json_encode($this_user); // Return user
    }
    else {
        http_response_code(403);
        echo '<div class="login_result">' . "buzzer wrong answer" . '</div>';
    }
?>
