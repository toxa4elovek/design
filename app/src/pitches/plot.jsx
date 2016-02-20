$(document).on('click', '#dinamic', function() {
    $.scrollTo($('#floatingblock'), {duration:500});
});

function renderFloatingBlock() {

    $.post('/pitches/getpitchdata.json', {"pitch_id": $('input[name=pitch_id]').val()}, function(response){
        const minimum = 3;

        if((response.type != 'company_project') && ((parseFloat(response.avgNum) < 3) || (response.guaranteed == '1'))) {
            $('#refundLabel').text('Нельзя вернуть деньги.').css('color', '#ed6567');
        }else {
            if(response.guaranteed == '0') {
                const refundLabel = $('#refundLabel');
                if((response.type != 'company_project') && (($('input[name="notFinish"]').val() == '1') && (parseFloat(response.avgNum) >= 3)))  {
                    refundLabel.text('Победитель будет выбран автоматически согласно регламенту');
                    $('#whatIsIt').attr('href','/answers/view/53').html('Регламент').css('margin-left','25px');
                } else {
                    refundLabel.text('Возможность вернуть деньги доступна.');
                }
            }
        }
        let colorBigNum = '#757472';
        let colorGrades = '#525252';
        /**
         * @param {{
         * avgNum: array,
         * avgArray: array,
         * moneyArray: array,
         * commentArray: array,
         * ratingArray: array,
         * percentages: array,
         * needWinnerPopup: string,
         * needRatingPopup: bool,
         * percentages.money,
         * }} response
         */
        if(response.avgNum < minimum) {
            colorBigNum = '#ed6567';
        }

        if(response.avgArray[response.avgArray.length - 1] < minimum) {
            colorGrades = '#ed6567';
        }
        let lowestGrade = (Math.floor(response.avgArray[response.avgArray.length - 1]));
        if(response.avgArray[response.avgArray.length - 1] > 4.6) {
            lowestGrade = 5;
        }
        $(`li[data-points='${lowestGrade}']`).css('font-weight', 'bold').css('color', colorGrades);
        $('#avgPoints, #avgPointsFloat').text(response.avgNum).css('color', colorBigNum);
        $('#avgPointsString, #avgPointsStringFloat').css('color', colorBigNum);
        let canvasWidth = '474';
        if(response.dates.length > 5) {
            canvasWidth = ((response.dates.length-1) * 106) + (28 * 2);
        }

        // Создаем сцену
        const stage = new Konva.Stage({
            container: 'container',
            width: canvasWidth,
            height: 150
        });

        // Создаем слой
        const layer = new Konva.Layer();
        // Создаем переменны для данных
        const lowestData = response.ratingArray;
        const middleData = response.moneyArray;
        const topData = response.commentArray;
        const avg = response.avgArray;
        // Строим данные для графиков
        var lowestGraphData = buildGraph(lowestData);
        var points = lowestGraphData.plot;
        var lowestToppoints = lowestGraphData.toppoints;
        var middleGraphData = buildGraph(middleData, lowestData);
        var middlePoints = middleGraphData.plot;
        var middleToppoints = middleGraphData.toppoints;
        var topGraphData = buildGraph(topData, lowestData, middleData);
        var topPoints = topGraphData.plot;
        var topToppoints = topGraphData.toppoints;
        // Получаем данные для верхний точек
        var topLinePoints = [];
        for(var i=0; i < topToppoints.length; i++) {
            if(i % 2 == 0) {
                topLinePoints.push(topToppoints[i]);
            }else {
                topLinePoints.push(topToppoints[i]);
            }
        }
        // Нижний график
        var lowestGraph = new Konva.Line({
            points: points,
            fill: '#8d8d92',
            opacity: 0.6,
            closed : true
        });
        // Линия для нижнего графика
        var lowestLine = new Konva.Line({
            points: lowestToppoints,
            strokeWidth:3,
            stroke: '#464750'
        });
        // Средний график
        var middleGraph = new Konva.Line({
            points: middlePoints,
            fill: '#a9c7ad',
            opacity: 0.6,
            closed : true
        });
        // Линия для среднего графика
        var middleLine = new Konva.Line({
            points: middleToppoints,
            stroke: '#678e6f',
            strokeWidth:3
        });
        // Верхний график
        var topGraph = new Konva.Line({
            points: topPoints,
            fill: '#a2b8c2',
            opacity: 0.9,
            closed : true
        });
        // Линия для вверхнего графика
        var topLine = new Konva.Line({
            points: topLinePoints,
            stroke: '#6990a1',
            strokeWidth:3
        });
        var toppoints = topLinePoints;

        var patternJSImage = new Image();
        patternJSImage.src = '/img/pattern_element.jpg';
        // Добавляем паттерн-сетку для графика
        var pattern = new Konva.Rect({
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
            var grid = new Konva.Rect({
                x: 0,
                y: 0,
                fillPatternImage: imageObj,
                width: canvasWidth,
                height: 143
            });

            layer.add(grid);

            grid.moveToTop();
            layer.draw();
            var index = 0;

            for(var i=0; i<toppoints.length; i++) {
                if(i % 2 == 0) {
                    continue;
                }
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

                var number = new Konva.Text({
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
                    var word = new Konva.Text({
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
                // Доюавляем текст даты
                var date = new Konva.Text({
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
                index++;
            }

            layer.add(lowestGraph);
            layer.add(middleGraph);
            layer.add(topGraph);
            layer.add(lowestLine);
            layer.add(middleLine);
            layer.add(topLine);
            layer.draw();
            stage.add(layer);
            $('#placeholder').hide();
            $('#floatingblock').show();
        };
        imageObj.src = '/img/table.png';


        if(response.dates.length > 5) {
            $('#scrollerarea').show();
            $('.konvajs-content', '#container').css('right', $('.konvajs-content', '#container').width() - 474 + 'px');
        }
        stage.add(layer);

        // Low Rating Popup
        if ((response.needRatingPopup == true) && (response.guaranteed != 1)) {
            fireRatingPopup();
        }

        // Low Rating Bubble
        if ((response.avgNum < 3) && (response.guaranteed != 1)) {
            let lowReason = '';
            if (response.percentages.rating < 20) {
                lowReason = 'недостаточно звезд.';
            }
            if (response.percentages.comment < 20) {
                lowReason = 'недостаточно комментариев.';
            }
            if ((response.percentages.comment < 20) && (response.percentages.rating < 20)) {
                lowReason = 'недостаточно комментариев и звезд.';
            }
            if(response.type === 'company_project') {
                lowReason = '<span>Вашей активности недостаточно, пожалуйста, помогите исполнителям вас понять.</span>';
                $('.bubble-content').html(lowReason);
            }else {
                $('.lowReason', '#dinamic').append(lowReason);
            }
            $('.bubble').show();
            $('#bubble-close', '#dinamic').on('click', function() {
                $(this).parent().hide();
                return false;
            });
        }else {
            $('.bubble').hide();
        }

        // Private Pitch Popups
        if (response.needWinnerPopup != false) {
            const whom = (response.needWinnerPopup == 'win') ? 'win' : 'los';
            fireWinnerPopup(whom);
        }

        let can, ctx,
            numSamples,
            radius, quarter;

        /**
         * @param response.percentages.money response
         * @type {*[]}
         */
        const q1Value = [response.percentages.money, response.percentages.rating, response.percentages.comment, response.percentages.empty];
        const fillColor = ['#629f6d', '#464750', '#6990a1', 'rgba(70,71,80,0)'];

        init();

        function init() {
            // set this value for your data
            numSamples = 4;
            can = document.getElementById("can");
            quarter = document.getElementById("quarter");
            ctx = can.getContext("2d");
            drawPie();
            if($('#canFloat').length > 0) {
                numSamples = 4;
                can = document.getElementById("canFloat");
                quarter = document.getElementById("quarter");
                ctx = can.getContext("2d");
                drawPieFloat();
            }
        }

        function drawPie() {
            const midX = can.width / 2;
            const midY = can.height / 2;
            var dataValue = q1Value;
            radius = can.height / 2.4;

            ctx.strokeStyle = "black";
            ctx.font = "9px Helvetica";
            ctx.fontStyle = "bold";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";

            // calculate total value of pie
            let total = 0;
            for (var j = 0; j < numSamples; j++) {
                total += dataValue[j];
            }
            // get ready to draw
            ctx.clearRect(0, 0, can.width, can.height);
            let oldAngle = 90;

            // for each sample
            for (var i = 0; i < numSamples; i++) {
                // draw wedge
                var portion = dataValue[i] / total;
                var wedge = 2 * Math.PI * portion;
                var angle = oldAngle + wedge;
                ctx.beginPath();

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
            let dataValue = q1Value;
            // calculate total value of pie
            let total = 0;
            for (var k = 0; k < numSamples; k++) {
                total += dataValue[k];
            }
            // get ready to draw
            ctx.clearRect(0, 0, can.width, can.height);
            let oldAngle = 90;

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
    const width = 106;
    const height = 21;
    const graphLowestPoint = 132;
    const graphLeftPoint = 28;
    let points = [];
    let top = [];
    let bottom = [];
    for (let n = 0; n < data.length; n++) {
        const element = (data[n] / 3);
        let lowPoint = 0;
        if(prevdata) {
            let prev = 0;
            let prevprev = 0;
            if(prevprevdata) {
                prev =  prevdata[n] / 3;
                prevprev = prevprevdata[n] / 3;
                lowPoint = graphLowestPoint - height * (prev) - height * (prevprev);
            }else {
                prev = (prevdata[n] / 3);
                lowPoint = graphLowestPoint - height * (prev);
            }
        }else {
            lowPoint = graphLowestPoint;
        }
        top.push(graphLeftPoint + ((n) * width));
        top.push(lowPoint - height * element);
        bottom.push(graphLeftPoint + ((n) * width));
        bottom.push(lowPoint);
    }
    var reversedBottom = [];
    for(let i = (bottom.length - 1); i >= 0; i-- ) {
        if(i % 2 == 0) {
            continue;
        }
        reversedBottom.push(bottom[i-1]);
        reversedBottom.push(bottom[i]);
    }
    for (let k = 0; k < top.length; k++) {
        points.push(top[k]);
    }
    for (let l = 0; l < bottom.length; l++) {
        points.push(reversedBottom[l]);
    }
    return {"plot": points, "toppoints": top};
}

function createImage(config, layer, moveToTop) {
    "use strict";
    const img = new Image();
    img.src = config.src;
    img.onload = function() {
        const kineticImage = new Konva.Image(config);
        kineticImage.setImage(img);
        layer.add(kineticImage);
        if(moveToTop == true) {
            kineticImage.moveToTop();
            layer.draw();
        }

    };
}

function isInt(n) {
    "use strict";
    return 0 === n % 1;
}
