<?php
class EBSessionHelper extends SessionHelper {
	public function flash($key = 'flash', $attrs = array()) {
		$out = false;
		
		if (CakeSession::check ( 'Message.' . $key )) {
			$flash = CakeSession::read ( 'Message.' . $key );
			$message = $flash ['message'];
			unset ( $flash ['message'] );
			
			if (! empty ( $attrs )) {
				$flash = array_merge ( $flash, $attrs );
			}
			
			if ($flash ['element'] === 'default') {
				$class = 'alert';
				if (! empty ( $flash ['params'] ['class'] )) {
					$class = $flash ['params'] ['class'];
				}
				$out = '<div id="' . $key . 'Message" class="' . $class . '"><button type="button" class="close" data-dismiss="alert"></button>' . $message . '</div>';
			} elseif (! $flash ['element']) {
				$out = $message;
			} else {
				$options = array ();
				if (isset ( $flash ['params'] ['plugin'] )) {
					$options ['plugin'] = $flash ['params'] ['plugin'];
				}
				$tmpVars = $flash ['params'];
				$tmpVars ['message'] = $message;
				$out = $this->_View->element ( $flash ['element'], $tmpVars, $options );
			}
			CakeSession::delete ( 'Message.' . $key );
		}
		return $out;
	}
}