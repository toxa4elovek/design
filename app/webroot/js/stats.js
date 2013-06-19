$(document).ready(function() {

    getUsersStat();
    /*setInterval(function() {
        getUsersStat();
    }, 30000)
    */

    function getUsersStat() {
        $.get('/users/stats.json', function(response) {
            var ele = $('#totalUsers');
            var clr = null;
            var startCounter = parseInt($('#totalUsers').html());
            var total = parseInt(response.data.count);
            var diff = response.data.count - startCounter;
            loop();
            function loop() {
                clearTimeout(clr);
                inloop();
                setTimeout(loop, 2500);
            }
            function inloop() {
                clearTimeout(clr);
                var currentPercentage = Math.round((startCounter / total) * (100), 2);
                if(currentPercentage > 95) {
                    var speed = 45;
                    var increase = 1;
                }else if(currentPercentage > 90) {
                    var speed = 35;
                    var increase = 2;
                }else if(currentPercentage > 80) {
                    var speed = 30;
                    var increase = 4;
                }else if(currentPercentage > 70) {
                    var speed = 25;
                    var increase = 5;
                }else if(currentPercentage > 60) {
                    var speed = 19;
                    var increase = 8;
                }else if(currentPercentage > 50) {
                    var speed = 15;
                    var increase = 10;
                }else if(currentPercentage > 40) {
                    var speed = 12;
                    var increase = 12;
                }else if(currentPercentage > 30) {
                    var speed = 9;
                    var increase = 14;
                }else if(currentPercentage > 20) {
                    var speed = 7;
                    var increase = 16;
                }else if(currentPercentage > 10) {
                    var speed = 6;
                    var increase = 18;
                }else if(currentPercentage >= 0) {
                    var speed = 5;
                    var increase = 20;
                }
                var newNumber = startCounter += increase;
                if(newNumber <= total) {
                    ele.html(newNumber);
                    clr = setTimeout(inloop, speed);
                }
            }
        })
    }
})