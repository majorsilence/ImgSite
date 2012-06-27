<?php
 echo "<!DOCTYPE html>\n";
?>
<html>
<head>
<title>Image Upload</title>

    <script src="javascript/jquery.min.js" type='text/javascript'></script>
    <script src="javascript/kendo.web.min.js" type='text/javascript'></script>
    <link href="styles/kendo.common.css" rel="stylesheet" />
    <link href="styles/kendo.default.css" rel="stylesheet" />

</head>
<body>

	<form method="post" action="image_upload_action.php" style="width:45%" >
		<div>
			<input name="userfile" id="userfile" type="file" />
			<p>
				<input type="submit" value="Submit" class="k-button" />
			</p>
		</div>
	</form>
	
	<?php
		session_start();
		if ($_SESSION['LastUpload'] != "")
		{
			echo "<br />Last File Uploaded: " . $_SESSION['LastUpload'] . "<br />";
		}
	?>
	
	<script>
		$(document).ready(function() {
			$("#userfile").kendoUpload();
		});
	</script>

</body>
</html>
