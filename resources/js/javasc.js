
var myElement = document.getElementById('myElement');

myElement.setAttribute("align","right");

var second_element = myElement.getElementsByTagName("div")[1]

var myNewElement    = document.createElement("div");
var myText          = document.createTextNode("newly created text");

myElement.insertBefore(myNewElement, second_element).setAttribute("style", "color: white;");

myNewElement.appendChild(myText);

myNewElement.onmouseover = function(){

    myNewElement.style.color = 'yellow';
    // myNewElement.setAttribute("style", "color: green;");
    console.log('sample console');
};

myNewElement.onmouseout = function(){
    myNewElement.setAttribute("style", "color: white;");
};
