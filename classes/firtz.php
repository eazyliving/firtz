<?php

	class firtz {
	
	
		public $data = array();
		
		function __construct() {
		
			global $main;
			
			$this->main = $main;
			$main->set('firtz',$this);
			$this->BASEURL ="http://".$main->get('HOST');
			$this->BASEPATH = $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']);
			#$main->set('BASEURL',$this->BASEURL)."/";
			$main->set('BASEPATH',$this->BASEPATH);
		}
	}

?>