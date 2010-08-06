<?php
/**
 * Contem a classe Router
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
 * @version 		$LastChangedRevision: 219 $
 * @lastModified	$LastChangedDate: 2010-07-13 08:46:47 -0300 (Ter, 13 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class Inflector required
 */
Zeanwork::import(LIBS, 'inflector');

/**
 * Class Uri required
 */
Zeanwork::import(LIBS, 'uri');


/**
 * Controla o roteamento de url
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Class required Inflector and Uri
 */
class Router extends Zeanwork {

	/**
	 * Instância da classe URI
	 * @var object
	 */
	public $uri = null;
	
	/**
	 * Prefixos
	 * @var array
	 */
	public static $prefixes = array();
	
	/**
	 * Rotas
	 * @var array
	 */
	public static $routes = array();
	
	/**
	 * Controller Root
	 * @var string
	 */
	public static $root = null;
	
	/**
	 * Base
	 * @var string
	 */
	public static $base = null;
	
	/**
	 * Aqui string
	 * @var
	 */
	private static $here = null;
	
	/**
	 * Filter of named
	 * @var array
	 */
	public static $filterNamed = array();
	
	/**
	 * Retorna a instância da classe Router
	 * @return object
	 */
    private static function &__getInstance(){
    	static $instance = array();
		if(!$instance){
			$instance[0] =& Zeanwork::getInstance('Router');
		}
		return $instance[0];
    }
	
	/**
	 * Normaliza uma url
	 * @param string $url
	 * @return string
	 */
	public static function normalize($url){
		if(is_array($url)){
			$url = Router::url($url);
		}elseif(preg_match('/^[a-z\-]+:\/\//', $url)){
			return $url;
		}
		$url = '/' . $url;

		while(strpos($url, '//') !== false){
			$url = str_replace('//', '/', $url);
		}
		$url = preg_replace('/(?:(\/$))/', '', $url);

		if(empty($url))
			return '/';
		return $url;
    }
	
	/**
	 * Seta uma conexão
	 * @param string $url Url de acesso
	 * @param string $route Url para roteamendo
	 * @return boolean
	 */
	public static function connect($url, $route){
		if(is_array($url)){
			foreach($url as $key => $value){
                self::connect($value, $route);
			}
		}elseif(!is_null($route)){
			self::$routes[] = array($url, $route);
		}
		return true;
    }
	
	/**
	 * Deleta uma conexão
	 * @param string $url Url de acesso
	 * @return boolean
	 */
	public static function disconnect($url){
        $url = rtrim($url, "/");
        unset(self::$routes[$url]);
        return true;
    }
	
	/**
	 * Retorna onde eu estou no momento
	 * @return string
	 */
	public static function getHere(){
		self::updatePath();
		return self::$here;
	}
	
	/**
	 * Seta um controller root
	 * @param string $controller Nome do controller
	 */
	public static function setRoot($controller){
        self::$root = $controller;
    }
	
	/**
	 * Retorna o root
	 * @return string
	 */
	public static function getRoot(){
        return self::$root;
    }
	
	/**
	 * Seta um filtro para os parametros nomeados
	 * @param string $key Nome no parametro a ser filtrodo
	 * @param string $typeValue Tipo de dados para este parametro
	 * @return boolean
	 */
	public static function setFilterNamed($key, $typeValue){
		if(is_array($key) && is_array($typeValue) && count($key) == count($typeValue)){
			foreach($key as $kKey => $vKey){
				self::setFilterNamed($vKey, $typeValue[$kKey]);
			}
		}else{
			self::$filterNamed[$key] = $typeValue;
			return true;
		}
	}
	
	/**
	 * Retorna um filtro para os parametros nomeados
	 * @return array
	 */
	public static function getFilterNamed(){
        return self::$filterNamed;
    }
	
	/**
	 * Adiciona um prefixo
	 * @param string $prefix Nome no prefixo
	 * @return boolean
	 */
	public static function addPrefix($prefix){
        if(is_array($prefix))
			$prefixes = $prefix;
        else
			$prefixes = func_get_args();
        foreach($prefixes as $prefix){
            self::$prefixes[] = $prefix;
		}
        return true;
    }
	
	/**
	 * Retorna os prefixos
	 * @return array
	 */
	public static function getPrefixes(){
        return self::$prefixes;
    }
	
	/**
	 * Deleta um prefixo
	 * @param string $prefix
	 * @return boolean
	 */
	public static function deletePrefix($prefix){
		if(array_key_exists($prefix, self::$prefixes))
       		unset(self::$prefixes[$prefix]);
		else
			return false;
        return true;
    }
	
