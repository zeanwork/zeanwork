<?php
/**
 * Contem a classe Input
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
 * @version 		$LastChangedRevision: 153 $
 * @lastModified	$LastChangedDate: 2010-04-07 21:04:50 -0300 (Qua, 07 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Gerencia a entrada de dados
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Input extends Zeanwork {
	
	/**
	 * Dados do Input
	 * @var
	 */
	private $data = array();
	
	/**
	 * Salva os dados
	 * @param array $data Dados a ser salvo
	 * @return 
	 */
	public function setData($data){
		$this->data = (array)$data;
	}
	
	/**
	 * Retorna um valor dos dados se existir, se não retorna false, se parametro for igual á null, retorna todos os dados
	 * @param string $who [optional] 
	 * @return 
	 */
	public function getData($who = null){
		if($who == null)
			return $this->data;
		else{
			if(array_key_exists($who, $this->data))
				return $this->data[$who];
			else
				return false;
		}
		return false;
	}	
	
	/**
	 * Retorna um valor do get se existir, se não retorna false, se parametro for igual á null, retorna todo o get
	 * @param string $who [optional] Qual key você quer do get
	 * @return Valor do get ou false se não existir o key no get ou array se o parametro for null
	 */
	public function get($who = null){
		$get = $this->getData('get');
		if($who == null)
			return $get;
		else{
			if(array_key_exists($who, $get))
				return $get[$who];
			else
				return false;
		}
		return false;
	}
	
	/**
	 * Retorna um valor do post se existir, se não retorna false, se parametro for igual á null, retorna todo o post
	 * @param string $who [optional] Qual key você quer do post
	 * @return Valor do post, false se não existir o key no post ou array se o parametro for null
	 */
	public function post($who = null){
		$post = $this->getData('post');
		if($who == null)
			return $post;
		else{
			if(array_key_exists($who, $post))
				return $post[$who];
			else
				return false;
		}
		return false;
	}
	
	/**
	 * Retorna um valor do $_FILES se existir, se não retorna false, se parametro for igual á null, retorna todo o files
	 * @param string $who [optional] Qual key você quer do files
	 * @return Valor do files, false se não existir o key no files ou array se o parametro for null
	 */
	public function files($who = null){
		$files = $this->getData('files');
		if($who == null)
			return $files;
		else{
			if(array_key_exists($who, $files))
				return $post[$files];
			else
				return false;
		}
		return false;
	}
	
	/**
	 * Retorna um valor do $_SERVER se existir, se não retorna false, se parametro for igual á null, retorna todo o server
	 * @param string $who [optional] Qual key você quer do server
	 * @return Valor do server, false se não existir o key no server ou array se o parametro for null
	 */
	public function server($who = null){
		if($who == null)
			return $_SERVER;
		else{
			if(array_key_exists($who, $_SERVER))
				return $_SERVER[$who];
			else
				return false;
		}
		return false;
	}
	
	/**
	 * Retorna um valor do $_COOKIE se existir, se não retorna false, se parametro for igual á null, retorna todo o cookie
	 * @param string $who [optional] Qual key você quer do cookie
	 * @return Valor do cookie, false se não existir o key no cookie ou array se o parametro for null
	 */
	public function cookie($who = null){
		if(!$_COOKIE)
			return false;
		if($who == null)
			return $_COOKIE;
		else{
			if(array_key_exists($who, $_COOKIE))
				return $_COOKIE[$who];
			else
				return false;
		}
		return false;
	}
	
	/**
	 * Retorna um valor da $_SESSION se existir, se não retorna false, se parametro for igual á null, retorna todo o session
	 * @param string $who [optional] Qual key você quer da session
	 * @return Valor da session, false se não existir o key na session ou array se o parametro for null
	 */
	public function session($who = null){
		if(!isset($_SESSION))
			return false;
		if($who == null)
			return $_SESSION;
		else{
			if(array_key_exists($who, $_SESSION))
				return $_SESSION[$who];
			else
				return false;
		}
		return false;
	}
}