<?php
/**
 * Contem a classe CookieComponent
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Components
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 153 $
 * @lastModified	$LastChangedDate: 2010-04-07 21:04:50 -0300 (Qua, 07 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Funções básicas de cookie
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Components
 */
class CookieComponent extends Component {
    
	/**
	 * Tempo para a expiração
	 * @var numeric
	 */
	public $expires;

	/**
	 * Pasta
	 * @var string
	 */
	public $path = '/';
	
	/**
	 * Dominio
	 * @var string
	 */
	public $domain = null;
	
	/**
	 * Cookie seguro
	 * @var boolean
	 */
	public $secure = false;
	
	/**
	 * Prefixo
	 * @var string
	 */
	public $prefix = 'Zeanwork';
	
	/**
	 * Componentes dependentes
	 * @var array
	 */
    public $components = array();
    
	/**
	 * Inicializa o componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
    public function initialize(&$controller){
        return true;
    }
	
	/**
	 * Faz as operações para a inicialização do componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
    public function startup(&$controller){
    	if(Configure::exist('cookie.path'))
			$this->path = Configure::read('cookie.path');
		if(Configure::exist('cookie.secure'))
			$this->secure = Configure::read('cookie.secure');
		if(Configure::exist('cookie.prefix'))
			$this->prefix = Configure::read('cookie.prefix');
		if(Configure::exist('cookie.domain'))
			$this->domain = Configure::read('cookie.domain');
		if(Configure::exist('cookie.expires'))
			$this->expires = Configure::read('cookie.expires');
        return true;
    }
	
	/**
	 * Finaliza o componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
    public function shutdown(&$controller){
        return true;
    }
    
	/**
	 * Salva um cookie
	 * @param string $name Nome do cookie
	 * @param string $value Valor para o cookie
	 * @param numeric $expires [optional] Tempo para a exiparação
	 * @return boolean
	 */
	public function write($name, $value, $expires = null){
		$expires = $this->expire($expires);
		$path = Router::normalize(Router::getBase() . $this->path);
		return setcookie($this->prefix . $name, $value, $expires, $path, $this->domain, $this->secure);
	}
	
	/**
	 * Retorna um cookie
	 * @param string $name Nome do cookie
	 * @return value of cookie
	 */
	public function read($name){
		if(array_key_exists($this->prefix . $name, $_COOKIE))
			return $_COOKIE[$this->prefix . $name];
		else
			return false;
	}
	
	/**
	 * Salva um cookie (Alian CookieComponent::write())
	 * @param string $name Nome do cookie
	 * @param string $value Valor para o cookie
	 * @param numeric $expires [optional] Tempo para a exiparação
	 * @return boolean
	 */
	public function set($name, $value, $expires = null){
		return $this->write($name, $value, $expires);
	}
	
	/**
	 * Retorna um cookie (Alias CookieComponent::read())
	 * @param string $name Nome do cookie
	 * @return value of cookie
	 */
	public function get($name){
		return $this->read($name);
	}
	
	/**
	 * Deleta um cookie
	 * @param string $name Nome do cookie
	 * @return boolean
	 */
	public function delete($name){
		$path = Router::normalize(Router::getBase() . $this->path);
		return setcookie($this->prefix . $name, '', time() - 42000, $path, $this->domain, $this->secure);
	}
	
	/**
	 * Expire
	 * @param numeric $expires [optional] Tempo
	 * @return numeric
	 */
	public function expire($expires = null){
		if($expires == null){
			$expires = $this->expires;
		}
		$now = time();
		if(is_numeric($expires))
			return $this->expires = $now + $expires;
		else
			return $this->expires = strtotime($expires, $now);
	}
}