<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <?php
        include("database.php");
    ?>
    <title>Add Plan</title>
</head>
<body>
    <h1>Add Plan for rwoods8</h1><br>

    <form action="database.php" method="post">
        Trip Name: <input type="text" name="tripName"><br>
        <br>
        Start Date: <input type="date" id="startDate" name="startDate"><br>
        <br>
        End Date: <input type="date" id="endDate" name="endDate"><br>
        <br>
        Number in group: <input type="text" name="noInTripGroup"><br>
        <br>
        Username: rwoods8 <input type="hidden" name="username" value="rwoods8"> <input type="hidden" name="form_type" value="addPlan"><br>
        <br>
        <input type="submit" value="Add Plan"><br><br>
        </form>
        <button type='button' onclick="location.href = 'index.php'">Home</button><br>
</body>
</html>
