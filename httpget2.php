
<?php

/* Gateway -> Server(http get)
ip/iottag/httpget.php?username=xxx&password=yyy
xxx = gatewayn id (userid)
yyy = salasana */

/* Server -> Gateway(json)
Select rfid from user
Palauta ovi ja kaikki rfidet jotka siihen sopii */

// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$username = $password = $search_rfid = $rfid = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "GET"){

	// Check if username is empty
    if(empty(trim($_GET["username"]))){
        $username_err = 'Please give username.';
    } else{
        $username = trim($_GET["username"]);
    }

	// Check if password is empty
    if(empty(trim($_GET['password']))){
        $password_err = 'Please give your password.';
    } else{
        $password = trim($_GET['password']);
    }
	
	// Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password, rfid FROM user WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password, $rfid);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
							// Credentials are valid
							$search_rfid = $rfid;
							//echo 'Credentials valid. ';
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
	// Check input errors before making a search
    if(empty($username_err) && empty($password_err)){
		
		// Attempt select query execution
		$sql = "SELECT doorname, rfid FROM access WHERE rfid = ?";
		
		    if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_rfid);
            
            // Set parameters
            $param_rfid = $search_rfid;
			
			// Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

			
				// Check if rfid exists, if yes then get doors
                if(mysqli_stmt_num_rows($stmt) >= 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $door, $rfid);
                    if(mysqli_stmt_fetch($stmt)){			 
						// An associative array
						$data = array("rfid"=> $rfid, "door"=>$door);
						header('Content-type: application/json');
						echo json_encode($data);
                    }
                } else{
                    // Display an error message if rfid doesn't exist
                    $username_err = 'No such rfid found.';
					echo 'No such rfid found.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
	}
	
    // Close connection
    mysqli_close($link);
}
?>