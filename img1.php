<?php
header("Content-Type: image/jpeg");
 $im  = imagecreatetruecolor(150, 30); /* Create a black image */
       $bgc = imagecolorallocate($im, 255, 255, 255);
       $tc  = imagecolorallocate($im, 0, 0, 0);
       imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
       /* Output an errmsg */
       imagestring($im, 3, 5, 5, "JPG Output works", $tc);
imagejpeg($im);
?>