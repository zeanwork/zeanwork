<?php
/**
 * Contem a classe Controller
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
 * @version 		$LastChangedRevision: 237 $
 * @lastModified	$LastChangedDate: 2010-07-28 11:29:51 -0300 (Qua, 28 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class Loader required and classes dependents of Loader
 */
Zeanwork::import(LIBS, 'loader');

/**
 * Controla o Controlador
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Classes requireds Loader, View, Component, Helper, Model, Inflector, Debugger, Router, input
 */
class Controller extends Zeanwork {
	
	/**
	 * Auto carregar a view
	 * @var boolean
	 */
	public $autoView = true;
	
	/**
	 * Auto carregar o layout
	 * @var boolean
	 */
	public $autoLayout = true;
	
	/**
	 * Auto renderizar
	 * @var boolean
	 */
	public $autoRender = true;
	
	/**
	 * Escrever no código fonte o tempo de execução da aplication
	 * @var unknown_type
	 */
	public $writeTimeExecution = true;
	
	/**
	 * Ajudantes
	 * @var array
	 */
	public $helpers = array();
	
	/**
	 * Ajudantes defaults
	 * @var array
	 */
	public $defaultsHelpers = array();
	
	/**
	 * Extensões
	 * @var array
	 */
	public $extensions = array();
	
	/**
	 * Extensões defaults
	 * @var array
	 */
	public $defaultsExtensions = array();
	
	/**
	 * Dados
	 * @var array
	 */
	public $data = array();
	
	/**
	 * Layout a ser usado
	 * @var string
	 */
	public $layout = 'default';
	
	/**
	 * Dados para o layout
	 * @var array
	 */
	public $dataForLayout = array();
	
	/**
	 * Scripts para o layout
	 * @var string
	 */
	public $scriptsForLayout = null;
	
	/**
	 * Css para o layout
	 * @var string
	 */
	public $cssForLayout = null;
	
	/**
	 * Saida de dados
	 * @var string
	 */
	public $output = null;
	
	/**
	 * Parametros
	 * @var array
	 */
	public $params = array();
	
	/**
	 * Models a serem usados
	 * @var array
	 */
	public $uses = array();
	
	/**
	 * Dados para a view
	 * @var array
	 */
	public $view = array();
	
	/**
	 * Componentes a serem usados
	 * @var array
	 */
	public $components = array();
	
	/**
	 * Componentes defaults
	 * @var array
	 */
	public $defaultsComponents = array();
	
	/**
	 * Instância da classe Loader
	 * @var object
	 */
	public $load = null;
	
	/**
	 * Variaveis cacheadas para renderizar na view
	 * @var array
	 */
	public $varsCached = array();
	
	/**
	 * Informações para a geração de cache
	 * @var array
	 */
	public $cache = array();
	
	/**
	 * Informações para a geração de cache default
	 * @var array
	 */
	public $defaultCache = array();
	
	/**
	 * Cria uma variavel na view com o perspectivo valor
	 * @param string $var Nome da variavel a ser criada na view
	 * @param data $value Valor para a variavel
	 * @return boolean
	 */
	public function setVar($var = 'untitled', $value = null){
		return $this->load->vars(array($var => $value));
	}
	
	/**
	 * Retorna o nome da classe. (Sem o Controller ex: Nome. AppController; Return: App)
	 * @return string
	 */
	public function getName(){
		return str_replace('Controller', null, get_class($this));
	}
	
	/**
	 * Seta os parametros
	 * @param array $params Parametros a serem setados
	 */
	public function setParams($params){
		$this->params = (array)$params;
	}
	
	/**
	 * Seta um parametro
	 * @param string $name Nome do parametro a ser setado
	 * @param string $value Valor para o parametro
	 */
	public function setParam($name, $value){
		$this->params[$name] = $value;
	}
	
	/**
	 * Retorna os parametros
	 * @return array
	 */
	public function getParams(){
		return (array)$this->params;
	}
	
	/**
	 * Retorna um parametro
	 * @param string $who Parametro a ser retornado
	 * @return 
	 */
	public function getParam($who){
		if(array_key_exists($who, $this->params))
			return $this->params[$who];
		else
			return false;
	}
	
