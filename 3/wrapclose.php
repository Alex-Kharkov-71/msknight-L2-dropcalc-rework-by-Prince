<?php
if (!$quality_level)
	$igo_head_back = "igo-head-back.jpg";
	$igo_head_middle = "igo-head-middle.jpg";	
	$igo_back_cb = "c-b.jpg";
	$igo_back_cr = "c-r.jpg";
	$igo_back_br = "c-br.jpg";
	$igo_back_bl = "c-bl.jpg";
echo "
<!-- Footer -->

</td><td style=\"background: url('$skin_dir/$igo_back_cr')\"></td></tr>
<tr><td style=\"background: url('$skin_dir/$igo_head_middle')\"><img src=\"$skin_dir/$igo_back_bl\" alt=\"\" width=\"23\" height=\"23\" border=\"0\"></td><td style=\"background: url('$skin_dir/$igo_back_cb')\" width=\"100%\"></td><td style=\"background: url('$skin_dir/$igo_head_middle')\"><img src=\"$skin_dir/$igo_back_br\" alt=\"\" width=\"23\" height=\"23\" border=\"0\"></td></tr>
</table></td></tr></table></td></tr></table></td></tr></table>
<!-- End Content -->
</body>
</html>
";

?>