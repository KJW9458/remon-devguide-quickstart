<?php
	include 'default.php';
	
	$id = $_GET['id'];
	if ( empty($id) ) die("No ID");
	$md5 = md5( $id );
	$dir = "data/book-upload/$md5";
	
	
	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset='utf-8'>
<style>
body {
	margin:0;
	padding:0
	font-size: 9pt;
	font-family: Arial;
	line-height: 20px;
}
div {
	line-height: 20px;
}
.filename {
	display:inline-block;
	margin: 2px;
	padding: 2px 4px;
	border: 1px solid #cdcdcd;
	border-radius: 2px;
	font-size:8pt;
	cursor: pointer;
}
</style>
<?include 'jquery.php'?>
<script>
$(function(){
	$('.filename').click(function(){
		var path = "<?=$dir?>/" + $(this).attr('filename');
		var data = { 'code': 'white-board-file-select', 'path' : path };
		parent.postMessage( data, '*' );
	});
});
</script>
</head>
<body>

<form action='whiteboard-book-upload-submit.php' method='post' enctype="multipart/form-data">
	<input type='hidden' name='id' value="<?=$id?>">

	Select File : <input type='file' name='file'> <input type='submit'>
	
</form>
<?php
	
	if ( file_exists( $dir ) ) {
		$files = file::files( $dir );
		if ( $files ) {
		
?>

<div>File List</div>
<?php

	
	foreach ( $files as $file ) {
		$pi = pathinfo( $file );
		$filename = base64_decode($pi['basename']);
		echo "<span class='filename' filename='$pi[basename]'>$filename</span><a href='whiteboard-book-delete.php?id=$id&path=$file'>[X]</a>";
	}
?>

<?		}
	}
?>

</body>
</html>