	/**
	 * Atualiza o caminho da página que estou
	 * @return string
	 */
	private static function updatePath(){
		$_this = self::__getInstance();
		if(!is_object($_this->uri)){
			$_this->uri = Zeanwork::getInstance('uri');
		}
		
		$_this->uri->identifyUriString();
		self::$here = $_this->uri->uriString;
	}
    
	/**
	 * Faz a interpretação das rotas
	 * @param string $url Url a ser Roteada
	 * @return string
	 */
	public static function interpretRoutes($url){
		foreach(self::$routes as $key => $route){
			$preg = $route[0];
			$preg = '%^' . str_replace(
									  array(
											':any'
										  , ':fragment'
										  , ':num'
										  , ':action'
										  , ':prefix'
										  , ':lang'
									)
									, array(
											  '(.+)'
											, '([^\/]+)'
											, '([0-9]+)'
											, '(index|show|add|create|edit|update|remove|del|delete|view|item)'
											, '('.implode('|', self::getPrefixes()).')'
											, '('.implode('|', self::getLanguages()).')'
									)
									, $preg
							) . '/?$%';
			$url = preg_replace($preg, $route[1], $url);
		}
		return self::normalize($url);
	}
	
	/**
	 * Filtra os parametros nomeados
	 * @param array $named Parametros nomeados
	 * @return array
	 */
	public static function filterNamed($named){
		$output = array();
		$types = array(
						'integer'
					  , 'float'
					  , 'string'
					  , 'array'
					  , 'boolean'
					  , 'null'
				);
		foreach($named as $key => $value){
			if(array_key_exists($key, self::$filterNamed)){
				if(in_array(self::$filterNamed[$key], $types)){
					settype($value, self::$filterNamed[$key]);
				}
				$output[$key] = $value;
			}else{
				$output[$key] = $value;
			}
		}
		return $output;
	}
	
	/**
	 * Retorna os idiomas configurados no App/Configs/languages.php
	 * @return array
	 */
	public function getLanguages(){
		return Configure::read('languages');
	}
	
	/**
	 * Retorna uma array com o controller, action e parametros e seus perpectivos valores
	 * @param string $url [optional] Url a ser parceada
	 * @param boolean $interpretRoutes [optional]
	 * @return array
	 */
	public static function parse($url = null, $interpretRoutes = true){
		if($url !== null){
			if($interpretRoutes)
				$url = self::interpretRoutes($url);
		}else{
			$url = self::interpretRoutes(self::getHere());
		}
		if($url == '/index.php'){
			$url = '/';
		}else{
			preg_match('%^/index.php?(.+)/?$%', $url, $index);
			if(count($index) > 0){
				$url = '/'.$index[1];
			}
		}
		
		$arr = array(
						'here'
					  , 'lang'
					  , 'prefix'
					  , 'controller'
					  , 'action'
					  , 'id'
					  , 'extension'
					  , 'named'
					  , 'queryString'
			);
		$path = array();
		
		$regs = array(
					  'lang' => '(?:('.implode('|', self::getLanguages()).')(?:\/|(?!\w)))'
					, 'prefix' => '(?:('.implode('|', self::getPrefixes()).')(?:\/|(?!\w)))'
					, 'controller' => '(?:([a-z_-]*)\/?)'
					, 'action' => '(?:([a-z_-]*)\/?)'
					, 'id' => '(?:(\d*))'
					, 'extension' => '(?:\.([\w]+))'
					, 'params' => '(?:\/?([^?]+))'
					, 'queryString' => '(?:\?(.*))'
				);
        $reg = '/^\/'
        	  . $regs['lang']
        	  . '?' . $regs['prefix']
        	  . '?' . $regs['controller']
        	  . '?' . $regs['action']
        	  . '?' . $regs['id']
        	  . '?' . $regs['extension']
        	  . '?' . $regs['params']
        	  . '?' . $regs['queryString']
        	  . '?/i';
		preg_match($reg, $url, $reg);
        
        foreach($arr as $k => $key){
            $path[$key] = isset($reg[$k]) ? $reg[$k] : null;
        }
        
		$path['named'] = $path['params'] = array();
		if(isset($reg[7])){
            foreach(explode('/', $reg[7]) as $param){
                if(preg_match("/([^:]*):([^:]*)/", $param, $reg))
                    $path['named'][$reg[1]] = urldecode($reg[2]);
				elseif($param != '')
                    $path['params'][] = urldecode($param);
			}
        }
        if(!empty($path['lang']))
        	Configure::write('toLanguage', $path['lang']);
		if(empty($path['controller']))
			$path['controller'] = self::getRoot();
        if(empty($path['action']))
			$path['action'] = 'index';
		if(empty($path['id']))
			$path["id"] = null;
		if(!empty($path['queryString'])){
			parse_str($path['queryString'], $queryString);
            $path['named'] = array_merge($path['named'], $queryString);
			unset($path['queryString']);
        }
		$path['named'] = self::filterNamed($path['named']);
		if(!empty($path['prefix']))
            $path['action'] = $path['prefix'].ucwords($path['action']);
		if(empty($path['extension']))
			$path['extension'] = Configure::read('defaultExtension');			
		return $path;
	}
	
