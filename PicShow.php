<?php 

	include_once('Header.php');



	extract($_REQUEST);

	$title = "圖片管理";

	
	$SQL_data = new SQLData('PicShowData','pic');
	$SQL_dataType = $SQL_data->getDataType();
	$SQL_tableName = $SQL_data->getTableName();

	// echo 'dataType:'.$SQL_dataType.' table_name:'.$SQL_tableName.'<BR>';

	$table_width = '95%';


	// 建立下拉選單
	$select_arr = array(
		'pic_type' => array(
			'obj_id'=>'pic_type_id',
			'value_col'=>'id',
			'text_col'=>'type_name',
			'where_cond'=>'(1=1) ORDER BY item_index',
			// 'empty_value' => '----------'
			'empty_value'=> ''
		),
		'select_item' => array(
			'obj_id'=>'is_album',
			'value_col'=>'id',
			'text_col'=>'name',
			'where_cond'=>'(1=1) ORDER BY id',
			// 'empty_value' => '----------'
			'empty_value'=> ''
		),


		

	);

?>
<link rel="stylesheet" href="./css/colorbox.css">
<script type='text/javascript'>

	stock_form_obj = {
		dg_id:'dg',
		form_id:'fm',
		dlg_id:'dlg',
		type_id:'type',
		master_id:'master_id',
		data_type:'<?php echo $SQL_dataType ?>',
 		data_table:'<?php echo $SQL_tableName ?>',
	
	}
	// $.messager.progress({
	// 	title:'讀取資料',
	// 	msg:'讀取中...'
	// });

	maskLoading(document.body);
	Dropzone.autoDiscover = false;

   $(document).ready(function () {
		$(".iframe").colorbox({iframe:true,width:"90%", height:"90%"});

        $("#fm").dropzone({
            maxFiles: 2000,
            url: "ajax/JAGetFileData.php/",
            success: function (file, response) {
                console.log(response);
            }
        });
   })

	$(function(){
		
		selectReload(stock_form_obj);

		var ok_img = '<img src="js/easyui/themes/icons/ok.png"/>';
		var no_img = '<img src="js/easyui/themes/icons/no.png"/>';
		var search_img = '<img src="js/easyui/themes/icons/search.png"/>';
		var edit_add_img = '<img src="js/easyui/themes/icons/edit_add.png"/>';
		var pencil_img = '<img src="js/easyui/themes/icons/pencil.png"/>';

  		var dg = $('#dg').datagrid({
		 	url:'<?php echo EasyUI_DATA_PATH ?>',
		 	nowrap: false,
		 	columns:[[
		        {field:'id',title:'<?php echo _('編號') ?>',width:'',align:'left',sortable:true,
		        	sorter:function(a,b){
		        		if (parseFloat(a))a = parseFloat(a);
						if (parseFloat(b))b = parseFloat(b);

		        		return (a>b?1:-1);
		        	} 

		    	},
		        {field:'pic_name',title:'<?php echo _('圖名') ?>',width:'10%',align:'left',sortable:true,sortOrder:'asc' },
		        // {field:'pic_name_en',title:'<?php echo _('圖名(英文)') ?>',width:'10%',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'make_age',title:'<?php echo _('製圖時代') ?>',width:'8%',align:'left',sortable:true,sortOrder:'asc' },
		        // {field:'make_age_en',title:'<?php echo _('製圖時代(英文)') ?>',width:'10%',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'uniform_number',title:'<?php echo _('統一編號') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'format_type',title:'<?php echo _('版式類型') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        // {field:'format_type_en',title:'<?php echo _('版式類型(英文)') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'pic_size',title:'<?php echo _('尺寸') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'pic_type_name',title:'<?php echo _('類別') ?>',width:'5%',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'description',title:'<?php echo _('描述') ?>',width:'15%',align:'left',sortable:true,sortOrder:'asc' },
		        // {field:'description_en',title:'<?php echo _('描述(英文)') ?>',width:'20%',align:'left',sortable:true,sortOrder:'asc' },
		        // {field:'pic_no',title:'<?php echo _('圖片編號') ?>',width:'',align:'right',sortable:true,sortOrder:'asc' },
		        {field:'is_album_name',title:'<?php echo _('冊頁') ?>',width:'',align:'center',sortable:true,sortOrder:'asc' },
		        {field:'width_1',title:'<?php echo _('原始檔寬') ?>',width:'',align:'right',sortable:true,sortOrder:'asc' },
		        {field:'height_1',title:'<?php echo _('原始檔高') ?>',width:'',align:'right',sortable:true,sortOrder:'asc' },
		        {field:'img_file_path1',title:'<?php echo _('圖片') ?>',width:'',align:'center',halign:'center',sortable:true,sortOrder:'asc',
	    			formatter:function(value,row){
	    				var has_pic = false;
	    				for(var i=1;i<=5;i++){
	    					if(row['img_file_path'+i]!=undefined){
	    						has_pic = true;
	    					}	
	    				}

	    				return (has_pic)?'<a href="javascript:doShowPic(stock_form_obj);">'+search_img+'</a>':'';
					}
		    	},

		    	{field:'img_file_path',title:'<?php echo _('縮圖') ?>',width:'10%',align:'center',halign:'center',sortable:true,sortOrder:'asc',
	    			formatter:function(value,row){
	    				var has_pic = false;
	    				for(var i=1;i<=5;i++){
	    					if(row['img_file_path'+i]!=undefined){
	    						has_pic = true;
	    					}	
	    				}
	    				var img_url= row['img_file_path1'];
	    				return (has_pic)?'<img height="100px" width="100px" src="./thumb/'+img_url+' " />':'';
					}
		    	},

		        {field:'create_name',title:'<?php echo _('建立者') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'create_time',title:'<?php echo _('建立時間') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'modify_name',title:'<?php echo _('修改者') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'modify_time',title:'<?php echo _('修改時間') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },

		        {field:'is_album',title:'<?php echo _('上傳冊頁圖片') ?>',width:'',align:'center',halign:'center',sortable:true,sortOrder:'asc',
	    			formatter:function(value,row){
	    				var is_album = false;
	    				var row_id = row['id'];
	    				if(row['is_album']==1){
	    					is_album=true;
	    				}	
	    				// return '<a class="iframe" href="./PicUpload.php">'+search_img+'</a>';
	    				return (is_album)?'<a href="javascript:doShowUploadPic(stock_form_obj,'+row_id+');">'+edit_add_img+'</a>':'';
					}
		    	},
		        // {field:'name',title:'Name',width:100},
		        // {field:'price',title:'Price',width:100,align:'right'}
		    ]],

		 	queryParams:{
		 		data_type:'<?php echo $SQL_dataType ?>',
		 		data_table:'<?php echo $SQL_tableName ?>',
			 	type:'select',
		 	},

		 	striped:true,
		 	multiSort:true,
		 	remoteSort:false,

		 	rownumbers:true,
		 	fitColumns:false, 
		 	singleSelect:true,
		 	pagination:true,
		 	pageNumber:1,
	 		pageSize:<?php echo PAGE_SIZE ?>,
		 	pageList:[<?php echo PAGE_LIST ?>],

		 	toolbar:"#toolbar",

		 	onBeforeLoad:function(){
	 			// $.messager.progress('close');
	 			maskLoadingEnd();
		 	},

		 	onLoadSuccess:function(){
	 			// $.messager.progress('close');
	 			// $('#big_mask').show();
	 			// maskLoadingEnd();
		 	},

		 	onDblClickRow:function(index,row){
        		Edit(stock_form_obj);
            }

   //  		onLoadSuccess:function(data){ 
			// 	$(".pic_button").each(function(){
			// 		$(this).linkbutton();
			// 		// this.linkbutton({ 
			// 		// 	text:this.text,
			// 		// 	plain:this.plain
			// 		// });
			// 	});
			// }
		 	// enableFilter:true,
		 	// collapsible:true
		});
	
		
		var p = $('#'+stock_form_obj.dg_id).datagrid('getPager');
		$(p).pagination({ 
	        // pageSize:10,
	        showPageList:true,
	       	pageSize:<?php echo PAGE_SIZE ?>,
		 	pageList:[<?php echo PAGE_LIST ?>],
	        beforePageText:'<?php echo _('第') ?>',
	        afterPageText:'<?php echo _('頁') ?> , <?php echo _('共') ?> {pages} <?php echo _('頁') ?>',
	        displayMsg:'<?php echo _('顯示') ?> {from} <?php echo _('到') ?> {to} <?php echo _('筆').''._('資料') ?> , <?php echo _('共') ?> {total} <?php echo _('筆').''._('資料') ?>'
	    }); 
		
  		// $('#dg').datagrid('reload');
  // 		setTimeout(function(){
		//     //do what you need here
  // 			console.log($('#dg').datagrid('getData'));
		// }, 2000);

	});

	function Add(form_obj){
		// console.log($('#'+form_id));
		$('#'+form_obj.form_id).form('clear');
		// $('#'+form_obj.form_id).form('resetDirty');
		// clearForm(form_obj.form_id);
		$('#'+form_obj.dg_id).datagrid('clearSelections');
		$('#'+form_obj.type_id).val('insert');

		selectReload(form_obj);

		$('#'+form_obj.dlg_id).dialog({
										title: "<?php echo _('新增') ?>",
										top:'40%'

										});
		$('#'+form_obj.dlg_id).dialog('open');

   		// $('#'+form_obj.dlg_id).dialog('open').dialog('setTitle','<?php echo _('新增') ?>');
	}
	function Edit(form_obj){
		var row = $('#'+form_obj.dg_id).datagrid('getSelected');

		if (row){

			// $('#'+form_obj.form_id).form({dirty:true});//只更新有改過的地方
			$('#'+form_obj.form_id).form('load',row);

			$('#'+form_obj.type_id).val('update');
			$('#'+form_obj.master_id).val(row.id);

			$('#warehouse_id').combobox({'disabled':true});

			selectReload(form_obj);
			
			$('#'+form_obj.dlg_id).dialog({
										title: "<?php echo _('修改') ?>",
										top:'40%'

										});
			$('#'+form_obj.dlg_id).dialog('open');
			// $('#'+form_obj.dlg_id).dialog('open').dialog('setTitle','<?php echo _('修改') ?>');
		}else{
			$.messager.show({
				title: '<?php echo _('未選擇資料') ?>',
				msg: '<?php echo _('請選擇一列') ?>',
				timeout:1000
			});
		}
	}
	function Delete(form_obj){


		var row = $('#'+form_obj.dg_id).datagrid('getSelected');

		if (row){
			var r = confirm("確定刪除?冊頁也會一併刪除!");
			if (r == true) {
			    $('#'+form_obj.type_id).val('delete');
				$('#'+form_obj.master_id).val(row.id);
				checkForm(form_obj);
			} else {
			    return;
			}

			
		}else{
			$.messager.show({
				title: '<?php echo _('未選擇資料') ?>',
				msg: '<?php echo _('請選擇一列') ?>',
				timeout:1000
			});
		}

	}

	function DetailReport(){
		window.open('StockDetailReport.php?');
	}


	function checkForm(form_obj){
		var type = $('#'+form_obj.type_id).val();
		var master_id = $('#'+form_obj.master_id).val();

		// console.log('type:'+type);

		if(type=='insert'){
			// alert(123);
			FormPost(form_obj);
		}else if(type=='update'){
			$.messager.confirm('Confirm','<?php echo _('確定要修改嗎')?>?',function(r){
				if (r){	
					FormPost(form_obj);
				}
			});
		}else if(type=='delete'){
			$.messager.confirm('Confirm','<?php echo _('確定要刪除嗎')?>?',function(r){
				if (r){	
					FormPost(form_obj);
				}
			});

		}

	}

	function FormPost(form_obj){
		var success_str = '';
		var type = $('#'+form_obj.type_id).val();

		var params = {
	 		data_type:form_obj.data_type,
	 		data_table:form_obj.data_table,
		 	type:type,
		 	master_id:$('#'+form_obj.master_id).val(),
		 	stock_id:$('#stock_id').val()
	 	};

	 	// console.log('type2:'+type);

	 	

		if(type!='delete'){
			if(type=='insert'){
				success_str = '<?php echo _('新增完成') ?>';
			}else if(type=='update'){
				success_str = '<?php echo _('修改完成') ?>';
			}
			// console.log(1230);

			maskLoading($('#'+form_obj.dlg_id));

			$('#'+form_obj.form_id).form('submit',{
				url: '<?php echo EasyUI_DATA_PATH ?>',
				queryParams:params,
				onSubmit: function(){
					// return $(this).form('validate');
				},
				success: function(result){
		
					// uploadFile();
					<?php if($_SESSION['user_id']=='admin'){ ?> 
						console.log(result);
					<?php } ?> 
					
					var result = eval('('+result+')');
					if (result.errorMsg){
						maskLoadingEnd();
						$.messager.alert('<?php echo _("警告") ?>',result.errorMsg,'error');
					} else {
						maskLoadingEnd();

						$.messager.show({
							title: success_str+'',
							msg: success_str+'!',
							timeout:1000,
							showType:'slide'
						});


						$('#'+form_obj.dlg_id).dialog('close');		// close the dialog

						//避免新增完一筆 其他筆按修改 會更新到別的商品圖片的問題
						$('[class^=easyui-filebox]').each(function(){
							// this.value='';
							$(this).filebox('clear');
							// $(this).filebox('setText','');
							
						});

						$('#'+form_obj.dg_id).datagrid('reload');	// reload the dg
						// $('#'+stock_form_obj.dg_id).datagrid('reload');	// reload the stock_dg

					}

				}
			});


		}else{

			success_str = '<?php echo _('刪除完成') ?>'; 

			maskLoading(document.body);
			$.post('<?php echo EasyUI_DATA_PATH ?>',params,function(result){
				// console.log('result:'+result);
				if (result.success){
					maskLoadingEnd();

					$.messager.show({
						title: success_str+'',
						msg: success_str+'!',
						timeout:1000,
						showType:'slide'
					});

					$('#'+form_obj.dg_id).datagrid('reload');	// reload the user data
				} else {
					maskLoadingEnd();
					$.messager.alert('<?php echo _('警告') ?>',result.errorMsg,'error');
				}
			},'json').fail(
				function(result){
					console.log(result.responseText);
				}
			);
		}

		
	}

	function selectReload(form_obj){
		// return;
	

		<?php if($select_arr!=''): ?>
			<?php foreach($select_arr as $table => $table_params ):
				extract($table_params);
			?>
				<?php echo 'getSelectData('.SQLStr($table).','.SQLStr($value_col).','.SQLStr($text_col).','.SQLStr($where_cond).',"'.($empty_value).'","json");'; ?>
			<?php endforeach ?>
		<?php endif ?>


		valueReload(form_obj);
		
		// parent.combobox('setValue', parent_val);
	}


	//把有些物件的值帶回
	function valueReload(form_obj){
		var type = $('#'+form_obj.type_id).val();

		//select 

		<?php if($select_arr!=''): ?>
			<?php foreach($select_arr as $table => $table_params ):
				extract($table_params);
			?>
				var dataObj = $('#<?php echo $table ?>');

				// var combo_id_arr = '<?php echo $table ?>_id';
				var combo_id_arr = '<?php echo $obj_id ?>'.split(',');

				for(var c=0;c<combo_id_arr.length;c++){

					var combo_id = combo_id_arr[c];
					var combo_obj = $('#'+combo_id);
					
					combo_obj.combobox({
			    		data:eval('('+dataObj.val()+')'),
			    		valueField:'<?php echo $value_col ?>',
			    		textField:'<?php echo $text_col ?>',
			    		width:'100%'
		    		});
					
			    	// combo_obj.combobox('reload');					
			    	var option_obj = combo_obj.combobox('options');	

			    	if(type!='update'){
			    		if(combo_obj.attr('default_value')!=undefined){
							var combo_val = combo_obj.attr('default_value');
		    			}else if(option_obj.data!=undefined && option_obj.data.length>0 ){
			    			var combo_val = option_obj.data[0].id;
			    		}else{
			    			var combo_val = '';
			    		}
			    	}else{
			    		var combo_val = $('#'+form_obj.dg_id).datagrid('getSelected')[combo_id];
			    	}

			    	//多選的下拉選單處理
			    	if(combo_val!='' && combo_val!=undefined){
			    		if(combo_obj.attr('multiple')){
				    		combo_obj.combobox('setValues',combo_val.split(','));
				    	}else{
				    		combo_obj.combobox('setValue',combo_val);	
				    	}	
			    	}else{
						if(combo_obj.attr('multiple')){
				    		combo_obj.combobox('setValues',[]);
				    	}else{
				    		combo_obj.combobox('setValue','');	
				    	}	
			    	}
			    	
	    			


	    			// console.log( $('#'+form_obj.dg_id).datagrid('getSelected'));
	    			// console.log('id:'+combo_id_arr[c]+' value:'+combo_val);
				}

				

			<?php endforeach ?>
		<?php endif ?>


		//switch
		$('[class^=easyui-switchbutton]').each(function(){
			// console.log();
			if($(this).attr('default-checked')){
				$(this).switchbutton('check');
			}
			// console.log('id:'+this.id+' val:'+$('#dg').datagrid('getSelected')[this.id])
		});

		//upload
		$('[class^=easyui-filebox]').each(function(){

			if(type=='update'){
				var img_id = 'org_img'+rightStr(this.id,1);
				var img_val = $('#'+form_obj.dg_id).datagrid('getSelected')[img_id];
				// console.log('selected:'+JSON.stringify($('#'+form_obj.dg_id).datagrid('getSelected')));
				

				$(this).filebox('setValue',img_val);
				$(this).filebox('setText',img_val);
				// console.log('id:'+this.id+' val:'+$('#dg').datagrid('getSelected')[this.id])	
			}
			
		});

		//hidden
		$('[type=hidden]').each(function(){
			// console.log();
			if($(this).attr('default_value')!=undefined){
				$(this).val($(this).attr('default_value'));
			}
			// console.log('id:'+this.id+' val:'+$('#dg').datagrid('getSelected')[this.id])
		});

	}


	// function doShowPic(pic){
	function doShowPic(form_obj){
		var row = $('#'+form_obj.dg_id).datagrid('getSelected');
		// console.log(pic);
		$('#img_dlg').dialog('open').dialog('setTitle','<?php echo _('圖片') ?>');
		// $('#img').attr('src',pic);

		
		for(var i=1;i<=5;i++){
			var img_id = '#img'+i;
			
			if(row['img_file_path'+i]!=undefined){
				var src = 'thumb/'+row['img_file_path'+i];	
				// var src = 'upload/'+row.id+'/'+row['img_file_path'+i];
				
			}else{
				var src = '';
				
			}
		
			$(img_id).attr('src',src);
		}
	}


	function clearForm(form_obj){

		$('#'+form_obj.form_id).find('input').each(function(){
			if(!$(this).attr('valueFixed')){
				this.value='';
			}
			// console.log('name:'+this.name+' fix:'+this.fixed+' attr:'+$(this).attr('fixed'));	

		})
	}


	function displayUpload(value){
		// if(value==1){
		// 	$('.pic_tr').show();
		// }else{
		// 	$('.pic_tr').hide();
		// }
	}

	function doShowUploadPic(form_obj,row_id){
		var row = $('#'+form_obj.dg_id).datagrid('getSelected');
		// console.log(pic);
		 
		
		$('#select_frame').attr('src','./PicUpload.php?pic_main_id='+row_id);


		$('#img_upload_dlg').dialog({
										title: "<?php echo _('圖片上傳') ?>",
										width:'90%',
										top:'10%',
										left:'10px'
										// position: 'top'

										 // position: {
									  //       my: "center center",
									  //       at: "center center",
									  //       of: window
									  //   }

										});


		$('#img_upload_dlg').dialog('open');
		// $('#img_upload_dlg').dialog('open').dialog('setTitle','<?php echo _('圖片上傳') ?>');

		// $('iframe[name=select_frame]').contents().find('#select_id').val(row_id);

		// $("div#img_upload_dlg").dropzone({ url: "ajax/JAGetFileData.php" });
		// Dropzone.autoDiscover = false;
		// $("#fm").dropzone({
  //           maxFiles: 2000,
  //           url: "ajax/JAGetFileData.php",
  //           success: function (file, response) {
  //               console.log(response);
  //           }
  //       });
	}

