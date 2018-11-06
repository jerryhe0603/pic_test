function changeAction(form,action){
	// if(document.getElementsByName('form1')[0].checkValidity()){
	if(form.checkValidity()){
		// if(!confirm('確定?')){
		// 	// alert(123);
		// 	$(form).submit(function(e) {
		// 	    // e.preventDefault();
		// 	    return false;
		// 	});
		// }
		$('[name=action]').val(action);
	}
	// console.log(document.getElementsByName('form1')[0].checkValidity());
	
	

	// console.log(123);
}

function doSearch(dataType,dataTable){
	var div_sign = '<CBK>';
	var obj = {
		type: 'select',
        data_type:dataType,
        data_table:dataTable
	};

	$('input[id^="search"],select[id^="search"]').each(function(){
		this.search_type = $(this).attr('search_type');
		this.search_column = $(this).attr('search_column');

		var search_type='',search_value='',search_column='';
		if(this.value!='' && this.search_column!='' ){
			// console.log('this.search_type:'+this.search_type);

			//確定搜尋條件
			search_column = this.search_column;
			search_type = (this.search_type!=undefined)?this.search_type:'=';

			// console.log('search_type:'+search_type);

			if(search_type=='like'){
				search_value = search_column+' '+search_type+' "%'+this.value+'%" ';
			}else if(search_type=='custom'){
				search_value = search_column.replace('__VALUE__',this.value);
			}else{
				search_value = search_column+' '+search_type+' "'+this.value+'" ';
			}

			obj[this.id] = search_value;
		}
	});

    $('#dg').datagrid('load',obj);
}

function doSearchClear(dataType,dataTable){
	$('input[id^="search"],select[id^="search"]').each(function(){
		this.value='';
		$(this).textbox('setText','');
	});


	doSearch(dataType,dataTable);
}


function getEasyUIFormat(format){
	var formatter = '';

	if(format=='rate'){
		formatter = function(value,row,index){
				return value+'%'
			};
	}

	return formatter;
	
}

function getSelectParams(table_name,value_col,text_col,where_cond,empty_value){
	var str='';

	str += '&table_name='+table_name;
	str += '&value_col='+value_col;
	str += '&text_col='+text_col;
	str += '&where_cond='+where_cond;
	str += '&empty_value='+empty_value;

	return str;

}
function getSelectData(table_name,value_col,text_col,where_cond,empty_value,echo_type){
	if(echo_type==undefined)echo_type='json';

	$.ajax({
	    url: 'ajax/JAGetData.php',
	    type: 'POST',
	    async: false,
	    data: {
	     	key:table_name,
	     	value_col:value_col,
	     	text_col:text_col,
	     	where_cond:where_cond,
	     	empty_value:empty_value,
	     	echo_type:echo_type
	    },
	    error: function(xhr) {
			alert('Ajax request 發生錯誤');
	    },
	    success: function(response) {
	    	// response = eval('('+response+')');
	    	// console.log(response)
	    	// alert(parent);
			
			// console.log($('#'+table_name));

	    	$('#'+table_name).val(response);
	    	return response;
		}
	});

}

function getSelectDataEx(key,table_name,value_col,text_col,where_cond,empty_value,echo_type){
	if(echo_type==undefined)echo_type='json';

	$.ajax({
	    url: 'ajax/JAGetData.php',
	    type: 'POST',
	    async: false,
	    data: {
	     	key:key,
	     	table_name:table_name,
	     	value_col:value_col,
	     	text_col:text_col,
	     	where_cond:where_cond,
	     	empty_value:empty_value,
	     	echo_type:echo_type
	    },
	    error: function(xhr) {
			alert('Ajax request 發生錯誤');
	    },
	    success: function(response) {
	    	// response = eval('('+response+')');
	    	// console.log(response);
	    	// alert(parent);
			
			// console.log($('#'+table_name));

	    	$('#'+key).val(response);
	    	return response;
		}
	});

}

function addKeyDown(keyCode,event,checkKeyCode){
	document.onkeydown = function(e){
		if(!checkKeyCode){
			if(e.keyCode==keyCode){
				event();
			}
		}else{
			alert("key:"+e.keyCode);
		}
	};	

	
}

function commafy(num){
	if(typeof num !== 'string'){
		num = num.toString();
	}
	return num.replace(/(-?\d+)(\d{3})/, "$1,$2");

}


function getUploadPath(obj){
	if(obj){
		if (window.navigator.userAgent.indexOf("MSIE") >= 1) {
	    	obj.select();
	    	return document.selection.createRange().text;
		}else if (window.navigator.userAgent.indexOf("Firefox") >= 1) {
			 if (obj.files) {
	        	return obj.files.item(0).getAsDataURL();
	    	}
	    	return obj.value;
		}
		return obj.value;
	}
}

function clearFilePath(id){
	$('#'+id).val('');
	$('#'+id).filebox('setValue','');
	$('#'+id).filebox('setText','');
}


