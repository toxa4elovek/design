
function AwardCalculator() {
    this.multiitems = false;
}

AwardCalculator.prototype.initialize = function() {
    //console.log('Detecting number of items counter...');
    if($('#sub-site').length > 0) {
        //console.log('Items counter found')
        this.multiitems = true
    }else {
        //console.log('Items counter not found')
    }
};

AwardCalculator.prototype.getMiddlePrice = function () {
    var high = parseInt($('#award').data('high'));
    var normal = parseInt($('#award').data('normal'));
    var diff = high - normal;
    var result = normal + Math.round(diff / 2);
    //console.log('Current Middle Price is ' + result)
    return result;
};

AwardCalculator.prototype.isAwardDefault = function() {
    if($('#award').attr('value') == '') {
        //console.log('No value found...')
        return true;
    }
    if ($('#award').attr('value') == $('#award').attr('placeholder')) {
        //console.log('Award is default');
        return true;
    }else {
        //console.log('=====')
        //console.log($('#award').attr('value'))
        //console.log($('#award').attr('placeholder'))
        //console.log('Award is not default')
        return false;
    }
};

AwardCalculator.prototype.getRealValue = function() {
    if(typeof($('#award').data('real-value')) != 'undefined') {
        return $('#award').data('real-value');
    }
    if($('#award').attr('value') == '') {
        return $('#award').attr('placeholder');
    }else {
        return $('#award').attr('value');
    }
};

var Calculator = new AwardCalculator();