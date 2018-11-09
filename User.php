<?php 
	include_once('Header.php');


	extract($_REQUEST);

	$title = "使用者";

	//權限檢查
	// include_once('Security.php');
	
	$SQL_data = new SQLData('UserData','user');
	$SQL_dataType = $SQL_data->getDataType();
	$SQL_tableName = $SQL_data->getTableName();

	// echo 'dataType:'.$SQL_dataType.' table_name:'.$SQL_tableName.'<BR>';

	$table_width = '95%';


	$select_arr = array(
		
	);

?>
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

	$(function(){
		
		selectReload(stock_form_obj);

		var ok_img = '<img src="js/easyui/themes/icons/ok.png"/>';
		var no_img = '<img src="js/easyui/themes/icons/no.png"/>';
		var search_img = '<img src="js/easyui/themes/icons/search.png"/>';
		var pencil_img = '<img src="js/easyui/themes/icons/pencil.png"/>';

  		var dg = $('#dg').datagrid({
		 	url:'<?php echo EasyUI_DATA_PATH ?>',

		 	columns:[[
		        {field:'user_id',title:'<?php echo _('帳號') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'user_name',title:'<?php echo _('使用者名稱') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },

		        {field:'description',title:'<?php echo _('備註') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'create_name',title:'<?php echo _('建立者') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'create_time',title:'<?php echo _('建立時間') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'modify_name',title:'<?php echo _('修改者') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
		        {field:'modify_time',title:'<?php echo _('修改時間') ?>',width:'',align:'left',sortable:true,sortOrder:'asc' },
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
		<?php $action ="add"  ?>
		// console.log($('#'+form_id));
		$('#'+form_obj.form_id).form('clear');
		// $('#'+form_obj.form_id).form('resetDirty');
		// clearForm(form_obj.form_id);
		$('#'+form_obj.dg_id).datagrid('clearSelections');
		$('#'+form_obj.type_id).val('insert');

		selectReload(form_obj);
   		$('#'+form_obj.dlg_id).dialog('open').dialog('setTitle','<?php echo _('新增') ?>');
	}
	function Edit(form_obj){
		var row = $('#'+form_obj.dg_id).datagrid('getSelected');
		<?php $action ="edit"  ?>
		if (row){

			// $('#'+form_obj.form_id).form({dirty:true});//只更新有改過的地方
			$('#'+form_obj.form_id).form('load',row);

			$('#'+form_obj.type_id).val('update');
			$('#'+form_obj.master_id).val(row.user_id);

			$('#warehouse_id').combobox({'disabled':true});

			selectReload(form_obj);
			

			$('#'+form_obj.dlg_id).dialog('open').dialog('setTitle','<?php echo _('修改') ?>');
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
			$('#'+form_obj.type_id).val('delete');
			$('#'+form_obj.master_id).val(row.user_id);
			
			checkForm(form_obj);
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

		// console.log('type:'+type+'  master_id:'+master_id);return;

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
					return $(this).form('validate');
				},
				success: function(result){
					// console.log(result);return;	
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
				var src = 'upload/'+row.id+'/'+row['img_file_path'+i];	
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


</script>


<center>

	<input type='hidden' id='type' name='type' value='' />
	<input type='hidden' id='master_id' name='master_id' value='' />


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
	    <a href="javascript:DetailReport();" id='report_btn' class="easyui-linkbutton" iconCls="icon-print" plain="true" style='<?php echo form_toolbar_btn ?>;' ><?php echo _('匯出') ?></a>

	    &nbsp;&nbsp;
	   <!--  <input class="easyui-searchbox"  data-options="prompt:'查詢編號',searcher:doSearch" style="">
	    <input class="easyui-searchbox"  data-options="prompt:'查詢名稱',searcher:doSearch" style="">
 -->
 		<span><?php echo _('帳號') ?>:</span>
     	<input id="search_code" class="easyui-textbox"  search_type='like' search_column='<?php echo $SQL_tableName ?>.user_id' >
	    <span><?php echo _('使用者名稱') ?>:</span>
	    <input id="search_name" class="easyui-textbox"  search_type='like' search_column='<?php echo $SQL_tableName ?>.user_name' >

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
						<label><?php echo _('帳號') ?>:</label>
					</td>
					<td>
						<?php if($action=="add"){ ?>	
							<input id="user_id" name="user_id" class="easyui-textbox" required="true"/>
						<?php }else{ ?>
							<input id="user_id" name="user_id" class="easyui-textbox" disabled="disabled" />
						<?php } ?>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('密碼') ?>:</label>
					</td>
					<td>	
						<?php if($action=="add"){ ?>
							<input id="password" name="password" class="easyui-textbox" required="true"/>
						<?php }else{ ?>
							<input id="new_password" name="new_password" class="easyui-textbox" value="" />
						<?php } ?>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('使用者名稱') ?>:</label>
					</td>
					<td>	
						<input id="user_name" name="user_name" class="easyui-textbox" required="true"/>
					</td>
				</tr>

				<tr>
					<td >
						<label><?php echo _('備註') ?>:</label>
					</td>
					<td colSpan=3>
						<input id="description" name="description" class="easyui-textbox"  multiline="true"  style='width:100%' />
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

			</table>				

		</form>
	</div>


	<?php //顯示圖片用 ?>


</center>

