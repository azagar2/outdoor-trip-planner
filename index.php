<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="script.js"></script>
<!--
    <script>
        $($.doge);
    </script>
-->
    <?php
        include("database.php");
    ?>
    <title>Outdoors Trip Planner</title>
</head>

<body>
    <h1>Outdoors Trip Planner</h1>
    <h2>Trip Plans </h2>
    <button type="button" onclick="location.href = 'addPlan.php'">Add New Plan</button><br>
    <br>

    <form action="database.php" method="post" id="planList">
		<table>
			<thead>
				<th>Trip Name</th>
				<th> Number of Events </th>
				<th> Start Date </th>
				<th> End Date </th>
				<th> Number in Trip Group </th>
			</thead>
			<tbody>
            <?php
                listTripPlans();
            ?>
			</tbody>
		</table>
        <br>
        
        <h3>Trip Options</h3>
        <input type = "submit" name ="mod_type" value="View Trip"> 
        <input type = "submit" name="mod_type" value = "Delete Trip">
        <br><br>
    </form>
    <h3>Other Options</h3>
    View places of interest by:  <br><br>
    <button type="button" onclick="location.href = 'viewByCity.php'">City</button>
    <button type="button" onclick="location.href = 'viewByType.php'">Type</button>
    <br><br>
    <button type="button" onclick="exitProgram()">Exit</button>

</body>
</html>
