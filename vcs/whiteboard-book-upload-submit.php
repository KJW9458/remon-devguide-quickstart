<?php
	
	include 'default.php';
	
	$id = $in['id'];
	if ( empty($id) ) die("No ID");
	$md5 = md5( $id );
	$dir = "data/book-upload/$md5";
	
	
	if ( ! file_exists( $dir ) ) {
		mkdir( $dir, 0777 );
	}
	
	
	
	$filename = base64_encode($_FILES['file']['name']);
	
	$o = array();
	$o['form_name'] = 'file';
	$o['path'] = "$dir/$filename";
	$o['thumbnail'] = 'no';
	$o['limit'] = 3000000;
	$re = file::upload( $o );
	switch ( $re ) {
		case file::NO_UPLOAD_FILE			: die( jsBack( ('Select File') ) );
		case file::UPLOAD_FAILED				: die( jsBack( ('Upload Failed') . ' ' . file::$error_message ) );
		case file::FILE_SIZE_ZERO										: die( jsBack( ( 'File size is zero' ) ) );
		case file::FILE_SIZE_TOO_LARGE							: die( jsBack( ( 'File size is too large' ) ) );
		case file::CANNOT_MOVE_UPLOAD_FILE					: die( jsBack( ( 'Cannot move uploaded file' ) ) );
		case file::THUMBNAIL_FAILED_ON_ORG_IMAGE		: die( jsBack( ( 'Cannot create thumbnail on orignal image' ) ) );
		case file::THUMBNAIL_FAILED									: die( jsBack( ( 'Cannot create thumbnail' ) ) );
		default :
			debug("file::upload() result -> $re");
			break;
	}
	
	jsGo("whiteboard-book-upload.php?id=$id");