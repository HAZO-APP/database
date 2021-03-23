<?php
	include 'SERVER.php';

	if(isset($_GET['lat']))
	{
		$lat = doubleval($_GET['lat']);	
	}
	else
	{
		echo "error: latitude not set";
		return;
	}
    
    if(isset($_GET['lon']))
	{
  		$lon = doubleval($_GET['lon']);
	}
	else
	{
		echo "error: longitude not set";
		return;
	}

	if(isset($_GET['rad']))
	{
    	$radius = doubleval($_GET['rad']);
    }
    else
    {
		echo "error: radius not set";
		return;
    }

    $conn = new mysqli(DB_SERVER, DB_NAME, DB_PASSWORD, DB_DATABASE);

	$command = $conn->prepare("SELECT `id`, `longitude`, `latitude`, `type`, `upVote`, `downVote`, `visitors` FROM `locations` WHERE `longitude` BETWEEN ? AND ? And `latitude` BETWEEN ? AND ?");

	$lonMin = $lon - $radius;
	$lonMax = $lon + $radius;

	$latMin = $lat - $radius;
	$latMax = $lat + $radius;

	$command->bind_param("dddd", $lonMin, $lonMax, $latMin, $latMax);


	$command->execute();

	$command->store_result();

	$command->bind_result($id, $longitude, $latitude, $type, $upVote, $downVote, $visitors);
	while ($command->fetch())
	{
		echo "id:".$id.",lat:".$latitude.",lon:".$longitude.",type:".$type.",upVote:".$upVote.",downVote:".$downVote.",visitors:".$visitors."<br>";
	}
	$conn->close();
?>