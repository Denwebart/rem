<?php

class RequestHelper
{
	public static function getIp() {
//		if(isset($HTTP_SERVER_VARS)) {
//			if(isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])) {
//				$realip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
//			} elseif(isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])) {
//				$realip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
//			} else {
//				$realip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
//			}
//		} else {
//			if(getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
//				$realip = getenv( 'HTTP_X_FORWARDED_FOR' );
//			} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
//				$realip = getenv( 'HTTP_CLIENT_IP' );
//			} else {
//				// IP тут
//				$realip = getenv( 'REMOTE_ADDR' );
//			}
//		}
//		return $realip;
		return Request::ip();
	}
}