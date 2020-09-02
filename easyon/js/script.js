
$(document).ready(function () {
    //접기탭
    $('#section02 .s-wrap .file-share .title').click(function () {
        $(this).toggleClass('on')
        if ($('#section02 .s-wrap .file-share .title').hasClass('on')) {
            $('#section02 .s-wrap .file-share .title').next('.wrap').hide()
        } else($('#section02 .s-wrap .file-share .title').next('.wrap').show())
    });
    $('#section02 .accessor .title').click(function () {
        $(this).toggleClass('on')
        if ($('#section02 .accessor .title').hasClass('on')) {
            $('#section02 .accessor .title').next('.wrap').hide()
        } else($('#section02 .accessor .title').next('.wrap').show())
    });
    $('#section02 .chat-wrap .title').click(function () {
        $(this).toggleClass('on')
        if ($('#section02 .chat-wrap .title').hasClass('on')) {
            $('#section02 .chat-wrap .title').next('.chat').hide()
        } else($('#section02 .chat-wrap .title').next('.chat').show())
    });
    
    //상단 도구탭
    $('#section01 .tab-wrap .tab').click(function(){
        $('#section01 .tab-wrap .tab').removeClass('on')
        $(this).addClass('on')
        
    });
    
    
    //  시작종료탭
    $('#controlRight').hide()
    $('#controlLeft').click(function(){
       $('#controlLeft').hide()
        $('#controlRight').show()        
    });
    $('#controlRight').click(function(){
       $('#controlRight').hide()
        $('#controlLeft').show()        
    });
    
});


