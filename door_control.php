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
$doorname = "";
$doorname_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
    // Validate doorname
    if(empty(trim($_POST["doorname"]))){
        $doorname_err = "Please enter a doorname.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM door WHERE doorname = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_doorname);
            
            // Set parameters
            $param_doorname = trim($_POST["doorname"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
				//Check if door already exists
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $doorname_err = "This doorname already exists.";
                } else{
                    $doorname = trim($_POST["doorname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
	
	// Validate password
    if(empty(trim($_POST['doorname']))){
        $doorname_err = "Please enter a doorname.";     
    } elseif(strlen(trim($_POST['doorname'])) < 3){
        $password_err = "Doorname must have atleast 3 characters.";
    } else{
        $doorname = trim($_POST['doorname']);
    }
	
	    // Check input errors before inserting in database
    if(empty($doorname_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO door (doorname) VALUES (?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_doorname);
            
            // Set parameters
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
    <title>Add doors to database</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif;
		.wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
		<h2>Add doors to database:</h2>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
 