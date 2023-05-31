function changeTime(){

    var now = new Date();
    var options = {
                timeZone: 'Asia/Manila',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            };
    var localTime = now.toLocaleString('en-US', options)

    document.getElementById('m_clock').innerHTML = localTime;    

}

setInterval(changeTime,1000);

document.getElementById('toytoy').innerHTML = "toytoy";

