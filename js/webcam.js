function active_webcam()
{
  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      photo        = document.querySelector('#photo'),
      width = 500,
      height = 380;

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
      streaming = true;
    }
  }, false);

  if (document.getElementById("lifile").style.display == "block")
  {
    document.getElementById("lifile").style.display = "none";
    document.getElementById("canvasfile").style.display = "none";
    document.getElementById("input_file").value = "";
    clear_context();
  }
  if (document.getElementById("livid").style.display != "block")
  {
    document.getElementById("livid").style.display = "block";
    document.getElementById("li_gif").style.display = "inline-block";
    document.getElementById("canvas").style.display = "block";
  }
  else
  {
    document.getElementById("livid").style.display = "none";
    document.getElementById("li_gif").style.display = "none";
    document.getElementById("canvas").style.display = "none";
  }
}
