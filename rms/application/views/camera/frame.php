<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="msapplication-tap-highlight" content="no" />
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="-1">
	<meta http-equiv="pragma" content="no-cache">
</head>
<body>
	<?
	$url = $cams['cam'.$num];
	$js = "
	<SCRIPT LANGUAGE=\"JavaScript\">
	var Camera = \"\"; 
	var File = \"$url\";
	if (Camera != \"\") {File += \"&camera=\" + Camera;}
	var output = \"\";
	if ((navigator.appName == \"Microsoft Internet Explorer\") &&
	(navigator.platform != \"MacPPC\") && (navigator.platform != \"Mac68k\"))
	{
	// If Internet Explorer under Windows then use ActiveX
	output = '<OBJECT ID=\"Player\" width='
	output += DisplayWidth;
	output += ' height=';
	output += DisplayHeight;
	output += ' CLASSID=\"CLSID:DE625294-70E6-45ED-B895-CFFA13AEB044\" ';
	output += 'CODEBASE=\"';
	output += 'activex/AMC.cab\">';
	output += '<PARAM NAME=\"MediaURL\" VALUE=\"';
	output += File + '\">';
	output += '<param name=\"MediaType\" value=\"mjpeg-unicast\">';
	output += '<param name=\"ShowStatusBar\" value=\"0\">';
	output += '<param name=\"ShowToolbar\" value=\"0\">';
	output += '<param name=\"AutoStart\" value=\"1\">';
	output += '<param name=\"StretchToFit\" value=\"1\">';
	output += '<BR><B>Axis Media Control</B><BR>';
	output += 'The AXIS Media Control, which enables you ';
	output += 'to view live image streams in Microsoft Internet';
	output += ' Explorer, could not be registered on your computer.';
	output += '<BR></OBJECT>';
	} else {
	// If not IE for Windows use the browser itself to display
	theDate = new Date();
	output = '<IMG SRC=\"';
	output += File;
	output += '&dummy=' + theDate.getTime().toString(10);
	output += '\" HEIGHT=\"';
	output += '\" WIDTH=\"';
	output += '\" ALT=\"Camera Image\">';
	}
	document.write(output);
	</SCRIPT>
	";
	echo $js;
	?>

</body>
</html>