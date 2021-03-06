﻿<!DOCTYPE html>
<html lang="ko">

<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=utf-8“>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>이지온</title>
    <meta name="description" content="홈페이지 소개">
    <meta property="og:type" content="website" />
    <meta property="og:image" content="img/opengraph-img.jpg" />
    <meta property="og:title" content="홈페이지 이름" />
    <meta property="og:description" content="홈페이지 소개" />
    <meta property="og:url" content="홈페이지 URL 입력" />
    <meta name="robots" content="index,follow" />
    <!-- Mobile Stuff -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- Chrome on Android -->

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="홈페이지 이름">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR:400,500,700&display=swap&subset=korean" rel="stylesheet">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="rgba(0,0,0,0)">
    <meta name="naver-site-verification" content="01f26d1bf9dfb238fe359fb66ee4c816281dd2fe" />
    <link rel="canonical" href="홈페이지 URL 입력">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script type='text/javascript' src='https://cdn.scaledrone.com/scaledrone.min.js'></script>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <link rel="stylesheet" href="stylesheets/pinBoard.css">

</head>
<style>
    .col-lg-5{max-width: 100%}</style>
<body>
    <header>
    </header>
    <main>
       <section id="section02">
            <div class="tab-wrap">
                <div class="sound">
                    <div class="sound-btn"></div>
                    <input type="range" name="sound" class="volume" min="0" max="100" onchange="vChange('sound', this.value)">
                </div>
                <div class="mic">
                    <div class="mic-btn"></div>
                    <input type="range" name="mic" class="volume" min="0" max="100" onchange="vChange('mic', this.value)">
                </div>
                <div class="rec">
                    <div class="rec-box on">녹화중</div>
                    <div class="rec-time">13:32</div>
                    <div class="test"></div>
                </div>
            </div>
            <div class="student-wrap">
                <div class="box">
                    <video id="gum" playsinline autoplay muted></video>
                </div>
                <div class="box"></div>
                <div class="box"></div>
            </div>
            <div class="chat-wrap">
                <div class="title">채팅창</div>
                <div class="chat" style="overflow-y:auto">
                    <div class="member">
                        <div class="profile-name">
<!--                            <div class="profile"></div>-->
<!--                            <div class="name">profile name</div>-->
                        </div>
<!--
                        <div class="chatbox">Greg Abbott, the Republican governor of the
                            country’s largest Republican-controlled
                            state, is facing increasing pressure over his
                            decision to open the economy.</div>
-->
                    </div>
<!--
                    <div class="me">
                        <div class="chatbox">Greg Abbott, the Republican governor of the
                            country’s largest Republican-controlled </div>
                    </div>
-->
                    <div class="messages">

                    </div>
                    <form class="footer" onsubmit="return false;">
                        <input type="text" placeholder="Your message..">
                        <button type="submit">Send</button>
                    </form>
                    <template data-template="message">
                        <div class="message">
                            <div class="message__name"></div>
                            <div class="message__bubble"></div>
                        </div>
                    </template>
                </div>
            </div>
                <div class="file-share">
                    <div class="title" id="file_title">파일공유리스트 (3)</div>
                    <!--               파일 수 만큼 숫자변경 -->

                    <div class="wrap" id="read">
                        <!--
                        <div class="file">
                            <p>과제01.jpg</p>
                            <p>보기</p>
                            <p>다운받기</p>
                        </div>
-->
                        <?php include 'readdir.php'?>
                    </div>
                
            </div>
            <div class="accessor">
                    <div class="title">현재접속자리스트 (4/4)</div>
                    <!--            접속자 수 만큼 변경-->
                    <div class="wrap">
                        <div class="user">
                            <p>사용자1</p>
                        </div>
                        <div class="user">
                            <p>사용자2</p>
                        </div>
                        <div class="user">
                            <p>사용자3</p>
                        </div>
                    </div>
                </div>

        </section>
        <section id="section01">
            <div class="tab-wrap">
                <div class="tab on" onclick="openTab(event, 'tab1')">화상교육</div>
                <div class="tab" onclick="openTab(event, 'tab2')">그림판</div>
                <div class="tab" onclick="openTab(event, 'tab3')">오디오공유</div>
                <div class="tab" onclick="openTab(event, 'tab4')">웹공유</div>
            </div>
            <div class="c-box01 tabcontent" id="tab1">
                <video id="localvideo" class="localVideo" muted autoplay playsinline></video>
                <div class="row">
                    <div id="controlLeft" class="col-6 col-sm-6 col-md-6">
                        <button id="startCall">시작</button>
                    </div>
                    <div id="controlRight" class="col-6 col-sm-6 col-md-6">
                        <button id="stopCall">종료</button>
                    </div>
                </div>
                

            </div>
            <div class="c-box01 tabcontent canvas_tab" id="tab2" style="display:none">
                <div class="pinBoard"></div>

                <script src="draw_js/jquery.min.js"></script>
                <script src="draw_js/pinBoard.js"></script>

                <script>
                    pinBoard();

                </script>
            </div>
            <div class="c-box01 tabcontent" id="tab3" style="display:none">
                <div class="audio">
                    <form method="post" enctype="multipart/form-data">
                        <div class="filebox">
                            <div class="btn" onclick="document.querySelector('#file').click();">첨부파일</div>
                            <div id="file_name">첨부파일이름</div>
                            <input type="file" id="file" name="file" style="display:none" onchange="change(this)">
                        </div>
                    </form>
                </div>
                오디오 공유
            </div>
            <div class="c-box01 tabcontent" id="tab4" style="display:none;overflow-y: scroll;">
                <div>
                    <input type="text" id="nsearch" name="nsearch" onkeydown="javascript:Enter_Check();">
                    <button id="nbtn" onclick="naver_btn()">검색</button>
                </div>
                <div id="naver"></div>
            </div>
        </section>
        
    </main>
    <div style="display:none">
      <button id="start">Start camera</button>
      <button id="record" disabled>Start Recording</button>
      <button id="play" disabled>Play</button>
      <button id="download" disabled>Download</button>
    </div>
    <footer>

    </footer>