function dateFormatter(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}
function dateParser(s){
	if (!s) return new Date();
	var ss = (s.split('-'));
	var y = parseInt(ss[0],10);
	var m = parseInt(ss[1],10);
	var d = parseInt(ss[2],10);
	if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
		return new Date(y,m-1,d);
	} else {
		return new Date();
	}
}


function JAGetColumnValue(table,col,where,print_sql){
	var str = '';
	$.ajax({
	    url: 'ajax/JAGetData.php',
	    type: 'POST',
	    async: false,
	    data: {
	     	key:'getColumnValue',
	     	table:table,
	     	col:col,
	     	where:where,
	     	print_sql:print_sql,

	     	master_id:'',
	     	empty_value:'',
	     	echo_type:''
	    },
	    error: function(xhr) {
			alert('Ajax request 發生錯誤');
	    },
	    success: function(response) {
	    	// console.log(response)

	    	str = response;
		}
	});

	return str;

}

function like(str,needle){
	if(str.indexOf(needle)>=0){
		return true;
	}else{
		return false;
	}
}

function getValue(obj){
	//是否用套件
	var is_package = true;
	// var is_package = false;
	var errMsg='';
	if(is_package){

		var package_jq = 'easyui';
		// console.log('id:'+obj.id+' className:'+obj.className);


		if(obj.className==undefined){
			errMsg = obj.id+' className is undefined!';
		}else{
			var className_arr = obj.className.split(' ');
			var check_name = '';
			for(var c=0; c<className_arr.length;c++){
				if(like(className_arr[c],package_jq)){
					check_name = className_arr[c];
					break;
				}
				// console.log('className:'+obj.className+' result:'+check_name);
			}

			if(check_name==''){

				errMsg = '找不到判斷方式';
				// console.log('找不到判斷方式');

			}

		}


		if(errMsg==''){

			var check_name_arr = check_name.split('-');
			var type = check_name_arr[1];

			// console.log('val:'+eval("$(obj)."+type+"('getValue')"));
			// console.log('obj+'+obj);

			var val = eval("$(obj)."+type+"('getValue')");

			return val;

		}else{

			alert(errMsg);

		}


	}else{
		var upper_tag = obj.tagName.toUpperCase();
		// console.log(upper_tag);

		if(upper_tag=='SPAN'){
			return obj.innerHTML;
		}else{
			return obj.value;
		}


	}
}


function setValue(obj,value){
	//是否用套件
	var is_package = true;
	// var is_package = false;
	var errMsg='';
	if(is_package){

		var package_jq = 'easyui';
		// console.log('id:'+obj.id+' className:'+obj.className);


		if(obj.className==undefined){
			errMsg = obj.id+' className is undefined!';
		}else{
			var className_arr = obj.className.split(' ');
			var check_name = '';
			for(var c=0; c<className_arr.length;c++){
				if(like(className_arr[c],package_jq)){
					check_name = className_arr[c];
					break;
				}
				// console.log('className:'+obj.className+' result:'+check_name);
			}

			if(check_name==''){

				errMsg = '找不到判斷方式';
				// console.log('找不到判斷方式');

			}

		}


		if(errMsg==''){

			var check_name_arr = check_name.split('-');
			var type = check_name_arr[1];

			// console.log('val:'+eval("$(obj)."+type+"('getValue')"));
			// console.log('obj+'+obj);

			var val = eval("$(obj)."+type+"('setValue','"+value+"')");

			// return val;

		}else{

			alert(errMsg);

		}


	}else{
		var upper_tag = obj.tagName.toUpperCase();
		// console.log(upper_tag);

		if(upper_tag=='SPAN'){
			// return obj.innerHTML;
			obj.innerHTML = value;
		}else{
			// return obj.value;
			obj.value = value
		}


	}
}


function maskLoading(obj){   
	var height_obj = '';
	if(obj.tagName=='BODY'){
		height_obj = window;
	}else{
		height_obj = document.body;
	}

	// console.log('height:'+height_obj);

	$("<div class=\"datagrid-mask\"></div>").css({display:"block",width:"100%",height:$(height_obj).height()}).appendTo(obj);   
	$("<div class=\"datagrid-mask-msg\"></div>").html("Processing, Please Wait...").appendTo(obj).css({display:"block",left:($(obj).outerWidth(true) - 190) / 2,top:($(height_obj).height() - 45) / 2});   

	// $("<div class=\"datagrid-mask\"></div>").css({display:"block",width:"100%",height:$(window).height()}).appendTo("body");   
	// $("<div class=\"datagrid-mask-msg\"></div>").html("Processing, Please Wait...").appendTo("body").css({display:"block",left:($(document.body).outerWidth(true) - 190) / 2,top:($(window).height() - 45) / 2});   
}   

function maskLoadingEnd(){   
	$(".datagrid-mask").remove();   
	$(".datagrid-mask-msg").remove();               
} 


function leftStr(str, num)
{
    return str.substring(0,num)
}

function rightStr(str, num)
{
    return str.substring(str.length-num,str.length)
}
