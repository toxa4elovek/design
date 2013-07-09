$(document).ready(function() {
var documentHeight = $(document).height();
//console.log(documentHeight);
var floatingBlockHeight = $('#floatingblock').height()
//console.log(floatingBlockHeight);
var offset = $('#floatingblock').offset();
//offset.top -= floatingBlockHeight;
//console.log(offset.top);
$(window).scroll(function() {
    var currentPosition = $(window).scrollTop() + floatingBlockHeight;
    /*if($('#floatingblock').hasClass('fixed')) {
        currentPosition += floatingBlockHeight;
    }*/
    if ((currentPosition  < offset.top)) {
        //console.log(currentPosition + ' vs ' + offset.top);
        //$('#floatingblock').addClass('fixed');
    } else {
        //console.log('removing fixed' + currentPosition);
        $('#floatingblock').removeClass('fixed');
    }
});

})



function renderFloatingBlock() {

$.post('/pitches/getpitchdata.json', {"pitch_id": $('input[name=pitch_id]').val()}, function(response){

    var updateBackgroundPos = function() {
        var x = -1 * (hscroll.getPosition().x - 10);
        $('.kineticjs-content', '#container').css('left', x);
        hscrollArea.setX(-100);
    };

    if((parseFloat(response.avgNum) < 3) || (response.guaranteed == '1')) {
        $('#switch').attr('src', '/img/off.png');
    }else {
        $('#switch').attr('src', '/img/on.png');
    }
    var minimum = 3;
    var colorBigNum = '#757472';
    if(response.avgNum < minimum) {
        colorBigNum = '#ed6567';
    }
    var colorGrades = '#525252';
    if(response.avgArray[response.avgArray.length - 1] < minimum) {
        colorGrades = '#ed6567';
    }
    var lowestGrade = (Math.floor(response.avgArray[response.avgArray.length - 1]));
    if(response.avgArray[response.avgArray.length - 1] > 4.6) {
        lowestGrade = 5
    }
    $('li[data-points='+ lowestGrade +']').css('font-weight', 'bold');
    $('li[data-points='+ lowestGrade +']').css('color', colorGrades);
    $('#avgPoints, #avgPointsFloat').text(response.avgNum).css('color', colorBigNum);
    $('#avgPointsString, #avgPointsStringFloat').css('color', colorBigNum);
    if(response.dates.length > 5) {
        var canvasWidth = ((response.dates.length-1) * 106) + (28 * 2);
    }else{
        var canvasWidth = '474';
    }


    var stage = new Kinetic.Stage({
        container: 'container',
        width: canvasWidth,
        height: 150
    });

    var layer = new Kinetic.Layer();
    var layer2 = new Kinetic.Layer();
    var lowestData = response.ratingArray;
    var middleData = response.moneyArray;
    var topData = response.commentArray;
    var avg = response.avgArray;
    var lowestGraphData = buildGraph(lowestData);
    var points = lowestGraphData.plot;
    var lowestToppoints = lowestGraphData.toppoints;
    var middleGraphData = buildGraph(middleData, lowestData);
    var middlePoints = middleGraphData.plot;
    var middleToppoints = middleGraphData.toppoints;

    var topGraphData = buildGraph(topData, lowestData, middleData);
    var topPoints = topGraphData.plot;
    var topToppoints = topGraphData.toppoints;

    var lowestGraph = new Kinetic.Polygon({
        points: points,
        fill: '#8d8d92',
        opacity: 0.6
    });
    var lowestLine = new Kinetic.Line({
        points: lowestToppoints,
        strokeWidth:3,
        stroke: '#464750'
    })
    var middleGraph = new Kinetic.Polygon({
        points: middlePoints,
        fill: '#a9c7ad',
        opacity:0.6
    });
    var middleLine = new Kinetic.Line({
        points: middleToppoints,
        stroke: '#678e6f',
        strokeWidth:3
    });
    var topGraph = new Kinetic.Polygon({
        points: topPoints,
        fill: '#a2b8c2',
        opacity:0.9
    });
    var topLinePoints = [];
    for(i=0;i < topToppoints.length;i++) {
        if(i % 2 == 0) {
            topLinePoints.push(topToppoints[i]);
        }else {
            topLinePoints.push(topToppoints[i]);
        }
    }
    var topLine = new Kinetic.Line({
        points: topLinePoints,
        stroke: '#6990a1',
        strokeWidth:3
    });
    var storage = {"objects": []}
    var toppoints = topLinePoints;

    var patternJSImage = new Image();
    patternJSImage.onload = function() {

    }
    patternJSImage.src = '/img/pattern_element.jpg';


    var pattern = new Kinetic.Rect({
        x: 0,
        y: 0,
        width: canvasWidth,
        height: 200,
        fillPatternImage: patternJSImage,
        opacity:1
    });

    layer.add(pattern);

    var imageObj = new Image();
    imageObj.onload = function() {
        var grid = new Kinetic.Rect({
            x: 0,
            y: 0,
            fillPatternImage: imageObj,
            width: canvasWidth,
            height: 143
        });

        layer.add(grid);
        lowestGraph.moveToBottom();
        grid.moveToTop();
        layer.draw();
        var index = 0;

        for(i=0;i<toppoints.length;i++) {
            if(i % 2 == 0) { continue };
            createImage({
                src: '/img/krug.png',
                width: 18,
                height: 18,
                x: toppoints[i-1] - 9,
                y: toppoints[i] - 9
            }, layer, true);

            var points = avg[index];


            var color = '#757472';
            if(points < minimum) {
                color = '#ed6567';
            }
            if(isInt(points)) {
                points = points + '.0';
            }

            var number = new Kinetic.Text({
                x: toppoints[i-1] -5,
                y: toppoints[i] - 25,
                text: points,
                fontSize: 19,
                fontStyle: 'bold',
                fontFamily: 'Arial',
                shadowColor: 'white',
                shadowBlur: 0,
                shadowOffset: 1,
                shadowOpacity: 1,
                fill: color
            });
            if(i != (toppoints.length - 1)) {
                var word = new Kinetic.Text({
                    x: toppoints[i-1] + 25,
                    y: toppoints[i] - 23,
                    text: 'балла',
                    fontSize: 10,
                    fontStyle: 'bold',
                    fontFamily: 'Arial',
                    shadowColor: 'white',
                    shadowBlur: 0,
                    shadowOffset: 1,
                    shadowOpacity: 1,
                    fill: color
                });
            }

            var date = new Kinetic.Text({
                x: toppoints[i-1] - 15,
                y: 132 + 5,
                text: response.dates[index],
                fontSize: '11',
                fontStyle: 'bold',
                fontFamily: 'Arial',
                fill: '#525252'
            });
            layer.add(number);
            if(typeof(word) != 'undefined') {
                layer.add(word);
            }
            layer.add(date);
            index++
        }

        layer.add(lowestGraph);
        layer.add(middleGraph);
        layer.add(topGraph);
        layer.add(lowestLine);
        layer.add(middleLine);
        layer.add(topLine);
        layer.draw();
        stage.add(layer);
        stage.add(layer2);
        $('#placeholder').hide();
        $('#floatingblock').show();
    };
    imageObj.src = '/img/table.png';


    if(response.dates.length > 5) {
        $('#scrollerarea').show();
        $('.kineticjs-content', '#container').css('right', $('.kineticjs-content', '#container').width() - 474 + 'px');
    }
    stage.add(layer);



    //======
    var can, ctx,
        numSamples,
        xScalar, yScalar,
        radius, quarter;
    // data sets -- set literally or obtain from an ajax call

    var q1Value = [ response.percentages.money, response.percentages.rating, response.percentages.comment, response.percentages.empty ];
    var fillColor = ["#629f6d", "#464750", "#6990a1", "rgba(70,71,80,0)" ];

    init();

    function init() {
        // set this value for your data
        numSamples = 4;
        can = document.getElementById("can");
        quarter = document.getElementById("quarter");
        ctx = can.getContext("2d");
        drawPie();
        can = document.getElementById("canFloat");
        quarter = document.getElementById("quarter");
        ctx = can.getContext("2d");
        drawPieFloat();
    }

    function drawPie() {
        radius = can.height / 2.4;
        var midX = can.width / 2;
        var midY = can.height / 2;
        ctx.strokeStyle = "black";
        ctx.font = "9px Helvetica";
        ctx.fontStyle = "bold";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        // get data set
        var dataValue = q1Value;
        // calculate total value of pie
        var total = 0;
        for (var i = 0; i < numSamples; i++) {
            total += dataValue[i];
        }
        // get ready to draw
        ctx.clearRect(0, 0, can.width, can.height);
        var oldAngle = 90;

        // for each sample
        for (var i = 0; i < numSamples; i++) {
            // draw wedge
            var portion = dataValue[i] / total;
            var wedge = 2 * Math.PI * portion;
            ctx.beginPath();
            var angle = oldAngle + wedge;
            ctx.arc(midX, midY, radius, oldAngle, angle);
            ctx.lineTo(midX, midY);
            ctx.closePath();
            ctx.fillStyle = fillColor[i];
            ctx.fill();    // fill with wedge color
            //ctx.stroke();  // outline in black

            // print label
            // set angle to middle of wedge
            var labAngle = oldAngle + wedge / 2;
            // set x, y for label outside center of wedge
            // adjust for fact text is wider than it is tall
            var labX = midX + Math.cos(labAngle) * radius * 0.8;
            var labY = midY + Math.sin(labAngle) * radius * 0.8;
            // print name and value with black shadow
            ctx.save();
            //ctx.shadowColor = "black";
            //ctx.shadowOffsetX = 1;
            //ctx.shadowOffsetY = -1;
            if((fillColor[i] != 'rgba(70,71,80,0)') && (dataValue[i] > 0)) {

                ctx.fillStyle = 'white';
                ctx.fillText(dataValue[i] + '%', labX, labY);
            }
            ctx.restore();
            // update beginning angle for next wedge
            oldAngle += wedge;
        }
    }

    function drawPieFloat() {
        radius = can.height / 2.4;
        var midX = can.width / 2;
        var midY = can.height / 2;
        ctx.strokeStyle = "black";
        ctx.font = "9px Helvetica";
        ctx.fontStyle = "bold";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        // get data set
        var dataValue = q1Value;
        // calculate total value of pie
        var total = 0;
        for (var i = 0; i < numSamples; i++) {
            total += dataValue[i];
        }
        // get ready to draw
        ctx.clearRect(0, 0, can.width, can.height);
        var oldAngle = 90;

        // for each sample
        for (var i = 0; i < numSamples; i++) {
            // draw wedge
            var portion = dataValue[i] / total;
            var wedge = 2 * Math.PI * portion;
            ctx.beginPath();
            var angle = oldAngle + wedge;
            ctx.arc(midX, midY, radius, oldAngle, angle);
            ctx.lineTo(midX, midY);
            ctx.closePath();
            ctx.fillStyle = fillColor[i];
            ctx.fill();    // fill with wedge color
            //ctx.stroke();  // outline in black

            // print label
            // set angle to middle of wedge
            var labAngle = oldAngle + wedge / 2;
            // set x, y for label outside center of wedge
            // adjust for fact text is wider than it is tall
            var labX = midX + Math.cos(labAngle) * radius * 0.8;
            var labY = midY + Math.sin(labAngle) * radius * 0.8;
            // print name and value with black shadow
            ctx.save();
            //ctx.shadowColor = "black";
            //ctx.shadowOffsetX = 1;
            //ctx.shadowOffsetY = -1;
            if((fillColor[i] != 'rgba(70,71,80,0)') && (dataValue[i] > 0)) {

                ctx.fillStyle = 'white';
                ctx.fillText(dataValue[i] + '%', labX, labY);
            }
            ctx.restore();
            // update beginning angle for next wedge
            oldAngle += wedge;
        }
        $('#dinamic').fadeIn(150);
    }
});
}
function buildGraph(data, prevdata, prevprevdata) {
    var width = 106;
    var height = 21;
    var graphLowestPoint = 132;
    var graphLeftPoint = 28;
    var points = [];
    var top = [];
    var bottom = [];
    //if(data.length < 5) {
    //    var diff = 5 - data.length;
    //    graphLeftPoint += diff * width;
    //}
/*
    if(data.length > 5) {
        var mod = data.length - 5;
        for(var i = 0; i < mod; i++) {
            data.shift();
        }
    }*/
    for (var i = 0; i < data.length; i++) {
        var element = (data[i] / 3);
        if(prevdata) {
            if(prevprevdata) {
                prev =  prevdata[i] / 3;
                prevprev = prevprevdata[i] / 3;
                var lowPoint = graphLowestPoint - height * (prev) - height * (prevprev);
            }else {
                prev = (prevdata[i] / 3);
                var lowPoint = graphLowestPoint - height * (prev);
            }
        }else {
            var lowPoint = graphLowestPoint;
        }
        top.push(graphLeftPoint + ((i) * width));
        top.push(lowPoint - height * element);
        bottom.push(graphLeftPoint + ((i) * width));
        bottom.push(lowPoint);
    }
    var reversedBottom = [];
    for(i = (bottom.length - 1); i >= 0; i-- ) {
        if(i % 2 == 0) { continue };
        reversedBottom.push(bottom[i-1]);
        reversedBottom.push(bottom[i]);
    }
    for (var i = 0; i < top.length; i++) {
        var element = top[i];
        points.push(element);
    }
    for (var i = 0; i < bottom.length; i++) {
        var element = reversedBottom[i];
        points.push(element);
    }

    return {"plot": points, "toppoints": top};
}

