<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <title>View by Type</title>
    <?php
        include("database.php");
    ?>
</head>

<body>
    <h1>Places of Interest by Type</h1>
    
    <button type='button' onclick="location.href = 'index.php'">Home</button><br><br>

    <table>
        <thead>
            <th>Hiking Trails</th>
            <th>Campgrounds</th>
            <th>Landmarks</th>
        </thead>
		<tbody>
			<?php
				viewType();
			?>
		</tbody>
    </table>
</body>
</html>
