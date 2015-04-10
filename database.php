<?php
$servername = "localhost";
$username = "root";
$password = "se3309";
$database = "3309";

/* CREATE CONNECTION CHECK FOR ERRORS */
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

// define functions first

/* LIST TRIP PLANS */
function listTripPlans() {
 
    global $conn;
    $plans = "";
    $block = $conn->query("SELECT tripName, tripStartDate, tripEndDate, noInTripGroup, COUNT(TripEvent.tripID) AS numEvents FROM TripPlan LEFT JOIN TripEvent USING (tripID) GROUP BY tripID;");
    
    if ($block && $block->num_rows) {
        $rowcount = true;
    }
    while ($row = $block->fetch_assoc())
    {
        if ($rowcount) {
            $plans .= "<tr>";
            $plans .= "<td>" . "<input type='radio' class='event' name='tripName' value='" . $row["tripName"] . "' checked>" . $row["tripName"] . "</td>";
            $plans .= "<td>" . $row["numEvents"] . "</td>";
            $plans .= "<td>" . $row["tripStartDate"] . "</td>";
            $plans .= "<td>" . $row["tripEndDate"] . "</td>";
            $plans .= "<td>" . $row["noInTripGroup"] . "</td>";
            $plans .= "</tr>";
            $rowcount = false;
        }
        else {
            $plans .= "<tr>";
            $plans .= "<td>" . "<input type='radio' class='event' name='tripName' value='" . $row["tripName"] . "'>" . $row["tripName"] . "</td>";
            $plans .= "<td>" . $row["numEvents"] . "</td>";
            $plans .= "<td>" . $row["tripStartDate"] . "</td>";
            $plans .= "<td>" . $row["tripEndDate"] . "</td>";
            $plans .= "<td>" . $row["noInTripGroup"] . "</td>";
            $plans .= "</tr>";
        }
    }
    $block->free();
    echo $plans;
}

/* ADD NEW PLAN */
function addPlan() {
    global $conn;
    
    //local variables
    $tripName = $_POST["tripName"];
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $noInTripGroup = $_POST["noInTripGroup"];
    $username = $_POST["username"];
    
    //Error checking before insert statement (checks noInGroup and tripName) **NEED DATE
    if ($tripName == '') {
	    echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Error: You must provide a trip name.");   
    }
    $checkName = $conn->query("SELECT * FROM TripPlan WHERE tripName='$tripName'");
    if ($checkName->num_rows > 0) {
	    echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Error: This trip name already exists in the database.");        
    }
    if (!intval($_POST["noInTripGroup"])) {
	    echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Invalid number in group.");
    }
    
    //insert statement
    $check = $conn->query("INSERT INTO TripPlan(tripName, tripStartDate, tripEndDate, noInTripGroup, username) VALUES (
    '$tripName', '$startDate', '$endDate', '$noInTripGroup', '$username')");
    
    //success messages
    if ($check) {
        echo "Successfully added Trip Plan to database!";
    }
    else {
	    echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Error! Please ensure all fields are filled and are valid."); 
    }
    echo "<br><br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br>";
}


/* ADD EVENT - INSERT INTO DATABASE (AFTER PAGE 2) */
function addEvent() {
    global $conn;
    $placeName = $_POST["placeName"];
    $eventName = $_POST["eventName"];
    $eventStartDate = $_POST["eventStartDate"];
    $eventEndDate = $_POST["eventEndDate"];
    $noInEventGroup = $_POST["noInEventGroup"];
    $username = "rwoods8";
    $tripName = $_POST["tripName"];
 
    // error checking for name
    //get tripID using tripName
    if ($eventName == '') {
	    echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Error: You must provide an event name.");   
    }
    $block = $conn->query("SELECT tripID FROM TripPlan WHERE tripName='$tripName';");
    while ($row = $block->fetch_assoc())
    {
        $tripID = $row["tripID"];
    }
    $block->free();
    //get placeID using placeName
    $block = $conn->query("SELECT placeID FROM PlaceOfInterest WHERE placeName='$placeName';");
    while ($row = $block->fetch_assoc())
    {
        $placeID = $row["placeID"];
    }
    $block->free();
    
    // error checking to make sure event name is not repeated 
    $checkName = $conn->query("SELECT * FROM TripEvent WHERE tripID='$tripID' AND placeID=$placeID;");
    if ($checkName->num_rows > 0) {
        echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Error: This event name already exists for this trip.");        
    }
    
    //Error checking before insert statement
    $number = intval($_POST["noInEventGroup"]);
    if ($number == 0) {
	    echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Invalid number in group!");  
    }
    // check to make sure event# <= trip#
    $tripNumber = '';
    $block = $conn->query("SELECT noInTripGroup FROM TripPlan WHERE tripName='$tripName';");
	while ($row = $block->fetch_assoc())
    {
        $tripNumber = $row["noInTripGroup"];
    }
    $block->free();
    if ($number > $tripNumber) {
	    echo "<br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br><br>";
        die("Invalid number in group! Must be less than number of people in trip.");  
    }
    
    //insert statement
    $check = $conn->query("INSERT INTO TripEvent(tripID, placeID, eventName, eventStartDate, eventEndDate, noInEventGroup) VALUES (
    '$tripID', '$placeID', '$eventName', '$eventStartDate', '$eventEndDate', '$noInEventGroup')");
    
    //success messages
    if ($check) {
        echo "Successfully added Trip Event to database!";
    }
    else {
        echo ("Error! Please ensure all fields are filled and are valid."); 
    }
    echo "<br><br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br>";
}


