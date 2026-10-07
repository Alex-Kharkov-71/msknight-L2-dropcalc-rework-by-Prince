<?php

if (!$quality_level)
{	$keyboard_bottom = "keybd-bot.jpg";
	$keyboard_right = "keybd-right.jpg";
	$keyboard_corner = "keybd-corner.jpg";
}
else
{	$keyboard_bottom = "l-keybd-bot.jpg";
	$keyboard_right = "l-keybd-right.jpg";
	$keyboard_corner = "l-keybd-corner.jpg";	
}
echo "
<!-- Footer -->
</td><td style=\"background:url('$skin_dir/$keyboard_right');\"></td></tr>
<tr><td class=\"back2\"><img src=\"$skin_dir/$keyboard_corner\" alt=\"\" width=\"58\" height=\"58\" border=\"0\"></td><td style=\"background:url('$skin_dir/$keyboard_bottom');\" width=\"100%\"></td><td class=\"back2\"><img src=\"$skin_dir/$keyboard_corner\" alt=\"\" width=\"58\" height=\"58\" border=\"0\"></td></tr>
</table></td><td><img src=\"$skin_dir/blank.gif\" height=\"1\" width=\"5\"></td></tr></table></td></tr></table></td></tr></table>
<!-- End Content -->
</body>
</html>
";


?>