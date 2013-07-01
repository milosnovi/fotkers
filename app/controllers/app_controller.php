<?php 
	class AppController extends Controller {
		var $helpers = array('Html', 'Form', 'Javascript', 'Ajax', 'Session');
		var $components = array('RequestHandler');
		
		 
		function ch($arr) {  
		     echo '<pre>';  
		     print_r($arr);  
		     echo '</pre>';  
		 }
		 
		 public function getSessionData($model, $field = null) {
		 	if (!isset($field)) {
		 		if(isset($_SESSION[$model])) {
		 			return $_SESSION[$model];
		 		}
		 	} else {
		 		$sessionModelData = $_SESSION[$model];
		 		return $sessionModelData[$field]; 		 		
		 	}
		 	return null;
		 }
		 
		 public function returnJsonData($data) {
		 	Configure::write('debug', 0);
			if (RequestHandlerComponent::isAjax()) {
				  header('Content-Type: text/javascript; charset="utf-8"');
				  $debugLogEntities = Configure::read('debugLogEntities');
			}
//			$debug = ob_get_contents();
//				if ($sendDebugData && $debug) {
//					if (Configure::read('log')) {
//						logDebug($debug);
//					}
//					$data[JSON_DEBUG] = (isset($data[JSON_DEBUG]) && !empty($data[JSON_DEBUG])) ? "{$data[JSON_DEBUG]}<br/>$debug" : $debug;
//					if (!CustomLog::useFlexiweb()) {
//						unset($data[JSON_DEBUG]);
//					}
//				}
//			ob_end_clean();
		  	echo json_encode(array('data' => $data)); 
		  	exit(0); 
		 }
	}
?>