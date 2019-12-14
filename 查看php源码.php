<?php
echo highlight_file("xampp01.php");
echo "\r\n\r\n<br><br><pre>";
echo htmlspecialchars(file_get_contents("xampp01.php")); 
