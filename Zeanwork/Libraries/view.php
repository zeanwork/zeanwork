<?php
/**
 * Contem a classe View
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
 * @version 		$LastChangedRevision: 238 $
 * @lastModified	$LastChangedDate: 2010-07-29 17:51:31 -0300 (Qui, 29 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controla as Views
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Classes requireds Loader and classes dependents of Loader 
 */
class View extends Zeanwork {

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
	 * Dados
	 * @var array
	 */
	public $data = array();
	
	/**
	 * Nome do layout
	 * @var string
	 */
	public $layout = null;
	
	/**
	 * Título da página
	 * @var string
	 */
	public $pageTitle;
	
	/**
	 * Parametros
	 * @var array
	 */
	public $params = array();

	/**
	 * Conteudo para o layout
	 * @var string
	 */
	public $contentForLayout = null;
	
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
	 * Ajudantes
	 * @var array
	 */
	public $helpers = array();
	
	/**
	 * Já foi carregado os helper? (sim/não)
	 * @var boolean
	 */
	private $helpersLoadeds = false;
	
	/**
	 * Instância da classe Loader
	 * @var object
	 */
	public $load = null;
	
	/**
	 * Variaveis chacheadas
	 * @var array
	 */
	public $varsCached = array();
	
	/**
	 * Dados de entrada
	 * @var array
	 */
	public $dataInput = array();
	
	/**
	 * Instância do controller
	 * @var object
	 */
	public $controller = null;
	
	/**
	 * Retorna os Parametros
	 * @return array
	 */
	public function getParams(){
		return $this->params;
	}
	
	/**
	 * Retorna umm parametro
	 * @param string $who Parametro a ser retornado
	 * @return valor do parametro
	 */
	public function getParam($who){
		if(array_key_exists($who, $this->params))
			return $this->params[$who];
		return;
	}
	
	/**
	 * Retorna o layout
	 * @return string
	 */
	public function getLayout(){
		return $this->layout;
	}
	
	/**
	 * Retorna os ajudantes
	 * @return array
	 */
	public function getHelpers(){
		return (array)$this->helpers;
	}
	
	/**
	 * Retorna os dados
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}
	
	/**
	 * Seta os dados
	 * @param array $data Dados a ser setado
	 */
	public function setData($data){
		$this->data = (array)$data;
	}
	
	/**
	 * Retorna os dados de entrada
	 * @return array
	 */
	public function getDataInput(){
		return $this->dataInput;
	}
	
	/**
	 * Seta os dados de entrada
	 * @param array $dataInput Dados a ser setado
	 */
	public function setDataInput($dataInput){
		$this->dataInput = (array)$dataInput;
	}
	
	/**
	 * Construct
	 * @return 
	 */
	public function __construct(){
		$classes = array(
						  'input' 	=> 'Input'
						, 'load'	=> 'Loader'
					);
		foreach($classes as $var => $class){
			$this->{$var} =& Zeanwork::getInstance($class);
		}
	}
	
	/**
	 * Antes de renderizar
	 * @return boolean
	 */
	public function beforeRender(){
		if(count($this->getDataInput()) == 0){
			$get = $this->getParam('named');
			foreach((array)$this->getParam('params') as $param){
				$get[$param] = null;
			}
			$this->setDataInput(array(
								  'get' => $get
								, 'files' => $_FILES
								, 'post' => $_POST
							)
					);
		}
		$this->input->setData($this->getDataInput());
		return true;
	}
	
	/**
	 * Renderiza uma view
	 * @param string $filename Nome da view a ser renderizada
	 * @param array $data [optional] Array com os dados para a view
	 * @return string
	 */
	public function renderView($filename, $data = array()){
		$this->loadHelpers();
		$output = null;
		extract($this->varsCached);
		ob_start();
		
		if(Configure::read('rewriteShortTags') == true && @ini_get('short_open_tag') == false){
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', @file_get_contents($filename))));
		}else{
			include($filename);
		}
		
		$output .= ob_get_clean();
		return $output;
    }
	
