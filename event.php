<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TechSPARKS IBMLT PPV</title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="icon" type="image/x-icon" href="white.png">
    <script src="https://hlsjs.video-dev.org/dist/hls.js"></script>
</head>

<body>
    <?php require 'nav.php';?>
    <div class="container">
        <div class="row">
            <div class="col">
                <video id="video" controls height="800"></video>
                <script>
                var video = document.getElementById('video');
                if (Hls.isSupported()) {
                    var hls = new Hls({
                        debug: true,
                    });
                    hls.loadSource('https://streaming.cnnphilippines.com/live/myStream/playlist.m3u8');
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MEDIA_ATTACHED, function() {
                        video.muted = true;
                        video.play();
                    });
                }
                // hls.js is not supported on platforms that do not have Media Source Extensions (MSE) enabled.
                // When the browser has built-in HLS support (check using `canPlayType`), we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video element through the `src` property.
                // This is using the built-in support of the plain video element, without using hls.js.
                else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = 'https://streaming.cnnphilippines.com/live/myStream/playlist.m3u8';
                    video.addEventListener('canplay', function() {
                        video.play();
                    });
                }
                </script>
            </div>
        </div>
    </div>
</body>

</html>