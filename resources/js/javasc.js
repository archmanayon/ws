
var myElement = document.getElementById('myElement');

myElement.setAttribute("align","right");

var second_element = myElement.getElementsByTagName("div")[1]

var myNewElement    = document.createElement("div");
var myText          = document.createTextNode("newly created text");
myNewElement.appendChild(myText);

myElement.insertBefore(myNewElement, second_element).setAttribute("style", "color: white;");

myNewElement.onmouseover = function(){
    myNewElement.style.color = 'yellow';
    // myNewElement.setAttribute("style", "color: green;");
    console.log('sample console');
};

myNewElement.onmouseout = function(){
    myNewElement.setAttribute("style", "color: white;");
};

const changeTime = () => {

    var now = new Date();
    var options = {
        timeZone: 'Asia/Manila',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric'
    };
    var localTime = now.toLocaleString('en-US', options)

    document.getElementById('m_clock').innerHTML = localTime;    

};

setInterval(changeTime,1000);

const movies = {
    first   : "archie",
    last    : "man",
    hobbies : ["coding", "lupad"],
    list    : function()
    {
        
        this.hobbies.forEach(function(hobby){

            // document.getElementById('here').innerHTML = hobby;  
            console.log(hobby + " | " + movies.first);  

        });

        
    }

};

movies.list();
