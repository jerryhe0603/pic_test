<?php 
	include_once('Header.php');


	extract($_REQUEST);

	$title = "圖片類別順序管理";

	//權限檢查
	// include_once('Security.php');
	
	$SQL_data = new SQLData('PicTypeData','pic_type');
	$SQL_dataType = $SQL_data->getDataType();
	$SQL_tableName = $SQL_data->getTableName();

	// echo 'dataType:'.$SQL_dataType.' table_name:'.$SQL_tableName.'<BR>';

	$table_width = '95%';



?>


<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>排序</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="//jqueryui.com/jquery-wp-content/themes/jqueryui.com/style.css"> -->
<!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<style>
#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
#sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
#sortable li span { position: absolute; margin-left: -1.3em; }
ul {
  text-align: left;
}
</style>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type='text/javascript'>

	// $.messager.progress({
	// 	title:'讀取資料',
	// 	msg:'讀取中...'
	// });


	$( function() {
	    $( "#sortable" ).sortable();
	    $( "#sortable" ).disableSelection();
	} );

	function test(){
		// $(".ui-state-default").html()
		
		$( ".ui-state-default" ).each(function (i) {
	       
          console.log($(this).attr("id")+"==="+(i+1));
	    });
	}



</script>


<center>
	<?php 
	$aPicTypeArr = $SQL_data->getData("select","");
	// echo "type".$type; echo "<pre>";print_r($aPicTypeArr); 
	?>
	<input type="button" name="test_name" onclick="test()" value="save">
	<!-- 主要資料 -->
	<ul id="sortable">
		<?php foreach ($aPicTypeArr as $key => $value) { ?>
			<li class="ui-state-default" id="<?php echo $value['id'] ?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo "test".$key; //$value['type_name'] ?></li>

		<?php } ?>
	  <!-- <li class="ui-state-default" id="001" value=1><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1</li>
	  <li class="ui-state-default" id="002" value=2><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
	  <li class="ui-state-default" id="003" value=3><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
	  <li class="ui-state-default" id="004" value=4><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
	  <li class="ui-state-default" id="005" value=5><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
	  <li class="ui-state-default" id="006" value=6><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
	  <li class="ui-state-default" id="007" value=7><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li> -->
	</ul>
	<input type="button" name="test_name" onclick="test()" value="save">
	

</center>

