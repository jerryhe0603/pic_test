$.extend($.fn.validatebox.defaults.rules, {
    invoice: {
        validator: function(value, param){
        	var regex = /^[a-z,A-Z]{2}[0-9]{8}$/;
        	var result = regex.test(value);
        	// console.log('value:'+value+' result:'+result);

            // return value.length >= param[0];
            return result;
        },
        // message: 'Please enter at least {0} characters.'
        message: '請輸入10位數字,包含開頭2碼英文字母, 例:AB12345678'
    }
});