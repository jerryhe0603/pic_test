<?php

class Pic_Interface{
	private $interface_partner ='';
	private $interface_type ='';

	private $params = array();
	private $errMsg = '';

	function __construct($interface_partner='',$interface_type=''){
		if($interface_partner!=''){
			$this->interface_partner = $interface_partner;
		}

		if($interface_type!=''){
			$this->interface_type = $interface_type;
		}
	}


	function setInterfacePartner($interface_partner){
		$this->interface_partner = $interface_partner;
	}

	function getInterfacePartner(){
		return $this->interface_partner;
	}


	function setInterfaceType($interface_type){
		$this->interface_type = $interface_type;
	}

	function getInterfaceType(){
		return $this->interface_type;
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



	function setErrMsg($errMsg){
		$this->errMsg = $errMsg;
	}
	function getErrMsg(){
		return $this->errMsg;
	}
	


	function connectInterface($type='',$print_response=false){
		$tmp = $this->interface_partner;

		if($type==''){
			$type = $this->interface_type;
		}

		$params = $this->params;


		$arr = array('msg'=>'','data'=>'');
		$errMsg = '';
		$return_str = '';
		if($tmp!=''){

			include($tmp.'.php');
		}


		if($errMsg!=''){
			$this->errMsg = $errMsg;

			$arr['msg'] = 'error';
		}else{
			$arr['msg'] = $return_str;
		}

		return $arr;
		
	}



}
?>