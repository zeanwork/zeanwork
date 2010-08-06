<?php
/**
 * Contem a classe Uri
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 156 $
 * @lastModified	$LastChangedDate: 2010-04-08 19:50:57 -0300 (Qui, 08 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */


/**
 * Faz a parte suja para indentificar a URL
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Class required Inflector
 */
class Uri extends Zeanwork {
	
	/**
	 * String da url
	 * @var string
	 */
	var $uriString = null;
	
	/**
	 * Protocolo que foi utilizado para a identificação da URL.
	 * @var string
	 */
	var $protocolUsed = null;
	
	/**
	 * Identifica uma a string da url
	 */
	public function identifyUriString(){
		switch(strtoupper(Configure::read('uriProtocol'))){
			case 'AUTO':
				if($uri = $this->queryString()){
					$this->uriString = $uri;
					return;
				}
				if($uri = $this->pathInfo()){
					$this->uriString = $uri;
					return;
				}
				if($uri = $this->origPathInfo()){
					$this->uriString = $uri;
					return;
				}
				if($uri = $this->requestUri()){
					$this->uriString = $uri;
					return;
				}
			break;
			case 'QUERY_STRING':
				if($uri = $this->queryString()){
					$this->uriString = $uri;
					return;
				}
			break;
			case 'PATH_INFO':
				if($uri = $this->pathInfo()){
					$this->uriString = $uri;
					return;
				}
			break;
			case 'ORIG_PATH_INFO':
				if($uri = $this->origPathInfo()){
					$this->uriString = $uri;
					return;
				}
			break;
			default:
			case 'REQUEST_URI':
				if($uri = $this->requestUri()){
					$this->uriString = $uri;
					return;
				}
			break;
		}
	}
	
	/**
	 * Interpreta uma URL pela requisição de queryString ($_GET)
	 * @return false or string
	 */
	public function queryString(){
		if(is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != ''){
			$this->protocolUsed = 'QUERY_STRING';
			return key($_GET);
		}else
			return false;
	}
	
	/**
	 * Interpreta uma URL pela requisição de path info ($_SERVER['PATH_INFO'])
	 * @return false or string
	 */
	public function pathInfo(){
		if(isset($_SERVER['PATH_INFO'])){
			$path = $_SERVER['PATH_INFO'];
			if(trim($path, '/') != ''){
				if($_SERVER['argv'])
					$path .= '?'.$_SERVER['argv'][0];
				$this->protocolUsed = 'PATH_INFO';
				return $path;
			}else
				return false;
		}
		else
			return false;
	}
	
	/**
	 * Interpreta uma URL pela requisição de orig path info ($_SERVER['ORIG_PATH_INFO'])
	 * @return false or string
	 */
	public function origPathInfo(){
		if(isset($_SERVER['ORIG_PATH_INFO'])){
			$path = str_replace($_SERVER['SCRIPT_NAME'], null, $_SERVER['ORIG_PATH_INFO']);
			if(trim($path, '/') != ''){
				if($_SERVER['argv'])
					$path .= '?'.$_SERVER['argv'][0];
				$this->protocolUsed = 'ORIG_PATH_INFO';
				return $path;
			}else
				return false;
		}else
			return false;
	}
	
	/**
	 * Interpreta uma URL pela requisição uri ($_SERVER['REQUEST_URI']) É preciso ter o mod redwrite do apache habilitado.
	 * @return false or string
	 */
	public function requestUri(){
		if(!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == null)
			return false;
		
		$base = dirname(dirname($_SERVER['PHP_SELF']));
		if($base == DS || $base == '.')
			$base = '/';
		
		$path = substr($_SERVER['REQUEST_URI'], strlen($base));
		
		if(!(strpos($path, '/') === 0))$path = '/' . $path;
		
		$this->protocolUsed = 'REQUEST_URI';
		if(trim($path, '/') != ''){
			return $path;
		}else
			return false;
	}
	
}