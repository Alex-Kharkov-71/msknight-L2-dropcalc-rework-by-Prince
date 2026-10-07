<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

/*
Michelle Knight's Drop Calc - Version 3
Author - Michelle Knight
Copyright 2006
Contact - dropcalc@msknight.com

GNU General Licence
Use and distribute freely, but leave headers intact and make no charge.
Change HTML code as necessary to fit your own site.
Code distributed without warantee or liability as to merchantability as
no charge is made for its use.  Use is at users risk.
*/


/*
----------------------------------------------------------
Flexible Map function by Michelle Knight 2006 (c) Copyright

Specify the co-ordinate range of the playing area in the map_left, etc.

Then specify the width and height of that play area as it is represented on your map.

Give it an offset from the top left hand corner of the map picture, to the top left
hand corner of the play area on the map. (in pixels on your map)

For each pointer, specify the pointer graphic file name, and the number of pixels from
the top and left side, to the center (for traditional mouse pointers, this will be 0,0)
plus the width and height of the pointer graphic.

Call the function with the game co-ordinates and the function should display the map, with 
the pointers on it.

NOTE - Uses the DIFNUMS funciton found in the common code area config.php - copy that 
function in to this code to make it totally freestanding.

----------------------------------------------------------
*/

function map_2($points, $images_dir, $map_num)
{

$map_right = 229388;
$map_left = -165886;
$map_top = -262143;
$map_bottom = 261000;
$map_name="aden";
$graphic_width = 113;
if ($map_num == 2)
{
	$map_right = -166144;
	$map_left = -329450;
	$map_top = -246560;
	$map_bottom = 259838;
	$map_name="gracia";
	$graphic_width = 47;
}
$graphic_height = 150;

$offset_x = 0;
$offset_y = 0;

$map_file = $images_dir. $map_name. "2.jpg";

//--------------------------------------------------------

$pointers = array(array($images_dir. "underg.gif",3,3,7,7));
array_push($pointers, array($images_dir. "overg.gif",3,3,7,7));
array_push($pointers, array($images_dir. "wfree.gif",3,3,7,7));
array_push($pointers, array($images_dir. "target.gif",7,7,15,15));
array_push($pointers, array($images_dir. "target2.gif",7,7,15,15));
array_push($pointers, array($images_dir. "target3.gif",7,7,15,15));
array_push($pointers, array($images_dir. "underg-s.gif",1,1,3,3));
array_push($pointers, array($images_dir. "overg-s.gif",1,1,3,3));
array_push($pointers, array($images_dir. "wfree-s.gif",1,1,3,3));
array_push($pointers, array($images_dir. "r1.gif",3,3,7,7));
array_push($pointers, array($images_dir. "r2.gif",3,3,7,7));
array_push($pointers, array($images_dir. "r4.gif",3,3,7,7));
array_push($pointers, array($images_dir. "r3.gif",3,3,7,7));

//--------------------------------------------------------


if ($map_right > $map_left)
{
	$x_scale = difnums2($map_left, $map_right);
	$base_x = $map_left;
}
else
{
	$x_scale = difnums2($map_right, $map_left);
	$base_x = $map_right;
}

if ($map_bottom > $map_top)
{
	$y_scale = difnums2($map_top, $map_bottom);
	$base_y = $map_top;
}
else
{
	$y_scale = difnums2($map_top, $map_bottom);
	$base_y = $map_bottom;
}

$x_scale = $x_scale / $graphic_width;
$y_scale = $y_scale / $graphic_height;

echo "<div style=\"position: relative;\"><img src=\"$map_file\" alt=\"\" width=\"$graphic_width\" height=\"$graphic_height\" border=\"0\"><div>";

$num = count($points);
$i = 0;
while ($i < $num) {
	$point = $points[$i];
	$x_co = $point[0];
	$y_co = $point[1];
	$pointer = $point[2];
	$point_dat = $pointers[$pointer];
	if (($x_co <= $map_right) && ($x_co >= $map_left) && ($y_co <= $map_bottom) && ($y_co >= $map_top))
	{
		$x_co = (intval( difnums($x_co,$base_x) / $x_scale) + $offset_x) - $point_dat[1];
		$y_co = (intval( difnums($y_co,$base_y) / $y_scale) + $offset_y) - $point_dat[2];
	
		echo "<img src=\"$point_dat[0]\" align=\"left\" style=\"position: absolute; top: $y_co; left: $x_co; width: $point_dat[3]; height: $point_dat[4]\" border=\"0\">";
		
	}
	$i++;
}

return 1;
}


// works out the difference between two numbers, regardless of whether one, other or both, are negative.

function difnums2($small, $big)
{
if (($small < 0) && ($big > 0))
{
 	$difnum = ($big + (-$small));
}
else
{
	$difnum = $big - $small;
}
if ($difnum < 0)
{
	$difnum = -$difnum;
}
return $difnum;
}

?>