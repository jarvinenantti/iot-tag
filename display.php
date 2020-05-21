

    <?php

    /* Attempt MySQL server connection. Assuming you are running MySQL
    server with default setting */
    $link = mysqli_connect("localhost", "root", "", "tags");
     

    // Check connection

    if($link === false){
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

     

    // Attempt select query execution

    $sql = "SELECT * FROM access";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>id</th>";
                    echo "<th>doorname</th>";
                    echo "<th>rfid</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['doorname'] . "</td>";
                    echo "<td>" . $row['rfid'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            // Free result set
            mysqli_free_result($result);

        } else{
            echo "No records matching your query were found.";
        }

    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

     

    // Close connection
    mysqli_close($link);

    ?>

 