<!--    <script src="video.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@remotemonster/sdk/remon.min.js"></script>
    <script>
        let remonCall;

        const config = {
            credential: {
                serviceId: 'ad1f48ee-4e50-4e45-9cb8-ce667a272173',
                key: '6e779e7ed3e3bf6301d6b49529863fa0ebea59b903f2972c5565ba12201edb34'
            },
            view: {
                remote1: '#remoteVideo1',
                remote2: '#remoteVideo21',
                local: '#localVideo'
            }
        }

        const listener = {
            onConnect(chid) {
                $('#channelId').text(chid);
                $('#channelState').text("대기 중");
                console.log(`onConnect: ${chid}`);
            },
            onComplete() {
                $('#channelState').text("통화 중");
                console.log(`onComplete`);
            },
            onClose() {
                $('#channelState').text("통화 종료");
                console.log(`onClose`);
                remonCall.close();
                if ($('#localVideo')[0].srcObject) {
                    $('#localVideo')[0].srcObject = undefined;
                }
                remonCall = new Remon({
                    config: config,
                    listener: listener
                });
            }
        }

        remonCall = new Remon({
            config: config,
            listener: listener
        });

        $('#startCall').click(function() {
            // connectCall의 인자는 통화채널의 ID입니다. 실제 서비스에서는 동일한 통화채널의 ID가 아닌, 고유하고 예측이 어려운 ID를 사용해야합니다.
            remonCall.connectCall('my-first-channel');
        });

        // "종료" 버튼을 클릭하면 통화채널에서 나갑니다.
        $('#stopCall').click(function() {
            remonCall.close();
        });

    </script>

    <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
    <script src="js/main.js" async></script>
    <!--    <script src="https://github.com/webrtc/samples/blob/gh-pages/src/js/lib/ga.js"></script>-->
    <script src="js/ga.js"></script>
    <script src="chat_script.js"></script>
    <script src="js/main.js" async></script>
</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<link rel="stylesheet" href="css/chat.css">
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/owl.carousel.min.js"></script>

<script>
    $('input[type=range]').on('input', function() {
        var val = $(this).val();
        $(this).css('background', 'linear-gradient(to right, #3ebaae 0%, #3ebaae ' + val + '%, #d5d4d3 ' + val + '%, #d5d4d3 100%)');
    });

    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" on", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " on";
    }

</script>
<script>
    function change(obj) {
        var str = obj.value;
        var num = str.length;
        var i = str.lastIndexOf("\\");
        var index = str.substr(i + 1, num);
        document.getElementById('file_name').innerHTML = index;

        //        var form = $('#file')[0];

        var formData = new FormData();
        formData.append('file', $('#file')[0].files[0]);

        $.ajax({
            url: 'upload.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'POST',
            success: function(response) {
                console.log(response);
                $('#read').load('readdir.php');
            },
            error: function(error) {
                alert(error);
            }
        });
    }

</script>
<script>
    function naver_btn() {
        var val = $('#nsearch').val();

        $.ajax({
            url: "./search.php?query=" + val,
            type: "POST",
            dataType: "html",
            data: {
                "query": val
            },
            cache: false,
            success: function(data) {
                //                console.log(JSON.parse(data));
                var n = JSON.parse(data);
                //                console.log(n.items[0].title);
                var end = n.display;
                var content = "";
                $('#naver').html('');
                for (var i = n.start; i <= end; i++) {
                    content += "<div>";
                    content += "<div><img src='" + n.items[i].thumbnail + "' style='width:" + n.items[i].sizewidth + ";height:" + n.items[i].sizewidth + "'></div>";
                    content += "<div>" + n.items[i].title + "</div>";
                    //                    content += "<div>"+n.items[i].description+"</div>";
                    content += "</div><br>";
                    $('#naver').html(content);
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    }

</script>
<script type="text/javascript">
    function Enter_Check() {
        // 엔터키의 코드는 13입니다.
        if (event.keyCode == 13) {
            document.querySelector('#nbtn').click();
            return;
        }
    }

</script>
<script>
$('.messages').scrollTop($('.messages').prop('scrollHeight'));
</script>
<script>
    function vChange(t, v){
        if(t=="sound"){
            
        } else if(t=="mic"){
             
        }
        
        $('.test').text(t+" "+v);
    }
</script>
</html>
