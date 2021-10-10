window.onload = function() {

    var heightchange = 40;

    String.prototype.toHHMMSS = function() {

        var sec_num = parseInt(this, 10); // don't forget the second param
        var hours = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);
        var time;

        if (hours < 10) {

            time = '0' + hours + ':' + minutes + ':' + seconds;

        }
        if (minutes < 10) {

            time = '0' + minutes + ':' + seconds;

        }

        if (seconds < 10) {

            time = minutes + ':0' + seconds;

        }

        return time;
    };

    // Video layout
    var videocontainer = document.getElementById("video-container");
    var videocontrols = document.getElementById("video-controls");

    var roundpgsss = document.getElementById("round-progress");

    var innerprogress = document.getElementById("innerprogress");

    var innerbuffer = document.getElementById("innerbuffer");

    // Timer
    var timer = document.getElementById("timer");

    // Video
    var video = document.getElementById("video");

    // Buttons
    var playButton = document.getElementById("play-pause");
    var muteButton = document.getElementById("mute");
    var fullScreenButton = document.getElementById("full-screen");

    // Sliders
    var seekbar = document.getElementById("seek-bar");
    var volumeBar = document.getElementById("volume-bar");

    var doc = window.document;

    video.width = $(window).width();
    //video.height = $(window).height();

    video.style.height = ($(window).height() - heightchange) + 'px';

    if (typeof seekbar.offsetWidth == 'undefined') {

        if (typeof videocontrols.offsetWidth == 'undefined') {

            seekbar.offsetWidth = $(window).width();

        } else {

            seekbar.offsetWidth = videocontrols.offsetWidth;

        }

    }

    $(window).resize(function() {

        video.width = $(window).width();
        //video.height = $(window).height();

        if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {

            video.style.height = ($(window).height() - heightchange) + 'px';

            video.style.margin = '0';

        } else {

            video.style.height = $(window).height() + 'px';

            video.style.margin = 'auto';

        }

        if (typeof seekbar.offsetWidth == 'undefined') {

            if (typeof videocontrols.offsetWidth == 'undefined') {

                seekbar.width = $(window).width();

            }

        }

    });

    seekbar.onclick = function(e) {

        var duration = video.duration;
        var width = seekbar.offsetWidth;
        var x, y;

        if (typeof videocontrols.offsetWidth == 'undefined') {

            x = e.pageX - seekbar.offsetLeft;
            y = e.pageY - seekbar.offsetTop;

        } else {

            var marLeft = ($(window).width() / 2) - (videocontrols.offsetWidth / 2) + ((videocontrols.offsetWidth / 2) - (seekbar.offsetWidth / 2));
            x = e.pageX - marLeft;
            y = e.pageY - seekbar.offsetTop;

        }

        video.currentTime = x / width * duration;

    };

    video.addEventListener("click", function() {

        if (video.paused === true) {

            // Play the video
            video.play();

        } else {

            // Pause the video
            video.pause();

        }

    });

    video.addEventListener("ended", function() {

        // Pause the video
        video.pause();

        // Update the button text to 'Restart'
        playButton.className = "restart";

    });

    // Event listener for the play/pause button
    playButton.addEventListener("click", function() {

        if (video.paused === true) {

            // Play the video
            video.play();

        } else {

            // Pause the video
            video.pause();

        }

    });

    video.addEventListener("onplay", function() {

        // Update the button text to 'Pause'
        playButton.className = "pause";

    });

    video.addEventListener("onpause", function() {

        // Update the button text to 'Pause'
        playButton.className = "play";

    });



    // Event listener for the volume bar
    volumeBar.addEventListener("change", function() {

        if (video.muted === false) {

            if (volumeBar.value < 50) {

                muteButton.className = "sound_down";

            } else {

                muteButton.className = "sound_up";

            }

        } else {

            muteButton.className = "mute";

        }

        // Update the video volume
        video.volume = volumeBar.value / 100;

    });

    // Event listener for the mute button
    muteButton.addEventListener("click", function() {

        if (video.muted === false) {

            // Mute the video
            video.muted = true;

            // Update the button text
            muteButton.className = "mute";

        } else {

            // Unmute the video
            video.muted = false;

            // Update the button text
            if (volumeBar.value < 50) {

                muteButton.className = "sound_down";

            } else {

                muteButton.className = "sound_up";

            }

        }

    });

    video.addEventListener("timeupdate", function() {

        if (video.paused === true) {

            // Update the button text to 'Play'
            playButton.className = "play";

        } else {

            // Update the button text to 'Pause'
            playButton.className = "pause";

        }

        var buffered = video.buffered;
        var lenght = buffered.length;
        var width = $(window).width();
        var height = seekbar.offsetHeight;
        var duration = video.duration;

        // Update the slider value played bar
        innerprogress.style.width = Math.floor((video.currentTime / 10000) * ($(window).width() / (duration / 10000)));

        var size = (((seekbar.offsetWidth / 10000) * Math.floor(video.currentTime * (10000 / duration))) - (roundpgsss.offsetWidth / 2));
        roundpgsss.style.marginLeft = size + 'px';

        // time update duration
        timer.innerHTML = video.currentTime.toFixed(0).toHHMMSS() + ' / ' + video.duration.toFixed(0).toHHMMSS();

    });

    $(window).keydown(function(e) {

        switch (e.keyCode) {

            case 37: //key is left

                if (video.currentTime > 0) {

                    video.currentTime = video.currentTime - 0.5;

                } else {

                    break;

                }

                return false;
            case 39: //key is right

                if (video.currentTime < video.duration) {

                    video.currentTime = video.currentTime + 0.5;

                }

                return false;

            case 32: //key is left

                if (video.paused === true) {

                    // Play the video
                    video.play();

                    // Update the button text to 'Pause'
                    playButton.className = "pause";

                } else {

                    // Pause the video
                    video.pause();

                    // Update the button text to 'Play'
                    playButton.className = "play";

                }

                return false;

        }

        return; //using "return" other attached events will execute

    });

    // Event listener for the full-screen button

    fullScreenButton.addEventListener("click", function() {

        var doc = window.document;

        if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {

            if (videocontainer.requestFullscreen) {

                videocontainer.requestFullscreen();

            } else if (videocontainer.mozRequestFullScreen) {

                videocontainer.mozRequestFullScreen();

            } else if (videocontainer.webkitRequestFullScreen) {

                videocontainer.webkitRequestFullScreen();

            } else if (videocontainer.msRequestFullscreen) {

                videocontainer.msRequestFullscreen();

            }

        } else {

            $('#video-controls').fadeIn();


            if (document.exitFullscreen) {

                document.exitFullscreen();

            } else if (document.mozCancelFullScreen) {

                document.mozCancelFullScreen();

            } else if (document.webkitCancelFullScreen) {

                document.webkitCancelFullScreen();

            } else if (document.msExitFullscreen) {

                document.msExitFullscreen();

            }
        }
    });

    $("#round-progress").draggable({
        axis: 'x'
    });

    var axis = $('#round-progress').draggable('option', 'axis');

    $('#round-progress').draggable('option', 'axis', 'x');

    $("#round-progress").bind('drag', function() {

    });


    $("#seek-bar").droppable({

        drop: function() {

            var duration = video.duration;
            var width = seekbar.offsetWidth;
            var left = $("#round-progress").css("left").replace(/[^-\d\.]/g, '');

            var margin = $("#round-progress").css("margin-left").replace(/[^-\d\.]/g, '');

            video.currentTime = (((parseFloat(left) - (parseFloat(margin) * -1)) / parseFloat(width)) * duration);

            $("#round-progress").css("left", "0");

            video.play();

        }

    });

    $('#video-container').mousemove(function() {

        $('#video-controls').fadeIn();

        var doc = window.document;

        if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {

        } else {

            lastTimeMouseMoved = new Date().getTime();
            var t = setTimeout(function() {

                var currentTime = new Date().getTime();

                if (currentTime - lastTimeMouseMoved > 1000) {

                    $('#video-controls').fadeOut();

                }

            }, 1000);
        }

    });

    innerbuffer.style.width = 0;

    video.addEventListener('progress', function() {

        var buffered = video.buffered.end(0);
        var lenght = buffered.length;
        var width = $(window).width();
        var height = seekbar.offsetHeight;
        var duration = video.duration;
        var bytesTotal = video.bytesTotal;
        var bufferedBytes = video.bufferedBytes;

        var percent;

        if (bytesTotal == undefined || bufferedBytes == undefined) {

            percent = (buffered / duration);

        } 
	   else if (bytesTotal != undefined || bufferedBytes != undefined) {

            percent = (bufferedBytes / bytesTotal);

        }

        if (percent !== null) {

            percent = 100 * Math.min(1, Math.max(0, percent));

            // Update the slider value played bar
            innerbuffer.style.width = Math.floor(percent * (width / 100));

        }


    });

};