	/**
	 * Retorna a url da base, Ele identifica qual protocolo foi utilizado para identificar a url e cria a base.
	 * Ex: Sua aplicação esta em http://localhost/Zeanwork.
	 * Você esta utilizando o protocolo 'REQUEST_URI', o que retornará é /Zeanwork
	 * Você esta utilizando o protocolo 'PATH_INFO' ou 'ORIG_PATH_INFO', o que retornará é /Zeanwork/index.php
	 * Você esta utilizando o protocolo 'QUERY_STRING', o que retornará é /Zeanwork/index.php?
	 * @return string
	 */
	public static function getBase(){
		if(self::$base !== null){
			return self::$base;
		}
			
		$_this = self::__getInstance();
		if(!is_object($_this->uri)){
			$_this->uri = Zeanwork::getInstance('uri');
		}
		
		switch($_this->uri->protocolUsed){
			case 'QUERY_STRING':
				$base = dirname($_SERVER['PHP_SELF']) . '/' . basename($_SERVER['PHP_SELF']) . '?';
			break;
			case 'PATH_INFO':
			case 'ORIG_PATH_INFO':
				$base = str_replace(substr($_SERVER['PHP_SELF'], strpos($_SERVER['PHP_SELF'], basename($_SERVER['SCRIPT_FILENAME']))), null, $_SERVER['PHP_SELF']) . basename($_SERVER['SCRIPT_FILENAME']);
			break;
			case 'REQUEST_URI':
				$base = dirname(dirname($_SERVER['PHP_SELF']));
			break;
			default:
				$base = '/';
			break;
		}
		
		if($base == DS || $base == '.')
			$base = '/';
		self::$base = $base;
		
		return $base;
	}
	
	/**
	 * Trata uma url
	 * @param string or array $url url a ser tratada
	 * @param boolean $full Retornar a url full
	 * @return string
	 */
	public function url($url = null, $full = false){
		if(is_array($url)){
			
			if(Configure::read('useMultiLanguage') == true){
				$lang = Configure::read('toLanguage');
			}else
				$lang = null;
			
			$defaults = array(
							'lang' => $lang
						  , 'prefix' => null
						  , 'controller' => null
						  , 'action' => null
						  , 'id' => null
						  , 'extension' => null
						  , 'named' => array()
						  , 'queryString' => null
				);
			$url = array_merge($defaults, $url);
				
			$finalUrl = '/';
			foreach($url as $key => $value){
				if(!key_exists($key, $defaults))
					$finalUrl .= $key . ':' . $value . '/';
				elseif($key == 'named'){
					foreach($value as $kNamed => $vNamed){
						$finalUrl .= $kNamed . ':' . $vNamed . '/';
					}
				}elseif($key == 'extension'){
					if(!is_null($value))
						$finalUrl .= '.' . $value;
				}elseif($key == 'queryString'){
					if(!is_null($value))
						$finalUrl .= '?' . $value;
				}elseif(!is_null($value))
					$finalUrl .= $value . '/';
			}
			
			$url = self::normalize(self::getBase() . $finalUrl);
		}else{
			if(preg_match("/^[a-z]+:/", $url))
				return $url;
			elseif(substr($url, 0, 1) == '/'){
				if(Configure::read('useMultiLanguage') == true){
					$arr = explode('/', $url);
					if(isset($arr[1]) && !in_array($arr[1], Configure::read('languages'))){
						$url = '/' . Configure::read('toLanguage') . $url;
					}
				}
				$url = self::getBase() . $url;
			}elseif(substr($url, 0, 1) != '#')
				$url = self::getBase() . self::getHere() . $url;
			else
				$url = self::getBase() . self::getHere() . '/' . $url;
			$url = self::normalize($url);
		}
		if($full === true)
			$url = self::normalize(substr(HOST, 0, -1) . $url);
		else
			$url;
		return $url;
	}
}