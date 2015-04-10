<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="script.js"></script>
    <?php
        include("database.php");
    ?>
    <title>Add Event</title>
</head>

<body>
    <h1>Add Event</h1><br>

    <form action="database.php" method="post" id="eventForm">
        <input type="radio" name="event" onclick="popList('HikingTrail')"> Hiking Trail <br>
        <input type="radio" name="event" onclick="popList('Campground')"> Campground <br>
        <input type="radio" name="event" onclick="popList('Landmark')"> Landmark <br><br>
        <select id="placeName" name="placeName" form="eventForm"></select><br><br>
        Event Name: <input type="text" name="eventName"><br>
        <br>
        Start Date: <input type="date" id="startDate" name="eventStartDate"><br>
        <br>
        End Date: <input type="date" id="endDate" name="eventEndDate"><br>
        <br>
        Number in group: <input type="text" name="noInEventGroup"><br>
        <br>
        Username: rwoods8 <input type="hidden" name="username" value="rwoods8"> 
        <input type="hidden" name="tripName" value="##tripName##"> 
        <input type="hidden" name="form_type" value="addEvent"><br>
        <br>
        <input type="submit" value="Add Event" onclick="compareDates()"><br><br>
    </form>
    
</body>
</html>