function add(var1) {
  // var new_chq_no = parseInt($('#total_chq').val()) + 1;
  var new_input = "<textarea name='reply-tag' style='resize:vertical; width:400px;' rows='6' id='new_'>";
  var st="<input type='submit' name='Reply-button' value='submit' id='sbmt'>";
  console.log("aaa")
  console.log(var1)
  // var el = document.getElementById('nested-cmnt');
  var a1 = document.getElementById(var1);
  console.log(a1.children[0])
  a1.children[1].children[1].innerHTML = new_input;
  a1.children[1].children[4].innerHTML = st;
  a1.children[1].children[3].name = "hide";
  
  // a1.innerHTML = new_input;
  // a1.appendChild();

  // a1.innerHTML = st;
  // $('#var1').html(new_input);
  // $('#sbmt').html(st);
}

function show(obj) {
	console.log("bbbb")
  // var el = document.querySelector('#nested-cmnt');
   var el = document.getElementById('nested-cmnt');
   var ee = document.getElementById('new_chq');

// <a href="/javascript/manipulation/creating-a-dom-element-51/">create a new element</a> that will take the place of "el"

var newdiv = document.createElement('h5');
newdiv.innerHTML = "Umair Muhammad";
newdiv.classList.add("btn-link");   
newdiv.classList.add("text-semibold");
newdiv.classList.add("media-heading");
newdiv.classList.add("box-inline");
// document.getElementById('a').style.margin="20px 0 0 0";
newdiv.style.margin="20px 0px 0px 20px";
var newEl = document.createElement('pre');
// document.write('<br>');
newEl.innerHTML = ee.firstChild.value;
ee.innerHTML = "";
obj.innerHTML = "";
newEl.style.margin="20px 0px 0px 20px";
el.appendChild(newdiv);
newdiv.appendChild(newEl);

// replace el with newEL
// el.parentNode.replaceChild(newEl, el);


}
// function cmnt-show(){
//
// }
