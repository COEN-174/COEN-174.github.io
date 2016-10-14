var judgesFile = "json/judges.json";
var judgeIdentifierLength = 4;

function searchJudges(identifier, callback) {
    if (identifier.length != judgeIdentifierLength) {
        callback(null);
    }
    readJudges(function(judges) {
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

function readJudges(callback) {
    var file = new XMLHttpRequest();
    file.overrideMimeType("application/json");
    file.open("GET", judgesFile, true);
    file.onreadystatechange = function () {
        if (file.readyState === 4 && file.status == "200" && file.responseText) {
            callback(JSON.parse(file.responseText))
        }
    };
    file.send(null);
}