/* DELETE EVENT */
function deleteEvent() {
    global $conn;
    $eventName = $_POST["eventName"];
    $tripName = $_POST["tripName"];
    
    //get tripID using tripName
    $block = $conn->query("SELECT tripID FROM TripPlan WHERE tripName='$tripName';");
    while ($row = $block->fetch_assoc())
    {
        $tripID = $row["tripID"];
    }
    $block->free();
    //get placeID using placeName
    $block = $conn->query("SELECT placeID FROM TripEvent WHERE eventName='$eventName';");
    while ($row = $block->fetch_assoc())
    {
        $placeID = $row["placeID"];
    }
    $block->free();
    
    //delete statement
    $check = $conn->query("DELETE FROM TripEvent WHERE tripID='$tripID' AND placeID='$placeID';");
    //success messages
    if ($check) {
        echo("Successfully deleted Trip Event from database!");
    }
    else {
        echo("Invalid delete statement."); 
    }

    echo "<br><br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br>";
}

function viewTrip () {
    global $conn;
    $tripName = $_POST["tripName"];
    
    $plans = "<html>";
    $plans .= "<head>";
    $plans .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">";
    $plans .= "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js\"></script>";
    $plans .= "</head>";
    $plans .= "<body>";
    $plans .= "<h1> $tripName Details</h1>";
    $plans .= "<h3> Events </h3>";
    $plans .= "<form action='database.php' method='post'>";
    $plans .= "<table id=\"eventTable\">";
    $plans .= "<thead>";
    $plans .= "<th>Event Name</th>";
    $plans .= "<th>Place of Interest</th>";
    $plans .= "<th>Start Date</th>";
    $plans .= "<th>End Date</th>";
    $plans .= "<th>Number in group</th>";
    $plans .= "</thead>";
    $plans .= "<tbody>";
    
    $block = $conn->query("SELECT eventName, placeName, eventStartDate, eventEndDate, noInEventGroup 
FROM TripEvent e INNER JOIN TripPlan p ON e.tripID=p.tripID INNER JOIN PlaceOfInterest i ON e.placeID=i.placeID WHERE p.tripName='$tripName';");

    if ($block && $block->num_rows) {
        $rowcount = true;
    }
 
    while ($row = $block->fetch_assoc())
    {
        if ($rowcount) {
            $plans .= "<tr>";
            $plans .= "<td>" . "<input type='radio' name='eventName' value='" . $row["eventName"] . "' checked>" . $row["eventName"] . "<br>" . "</td>";
            $plans .= "<td>" . $row["placeName"] . "</td>";
            $plans .= "<td>" . $row["eventStartDate"] . "</td>";
            $plans .= "<td>" . $row["eventEndDate"] . "</td>";
            $plans .= "<td>" . $row["noInEventGroup"] . "</td>";
            $plans .= "</tr>";
            $rowcount = false;
        }
        else {
            $plans .= "<tr>";
            $plans .= "<td>" . "<input type='radio' name='eventName' value='" . $row["eventName"] . "'>" . $row["eventName"] . "<br>" . "</td>";
            $plans .= "<td>" . $row["placeName"] . "</td>";
            $plans .= "<td>" . $row["eventStartDate"] . "</td>";
            $plans .= "<td>" . $row["eventEndDate"] . "</td>";
            $plans .= "<td>" . $row["noInEventGroup"] . "</td>";
            $plans .= "</tr>";
        }
    }
    $block->free();
    $plans .= "</tbody>";
    $plans .= "</table>";

    $plans .= "<input type='hidden' name='tripName' value='$tripName'><br><br>";
    $plans .= "<input type='submit' name='modEvent' value='Add Event'>";
    $plans .= " <input type='submit' name='modEvent' value='Delete Event'>";
    $plans .= "<br><br><button type='button' onclick=\"location.href = 'index.php'\">Home</button><br>";
    $plans .= "</form>";
    $plans .= "</body>";
    $plans .= "</html>";
    echo $plans;   
}

function deleteTrip () {
    global $conn;
    $tripName = $_POST["tripName"];
    
    // Get tripID using tripName
    $block = $conn->query("SELECT tripID FROM TripPlan WHERE tripName='$tripName';");
    while ($row = $block->fetch_assoc())
    {
        $tripID = $row["tripID"];
    }
    $block->free();
    
    // Delete statement query
    $check = $conn->query("DELETE FROM TripPlan WHERE tripID='$tripID';");
    
    // Success messages
    if ($check) {
        echo("Successfully deleted Trip Plan from database!");
    }
    else {
        echo("Invalid delete statement!"); 
    }
    echo "<br><br><button type=\"button\" onclick=\"location.href = 'index.php'\">Home</button><br>";
}

function sendBackPOI() {
    global $conn;
    $POI = $_POST["poi"];
    $myString = "";
    $block = $conn->query("SELECT placeName FROM $POI INNER JOIN PlaceOfInterest USING (placeID);");
    while ($row = $block->fetch_assoc())
    {
        $myString .= $row["placeName"] . " ";
    }
    $block->free();
    
    echo json_encode($myString);
}

function viewCity() {
	global $conn;
	
	// display POIs by city
	$cityname = '';
	$plans = '';
    
    $block = $conn->query("SELECT cityName FROM City ORDER BY cityName");
    while ($row = $block->fetch_assoc())
    {
    	$plans .= "<tr>";
		$cityname = $row["cityName"];
		$plans .= "<td>" . $cityname . "</td>";
		$plans .= "<td>";
		$plans .= "<ul>";
		
		// populate <li> using POI query results
        $block1 = $conn->query("SELECT placeName FROM PlaceOfInterest p INNER JOIN Landmark l ON p.placeID=l.placeID INNER JOIN City c ON 								l.cityID=c.cityID WHERE cityName='$cityname'");   
		if ($block1->num_rows > 0)
		{
			while ($cell = $block1->fetch_assoc())
			{
            $plans .= "<li>" . $cell["placeName"] . "</li>";
        	} 
        	$block1->free();
		}
        $block2 = $conn->query("SELECT placeName FROM PlaceOfInterest p INNER JOIN HikingTrail h ON p.placeID=h.placeID INNER JOIN Park k ON 							h.parkID=k.parkID INNER JOIN City c ON k.cityID=c.cityID WHERE cityName='$cityname'");   
        if ($block2->num_rows > 0)
        {
	        while ($cell = $block2->fetch_assoc())
			{
            	$plans .= "<li>" . $cell["placeName"] . "</li>";
        	} 
        	$block2->free();
        }
        $block3 = $conn->query("SELECT placeName FROM PlaceOfInterest p INNER JOIN HikingTrail h ON p.placeID=h.placeID INNER JOIN Park k ON 							h.parkID=k.parkID INNER JOIN City c ON k.cityID=c.cityID WHERE cityName='$cityname'");   
        if ($block3->num_rows > 0)
        {
	       while ($cell = $block3->fetch_assoc())
		   	{
            	$plans .= "<li>" . $cell["placeName"] . "</li>";
        	} 
        	$block3->free();    
        }

        $plans .= "</ul>";
        $plans .= "</td>";
        $plans .= "</tr>";
    }
    $block->free();
    echo $plans;
}

function viewType() {
	global $conn;
	
	// display POIs by city
	$cityname = '';
	$plans = '';
    
    $plans .= "<tr>";
    
    $plans .= "<td>";
    $plans .= "<ul>";
    $block1 = $conn->query("SELECT placeName FROM PlaceOfInterest p INNER JOIN HikingTrail h ON p.placeID=h.placeID");
	while ($cell = $block1->fetch_assoc())
	{
       	$plans .= "<li>" . $cell["placeName"] . "</li>";
    } 
    $block1->free();
    $plans .= "</ul>";
    $plans .= "</td>";
    
    $plans .= "<td>";
    $plans .= "<ul>";
    $block2 = $conn->query("SELECT placeName FROM PlaceOfInterest p INNER JOIN Campground c ON p.placeID=c.placeID");
	while ($cell = $block2->fetch_assoc())
	{
       	$plans .= "<li>" . $cell["placeName"] . "</li>";
    } 
    $block2->free();
    $plans .= "</ul>";
    $plans .= "</td>";
    
    $plans .= "<td>";
    $plans .= "<ul>";
    $block3 = $conn->query("SELECT placeName FROM PlaceOfInterest p INNER JOIN Landmark l ON p.placeID=l.placeID");
	while ($cell = $block3->fetch_assoc())
	{
       	$plans .= "<li>" . $cell["placeName"] . "</li>";
    } 
    $block3->free();
    $plans .= "</ul>";
    $plans .= "</td>";
    
    $plans .= "</tr>";
    echo $plans;
}

// Handles post requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST["poi"])) {
        sendBackPOI();
    }
    if ($_POST["form_type"] === 'addPlan') {
        addPlan();
    }
    if ($_POST["form_type"] === 'addEvent') {
        addEvent();
    }
    if ($_POST["mod_type"] === 'View Trip') {
        viewTrip();
    }
    if ($_POST["mod_type"] === 'Delete Trip') {
        deleteTrip();
    }
    if ($_POST["modEvent"] === 'Add Event') {
        $thing = file_get_contents("addEvent.php");
        echo str_replace("##tripName##", $_POST["tripName"], $thing);
    } 
    if ($_POST["modEvent"] === 'Delete Event') {
	    deleteEvent();
    }
    
}


?>