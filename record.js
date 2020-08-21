var video = document.getElementsByTagName('video')[0],
    recordRTC = null,
    videoURL = '',
    options = {
        type: 'video',
        video: { width: 320, height: 240 },
        canvas: { width: 320, height: 240 }
    };

function init() {
    try {
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    } catch (e) {
        window.alert('Your browser does not support WebVideo, try Google Chrome');
    }
    if (navigator.getUserMedia) {
        navigator.getUserMedia({video: true}, function (stream) {
            console.log('stream', stream);
            video.src = window.URL.createObjectURL(stream);
            recordRTC = RecordRTC(stream, options);
        }, function (e) {
            window.alert('Please enable your webcam to begin recording');
        });
    } else {
        window.alert('Your browser does not support recording, try Google Chrome');
    }
}

function record() {
    recordRTC.startRecording();
}

function stop() {
    recordRTC.stopRecording(function(url) { 
        videoURL = url;
    });
}

function load() {
    video.src = videoURL;
}