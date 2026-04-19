<?php 
$html = file_get_contents('https://goveportal.keromultiservice.com/login'); 
file_put_contents('temp.html', $html);
