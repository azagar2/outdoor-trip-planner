function addEvent() {
    
    var temp = $('.event:checked');
    alert(temp.val());
}

function popList(POI) {
    
      $.ajax({
        type: "POST",
        url: "/assignment4/database.php",
        data: "poi="+POI,
        contentType: "application/x-www-form-urlencoded",
        dataType: "json",
        success: function(data){
            var list = data.split(" ");
            $('#placeName').empty();
            for (var i = 0; i < list.length; i+=2)
            {
	            var entry = list[i] + " " + list[i+1];
	            $('#placeName').append("<option value='" + entry + "'>" + entry + "<br>");
            }
        }, 
        failure: function(errMsg) {
            alert(errMsg);
        }
  });
}

function exitProgram() {
	
	alert("Goodbye!");
}

/*
// doesn't seem to work yet..
function compareDates()
{
    var startDate = new Date($("#startDate").value);
    var endDate = new Date($("#endDate").value);

    if (startDate.getTime() > endDate.getTime())
    {
        alert ("Date error!");
        return false;
    }
}
*/