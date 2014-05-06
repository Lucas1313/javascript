<?php

$messageBodyHTML = <<<EOD
<html>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#ffffff" align="center">

	<!--TopImage -->
	<tr>
		<td width="600" colspan="3" bgcolor="#ffffff">
		<img src="$webRoot/img/email/welcome_top.jpg" alt="Clorox" width="600" height="262" border="0" style="display:block;"></td>
	</tr>
	
	<!--sideImage -Text- sideImage -->
	<tr>
		<td width="42" valign="top" align="left">
			<img src="$webRoot/img/email/left.jpg" alt="" width="42" height="270" border="0" style="display:block;">
		</td>
		<td align="left" valign="top" width="489" bgcolor="#ffffff" style="font-family:Arial, Helvetica, Sans-serif; font-size:12px; line-height:15px; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; color:#637184;">
		
			<span style="color:#4ea0d3; font-size:14px;">Dear $greeting,</span><br><br>
			
			Thank you for registering with <a href="http://www.clorox.com/" style="color:#003999;" target="_blank">Clorox.com</a>.<br><br>
			
			Please use the following email address to log in:<br>
			<span style="color:#4ea0d3;">$email</span><br><br>
			
			We occasionally email news, special offers and other information to our members. If you no longer wish to receive these emails click <a href="$unsubcribeUri" style="color:#003999;" target="_blank">here</a>.<br><br>
			
			If you have any questions, please select Contact Us on Clorox.com.<br><br>
			
			Have a great day!<br><br>
			The Clorox Company
		</td>

		<td width="69" valign="top" align="left">
			<img src="$webRoot/img/email/right.jpg" alt="" width="69" height="270" border="0" style="display:block;">
		</td>
	</tr>
	
	<!--bottomImage -->
	<tr>
		<td valign="top" align="left" width="600" colspan="3" bgcolor="#ffffff">
			<img src="$webRoot/img/email/bottom.jpg" alt="" width="600" height="35" border="0" style="display:block;">
		</td>
	</tr>
	
	
	
	<!--Footer Text-->
	<tr>
		<td width="42"></td>
		<td width="489" align="left" valign="top" style="font-family:Arial, Helvetica, Sans-serif; font-size:9px; line-height:11px; color:#808080; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">	
			&copy;$copyrightYear The Clorox<sup style="line-height:0px;">&reg;</sup> Company. All Rights Reserved.<br>
			Read our <a href="$privacyUri" style="color:#003999;" target="_blank">Privacy Policy</a>. <br>
			Read our <a href="$termsUri" style="color:#003999;" target="_blank">Terms of Use</a>. <br>
			This email was sent by: <br>
			The Clorox Company<br>
			1221 Broadway Oakland, CA 94612
		</td>
		<td width="69"></td>
	</tr>
</table>

</body>
</html>
EOD;


