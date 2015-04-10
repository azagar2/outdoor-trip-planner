<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">

<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" type="text/javascript"></script>
    <title>View by City</title>
    <?php
        include("database.php");
    ?>
</head>

<body>
    <h1>Places of Interest by City</h1>
    
    <button type='button' onclick="location.href = 'index.php'">Home</button><br><br>

    <table>
        <thead>
            <th>City</th>
            <th>Place of Interest</th>
        </thead>
		<tbody>
			<?php
				viewCity();
			?>
		</tbody>
    </table>
</body>
</html>
