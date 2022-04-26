<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href = "styles">
        <title>Smart Room Monitoring System</title>
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
    <body>
        <h1 class="center-fit" style ="font-size: 50px; padding-bottom: 50px;">
            <div style="font-size: 35px;color: rgb(44, 45, 92);">Ruang 4200</div>
        </h1>
        <div style="width: 100%; display:flex; justify-content: space-between">
            <div style="width:50%; height:100px;float: left;font-size: 20px;">
                <img src="Warning.png" style="margin-right: 20px">
                Terdapat 0 kejadian penting
                <br> Lihat di bawah
            </div>    
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
        $sql2 = "SELECT lamp_status, current_total FROM room_status LIMIT 1";

        // if ($result = $conn->query($sql)) {
        //     while ($row = $result->fetch_assoc()) {
        //         $row_value = $row["value"];
                
        //         echo '
        //             <div style="background: blue ; width:50%; height:100px;float: left;font-size: 30px; outline: 3px solid black;">Terdapat 0 kejadian penting <br>Lihat di bawah ' . $row_value . '/17</div>
        //         ';


        //     }
        //     $result->free();
        // }

        if ($result2 = $conn->query($sql2)) {
            while ($row2 = $result2->fetch_assoc()) {
                $lamp_status = $row2["lamp_status"];
                $current_total = $row2["current_total"];
                
                echo '
                    <div style="width:50%; font-size: 30px; border-left: 1px solid black; diplay: flex; align-items: center;""> <img src="Visitor.png" style="margin-right: 20px">' . $lamp_status . '/17</div>
                ';

                echo ($current_total == "1") ? 
                '
                    <div style="width:50%; font-size: 30px; border-left: 1px solid black; diplay: flex; align-items: center;""> <img src="Lightbulb.png" style="margin-right: 20px"> ON </div>
                '
                : 
                '
                    <div style="width:50%; font-size: 30px; border-left: 1px solid black; diplay: flex; align-items: center;"> <img src="Lightbulb.png" style="margin-right: 20px"> OFF </div>
                ';

            }
            $result2->free();
        }

        $conn->close();
        ?>
    </div>
    <div style="float: left; display: flex; flex-direction: column; margin-top: 20px; text-align: left;">
        <h3 style="float: left;">
            Kejadian Penting
        </h3>
        <br>
        <div>
            Tidak ada kejadian penting baru
        </div>
    </div>
    </div>
    <br><br><br><br>
    </div>
    </body>
</html>