<?php
// Include config file
require_once 'config.php';

// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username']) || ($_SESSION['username'] != "admin")){
  header("location: login.php");
  exit;
}

// Define variables and initialize with empty values
$rfid = $doorname = "";
$rfid_err = $doorname_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	// Check if rfid is empty
    if(empty(trim($_POST["rfid"]))){
        $rfid_err = 'Please enter rfid.';
    } else{
        $rfid = trim($_POST["rfid"]);
    }
    
    // Check if doorname is empty
    if(empty(trim($_POST['doorname']))){
        $doorname_err = 'Please enter doorname.';
    } else{
        $doorname = trim($_POST['doorname']);
    }
			
	
	// Add access (check like login, add like register)
    if(empty($rfid_err) && empty($doorname_err)){
        // Prepare a select statement
        $sql = "SELECT rfid, doorname FROM access WHERE rfid = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_rfid);
            
            // Set parameters
            $param_rfid = $rfid;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                // Check if rfid exists, if yes then check doornames
                if(mysqli_stmt_num_rows($stmt) == 1){                    
					//Bind result variables (stmt and rfid and doorname they return)
					mysqli_stmt_bind_result($stmt, $rfid, $doornamecheck);
					if(mysqli_stmt_fetch($stmt)){
						if($doorname == $doornamecheck){
							/*Found to already have same door in accesses*/
							$doorname_err = 'Record already exists.';
						} else{
							// Doorname inserted successfully
						}
					}
                } else{
					// Display an error message if rfid doesn't exist
					//$rfid_err = 'There is no such rfid.';
				}
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
	
	// Check input errors before inserting in database
    if(empty($rfid_err) && empty($doorname_err)){
		
		// Prepare an insert statement
        $sql = "INSERT INTO access (rfid, doorname) VALUES (?, ?)";
		
		if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_rfid, $param_doorname);
            
            // Set parameters
            $param_rfid = $rfid;
            $param_doorname = $doorname;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
	
	// Close connection
	mysqli_close($link);
}	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin access control</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif;
		.wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
		<h2>Admin access control for rfids:</h2>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-group <?php echo (!empty($rfid_err)) ? 'has-error' : ''; ?>">
                <label>RIFD</label>
                <input type="text" name="rfid"class="form-control" value="<?php echo $rfid; ?>">
                <span class="help-block"><?php echo $rfid_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($doorname_err)) ? 'has-error' : ''; ?>">
                <label>doorname</label>
                <input type="text" name="doorname" class="form-control" value="<?php echo $doorname; ?>">
                <span class="help-block"><?php echo $doorname_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
			</div>
			<p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>
		</form>
	</div>
</body>
</html>
 