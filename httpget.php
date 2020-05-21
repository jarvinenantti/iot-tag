
<?php

/* Palauta kaikki rfid
loop ja json array */

// Include config file
require_once 'config.php';

$rfid = "";
$data = [];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "GET"){

		
	// Attempt select query execution
	$sql = "SELECT * FROM access";
	if($result = mysqli_query($link, $sql)){
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_array($result)){
				$data[] = $row['rfid'];	
			}
			mysqli_free_result($result);
			header('Content-type: application/json');
			echo json_encode($data);
		} else{
			echo "No records matching your query were found.";
		}
	} else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
	
    // Close connection
    mysqli_close($link);
}
?>