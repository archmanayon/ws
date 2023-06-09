function changeTime(){

    var now = new Date();
    var hour_options = {
                timeZone: 'Asia/Manila',
                hour: 'numeric',
                minute: 'numeric'
                // second: 'numeric'
            };
    
    var seconds_option = {
                
                second: 'numeric'
            };

    var localHour = now.toLocaleString('en-US', hour_options)

    document.getElementById('m_clock').innerHTML = localHour;

    
    var localSeconds = now.toLocaleString('en-US', seconds_option)

    document.getElementById('sec').innerHTML = ':'+localSeconds+' sec';  

}

setInterval(changeTime,1000);

document.getElementById('toytoy').innerHTML = "toytoy";

