function advisorReports(){
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/advisor_csv.php";
    
}

function shanesReport() {}

function teamScore() {
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/write_csv.php"
    var payload = {};

    //var judge = "Captain America";
    var judge = _userId;
    //var advisor = "Bullox";
    var advisor = _projectArr[menu.selected/2].advisors;

    //Set advisor
    payload["Advisor"] = advisor;

    //Generate dot separated string of team members
    var teamMembers = document.getElementById("team_container");
    teamMembers = teamMembers.getElementsByClassName("chip noselect");
    var teamName = [];
    for(var i = 0; i < teamMembers.length; i++) {
        teamName.push(teamMembers[i]["innerHTML"]);
    }
    teamName = teamName.toString(); 
    teamName = teamName.replace(/,/g,"."); 
    payload["Team"] = teamName;
    console.log("Team to string: %s", payload["Team"]);


    //Generate JSON of categories -> scores
    var categories = document.getElementsByClassName("category_label noselect style-scope judging-category-fivepoint");
    var scores = document.getElementsByClassName("category_radio_button noselect style-scope judging-category-fivepoint x-scope paper-radio-button-0 iron-selected");
    if(scores.length != categories.length) {
        return false; // Not all score fields are filled out
    }
    for(var i = 0; i < categories.length; i++) {
        payload[categories[i]["innerHTML"]] = scores[i].name;
    }

    //Sum all selected scores
    var score = 0;
    for (var i = 0; i < scores.length; i++) {
        score += parseInt(scores[i].name);
    }
    payload["Score"] = score.toString();
    console.log("Score: %s", payload["Score"]);

    //Set Considerations
    //Don't know how to grab button text

    //Set Comments
    var comments = document.getElementsByClassName("mirror-text style-scope iron-autogrow-textarea");
    payload["Comments"] = comments[0]["innerHTML"];

    //Set Judge
    payload["Judge"] = judge;

    console.log(payload);
    var http = new XMLHttpRequest();
    http.open("POST", API_URL, true);
    //http.setRequestHeader("user-is", username, false);
    payload = JSON.stringify(payload);
    http.setRequestHeader("Content-Type","application/json");

    http.send(payload);
    
}
