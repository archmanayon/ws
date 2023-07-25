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

setTimeout(function() {
    // document.getElementById('student_id').value = '';
    // if(document.getElementById('student_id').getAttribute('placeholder')==''){
    
    // } else {
        
    //     location.reload();
    // }
    
    if(document.getElementById('student_id').getAttribute('placeholder')){
        document.getElementById('student_id').value = '';
        location.reload();
    } else {
        
        
    }
    
}, 10000);



