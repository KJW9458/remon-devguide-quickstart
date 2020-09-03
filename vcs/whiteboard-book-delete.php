<?php
	include 'default.php';
	@unlink($in['path']);
	jsGo("whiteboard-book-upload.php?id=$in[id]");
	