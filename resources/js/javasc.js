
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
        let all_hobbies = [];
        
        this.hobbies.forEach(function(hobby){

            all_hobbies.push(hobby);

            // document.getElementById('here').innerHTML = hobby;  
            // console.log(hobby + " | " + movies.first);  

        });

        document.getElementById('here').innerHTML = all_hobbies.join('- \n'); 
    }

};

movies.list();

const myBtn = document.getElementById('btn');
myBtn.addEventListener('click', function (e){
    console.log(e);
});

// myPromise = new Promise((resolve, reject) => {

//     let user = {
//         name: 'archie-bitin-promise',
//         email: 'archmaayon@gmail.com'
//     };

//     let jBtn = document.getElementById('btn');
//     let jThere = document.getElementById('there');
    
    
    
//     resolve(jBtn.addEventListener('click', function(){
//             document.getElementById('there').innerHTML = user.name
//         })
//     );
    

//  })

// myPromise.then();



// practice event listener and pushing to an array variable. and foreach
    // let userss = [    
    //             {name:'leth',
    //             gender: 'f'}
    //         ,
            
    //             {name: 'arc',
    //             gender: 'm'}    
    //     ];

    //     let Uthere = document.getElementById('there');
    //     let Ubtn = document.getElementById('btn');

    //     let allU = [];

    //     userss.forEach(
    //         individual => {
    //             allU.push(individual.name+'--'+individual.gender);
    //         }
    //     );

    //     Ubtn.addEventListener('click',
    //         () => {
    //             Uthere.innerHTML = allU.join('| |');
    //         }
// );


// practice using 'this'
let clickMe = document.getElementById('btn');

clickMe.addEventListener('click',

    function(){
        
        let getDetails = () => {
            return this.getAttribute('id');
        };

        this.innerHTML = getDetails();

    }

);