</script>


<center>

	<input type='hidden' id='type' name='type' value='' />
	<input type='hidden' id='master_id' name='master_id' value='' />
	<input type='hidden' id='stock_id' name='stock_id' value='' />

	<?php //撈下拉選單需要使用  ?>
	<?php if($select_arr!=''): ?>
		<?php foreach($select_arr as $table => $table_params ):	?>
			<?php echo "<input type='hidden' id='".$table."' name='".$table."'  value='' />" ?>
		<?php endforeach ?>
	<?php endif ?>
	


	<!-- 上方 工具列 -->
	<div id="toolbar">
	    <a href="javascript:Add(stock_form_obj);" id='add_btn' class="easyui-linkbutton" iconCls="icon-add" plain="true" style='<?php echo form_toolbar_btn ?>;' ><?php echo _('新增') ?></a>
	    <a href="javascript:Edit(stock_form_obj);" id='edit_btn' class="easyui-linkbutton" iconCls="icon-edit" plain="true" style='<?php echo form_toolbar_btn ?>;' ><?php echo _('修改') ?></a>
	    <a href="javascript:Delete(stock_form_obj);" id='delete_btn' class="easyui-linkbutton" iconCls="icon-remove" plain="true" style='<?php echo form_toolbar_btn ?>;' ><?php echo _('刪除') ?></a>

	    &nbsp;&nbsp;
	   <!--  <input class="easyui-searchbox"  data-options="prompt:'查詢編號',searcher:doSearch" style="">
	    <input class="easyui-searchbox"  data-options="prompt:'查詢名稱',searcher:doSearch" style="">
 -->
 		<span><?php echo _('編號') ?>:</span>
     	<input id="search_code" class="easyui-textbox"  search_type='like' search_column='<?php echo $SQL_tableName ?>.id' >
	    <span><?php echo _('名稱') ?>:</span>
	    <input id="search_name" class="easyui-textbox"  search_type='like' search_column='<?php echo $SQL_tableName ?>.pic_name' >

	    &nbsp;
	    <a href="javascript:doSearch('<?php echo $SQL_dataType ?>','<?php echo $SQL_tableName ?>');" id='search_btn' class="easyui-linkbutton" iconCls='icon-search' plain="true" style='<?php echo form_search_btn ?>;' ><?php echo _('搜尋') ?></a>
	    &nbsp;
	    <a href="javascript:doSearchClear('<?php echo $SQL_dataType ?>','<?php echo $SQL_tableName ?>');" id='clear_search_btn' class="easyui-linkbutton" iconCls='icon-clear' plain="true" style='<?php echo form_search_btn ?>'><?php echo _('清除') ?></a>
	</div>


	<!-- 主要資料 -->
	<table id="dg" title="<?php echo $title ?>" class="easyui-datagrid" width='<?php echo $table_width ?>'  >
	</table>


	<!-- 新增 修改頁 -->
	<div id="dlg" class="easyui-dialog" style="<?php echo form_dialog_big ?>" closed="true" buttons="#dlg-buttons" resizable=true  modal=true shadow=false >
		<form id="fm" name='fm' method="post" novalidate enctype="multipart/form-data" >
			<!-- <div class="fitem"> -->
			<table border=0 class=form_table >
				<!-- <tr>
					<td colspan="" >
						<label><?php echo _('編號') ?>:</label>
					</td>
					<td colspan="3" >
						<input id="code" name="code" class="easyui-textbox" required="true"/>
					</td>
				</tr> -->
				<tr>
					<td >
						<label><?php echo _('圖名') ?>:</label>
					</td>
					<td>	
						<input id="pic_name" name="pic_name" class="easyui-textbox" required="true"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('圖名(英文)') ?>:</label>
					</td>
					<td>	
						<input id="pic_name_en" name="pic_name_en" class="easyui-textbox" required="true" validtype="english"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('製圖時代') ?>:</label>
					</td>
					<td>	
						<input id="make_age" name="make_age" class="easyui-textbox" required="true"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('製圖時代(英文)') ?>:</label>
					</td>
					<td>	
						<input id="make_age_en" name="make_age_en" class="easyui-textbox" required="true" validtype="english"/>
					</td>
				</tr>
				
				<tr>
					<td >
						<label><?php echo _('統一編號') ?>:</label>
					</td>
					<td>	
						<input id="uniform_number" name="uniform_number" class="easyui-textbox" required="true"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('版式類型') ?>:</label>
					</td>
					<td>	
						<input id="format_type" name="format_type" class="easyui-textbox" required="true"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('版式類型(英文)') ?>:</label>
					</td>
					<td>	
						<input id="format_type_en" name="format_type_en" class="easyui-textbox" required="true" validtype="english"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('尺寸') ?>:</label>
					</td>
					<td>	
						<input id="pic_size" name="pic_size" class="easyui-textbox" required="true"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('圖像編號') ?>:</label>
					</td>
					<td>	
						<input id="pic_no" name="pic_no" class="easyui-textbox" required="true"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('類別') ?>:</label>
					</td>
					<td>
						<input id='pic_type_id' name="pic_type_id" class="easyui-combobox input_type" />
						<span id='pic_type_name_disp' name='pic_type_name_disp' class='disp_type' ></span>
					</td>

				</tr>
				
				<tr>
					<td >
						<label><?php echo _('是否為冊頁') ?>:</label>
					</td>
					<td>	
						<input id='is_album' name="is_album" class="easyui-combobox input_type" data-options="panelHeight:'auto',"  />
						<span id='is_album_disp' name='is_album_disp' class='disp_type' ></span>
						<!-- <select id="is_album" name="is_album" class="easyui-combobox" data-options="panelHeight:'auto'" style="width:200px;">
						 	<option value="0" selected>否</option>
						    <option value="1">是</option>
						</select> -->
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('地圖圖片') ?>1:</label>
					</td>
					<td colSpan=3>
						<input id=img_file_path1 name="img_file_path1" class="easyui-filebox"  data-options="prompt:'<?php echo _('請選擇檔案')?>...'" accept='image/*' buttonText='<?php echo _('請選擇檔案')?>' style="width:100%">
						<!-- <a href="javascript:clearFilePath('img_file_path1');" class="easyui-linkbutton" iconCls='icon-clear' plain="true"  style='<?php echo form_search_btn ?>;' ><?php echo _('清除') ?></a> -->
					</td>
				</tr>
				

				
				<tr>
					<td >
						<label><?php echo _('備註') ?>:</label>
					</td>
					<td colSpan=3 >
						<input id="description" name="description" class="easyui-textbox"  multiline="true"  style='width:300px;height:300px' />
						<!-- <textarea cols="50" rows="5" id="description" name="description" class="easyui-textbox">
						</textarea> -->

					</td>
					
				</tr>

				<tr>
					<td >
						<label><?php echo _('備註(英文)') ?>:</label>
					</td>
					<td colSpan=3 >
						<input id="description_en" name="description_en" class="easyui-textbox"  multiline="true"  style='width:300px;height:300px' />
						<!-- <textarea cols="50" rows="5" id="description" name="description" class="easyui-textbox">
						</textarea> -->

					</td>
					
				</tr>

				<tr>
					<td colSpan=4 style='text-align: center;'>
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="checkForm(stock_form_obj);" ><?php echo _('儲存') ?></a>
						<!-- &nbsp;&nbsp;
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="$('#fm').form('reset');" style=""><?php echo _('清除') ?></a> -->
						&nbsp;&nbsp;
						<a href="javascript:void(0)" class="easyui-linkbutton" onclick="$('#dlg').dialog('close');" style=""><?php echo _('關閉') ?></a>
					</td>
				</tr>

				<input id="is_valid" name="is_valid" type='hidden' class='' value='1'  default_value='1' />
				<input id="is_serial_stock" name="is_serial_stock" type='hidden' value='0' />
				<input id="is_gift" name="is_gift" type='hidden' value='0' />
				<input id="is_consignment" name="is_consignment" type='hidden' value='0' />

			</table>				

		</form>
	</div>


	<?php //顯示圖片用 ?>
	<div id='img_dlg' class='easyui-dialog' style="<?php echo long_pic_dialog ?>" closed="true" buttons="#dlg-buttons" resizable=true  modal=true shadow=false  >
		<table>
			<tr>
				<td width=20% align=center >圖片1</td>
				<!-- <td width=20% align=center>圖片2</td>
				<td width=20% align=center>圖片3</td> -->
				<!-- 
				<td width=20% align=center >圖片4</td>
				<td width=20% align=center >圖片5</td> -->
			</tr>
			<tr>
				<td><img src='' width='100%'  id='img1' /></td>
				<!-- <td><img src='' width='100%'  id='img2' /></td>
				<td><img src='' width='100%'  id='img3' /></td> -->
				<!-- 
				<td><img src='' width='100%'  id='img4' /></td>
				<td><img src='' width='100%'  id='img5' /></td> -->
			</tr>
		</table>
		
		<center>
			<a href="javascript:void(0)" class="easyui-linkbutton" onclick="$('#img_dlg').dialog('close');" style=""><?php echo _('關閉') ?></a>
		</center>
	</div>


	<?php //上傳圖片用 ?>
	<div id='img_upload_dlg' class='easyui-dialog' style="<?php echo long_pic_dialog ?>" closed="true" buttons="#dlg-buttons" resizable=true  modal=true shadow=false  >
		<center>
		<table width="80%">
			<tr>
				<td>
					<iframe id="select_frame" name="select_frame" width="100%" height="600" src=""></iframe>
					<input id="is_valid" name="is_valid" type='hidden' class='' value='1'  default_value='1' />
				</td>
			</tr>
		</table>
		<?php /*
		<form id="fm_pic" name='fm_pic' method="post" class="dropzone" enctype="multipart/form-data" >
			<!-- <div class="fitem"> -->
			<!-- <div id=myid class="fallback">
			    <input id="file" name="file" type="file"  />
		  	</div> -->

			<input id="file" name="file" type="file"  />

		</table>

		</form>*/ ?>
		
		<center>
			<a href="javascript:void(0)" class="easyui-linkbutton" onclick="$('#img_upload_dlg').dialog('close');" style=""><?php echo _('關閉') ?></a>
		</center>
	</div>



</center>



