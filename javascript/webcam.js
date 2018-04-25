(function() {

    var streaming = false,
        video        = document.querySelector('#video'),
        cover        = document.querySelector('#cover'),
        canvas       = document.querySelector('#canvas'),
        photo        = document.querySelector('#photo'),
        startbutton  = document.querySelector('#startbutton'),
        width = 406,
        height = 0;
  
    navigator.getMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia);
  
    navigator.getMedia(
      {
        video: true,
        audio: false
      },
      function(stream) {
        if (navigator.mozGetUserMedia) {
          video.mozSrcObject = stream;
        } else {
          var vendorURL = window.URL || window.webkitURL;
          video.src = vendorURL.createObjectURL(stream);
        }
        video.play();
      },
      function(err) {
        console.log("An error occured! " + err);
      }
    );
  
    video.addEventListener('canplay', function(ev){
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth/width);
        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);
  
    function takepicture() {
      canvas.width = width;
      canvas.height = height;
      var ctx = canvas.getContext('2d');
      ctx.translate(width, 0);
	    ctx.scale(-1, 1);
      ctx.drawImage(video, 0, 0, width, height);
      var data = canvas.toDataURL('image/png');
      photo.setAttribute('src', data);
    }
  
    startbutton.addEventListener('click', function(ev){
        takepicture();
      ev.preventDefault();
    }, false);
  
    function save() {

      test = document.getElementById("test");
      finish = document.getElementById('finish'),
      finish.addEventListener('click', 
      function (ev) {
        var data = canvas.toDataURL('image/png');
        var data_img = photo.getAttribute('src');
        var check = document.getElementById("video").getAttribute("style");
        var check_img = document.getElementById("photo").getAttribute("style");
        var check_canvas = document.getElementById("canvas").getAttribute("style")
        if (check_canvas == "display:block") {
          test.setAttribute('value', data);
        }
        else if (check_img == "display:block") {
          test.setAttribute('value', data_img);
        }
          document.getElementById("photo").setAttribute("src", "./script/image.php");
          setTimeout(document.getElementById('zdp').submit(), 40);
        }, false);
    }
  })();