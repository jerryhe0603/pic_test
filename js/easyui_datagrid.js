var easyui_dg = {
	dg:'',
	dg_id:'',
	form_id:'',
	dlg_id:'',
	data_type:'',
	data_table:'',

	pageSize:'',

	init:function(){
		
	},

	createDG:function(){
		dg = $('#dg').datagrid({
			url:'<?php echo EasyUI_DATA_PATH ?>',

		 	columns:[[
		        // {field:'code',title:'<?php echo _('編號') ?>',width:'',sortable:true,sortOrder:'asc' },
		        {field:'name',title:'<?php echo _('名稱') ?>',width:'',align:'center',sortable:true,sortOrder:'asc' },
		        {field:'description',title:'<?php echo _('備註') ?>',width:'',sortable:true,sortOrder:'asc' },
		        {field:'create_name',title:'<?php echo _('建立者') ?>',width:'',sortable:true,sortOrder:'asc' },
		        {field:'create_time',title:'<?php echo _('建立時間') ?>',width:'',sortable:true,sortOrder:'asc' },
		        {field:'modify_name',title:'<?php echo _('修改者') ?>',width:'',sortable:true,sortOrder:'asc' },
		        {field:'modify_time',title:'<?php echo _('修改時間') ?>',width:'',sortable:true,sortOrder:'asc' },
		        // {field:'name',title:'Name',width:100},
		        // {field:'price',title:'Price',width:100,align:'right'}
		    ]],

		 	queryParams:{
		 		data_type:'<?php echo $SQL_dataType ?>',
		 		data_table:'<?php echo $SQL_tableName ?>',
			 	type:'select'
		 	},

		 	multiSort:true,
		 	remoteSort:false,

		 	rownumbers:true,
		 	fitColumns:true, 
		 	singleSelect:true,
		 	pagination:true,
		 	pageNumber:1,

		 	toolbar:"#toolbar",

		 	// enableFilter:true,
		 	// collapsible:true
		});

		var p = $('#dg').datagrid('getPager');
		$(p).pagination({ 
	        // pageSize:10,
	        showPageList:true,
	       	pageSize:<?php echo PAGE_SIZE ?>,
		 	pageList:[<?php echo PAGE_LIST ?>],
	        beforePageText:'<?php echo _('第') ?>',
	        afterPageText:'<?php echo _('頁') ?> , <?php echo _('共') ?> {pages} <?php echo _('頁') ?>',
	        displayMsg:'<?php echo _('顯示') ?> {from} <?php echo _('到') ?> {to} <?php echo _('筆').''._('資料') ?> , <?php echo _('共') ?> {total} <?php echo _('筆').''._('資料') ?>'
	    }); 

	},
	setDataType:function(data_type){
		this.data_type = data_type;
	},

	setDataTable:function(data_table){
		this.data_table = data_table;
	},
	Add:function(){

   		$('#dlg').dialog('open').dialog('setTitle','<?php echo _('新增') ?>');
		$('#fm').form('clear');
		$('#dg').datagrid('clearSelections');
		$('#type').val('insert');
	},
	Edit:function(){
		var row = $('#dg').datagrid('getSelected');

		if (row){

			$('#dlg').dialog('open').dialog('setTitle','<?php echo _('修改') ?>');
			$('#fm').form('load',row);
			$('#type').val('update');
			$('#master_id').val(row.id);

		}else{
			$.messager.show({
				title: '<?php echo _('未選擇資料') ?>',
				msg: '<?php echo _('請選擇一列') ?>',
				timeout:1000
			});
		}
	},
	Delete:function(){
		var row = $('#dg').datagrid('getSelected');

		if (row){
			$('#type').val('delete');
			$('#master_id').val(row.id);
			
			checkForm();
		}else{
			$.messager.show({
				title: '<?php echo _('未選擇資料') ?>',
				msg: '<?php echo _('請選擇一列') ?>',
				timeout:1000
			});
		}

	},
	checkForm:function(){
		var type = $('#type').val();
		var master_id = $('#master_id').val();

		if(type=='insert'){

			FormPost(type,master_id);
		}else if(type=='update'){
			$.messager.confirm('Confirm','<?php echo _('確定要修改嗎')?>?',function(r){
				if (r){	
					FormPost(type,master_id);
				}
			});
		}else if(type=='delete'){
			$.messager.confirm('Confirm','<?php echo _('確定要刪除嗎')?>?',function(r){
				if (r){	
					FormPost(type,master_id);
				}
			});

		}

	},
	FormPost:function(type,master_id){
		var success_str = '';

		var params = {
	 		data_type:'<?php echo $SQL_dataType ?>',
	 		data_table:'<?php echo $SQL_tableName ?>',
		 	type:type,
		 	master_id:master_id
	 	};

		if(type!='delete'){
			if(type=='insert'){
				success_str = '<?php echo _('新增完成') ?>';
			}else if(type=='update'){
				success_str = '<?php echo _('修改完成') ?>';
			}

			$('#fm').form('submit',{
				url: '<?php echo EasyUI_DATA_PATH ?>',
				queryParams:params,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					// console.log(result);
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.alert('警告',result.errorMsg,'error');
					} else {
						$.messager.show({
							title: success_str+'',
							msg: success_str+'!',
							timeout:1000,
							showType:'slide'
						});

						$('#dlg').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');	// reload the user data

					}
				}
			});


		}else{

			success_str = '<?php echo _('刪除完成') ?>'; 

			$.post('<?php echo EasyUI_DATA_PATH ?>',params,function(result){
				if (result.success){
					$.messager.show({
						title: success_str+'',
						msg: success_str+'!',
						timeout:1000,
						showType:'slide'
					});

					$('#dg').datagrid('reload');	// reload the user data
				} else {
					$.messager.show({	// show error message
						title: 'Error',
						msg: result.errorMsg
					});
				}
			},'json');

		}
		
	},
	doSearch:function(dataType,dataTable){
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
				}else{
					search_value = search_column+' '+search_type+' "'+this.value+'" ';
				}

				obj[this.id] = search_value;
			}
		});

	    $('#dg').datagrid('load',obj);
	}
	doSearchClear:function(dataType,dataTable){
		$('input[id^="search"],select[id^="search"]').each(function(){
			this.value='';
		});


		doSearch(dataType,dataTable);
	}

}


$(function(){

  		var dg = $('#dg').datagrid({
		 	
		
  		// $('#dg').datagrid('reload');
	});

	