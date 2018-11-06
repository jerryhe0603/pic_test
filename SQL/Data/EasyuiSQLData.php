<?php

	include_once('../../Config.php');
	include_once('../../SQL/Data/SQLData.php');
	include_once('../../SQL/SQL_Connect.php');
	include_once('../../function/Function.php');
	// include_once('../../function/ChatBotFunction.php');

	include_once('../../Session.php');
	include_once('../../API/Pic_Interface.php');


	$data_type = $_REQUEST['data_type'];
	$data_table = $_REQUEST['data_table'];
	$type = $_REQUEST['type'];

	// $file = fopen('../../log/EasyuiSQLData.txt_'.date('YmdHis').'.txt','a+');
	// fwrite($file,'serialize:'."\r\n".serialize(($_REQUEST))."\r\n");
	// fclose($file);


	$result = array();

	// $SQL_data->getDataType();
	$SQL_data = new SQLData($data_type,$data_table);
	$SQL_data->setParams(array());


	if($type=='select' || $type=='select_detail' ){
		//搜尋條件
		$search_arr = array();
		$search_count_arr = array();

		if($_REQUEST!=''){

			
			foreach($_REQUEST as $key => $value){
				if(like($key,'search')){
					$search_arr[$key] = $value;
					$search_count_arr[$key] = $value;
				}
			}

			//分頁功能
			if(isset($page) && isset($rows)){

				$start = ($page-1) * $rows;
				// $end = $start + $rows;
				// $end = PAGE_SIZE;
				$end = $rows;

				// $limit_arr = array('LIMIT'=> $start.','.$end);
				$search_arr['LIMIT'] = $start.','.$end;
			}

			$SQL_data->setParams($search_arr);
		}


		if($type=='select'){
			//顯示目前的資料
			$arr = $SQL_data->getData('select',true);
			// $arr = $SQL_data->getData('select',false);

			$result["rows"] = $arr;


			//總筆數
			$SQL_data->setParams($search_count_arr);
			// $count = $SQL_data->getData('select_count',true);
			$count = $SQL_data->getData('select_count',false);

			$result["total"] = $count;
			// printArr($result);exit;

		}else if($type=='select_detail'){
				// 顯示目前的資料
			// $arr = $SQL_data->getData('select_detail',true);
			$arr = $SQL_data->getData('select_detail',false);

			$result["rows"] = $arr;

			// $file = fopen('../../log/EasyuiSQLData.txt_'.date('YmdHis').'.txt','a+');
			// fwrite($file,'serialize:'."\r\n".serialize(($arr))."\r\n");
			// fclose($file);

			//總筆數
			// $SQL_data->setParams($search_count_arr);
			// $count = $SQL_data->getData('select_count',true);
			// $count = $SQL_data->getData('select_count',false);

			$result["total"] = count($arr);
			// printArr($result);exit;

		}
		
		
		echo json_encode($result);


	}else if($type=='insert'){
		if($_REQUEST!=''){
			$arr = array();
			foreach($_REQUEST as $key => $value){

				$arr[$key] = $value;
			}
			
			if(isset($_FILES)){
				foreach($_FILES as $key => $value){
					$arr[$key] = $value;
				}
			}


			// $file = fopen('../../log/EasyuiSQLData.txt_'.date('YmdHis').'.txt','a+');
			// fwrite($file,'serialize:'."\r\n".serialize(($_REQUEST))."\r\n");
			// fclose($file);

			$SQL_data->setParams($arr);
			// $return_str = $SQL_data->getData('insert',true);
			$return_str = $SQL_data->getData('insert',false);

			if($return_str !='error'){
				$id = $return_str;
				echo json_encode(array('id'=>$id));
			}else{
				echo json_encode(array('errorMsg'=>$SQL_data->getErrMsg()));
			}
		}

	}else if($type=='update'){

		// $file = fopen('../../log/EasyuiSQLData.txt_'.date('YmdHis').'.txt','a+');
		// fwrite($file,'_FILES:'."\r\n".serialize(($_FILES))."\r\n");
		

		if($_REQUEST!=''){
			$arr = array();
			foreach($_REQUEST as $key => $value){

				$arr[$key] = $value;
			}

			if(isset($_FILES)){
				foreach($_FILES as $key => $value){

					if(isset($value['name']) && $value['name']!='' ){

						$arr[$key] = $value;
					}
				}
			}

			// fwrite($file,'arr:'."\r\n".serialize(($arr))."\r\n");

			$SQL_data->setParams($arr);
			$return_str = $SQL_data->getData('update',true);
			// $return_str = $SQL_data->getData('update',false);


			if($return_str !='error'){
				$id = $return_str;
				echo json_encode(array('id'=>$id));
			}else{
				echo json_encode(array('errorMsg'=>$SQL_data->getErrMsg()));
			}
			// echo json_encode(array('id'=>$id));

		}
		// fclose($file);
		

	}else if($type=='delete'){
		if($_REQUEST!=''){
			$arr = array();
			foreach($_REQUEST as $key => $value){

				// $arr[$key] = htmlspecialchars($value);
				$arr[$key] = $value;
			}

			$SQL_data->setParams($arr);
			// $id = $SQL_data->getData('delete',true);
			$id = $SQL_data->getData('delete',false);


			if($id !='error'){
				echo json_encode(array('success'=>true));
			}else{
				
				echo json_encode(array('errorMsg'=>$SQL_data->getErrMsg()));
			}

		}


	}else if($type=='pick'){
		if($_REQUEST!=''){
			$arr = array();
			foreach($_REQUEST as $key => $value){

				// $arr[$key] = htmlspecialchars($value);
				$arr[$key] = $value;
			}

			$SQL_data->setParams($arr);
			// $id = $SQL_data->getData('pick',true);
			$id = $SQL_data->getData('pick',false);

			if($id !='error'){
				echo json_encode(array('success'=>true));
			}else{
				echo json_encode(array('errorMsg'=>$SQL_data->getErrMsg()));
			}
		}

	}else if($type=='shipment'){
		

		if($_REQUEST!=''){
			$arr = array();
			foreach($_REQUEST as $key => $value){

				// $arr[$key] = htmlspecialchars($value);
				$arr[$key] = $value;
			}
			$SQL_data->setParams($arr);
			// $id = $SQL_data->getData('shipment',true);
			$id = $SQL_data->getData('shipment',false);


			if($id !='error'){
				echo json_encode(array('success'=>true));
			}else{
				echo json_encode(array('errorMsg'=>$SQL_data->getErrMsg()));
			}
		}
		
	}else if($type=='warehousing'){
		

		if($_REQUEST!=''){
			$arr = array();
			foreach($_REQUEST as $key => $value){

				// $arr[$key] = htmlspecialchars($value);
				$arr[$key] = $value;
			}
			$SQL_data->setParams($arr);
			// $id = $SQL_data->getData('warehousing',true);
			$id = $SQL_data->getData('warehousing',false);


			if($id !='error'){
				echo json_encode(array('success'=>true));
			}else{
				echo json_encode(array('errorMsg'=>$SQL_data->getErrMsg()));
			}
		}
		
	}

	

?>