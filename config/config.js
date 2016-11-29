/**
 * Created by blaketsuzaki on 11/28/16.
 */
function getAdvisor(auth_id, identifier, callback) {
    _doGet("get_advisors.php", {"auth_id": auth_id, "id": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data.advisor);
        }
    });
}

function setAdvisor(auth_id, advisor, callback) {
    advisor.auth_id = auth_id;
    _doPost("add_advisor.php", advisor, function(object) {
        if (object.success) {
            callback(object);
        }
    });
}

function getAdvisors(auth_id, callback) {
    _doGet("get_advisors.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.advisors);
        }
    });
}

function getAdmin(auth_id, identifier, callback) {
    _doGet("get_admins.php", {"auth_id": auth_id, "id": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data.admin);
        }
    });
}

function setAdmin(auth_id, admin, callback) {
    admin.auth_id = auth_id;
    _doPost("add_admin.php", admin, function(object) {
        if (object.success) {
            callback(object);
        }
    });
}

function getAdmins(auth_id, callback) {
    _doGet("get_admins.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.admins);
        }
    });
}

function getJudge(auth_id, identifier, callback) {
    _doGet("get_judges.php", {"auth_id": auth_id, "id": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data.judge);
        }
    });
}

function setJudge(auth_id, judge, callback) {
    judge.auth_id = auth_id;
    _doPost("add_judge.php", judge, function(object) {
        if (object.success) {
            callback(object);
        }
    });
}

function getJudges(auth_id, callback) {
    _doGet("get_judges.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.judges);
        }
    });
}

function getProject(auth_id, identifier, callback) {
    _doGet("get_projects.php", {"auth_id": auth_id, "id": identifier}, function(object) {
        if (object.success && object.data) {
            callback(object.data.project);
        }
    });
}

function setProject(auth_id, project, callback) {
    project.auth_id = auth_id;
    _doPost("add_project.php", project, function(object) {
        if (object.success) {
            callback(object);
        }
    });
}

function getProjects(auth_id, callback) {
    _doGet("get_projects.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.projects);
        }
    });
}

function getSessions(auth_id, callback) {
    _doGet("get_sessions.php", {"auth_id": auth_id}, function(object) {
        if (object.success && object.data) {
            callback(object.data.sessions);
        }
    });
}

function setSession(auth_id, session, callback) {
    session.auth_id = auth_id;
    _doPost("add_session.php", session, function(object) {
        if (object.success) {
            callback(object);
        }
    });
}