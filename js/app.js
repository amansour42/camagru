/* note fonction javascript qui va gêrer l'affichage de l'image avec rafraichissement */
var webcam_ = 0;
function webcam() {
  if( !document.getElementById('webcam')) {
    document.write( '<div id="webcam" style="display:none;"></div>' );
    document.write( '<div id="webcam2"></div>' ); 
  }
  if(document.getElementById('webcam') && document.getElementById('webcam2')) {
    var date =  new Date();
    var url = '/webcam/photo.jpg?id='+date.getHours()+date.getMinutes()+date.getSeconds();
    if(webcam_++%2 == 0) {
      document.getElementById('webcam2').innerHTML = '<img data-dimension="destroy" src="'+url+'" alt="Webcam"/>';
      document.getElementById('webcam2').style.display = 'none';
      document.getElementById('webcam').style.display = 'block';
    } else {
      document.getElementById('webcam').innerHTML = '<img data-dimension="destroy" src="'+url+'" alt="Webcam"/>';
      document.getElementById('webcam').style.display = 'none';
      document.getElementById('webcam2').style.display = 'block';  
    }
    if(webcam_==1) {
      document.getElementById('webcam2').style.display = 'block';
      document.getElementById('webcam').style.display = 'none';
    }
    /* 5000 = 5 secondes : vitesse de rafraichissement de l'image */
    setTimeout("webcam()", 5000);
  }
}