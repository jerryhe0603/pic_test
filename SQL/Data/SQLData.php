<?php

class SQLData{
	private $data_type='';
	private $params = array();
	private $errMsg = '';
	private $table_name = '';

	function __construct($data_type='',$table_name=''){
		$this->data_type = $data_type;
		$this->table_name = $table_name;
	}
	function setDataType($data_type){
		$this->data_type = $data_type;
	}

	function getDataType(){
		return $this->data_type;
	}


	function setParams($array){
		$this->params = $array;
	}

	function addParams($val){
		$this->params[] = $val;
	}	


	function getParams(){
		return $this->params;
	}

	function getParamsByKey($key){
		return $this->params[$key];
	}

	function getErrMsg(){
		return $this->errMsg;
	}
	function getTableName(){
		return $this->table_name;
	}

	function getData($type,$print_sql){
		$tmp = $this->data_type;

		$arr = array();
		$return_type = '';
		$return_str = '';
		$errMsg = '';
		if($tmp!=''){

			$params = $this->params;
			$table_name = $this->table_name;
			
			include($tmp.'.php');
		}


		if($errMsg!=''){
			$this->errMsg = $errMsg;
			return 'error';
		}else{

			if($return_type!=''){
				if($return_type == 'array'){
					return $arr;
				}else if($return_type == 'string'){
					return $return_str;
				}

			}else{
				if($type=='select' || $type=='select_detail' ){
					return $arr;
				}else {
					return $return_str;
				}	
			}
			
			
		}
		
	}



}
?>