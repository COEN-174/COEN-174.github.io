function login(username, password){
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/login.php";
    var payload = {};
    payload["username"] = username;
    payload["password"] = password;
    
    payload = JSON.stringify(payload);

    var http = new XMLHttpRequest();
    http.open("POST", API_URL, true);
    http.setRequestHeader("Content-Type", "application/json");

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            console.log("Success! %s", http.responseText);
            var user_creds = http.responseText;
            // Will be a user object with password unset
            /*
                {
                  "username":"datkinson",
                  "name":"Darren Atkinson",
                  "type":["judge"],
                  "session":["Computer Engineering","1"]
                }
            */
            return user_creds;
        }
        else if (http.readyState == 4 && http.status == 403) {
            console.log("Could not log in");
        }
    }
    http.send(payload);
}

// Will create JSON project objects to be displayed in left column
// depending on what session the judge is judging
function populate_column(session, rest_auth){
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/populate_column.php";
    var payload = {};
    payload["session"] = session;
    payload["rest_auth"] = rest_auth;
    
    payload = JSON.stringify(payload);

    var http = new XMLHttpRequest();
    http.open("POST", API_URL, true);
    http.setRequestHeader("Content-Type", "application/json");

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            console.log("Success! %s", http.responseText);
            var return_data = http.responseText;
            return return_data;
        }
        else if (http.readyState == 4 && http.status == 403) {
            console.log("Could not populate column %s", http.responseText);
        }
    }
    http.send(payload);
}

// Returns array of all projects and a download link to csv
function shanesReport(username, rest_auth){
    if (username == "shane") {
        var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/shanes_report.php";
        //var username = document.getElementById("username")["innerHTML"];
        //var rest_auth = document.getElementById("rest_auth")["innerHTML"];
        var payload = {};
        payload["username"] = username;
        payload["rest_auth"] = rest_auth;
        payload = JSON.stringify(payload);

        var http = new XMLHttpRequest();
        http.open("POST", API_URL, true);
        http.setRequestHeader("Content-Type", "application/json");

        http.onreadystatechange = function() {
            if (http.readyState == 4 && http.status == 200) {
                console.log("Success! %s", http.responseText);
                var return_data = http.responseText;
                return return_data;
            }
            else if (http.readyState == 4 && http.status == 403) {
                console.log("Could not get report %s", http.responseText);
            }
        }
        http.send(payload);
    }
    else {
        console.log("You're not shane");
    }
}


//Expects advisor = "Some Advisor"
function advisorReport(){
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/advisor_report.php";
    //var name = document.getElementById("name")["innerHTML"];
    //var username = document.getElementById("username")["innerHTML"];
    //var rest_auth = document.getElementById("rest_auth")["innerHTML"];
    var payload = {};
    payload["name"] = "Ahmed Amer";
    payload["username"] = "aamer";
    payload["rest_auth"] = rest_auth;
    payload = JSON.stringify(payload);

    var http = new XMLHttpRequest();
    http.open("POST", API_URL, true);
    http.setRequestHeader("Content-Type", "application/json");

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            console.log("Success! %s", http.responseText);
            var return_data = http.responseText;
            return return_data;
        }
        else if (http.readyState == 4 && http.status == 403) {
            console.log("Could not get report %s", http.responseText);
        }
    }
    http.send(payload);
}

// session should be of form ["Session","Number"]
//      e.g. ["Computer Engineering","1"]
//function sessionReport(session, username, rest_auth) {
function sessionReport() {
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/session_report.php";
    //session[0] = document.getElementById("session_name")["innerHTML"];
    //session[1] = document.getElementById("session_number")["innerHTML"];
    //var username = document.getElementById("username")["innerHTML"];
    //var rest_auth = document.getElementById("rest_auth")["innerHTML"];
    session = ["Computer Engineering","1"];
    var payload = {};
    payload["session"] = session;
    payload["username"] = username;
    payload["rest_auth"] = rest_auth;
    payload = JSON.stringify(payload);

    
    var http = new XMLHttpRequest();
    http.open("POST", API_URL, true);
    http.setReqestHeader("Content-Type", "application/json");

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            console.log("Success! %s", http.responseText);
            var return_data = http.responseText;
            return return_data;
        }
        else if (http.readyState == 4 && http.status == 403) {
            console.log("Could not get report %s", http.responseText);
        }
    }
    http.send(payload);
}

function submit(username, rest_auth) {
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/submit.php";

    //Set judge
    //var username = document.getElementById("judge_id")["innerHTML"];
    //Set array of advisors
    var advisor_container = document.getElementsByClassName("advisor_container");//Array of advisors
    var advisors = [];
    for(var i = 0; i < advisor_container.length; i++) {
        advisors.push(advisor_container[i]["innerHTML"])
    }

    //Set Judge auth
    //var rest_auth = document.getElementById("rest_auth")["innerHTML"]; // This comes from logging in
    
    //Generate array of team members
    var team_container = document.getElementById("team_container");
    team_container = team_container.getElementsByClassName("chip noselect");
    var team = [];
    for(var i=0; i < team_container.length; i++) {
        team.push(team_container[i]["innerHTML"]);
    }
    /*for(var i = 0; i < teamMembers.length; i++) {
        team.push(teamMembers[i]["innerHTML"]);
    }*/

    //Generate project name
    var project = document.getElementById("project_id")["innerHTML"];

    //Generate hash of categories -> scores
    var grades = {};
    var categories = document.getElementsByClassName("category_label noselect style-scope judging-category-fivepoint");
    var scores = document.getElementsByClassName("category_radio_button noselect style-scope judging-category-fivepoint x-scope paper-radio-button-0 iron-selected");
    if(scores.length != categories.length) {
        console.log("Fill out all categories pls");
        return false; // Not all score fields are filled out
    }
    for(var i = 0; i < categories.length; i++) {
        grades[categories[i]["innerHTML"]] = scores[i].name;
    }

    //Set array of considerations
    //Don't know how to do this

    //Set Comments
    var comments = document.getElementsByClassName("mirror-text style-scope iron-autogrow-textarea");
    comments = comments[0]["innerHTML"];

    //Build Payload
    var payload = {};
    payload["username"] = username;
    payload["rest_auth"] = rest_auth; //judge_auth => ""
    payload["advisors"] = advisors; //advisors => []
    payload["project"] = project; //project => ""
    payload["members"] = team; //members => []
    payload["scores"] = grades; //scores => {}
    payload["considerations"] = considerations; //considerations=>[]
    payload["comments"] = comments; //comments => ""

    console.log(payload);
    var http = new XMLHttpRequest();
    http.open("POST", API_URL, true);
    payload = JSON.stringify(payload);
    http.setRequestHeader("Content-Type","application/json");

    var response = "";

    http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            console.log("Success! %s", http.responseText);
            response = http.responseText;
            return response;
        }
        else if (http.readyState == 4 && http.status == 403) {
            console.log("Wrong credentials: Error %d", http.status);
            response = "ERROR";
        }
    }

    http.send(payload);
}