	/**
	 * Retrona os métodos da classe
	 * @return array com todos os métodos da classe
	 */
	public function getMethods(){
		$child = get_class_methods($this);
		$parent = get_class_methods('Controller');
		return array_diff($child, $parent);
	}
	
	/**
	 * Seta os models a serem usados
	 * @param array $uses Models a ser setado
	 */
	public function setUses($uses){
		$this->uses = (array)$uses;
	}
	
	/**
	 * Retorna os models
	 * @return array
	 */
	public function getUses(){
		return (array)$this->uses;
	}
	
	/**
	 * Retorna os componentes
	 * @return array
	 */
	public function getComponents(){
		return array_merge((array)$this->defaultsComponents, (array)$this->components);
	}
	
	/**
	 * Retorna as extensões
	 * @return array
	 */
	public function getExtensions(){
		return array_merge((array)$this->extensions, (array)$this->defaultsExtensions);
	}
	
	/**
	 * Seta os dados
	 * @param array $data Dados a ser setado
	 */
	public function setData($data){
		$this->data = (array)$data;
	}
	
	/**
	 * Retorna os dados
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}	
	
	/**
	 * Construct
	 * @return 
	 */
    public function __construct(){
		$classes = array(
						  'input' 	=> 'Input'
						, 'load'	=> 'Loader'
						, 'model'	=> 'Model'
					);
		foreach($classes as $var => $class){
			$this->{$var} =& Zeanwork::getInstance($class);
		}
		
        /*
         * Carrega os components
         */
		$this->load->component($this->getComponents(), $this, false);
		
		/*
		 * Carrega as extensions
		 */
		$this->load->extension($this->getExtensions());
		
		/*
		 * Carrega os models
		 */
        $this->load->model($this->getUses(), $this);
    }
	
	/**
	 * Renderisa algo carregado pelo $this->load
	 * @param string $attribute Nome do atributo (Nome da variavel que será criada da classe)
	 * @return boolean
	 */
	function renderLoad($attribute){
		if(is_object($this->load->{$attribute})){
			$this->{$attribute} =& $this->load->{$attribute};
			unset($this->load->{$attribute});
		}
	}
	
	/**
	 * Antes de executar o controller
	 * @return boolean
	 */
	public function beforeExecution(){
		return true;
	}
	
	/**
	 * Antes de renderizar
	 * @return boolean
	 */
	public function beforeRender(){
		return true;
	}
	
	/**
	 * Depois de executar o controller
	 * @return boolean
	 */
	public function afterExecution(){
 		return true;
	}
	
	/**
	 * Antes de executar o controller e o beforeExecution();
	 * @return boolean
	 */
	public function beforeExecutionZeanwork(){
		$get = $this->getParam('named');
		foreach($this->getParam('params') as $param){
			$get[$param] = null;
		}
		$this->setData(array(
							  'get' => $get
							, 'files' => $_FILES
							, 'post' => $_POST
						)
				);
		$this->input->setData($this->getData());
		if(Configure::read('auto.connect.database')){
			oConn::open();
		}
		
		if(Configure::read('useMultiLanguage') == true){
			Zeanwork::import(LIBS, 'translate');
		}
		
		if(Configure::read('autoLayout.isAjax') == false && $this->isAjax() === true){
			$this->autoLayout = false;
		}
		return true;
	}
	
	/**
	 * Depois de executar o controller e afterExecution();
	 * @return boolean
	 */
	public function afterExecutionZeanwork(){
		if(Configure::read('auto.disconnect.database') && !Database::getConfigure('persistent'))
			oConn::close();
 		return true;
	}
	
	public function isAjax(){
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
	}
	
	/**
	 * Seta uma action
	 * @param string $action Action a ser setada
	 * @return 
	 */
	public function setAction($action){
		$this->params['action'] = $action;
		$args = func_get_args();
		unset($args[0]);
		return call_user_func_array(array(&$this, $action), $args);
	}
	
