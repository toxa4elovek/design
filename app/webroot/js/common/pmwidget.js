$.fn.pmwidget = function () {
    this.each(function () {
        var $pmwidget = $(this);
        $pmwidget.find(".paySystem").click(function () {
            return false;
            var amInput = $pmwidget.find("#pmOpenAmount");
            if (amInput.length > 0) {
                if (amInput.val().match(/^\s*\d+.?\d*\s*$/)) $pmwidget.find("input[name='LMI_PAYMENT_AMOUNT']").val(amInput.val());
                else {alert('Укажите, пожалуйста, корректную сумму платежа');
                    return false;
                }}
            $pmwidget.find("input#pmwidgetPS").val($(this).attr("rel"));
            $pmwidget.find("#pmwidgetForm").submit();

        });
    });
    this.show();
};