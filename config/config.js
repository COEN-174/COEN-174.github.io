/**
 * Created by blaketsuzaki on 11/28/16.
 */
var _apiBaseURL = "./../php-cgi/";

function getAdvisor(auth_id, identifier, callback) {
    _doGetConfig("get_advisors.php", {"auth_id": auth_id, "id": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data.advisor);
        }
    });
}

function getAdvisors(auth_id, callback) {
    _doGetConfig("get_advisors.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.advisors);
        }
    });
}

function getJudge(auth_id, identifier, callback) {
    _doGetConfig("get_judges.php", {"auth_id": auth_id, "id": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data.judge);
        }
    });
}

function getJudges(auth_id, callback) {
    _doGetConfig("get_judges.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.judges);
        }
    });
}

function getProject(auth_id, identifier, callback) {
    _doGetConfig("get_projects.php", {"auth_id": auth_id, "id": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data.project);
        }
    });
}

function getProjects(auth_id, callback) {
    _doGetConfig("get_projects.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.projects);
        }
    });
}

function getSessions(auth_id, callback) {
    _doGetConfig("get_sessions.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.sessions);
        }
    });
}

function _doGetConfig(path, data, callback) {
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
    request.open("GET", url, true);
    request.setRequestHeader("Content-Type", "application/json");
    request.onreadystatechange = function() {
        if (request.readyState === XMLHttpRequest.DONE && request.readyState === XMLHttpRequest.DONE) {
            console.log(request.responseText);
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
    var request = new XMLHttpRequest();
    var params = JSON.stringify(data);
    request.open("POST", _apiBaseURL + path, true);
    request.setRequestHeader("Content-type", "application/json");
    request.onreadystatechange = function() {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                callback({"data": JSON.parse(request.responseText), "success": true});
            } else {
                callback({"success": false});
            }
        }
    }
    request.send(params);
}