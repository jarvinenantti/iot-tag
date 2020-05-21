    <?php

    /* Attempt MySQL server connection. Assuming you are running MySQL

    server with default setting (user 'root' without password) */

    $link = mysqli_connect("localhost", "root", "", "tags");

     

    // Check connection

    if($link === false){

        die("ERROR: Could not connect. " . mysqli_connect_error());

    }

     

    // Attempt create door table query execution

    $sql = "CREATE TABLE door(

        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
		doorname VARCHAR(50) NOT NULL UNIQUE

    )";

    if(mysqli_query($link, $sql)){

        echo "Door table created successfully.";

    } else{

        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

    }

	$sql = "insert into door values(default)";
    
	// Attempt create access table query execution
	// every access element is bind to specific door and rfid
    $sql = "CREATE TABLE access(

        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        doorname VARCHAR(50),
  		rfid VARCHAR(255),
  		foreign key(doorname) references door(doorname),
  		foreign key(rfid) references user(rfid)

    )";

    if(mysqli_query($link, $sql)){

        echo "Access table created successfully.";

    } else{

        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

    }

    $sql = "insert into access values(default, 1)";

     // Attempt create user table query execution

    $sql = "CREATE TABLE user(

        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        rfid VARCHAR(255) NOT NULL UNIQUE

    )";

    if(mysqli_query($link, $sql)){

        echo "User table created successfully.";

    } else{

        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);

    }


    // Close connection

    mysqli_close($link);

    ?> 
