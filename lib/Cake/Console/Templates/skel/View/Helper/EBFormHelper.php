<?php
App::uses ( 'FormHelper', 'View/Helper' );
class EBFormHelper extends FormHelper {
	public function postLink($title, $url = null, $options = array(), $confirmMessage = false, $checkPriv = true) {
		if ($checkPriv) {
			$cntr = "";
			$act = "";
			$role = ""; // Variables truncated to avoid any possible conflicts
			if (isset ( $_SESSION ["Auth"] ["User"] ["role"] ))
				$role = $_SESSION ["Auth"] ["User"] ["role"];
			else
				$role = "guest";
			
			if (is_array ( $url )) {
				$arrData = $url;
			} else {
				$arrData = Router::parse ( $url );
			}
			
			if (! isset ( $arrData ['controller'] ) || $arrData ['controller'] == "") {
				$arrData ['controller'] = $this->request->params ['controller'];
			}
			
			if (isset ( $arrData ['action'] ) && $arrData ['action'] != "") {
				$cntr = Inflector::camelize ( $arrData ['controller'] );
				$act = $arrData ['action'];
				if (App::import ( "Controller", $cntr )) {
					$cntr .= "Controller";
					$controllerObject = & new $cntr ();
					if (! $controllerObject->checkPriv ( $role, $act, true )) {
						return "";
					}
				}
			}
		}
		
		return parent::postLink ( $title, $url, $options, $confirmMessage );
	}
}