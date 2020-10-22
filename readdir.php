<?php
$dir = "./uploads"; 
$cnt = 0; 
$dir_handle=opendir($dir); // 디렉토리 열기 
    
//디렉토리의 파일을 읽어 들임 
while(($file=readdir($dir_handle)) !== false) { 
    $fname = $file; 
    
    //파일명을 출력해보면 .과 ..도 출력이 된다. 
    //(상위 폴더로 가기도 출력이 된다) 
    //따라서 다음과 같이 .과 ..을 만나면 continue하도록 한다. 
    if($fname == "." || $fname == "..") { 
        continue; 
    } 
    ?>
    <div class="file">
        <p><?php echo $fname?></p>
        <p>보기</p>
        <p onclick="document.querySelector('#download<?php echo $cnt?>').click();">다운받기</p>
        <a href="<?php echo $dir?>/<?php echo $fname?>" id="download<?php echo $cnt?>" download></a>
    </div>
    <?php
//    echo $fname." "; 
    //파일명 출력 
    $cnt++; //반복해서 카운터를 증가시킴 
}

closedir($dir_handle); // 마지막으로 디렉토리를 닫기
?>
<script>
    document.getElementById('file_title').innerHTML="파일공유리스트 (<?php echo $cnt?>)";
</script>