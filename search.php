<?php
$text=$_REQUEST['query'];

//echo json_test($text);

//function json_test($query){
  $client_id = "xvRY8SbDOk_9c2eNnV6m";
  $client_secret = "h4Om9RyMvN";
  $encText = urlencode("$text");
  $url = "https://openapi.naver.com/v1/search/image.json?query=".$encText; // json 결과
//  $url = "https://openapi.naver.com/v1/search/blog.xml?query=".$encText; // xml 결과
  $is_post = false;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, $is_post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $headers = array();
  $headers[] = "X-Naver-Client-Id: ".$client_id;
  $headers[] = "X-Naver-Client-Secret: ".$client_secret;
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec ($ch);
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//  echo "status_code:".$status_code."";
  curl_close ($ch);
  if($status_code == 200) {
    echo $response;
  } else {
    echo "Error 내용:".$response;
  }
//}
?>