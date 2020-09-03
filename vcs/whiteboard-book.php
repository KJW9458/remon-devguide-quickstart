<?php
	include 'default.php';
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset='utf-8'>
<style>
img {
	width:100%;
	border:0;
}
</style>
<script>

		/* Book Open & Scroll Sync */
		var timer_scroll = 0;
		var scroll_just_set = false;
		var count_scroll = 0;
		window.addEventListener( 'message', postMessageListener, false );
		function postMessageListener( e )
		{
			if ( e.data.code == 'scroll' ) {
				scroll_just_set = true;
				window.scrollTo(0,e.data.top);
			}
		}
		window.onscroll = doScroll;
		function doScroll() {
			if ( scroll_just_set ) {
				scroll_just_set = false;
				return;
			}
			if ( ++count_scroll == 1 ) return; // 맨 처음 페이지를 열면 자동으로 스크롤 0 이 발생한다.
			clearTimeout(timer_scroll);
			timer_scroll = setTimeout(function(){
				var top_px = window.pageYOffset ;
				var data = {'code':'page-scroll', 'top': top_px};
				parent.postMessage( data, '*' );
				// alert("sent:" + data.top);
				console.log("sent:" + data.top);
			}, 400);
		}
		
</script>
</head>
<body>
<?php
	if ( file_exists( $in['path'] ) ) {
		echo "<img src='$in[path]'>";
	}
	else {
		echo "No file by that path - $in[path]";
	}
?>
</body>
</html>
