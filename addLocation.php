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

	if(isset($_GET['type']))
	{
		$type = intval($_GET['type']);	
	}
	else
	{
		echo "error: type not set";
		return;
	}

    $conn = new mysqli(DB_SERVER, DB_NAME, DB_PASSWORD, DB_DATABASE);

    //checking if a location 
    $radiusMinimum = 1/111111*10;

    $command = $conn->prepare("SELECT `id`, `longitude`, `latitude`, `type`, `upVote`, `downVote`, `visitors` FROM `locations` WHERE `longitude` BETWEEN ? AND ? And `latitude` BETWEEN ? AND ? AND `type` = ?");

    $lonMin = $lon - $radiusMinimum;
	$lonMax = $lon + $radiusMinimum;

	$latMin = $lat - $radiusMinimum;
	$latMax = $lat + $radiusMinimum;

	$command->bind_param("ddddi", $lonMin, $lonMax, $latMin, $latMax, $type);

	$command->execute();

	$command->store_result();

	if($command->num_rows == 0)
	{
		$command->close();
		if($command = $conn->prepare("INSERT INTO `locations`(`longitude`, `latitude`, `type`) VALUES (?,?,?)"))
		{
			$command->bind_param("ddi", $lon, $lat, $type);

			if($command->execute() === true)
			{
				echo "success: Location added";
			}
			else
			{
				echo "error: Failure to insert location";
			}
		}
		else
		{
			echo "error: internal error";
		}
	}
	else
	{
		echo "error: Location too close to another location";
	}

	$conn->close();


?>