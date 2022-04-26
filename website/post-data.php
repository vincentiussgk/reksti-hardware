<?php
/*
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com
  
  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.
  
  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
*/

$servername = "localhost";

// REPLACE with your Database name
$dbname = "id16579399_database";
// REPLACE with Database user
$username = "id16579399_18219024";
// REPLACE with Database user password
$password = '1Y-{$R7@B#La~^X^';

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

$api_key = $value1 = $value2 = $value3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $value1 = test_input($_POST["value1"]);
        $value2 = test_input($_POST["value2"]);
        
        $value3 = test_input($_POST["value3"]);
        $value4 = test_input($_POST["value4"]);
        $value5 = test_input($_POST["value5"]);
        $value6 = test_input($_POST["value6"]);

        // If value 3456 gaada => Exit (Only to Room status)
        
        // If value ada => Entrance (Room status 1,2 ; Events 3,4,5,6)
        
        if ($value3)
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        // Post Data
        if ($value3) {
            $sql = "INSERT INTO room_status (value1, value2)
            VALUES ('" . $value1 . "', '" . $value2 . "')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } 

            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $events = "INSERT INTO events (value3, value4, value5, value6)
            VALUES ('" . $value3 . "', '" . $value4 . ", '" . $value5 . ", '" . $value6 . "')";

            if ($conn->query($events) === TRUE) {
                echo "New record created successfully";
            }
            
            else {
                echo "Error: " . $events . "<br>" . $conn->error;
            }
        }
        else {
            $sql = "INSERT INTO room_status (value1, value2)
            VALUES ('" . $value1 . "', '" . $value2 . "')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}