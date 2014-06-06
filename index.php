<?php
include('memegen.php');



/* Get the server information */
$servername = $_SERVER['SERVER_NAME'];
$uri = $_SERVER['REQUEST_URI'];

$complete_uri = 'http://'.$servername.$uri;


$servername_pts = explode('.', $servername);
array_pop($servername_pts);
$servername = array_pop($servername_pts);


/* Generate the meme */
print_meme($servername,'random');


// save url - todo for some day if a need arises. 



?>