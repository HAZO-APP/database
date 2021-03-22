<?php
	include 'SERVER.php';
	$lat = doubleval($_GET['lat']);
    
    $lon = doubleval($_GET['lon']);

    $radius = doubleval($_GET['rad']);

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
	while ($command->fetch()){

		//$result = array("question_ID"=>$question_ID,"Question"=>$question);

		echo "id:".$id.",Lon:".$longitude.",lat:".$latitude.",type:".$type.",upVote:".$upVote.",downVote:".$downVote.",visitors:".$visitors."<br>";
	}
?>