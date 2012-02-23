<?php
include 'memegen.php';




$servername = $_SERVER['SERVER_NAME'];
$uri = $_SERVER['REQUEST_URI'];


$complete_uri = 'http://'.$servername.$uri;



$servername_pts = explode('.', $servername);
array_pop($servername_pts);
$servername = array_pop($servername_pts);

print_meme($servername,'random');


// save url



?>