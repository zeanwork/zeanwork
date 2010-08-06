<?php
/**
 * Contem a classe AuthComponent
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
 * @version 		$LastChangedRevision: 220 $
 * @lastModified	$LastChangedDate: 2010-07-13 11:50:55 -0300 (Ter, 13 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Funções básicas de autentificão
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Components
 */
class AuthComponent extends Component {
	
	/**
	 * Componentes dependentes
	 * @var array
	 */
	public $components = array('session');
	
	/**
 	 * Permição default para URLs que não foram definidas as permições
	 * @var boolean
	 */
	public $authorized = false;
	
	/**
	 * Auto checagem
	 * @var boolean
	 */
	public $autoCheck = true;
	
	/**
	 * Campos na tabela de usuários
	 * @var array
	 */
	public $fields = array(
							  'idUsers' => 'idUsers'
							, 'user' => 'user'
							, 'pass' => 'pass'
						);
	
	/**
	 * Tipo do méthodo a ser utilizado (SESSION ou COOKIE) valores: SESSION = 0 [default], COOKIE = 1.
	 * @var numeric
	 */
	public $methodType = 0;
	
	/**
	 * Objeto do methodo utilizado (SESSION or COOKIE)
	 * @var object
	 */
	public $methodObject = null;
	
	/**
	 * Logado
	 * @var boolean
	 */
	public $logged = null;
	
	/**
	 * URL onde fica a tela de login
	 * @var string
	 */
	public $loginAction = '/users/login';
	
	/**
	 * URL onde fica a tela de logout
	 * @var string
	 */
	public $logoutAction = '/users/logout';
	
	/**
	 * Destruir a session quando fazer o logout?
	 * @var boolean
	 */
	public $logoutDestroySession = true;
	
	/**
	 * Página para onde será redirecionado quando fizer o login
	 * @var string
	 */
	public $loginRedirect = '/';
	
	/**
	 * Permições para as URLs
	 * @var array
	 */
	public $permissions = array();
	
	/**
	 * Dados do usuário
	 * @var array
	 */
	public $userData = array();
	
	/**
	 * Model da tabela de usuários
	 * @var string
	 */
	public $userModel = 'users';
	
	/**
	 * Condições default para query
	 * @var array
	 */
	public $userConditions = array();
	
	/**
	 * Erro de usuário/senha incorreto
	 * @var string
	 */
	public $loginError = 'Usuário/Senha Incorretos.';
	
	/**
	 * Erro de acesso negado
	 * @var string
	 */
	public $authError = 'Você tentou acessar uma <i><b>Área Restrita</b></i>. Faça o login para proceguir!';
	
	/**
	 * Usuário autenticado
	 * @var boolean
	 */
	public $authenticate = false;
	
	/**
	 * Objeto do controller
	 * @var object
	 */
	public $controller;
	
	/**
	 * Dominio para o cookie
	 * @var string
	 */
	public $domain = null;

	/**
	 * Pasta para o cookie
	 * @var string
	 */
	public $path = '/';
	
	/**
	 * Tempo para a expiração do cookie
	 * @var numeric
	 */
	public $expires = null;
	
	/**
	 * Cookie seguro
	 * @var boolean
	 */
	public $secure = false;
	
	/**
	 * Verifica se o usuário tem permição para acessar a página corrente e/ou se esta logado
	 * @return boolean
	 */
	public function isAuthorized(){
		return $this->isLogged() || $this->isPublic();
	}
	
	/**
	 * Verifica se a página corrente é public
	 * @return boolean
	 */
	public function isPublic(){
		$authorized = $this->authorized;
		foreach($this->permissions as $key => $value){
			if($key == Router::getHere())	
				$authorized = $value;
		}
		return $authorized;
	}
	
	/**
	 * Libera uma página ao usuário
	 * @param string $url [optional] Url a ser liberada
	 * @return
	 */
	public function allow($url = null){
		if($url == null)
			$this->authorized = true;
		else
			$this->permissions[$url] = true;
	}
	
	/**
	 * Nega uma página ao usuário
	 * @param string $url [optional] Url a ser negada
	 * @return
	 */
	public function deny($url = null){
		if($url == null)
			$this->authorized = false;
		else
			$this->permissions[$url] = false;
	}
	
	/**
	 * Verifica se o usuário esta logado
	 * @return boolean
	 */
	public function isLogged(){
		if($this->logged === null){
			$this->getMethodObject();
			if($this->methodType == 0){
				if(isset($_SESSION['logged']) && $_SESSION['logged'] === true && isset($_SESSION['idUsers']) && !empty($_SESSION['idUsers']))
					$this->logged = true;
				else
					$this->logged = false;
			}else{
				$idUsers = $this->methodObject->read('idUsers');
				$pass = Security::decrypt($this->methodObject->read('userPass'));
				if(!empty($idUsers) && !empty($pass)){
					$this->logged = $this->isCorrectUser(array(
																  $this->fields['idUsers'] => $idUsers
																, $this->fields['pass'] => $pass
															)
													);
				}else
					$this->logged = false;
			}
		}
		return $this->logged;
	}
	
