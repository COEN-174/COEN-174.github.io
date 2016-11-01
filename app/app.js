var judgesFile = "json/judges.json";
var projectsFile = "json/projects.json";
var sessionsFile = "json/sessions.json";
var judgeAuthIdentifierLength = 4;
var judgeIdentifierLength = 3;
var sessionIdentifierLength = 3;
var projectIdentifierLength = 3;

var advisorsFile = "json/advisors.json";
var advAuthIdentifierLength = 4;
var advIdentifierLength = 3;

function searchJudgesAuth(identifier, callback) {
    if (identifier.length != judgeAuthIdentifierLength) { callback(null); }
    readFile(judgesFile, function(judges) {
        var result = judges.filter(function(judge) {
            return judge.auth_id === identifier;
        });
        if (result.length > 0) {
            callback(result[0]);
        } else {
            callback(null);
        }
    });
}

function searchAdvisorsAuth(identifier, callback) {
    if (identifier.length != advAuthIdentifierLength) { callback(null); }
    readFile(advisorsFile, function(advisors) {
        var result = advisors.filter(function(advisor) {
            return advisor.auth_id === identifier;
        });
        if (result.length > 0) {
            callback(result[0]);
        } else {
            callback(null);
        }
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
