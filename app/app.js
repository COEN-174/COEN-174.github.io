var _apiBaseURL = "./php-cgi/";

function _doGet(path, data, callback) {
    var request = new XMLHttpRequest();
    var url = _apiBaseURL + path;
    if (data && typeof data === 'object') {
        url += "?";
        var str = [];
        for(var p in data) {
            if (data.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(data[p]));
            }
        }
        url += str.join("&");
    }
    console.log(url);
    request.open("GET", url, true);
    request.setRequestHeader("Content-Type", "application/json");
    request.onreadystatechange = function() {
        console.log(request.responseText);
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                var data = request.responseText ? JSON.parse(request.responseText) : null;
                callback({"data": data, "success": true});
            } else {
                callback({"success": false});
            }
        }
    }
    request.send();
}

function _doPost(path, data, callback) {
    _doGet(path, data, callback);
}

var judgesFile = "json/judges.json";
var projectsFile = "json/projects.json";
var sessionsFile = "json/sessions.json";
var adminFile = "json/admin.json";

var judgeIdentifierLength = 3;
var sessionIdentifierLength = 3;
var projectIdentifierLength = 3;

var advisorsFile = "json/advisors.json";
var advIdentifierLength = 3;

function tryAuth(identifier, callback) {
    _doGet("login.php", {"username": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data);
        }
    });
}

function getProjectsList(identifier, callback) {
    _doGet("get_projects.php", {"user_id": identifier}, function(object) {
        callback(object.data.projects);
    });
}


function searchJudges(identifier, callback) {
    if (identifier.length != judgeIdentifierLength) { callback(null); }
    readFile(judgesFile, function(judges) {
        var result = judges.filter(function(judge) {
            return judge.id === identifier;
        });
        if (result.length > 0) {
            callback(result[0]);
        } else {
            callback(null);
        }
    });
}

function searchAdvisors(identifier, callback) {
    if (identifier.length != advIdentifierLength) { callback(null); }
    readFile(advisorsFile, function(advisors) {
        var result = advisors.filter(function(advisor) {
            return advisor.id === identifier;
        });
        if (result.length > 0) {
            callback(result[0]);
        } else {
            callback(null);
        }
    });
}

function searchAdmin(identifier, callback) {
    if (identifier.length != advIdentifierLength) { callback(null); }
    readFile(adminFile, function(admins) {
        var result = admins.filter(function(admin) {
            return admin.id === identifier;
        });
        if (result.length > 0) {
            callback(result[0]);
        } else {
            callback(null);
        }
    });
}

function searchProjects(identifier, callback) {
    if (identifier.length != projectIdentifierLength) { callback(null); }
    readFile(projectsFile, function(projects) {
        var result = projects.filter(function(project) {
            return project.id === identifier;
        });
        if (result.length > 0) {
            callback(result[0]);
        } else {
            callback(null);
        }
    });
}

function getAllProjects(callback) {
    readFile(projectsFile, function(projects) {
        callback(projects);
    });
}

function searchSessions(identifier, callback) {
    if (identifier.length != sessionIdentifierLength) { callback(null); }
    readFile(sessionsFile, function(sessions) {
        var result = sessions.filter(function(session) {
            return session.id === identifier;
        });
        if (result.length > 0) {
            callback(result[0]);
        } else {
            callback(null);
        }
    });
}

function readFile(filename, callback) {
    var file = new XMLHttpRequest();
    file.overrideMimeType("application/json");
    file.open("GET", filename, true);
    file.onreadystatechange = function () {
        if (file.readyState === 4 && file.status == "200" && file.responseText) {
            callback(JSON.parse(file.responseText))
        }
    };
    file.send(null);
}

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
function populate_column(session){
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/populate_column.php";
    var payload = {};
    payload["session"] = session;

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
function shanesReport(username){
    if (username == "shane") {
        var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/shanes_report.php";
        //var username = document.getElementById("username")["innerHTML"];
        var payload = {};
        payload["username"] = username;
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
function advisorReport(name, username){
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/advisor_report.php";
    //var name = document.getElementById("name")["innerHTML"];
    //var username = document.getElementById("username")["innerHTML"];
    var payload = {};
    payload["name"] = name;
    payload["username"] = username;
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
function sessionReport(session_id, session_substr) {
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/session_report.php";
    //session[0] = document.getElementById("session_name")["innerHTML"];
    //session[1] = document.getElementById("session_number")["innerHTML"];
    //var username = document.getElementById("username")["innerHTML"];

    var payload = {};
    payload["session_id"] = session_id;
    payload["session_substr"] = session_substr;
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

function submit(username) {
    var API_URL = "http://students.engr.scu.edu/~pmiller/php-cgi/submit.php";

    //Set judge
    //var username = document.getElementById("judge_id")["innerHTML"];
    //Set array of advisors
    var advisor_container = document.getElementsByClassName("advisor_container");//Array of advisors
    var advisors = [];
    for (var i = 0; i < advisor_container.length; i++) {
        advisors.push(advisor_container[i]["innerHTML"])
    }

    //Generate array of team members
    var team_container = document.getElementById("team_container");
    team_container = team_container.getElementsByClassName("chip noselect");
    var team = [];
    for (var i = 0; i < teamMembers.length; i++) {
        team.push(teamMembers[i]["innerHTML"]);
    }

    //Generate project name
    var project = document.getElementById("project_id")["innerHTML"];

    //Generate hash of categories -> scores
    var grades = {};
    var categories = document.getElementsByClassName("category_label noselect style-scope judging-category-fivepoint");
    var scores = document.getElementsByClassName("category_radio_button noselect style-scope judging-category-fivepoint x-scope paper-radio-button-0 iron-selected");
    if (scores.length != categories.length) {
        console.log("Fill out all categories pls");
        return false; // Not all score fields are filled out
    }
    for (var i = 0; i < categories.length; i++) {
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
    http.setRequestHeader("Content-Type", "application/json");

    var response = "";

    http.onreadystatechange = function () {
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
};


