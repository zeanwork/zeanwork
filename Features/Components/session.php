<?php
/**
 * Contem a classe SessionComponent
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
 * @version 		$LastChangedRevision: 215 $
 * @lastModified	$LastChangedDate: 2010-06-27 16:55:31 -0300 (Dom, 27 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Funções básicas de seções
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Components
 */
class SessionComponent extends Component {
	
	/**
	 * Componentes dependentes
	 * @var array
	 */
	public $components = array();
	
	/**
	 * Inicia o trabalho com session (session_start();)
	 * @return boolean
	 */
	public function start(){
		if(isset($_SESSION))
			$session = $_SESSION;
		else{
			$session = array();
			session_start();
			$_SESSION = array_merge($_SESSION, $session);
		}
		return true;
	}
	
	/**
	 * Destroi o trabalho de session (session_destroy())
	 * @return boolean
	 */
	public function destroy(){
		 $_SESSION = array();
		 session_destroy();
		 return true;
	}
	
	/**
	 * Retorna true se já está iniciado a seção
	 * @return boolean
	 */
	public function started(){
        return isset($_SESSION);
    }
	
	/**
	 * Seta uma session
	 * @param string $name Key para a session
	 * @param string or numeric $value Valor para a session
	 * @return boolean
	 */
	public function set($name, $value){
		if(!self::started()) self::start();
		$_SESSION[$name] = $value;
		return true;
	}
	
	/**
	 * Retorna uma session ou toda ela
	 * @param string $name [optional] Key da session a ser retornada
	 * @return value of session
	 */
	public function get($name = false){
		if(!self::started()) self::start();
		if($name === false)
			return $_SESSION;
		else{
			if(array_key_exists($name, $_SESSION))
				return $_SESSION[$name];
			else
				return false;
		}
	}
	
	/**
	 * Deleta uma session (unset($_SESSION['test']))
	 * @param string $name Key da session a ser deletada
	 * @return boolean
	 */
	public function delete($name){
    	unset($_SESSION[$name]);
		return true;
    }
    
    /**
     * Seta ou retorna uma mensagem temporária
     * @param string $name Nome para a session
     * @param string $value Valor a ser setado
     */
	public function flash($name, $value = null){
		if(!is_null($value))
			return $this->set('flash_' . $name, $value);

		$value = $this->read('flash_' . $name);
		$this->delete('flash_' . $name);
		return $value;
    }
    
	/**
	 * Inicializa o componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
	public function initialize(&$controller){
        return self::start();
    }
	
	/**
	 * Faz as operações para a inicialização do componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
    public function startup(&$controller){
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
}