	/**
	 * Verifica se o usuário está correto
	 * @param array $conditions Condições para a query
	 * @return boolean
	 */
	public function isCorrectUser($conditions){
		$model = $this->controller->load->model($this->userModel, false);
		$params = array(
						'conditions' => array_merge(
													  $this->userConditions
													, $conditions
											)
					);
		$result = $model->read($params);
		if(count($result) > 0){
			$this->userData = $_SESSION['userData'] = $result[0];
			return true;
		}else
			return false;
	}
	
	/**
	 * Faz o login e faz os redirecionamentos necessários
	 * @return 
	 */
	public function login(){
		if($this->controller->input->post()){
			$user = $this->controller->input->post($this->fields['user']);
			$pass = $this->controller->input->post($this->fields['pass']);
			$correct = $this->isCorrectUser(array(
													  $this->fields['user'] => $user
													, $this->fields['pass'] => md5($pass)
												)
										);
			if($correct == true)
				$this->authenticate = true;
			else
				$this->setError($this->loginError);
			$this->loginRedirect();
		}
	}
	
	/**
	 * Salva a autentificação do usuário
	 * @return 
	 */
	public function saveAuthentication(){
		$this->session->delete('auth.error');
		$this->getMethodObject();
		if($this->methodType == 0){
			$_SESSION['logged'] = true;
			$_SESSION['idUsers'] = $this->userData[$this->fields['idUsers']];
		}else{
			$this->methodObject->domain = $this->domain;
			$this->methodObject->path = $this->path;
			$this->methodObject->secure = $this->secure;
			$this->methodObject->write('idUsers', $this->userData[$this->fields['idUsers']], $this->expires);
			$this->methodObject->write('userPass', Security::encrypt($this->userData[$this->fields['pass']]), $this->expires);
		}
	}
	
	/**
	 * Redireciona a página e salva a autentificação do usuário
	 * @return 
	 */
	public function loginRedirect(){
		if($this->authenticate == true){
			$this->saveAuthentication();
			if($this->getLocation() != null)
				$this->loginRedirect = $this->getLocation();
			$this->controller->redirect($this->loginRedirect);
		}else{
			if($this->loginAction != Router::getHere())
				$this->controller->redirect($this->loginAction);
		}
	}
	
	/**
	 * Verefica se o usuário está logado e se ele tem permisão para a url acessada, se não tiver, rerireciona para a página de login
	 * @return boolean
	 */
	public function check(){
		if(!$this->isAuthorized()){
			$this->setError($this->authError);
			$this->setLocation(Router::getHere());
			$this->controller->redirect($this->loginAction);
			return false;
		}
		return true;
	}
	
	/**
	 * Faz o logout do usuário
	 * @return boolean
	 */
	public function logout(){
		$this->getMethodObject();
		if($this->methodType == 0){
			$_SESSION['logged'] = false;
			unset($_SESSION['idUsers']);
			if($this->logoutDestroySession == true)
				$this->methodObject->destroy();
		}else{
			$this->methodObject->domain = $this->domain;
			$this->methodObject->path = $this->path;
			$this->methodObject->secure = $this->secure;
			$this->methodObject->delete('idUsers');
			$this->methodObject->delete('userPass');
		}
		$this->controller->redirect($this->logoutAction);
		return true;
	}
	
	/**
	 * Seta um error
	 * @param string $datails [optional] Erro a ser setado
	 * @return 
	 */
	public function setError($datails = null){
		$this->session->set('auth.error', $datails);
	}
	
	/**
	 * Retorna o error
	 * @return string
	 */
	public function getError(){
		$this->session->get('auth.error');
	}
	
	/**
	 * Seta a localização para onde deve ser redirecionado
	 * @param string $location [optional] URL para redirecionamento
	 * @return 
	 */
	public function setLocation($location = null){
		$this->session->set('auth.location', $location);
	}
	
	/**
	 * Retorna a localização para onde deve ser redirecionado
	 * @return string
	 */
	public function getLocation(){
		return $this->session->get('auth.location');
	}
	
	/**
	 * Retorna o objeto do methodo utilizado
	 * @return object
	 */
	public function getMethodObject(){
		if(!is_object($this->methodObject)){
			if($this->methodType == 0)
				$this->methodObject = $this->getComponent('session');
			else
				$this->methodObject = $this->getComponent('cookie');
		}
		return $this->methodObject;
	}
	
	/**
	 * Retorna os dados do usuário autentidicado
	 * @param string $who [optional] Qual informação você que do usuário
	 * @return array or value of field user
	 */
	public function user($who = null){
		if($this->isLogged()){
			if($who == null){
				if(count($this->userData) == 0){
					if(isset($_SESSION) && array_key_exists('userData', $_SESSION))
						return $_SESSION['userData'];
				}else
					return $this->userData;
			}else{
				if(array_key_exists($who, $this->userData))
					return $this->userData[$who];
				else{
					if(isset($_SESSION) && array_key_exists('userData', $_SESSION) && array_key_exists($who, $_SESSION['userData']))
						return $_SESSION['userData'][$who];
					else
						return false;
				}
			}
		}
		return null;
	}
	
	/**
	 * Inicializa o componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
	public function initialize(&$controller){
		$this->controller =& $controller;
		$this->session->initialize(&$controller);
        return true;
    }
	
	/**
	 * Faz as operações para a inicialização do componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
    public function startup(&$controller){
    	$this->allow($this->loginAction);
		$this->allow($this->logoutAction);
		if($this->autoCheck == true)
			$this->check();
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