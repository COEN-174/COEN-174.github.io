function advisorReports(){
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/advisor_csv.php";
    
}

function shanesReport() {}

function teamScore() {
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/write_csv.php";

    //Set judge
    var judge = document.getElementById("judge_id")["innerHTML"];
    //Set array of advisors
    var advisor_container = document.getElementsByClassName("advisor_container");//Array of advisors
    var advisors = [];
    for(var i = 0; i < advisor_container.length; i++) {
        advisors.push(advisor_container[i]["innerHTML"])
    }

    //Set Judge auth
    var judge_auth = document.getElementById("judge_auth")["innerHTML"]; // This comes from logging in
    
    //Generate array of team members
    var team_container = document.getElementById("team_container");
    team_container = team_container.getElementsByClassName("chip noselect");
    var team = [];
    for(var i = 0; i < teamMembers.length; i++) {
        team.push(teamMembers[i]["innerHTML"]);
    }

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
    payload["judge"] = judge; // judge=> ""
    payload["post_auth"] = judge_auth; //judge_auth => ""
    payload["advisors"] = advisors; //advisors => []
    payload["project"] = project; //project => ""
    payload["members"] = team; //members => []
    payload["scores"] = grades; //scores => {}
    payload["emphases"] = categories; //categories=>{}
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
        }
        else if (http.readyState == 4 && http.status == 403) {
            console.log("Wrong credentials: Error %d", http.status);
            response = "ERROR";
        }
    }

    http.send(payload);
    });
}
    /* May be removed later :///
    //Sum all selected scores
    var score = 0;
    for (var i = 0; i < scores.length; i++) {
        score += parseInt(scores[i].name);
    }
    payload["Score"] = score.toString();
    console.log("Score: %s", payload["Score"]);
    */
