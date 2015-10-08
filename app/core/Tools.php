<?php
class Tools {	
	/**
	* Get a value from $_POST / $_GET
	* @key - Value key (string)
	* @default - default value (optional)
	*/
	public static function getValue($key, $default = false){
		if (!isset($key) || empty($key) || !is_string($key))
			return false;
		$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default));

		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret)? $ret : stripslashes($ret);
	}
	
	/**
	* Get method type
	* @type - method type (string)
	*/
	public static function isMethod($type = null){
		if ($type === null)
			return $_SERVER['REQUEST_METHOD'];
		else 
			if (($type == 'post' || $type == 'POST') && $_SERVER['REQUEST_METHOD'] === 'POST')
				return true;
			elseif (($type == 'get' || $type == 'GET') && $_SERVER['REQUEST_METHOD'] === 'GET')
				return true;
			else
				return false;
	}
	
	/**
	* Check POST method
	*/
	public static function isPost(){
		return Tools::isMethod('POST');
	}
	
	/**
	* Return path to view
	* @backtrace - debug_backtrace data (array)
	*/
	public static function getViewPath($backtrace){
		if (!$backtrace || !isset($backtrace[1]))
			return false;

		$view = strtolower(preg_replace('/Controller/', '', $backtrace[1]['class'])).'/'.strtolower(preg_replace('/Action/', '', $backtrace[1]['function'])).'.php';
		return $view;
	}
	
	public static function declension($int, $expressions){
        if (count($expressions) < 3) $expressions[2] = $expressions[1];
        settype($int, "integer");
        $count = $int % 100;
        if ($count >= 5 && $count <= 20) {
            $result = $expressions['2'];
        } else {
            $count = $count % 10;
            if ($count == 1) {
                $result = $expressions['0'];
            } elseif ($count >= 2 && $count <= 4) {
                $result = $expressions['1'];
            } else {
                $result = $expressions['2'];
            }
        }
        return $result;
    }

    public static function cutString ($Astring, $length) {
        $string = strip_tags($Astring);
        if (mb_strlen($string) > $length) {
            $string = mb_substr($string, 0, $length, 'UTF-8');
            $string = rtrim($string, "!,.-");
            $length = $length - 10;
            $position = mb_strpos($string, ' ', $length);
            $string = mb_substr($string, 0, $position, 'UTF-8');
            $string .= "...";
            return $string;
        } else { 
            $string = rtrim($string, "!,.-");
            $string .= "...";
            return $string;
        }
    }
}