<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href = "styles">
        <title>Pollux Paragon Mall Semarang</title>
        <meta itemprop="image" content="https://paragonsemarang.id/wp-content/uploads/2021/03/Pollux-Mall-Paragon-200.png" />
        <link rel="icon" href="https://paragonsemarang.id/wp-content/uploads/2021/03/Pollux-Mall-Paragon-200.png" />
        <style>
            body,h1,h2,h3,h4,h5,h6 {font-family: "Karma", sans-serif; text-align: center;}
            body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            }

            .topnav {
            overflow: hidden;
            background-color: rgb(255, 255, 255);
            }

            .topnav a {
            float: left;
            color: #000000;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
            }

            .topnav a:hover {
            background-color: #ddd;
            color: black;
            }

            .topnav a.active {
            background-color: #3d6fcc;
            color: white;
            }
            .imgbox {
                display: grid;
                height: 100%;
            }
            .center-fit {
                max-width: 100%;
                max-height: 100vh;
                margin: auto;
            }

        </style>
    </head>
    <body style="background: rgb(207, 200, 200);">
        <div class="topnav">
            <a class="active" href="#home">Parking System</a>
            <a href = "#pathfinding">Pathfinding</a>
            <a href="#contact">Contact us</a>
        </div>
        <h1 class="center-fit" style ="background-color: aquamarine;color: rgb(0, 0, 0);background: rgb(189, 189, 189); font-size: 50px; padding-bottom: 50px;">
            SMART PARKING SYSTEM <br> <div style="font-size: 35px;color: rgb(44, 45, 92);">PARAGON MALL</div>
        </h1>
        <div class= "imgbox">    
            <img class= "center-fit" src="paragon-mall-semarang.jpg" width="1500" height ="800">
        </div>
        <div style="width: 100%">
            <br><br>
            <h2 class = "center-fit" style ="background-color:grey;padding: 0; font-size: 35px;">Parking Slots</h2>
            <div style ="color: white;">      
        <?php
        /*
          Rui Santos
          Complete project details at https://RandomNerdTutorials.com/esp32-esp8266-mysql-database-php/
          
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

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "SELECT value FROM events ORDER BY id DESC LIMIT 5";
        $sql2 = "SELECT value FROM room_status ORDER BY id DESC LIMIT 1";

        function getSlotColor($value) {
            if ($value < 10 || ($value > 50)) {
                return "#42f560";
            }
            return "#f54b42";
        }

        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $row_value = $row["value"];
                
                echo '
                    <div style="background:' . getSlotColor($row_value) . '; width:50%; height:100px;float: left;font-size: 30px; outline: 3px solid black;">Slot 1: ' . $row_value . '</div>
                ';


            }
            $result->free();
        }
        if ($result2 = $conn->query($sql2)) {
            while ($row2 = $result2->fetch_assoc()) {
                $row_value2 = $row2["value"];
                
                echo '
                    <div style="background:' . getSlotColor($row_value2) . '; width:50%; height:100px;float: left;font-size: 30px; outline: 3px solid black;">Slot 2: ' . $row_value2 . '</div>
                ';

            }
            $result2->free();
        }

        $conn->close();
        ?>
        </div>
    </div>
    <br><br><br><br>
    </div>
    </body>
</html>