function change(){
var img = document.getElementById("icon");
var option = document.getElementById("op");
if (option)
{
  var index = option.selectedIndex;
  if (index == 0)
    index = 1;
  var path = "../img/icons/";
  path += option.options[index].value;;
  path += ".jpg";
  img.src = path;
}
}