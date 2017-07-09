var init = function(){
  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 1000,
      height = 1100;
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
    canvas.getContext('2d').drawImage(video, 0, 0, width, height);
    var data = canvas.toDataURL('image/jpeg');
    canvas.setAttribute('src', data);
    document.getElementById('toto').value = data;
    document.forms['uploadphoto'].submit();
  }
  function snapshot()
  {
    canvas.width = 655;
    canvas.height = 655;
    canvas.getContext('2d').drawImage(video, 0, 0, 700,700);
    var data = canvas.toDataURL('image/jpeg', 1);
    document.getElementById('toto').value = data;
    document.forms['uploadphoto'].submit();
  }
  startbutton.addEventListener('click', function(ev){
      snapshot();
    ev.preventDefault();
  }, false);
};