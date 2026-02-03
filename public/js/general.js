var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

$('#payment-input').change( function () {
    paymentOptions(this.value);
})

function paymentOptions(method) {
    $('#payment-options-name-input option').each (function () {
        if (method=='Invoice') {
            if (this.value=='None')
                $(this).hide()
            else $(this).show()
        } else {
            if (this.value!='None') {
                $(this).hide()
                $('#payment-options-name-input option').eq(8).prop('selected','')
            } else { 
                $(this).show()
                $('#payment-options-name-input option').eq(8).prop('selected','selected')
            }
        }
    })
    if (method=='Invoice') 
         $('#payment-options-name-input option').eq(0).prop('selected','selected')

}

Number.prototype.formatMoney = function(c, d, t){
    var n = this, 
        c = isNaN(c = Math.abs(c)) ? 2 : c, 
        d = d == undefined ? "." : d, 
        t = t == undefined ? "," : t, 
        s = n < 0 ? "-" : "", 
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
        j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};