function createImage(config, layer, moveToTop, storage) {
    var img = new Image();
    img.src = config.src;
    img.onload = function() {
        var kineticImage = new Kinetic.Image(config)
        kineticImage.setImage(img);

        layer.add(kineticImage);
        if(moveToTop == true) {
            kineticImage.moveToTop();
            layer.draw();
        }

    }
}

function isInt(n) {
    return n % 1 === 0;
}

renderFloatingBlock();
$( "#scroller" ).draggable({ drag: function() {
    var x = $('#scroller').css('left');
    x = parseInt(x.substring(0, x.length - 2));
    var mod = ($('.kineticjs-content', '#container').width() - 476) / 350;
    $('.kineticjs-content', '#container').css('right', Math.round(x * mod) + 'px');
}, axis: "x", containment: "parent"});

window.onscroll = function () {
    var height = $(window).height();
    var scrollTop = $(window).scrollTop();
    var obj = $('#floatingblock')
    var pos = obj.position();
    if (height + scrollTop > pos.top) {
        $('#dinamic').fadeOut(150);
    }
    else {
        $('#dinamic').fadeIn(150);
    }
}

$('#dinamic').on('mouseover', function() {
    $(this).animate({
        opacity: 1
    }, 100)
})
$('#dinamic').on('mouseleave', function() {
    $(this).animate({
        opacity:0.6
    }, 100)
})
$('#dinamic').on('click', function() {
    console.log('click')
    $.scrollTo($('#floatingblock'), {duration:500});
})