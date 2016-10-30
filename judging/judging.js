function teamScore() {

    //Generate dot separated string of team members
    var teamMembers = document.getElementsByClassName("chip noselect");
    for(var i = 0; i < teamMembers.length; i++) {
        console.log(teamMembers[i]["innerHTML"]);
    }
    console.log(teamMembers);
    teamMembers = teamMembers.toString(); 
    teamMembers = teamMembers.replace(",","."); 

    //Sum all selected scores
    var scores = document.getElementsByClassName("category_radio_button noselect style-scope judging-category-fivepoint x-scope paper-radio-button-0 iron-selected");
    var score = 0;
    for (var i = 0; i < scores.length; i++) {
        score += parseInt(scores[i].name);
    }
   // document.getElementById("tally").setAttribute("id", score); // Display score broken
    console.log("Team: %s Score: %d", teamMembers, score);
  
//    $.ajax({
//        method: "POST",
//        url: "write_csv.php",
//        data: { Team: teamMembers, Score: score }
//    });
}
