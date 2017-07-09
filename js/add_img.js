var add = function(name){
  document.getElementById('clipprep').value= name;
  if (name == "uni-hat")
    document.getElementById('img').style="margin-top: 40px; margin-left: 300px;";
  if (name == "obama")
    document.getElementById('img').style="margin-top: 80px; margin-left: 300px;";
  if (name == "canard")
    document.getElementById('img').style="margin-top: 130px; margin-left: 330px;";
  if (name == "chat")
    document.getElementById('img').style="margin-top: 160px; margin-left: 170px;";
  if (name == "birthday")
    document.getElementById('img').style="margin-top: 45px; margin-left: 155px; width: 500px; heigth: 500px;";
  document.getElementById('img').src="client/img/"+name+".png";
  document.getElementById('startbutton').disabled = false;
  console.log('toto');
};