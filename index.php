<?php
	include 'SERVER.php'
	$lat = $_GET['lat'];
	floatval($lat);
    
    $lon = $_GET['lon'];
	floatval($lon);

    $radius = $_GET['rad'];
	floatval($radius);

    $conn = new mysqli(DB_SERVER, DB_NAME, DB_PASSWORD, DB_DATABASE);

	$command = $conn->prepare("SELECT `id`, `longitude`, `latitude`, `type`, `upVote`, `downVote`, `visitors` FROM `locations` WHERE `longitude` BETWEEN ? AND ? AND `latitude` BETWEEN ? AND ? ");

	$command->bind_param("iiii", $lon - $radius, $lon + $radius, $lat - $radius, $lat + $radius);

	$command->execute();

	$command->store_result();

	$command->bind_result($location);

	while ($command->fetch()){
		echo $location;
	}
?>