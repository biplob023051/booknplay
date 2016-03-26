<?php
App::uses ( 'HtmlHelper', 'View/Helper' );
class EBHtmlHelper extends HtmlHelper {
	public function link($title, $url = null, $options = array(), $confirmMessage = false, $checkPriv = true, $simpleText = false) {
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
						if (! $simpleText)
							return "";
						else
							return $title;
					}
				} else {
				}
			}
		}
		
		return parent::link ( $title, $url, $options, $confirmMessage );
	}
}