	/**
	 * Renderiza a view
	 * @param string $action [optional] Action a ser renderizada
	 * @param string $layout [optional] Layout a ser utilizado na renderizão
	 * @return string
	 */
	public function render($action = null, $layout = null){
		$this->beforeRender();
		$view =& Zeanwork::getInstance('View');
		$view->controller =& $this;
		$view->autoView = $this->autoView;
		$view->autoLayout = $this->autoLayout;
		$view->varsCached = $this->varsCached;
		$view->dataInput = $this->input->getData();
		$view->params = $this->params;
		$view->layout = $this->layout;
		$view->scriptsForLayout = $this->scriptsForLayout;
		$view->cssForLayout = $this->cssForLayout;
		$view->dataForLayout = $this->dataForLayout;
		$view->helpers = array_merge($this->defaultsHelpers, $this->helpers);
		$view->setData($this->view);
		$this->autoRender = false;
		$view->setDataInput($this->input->getData());
		$view->beforeRender();
		return $this->output .= $view->render($action, $layout);
	}
	
	/**
	 * Limpa a saida
	 * @return 
	 */
	public function clear(){
		$this->output = null;
		return true;
	}
	
	/**
	 * Retorna a página
	 * @param srint $param [optional] Parametro a ser selecionado
	 * @return string
	 */
	public function page($param = 'page'){
		$page = $this->getParam($param);
		if(is_null($page) || empty($page))
			$page = 1;
		return $page;
	}
	
	/**
	 * Executa um evento no componente
	 * @param string $event Nome do evento (function)
	 * @return boolean ou o retorno do methodo
	 */
	public function componentsEvent($event){
		$components = $this->getComponents();
		if($event == 'shutdown'){
			$componentsLoadeds = Zeanwork::getInstance('Component')->loaded;
			foreach($componentsLoadeds as $key => $value){
				if(!in_array($key, $components))
					$components[] = $key;
			}
		}
		
		foreach($components as $component){
			$component = Inflector::lowerCamelize($component);
			if($component != null && isset($this->{$component})){
				if(method_exists($this->{$component}, $event)){
					$this->{$component}->{$event}($this);
				}else{
					$calledFrom = debug_backtrace();
					echo Debugger::errorTrigger(E_USER_WARNING, 'Não foi possível chamar o método ' . $event . ' em ' . $component, $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			}
		}
		return true;
    }
    
	/**
	 * Redireciona a página
	 * @param string $url Destino da url
	 * @param string $status [optional] Status no redirecionamento
	 * @param boolean $exit [optional] Executar um exit()
	 */
	public function redirect($url, $status = null, $exit = true){
		$this->autoRender = false;
		$codes = array(
					  100 => 'Continue'
					, 101 => 'Switching Protocols'
					, 200 => 'OK'
					, 201 => 'Created'
					, 202 => 'Accepted'
					, 203 => 'Non-Authoritative Information'
					, 204 => 'No Content'
					, 205 => 'Reset Content'
					, 206 => 'Partial Content'
					, 300 => 'Multiple Choices'
					, 301 => 'Moved Permanently'
					, 302 => 'Found'
					, 303 => 'See Other'
					, 304 => 'Not Modified'
					, 305 => 'Use Proxy'
					, 307 => 'Temporary Redirect'
					, 400 => 'Bad Request'
					, 401 => 'Unauthorized'
					, 402 => 'Payment Required'
					, 403 => 'Forbidden'
					, 404 => 'Not Found'
					, 405 => 'Method Not Allowed'
					, 406 => 'Not Acceptable'
					, 407 => 'Proxy Authentication Required'
					, 408 => 'Request Time-out'
					, 409 => 'Conflict'
					, 410 => 'Gone'
					, 411 => 'Length Required'
					, 412 => 'Precondition Failed'
					, 413 => 'Request Entity Too Large'
					, 414 => 'Request-URI Too Large'
					, 415 => 'Unsupported Media Type'
					, 416 => 'Requested range not satisfiable'
					, 417 => 'Expectation Failed'
					, 500 => 'Internal Server Error'
					, 501 => 'Not Implemented'
					, 502 => 'Bad Gateway'
					, 503 => 'Service Unavailable'
					, 504 => 'Gateway Time-out'
		        );
        if(!is_null($status) && isset($codes[$status]))
            header('HTTP/1.1 ' . $status . ' ' . $codes[$status]);
		header('Location: ' . Router::url($url, true));
        if($exit)
			exit();
	}
}