	/**
	 * Renderiza
	 * @param string $action [optional]
	 * @param string $layout [optional]
	 * @return string or boolean
	 */
    public function render($action = null, $layout = null){
        if(is_null($action)){
            $controller = $this->getParam('controller');
            $action = $this->getParam('action');
            $ext = $this->getParam('extension');
            $layout = $this->getLayout();
        }else{
            $filename = explode('.', (string)$action);
            $controller = null;
            $action = $filename[0];
            $ext = $filename[1] ? $filename[1] : $this->getParam('extension');
        }
		
		if(!$fileView = Zeanwork::pathTreated('View', $controller . DS . $action . '.' . $ext))
			$fileView = Zeanwork::pathTreated('ViewZeanwork', $controller . DS . $action . '.' . $ext);
		
        if($fileView){
        	if($this->autoView)
            	$output = $this->renderView($fileView, $this->getData());
            else
            	$output = null;
			if(is_null($layout))
				$layout = $this->getLayout();
				
            if($this->autoLayout && $layout){
                $output = $this->renderLayout($output, $layout, $ext);
            }
            return $output;
        }else{
			Zeanwork::fatalError('View', array(
												  'controller' 	=> $controller
												, 'extension' 	=> $ext
												, 'view'		=> $action
											)
						);
            return false;
        }
		return false;
    }
	
	/**
	 * Renderiza um layout
	 * @param string $content Conteúdo para o layout
	 * @param string $layout Layout a ser renderizado
	 * @param string $ext [optional] Extenção do arquivo do layout
	 * @return string
	 */
    public function renderLayout($content, $layout, $ext = null){
        if(is_null($ext))
            $ext = $this->getParam('extension');
		if(is_null($layout))
			$layout = $this->getLayout();
		$fileLayout = Zeanwork::pathTreated('Layout', $layout . '.' . $ext);
		if(!$fileLayout){
				$fileLayout = Zeanwork::pathTreated('LayoutZeanwork', $layout . '.' . $ext);
		}
		if($fileLayout){
			$this->contentForLayout = $content;
			return $this->renderView($fileLayout, $this->getData());
		}else{
			Zeanwork::fatalError('Layout', array(
												  'layout' 		=> $layout
												, 'extension' 	=> $ext
											)
						);
            return false;
       }
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
	 * Carrega um elemento
	 * @param string $element Nome do elemento a ser carregado
	 * @param array $params [optional] Parametros para o elemento
	 * @return string
	 */
    public function element($element, $params = array()){
		if(!is_array($params))
			$params = array('data' => $params);

		$elementName = basename($element);    	
        $folders = explode('/', dirname($element));
        $element = null;
		foreach($folders as $folder){
			$element .= Inflector::camelize($folder) . DS;
		}
		$element .= $elementName;

		if(array_key_exists('extension', $params) && !is_null($params['extension']))
			$ext = $params['extension'];
		elseif(!is_null($this->getParam('extension')))
			$ext = $this->getParam('extension');
		else
			$ext = Configure::read("defaultExtension");
		if(file_exists(Zeanwork::pathTreated('Element') . $element . '.' . $ext . '.php')){
        	return $this->renderView(Zeanwork::pathTreated('Element', $element . '.' . $ext), $params);
		}else{
			Zeanwork::fatalError('Element', array(
												  'element'		=> $element
												, 'extension' 	=> $ext
												, 'url'			=> Zeanwork::pathTreated('Element') . $element . '.' . $ext . '.php'
											)
						);
            return false;
		}
    }
	
	/**
	 * Carrega os ajudantes
	 * @return boolean
	 */
	public function loadHelpers(){
		if($this->helpersLoadeds === false){
			$this->helpersLoadeds = true;
			return $this->load->helper($this->getHelpers(), $this);
		}
	}
}