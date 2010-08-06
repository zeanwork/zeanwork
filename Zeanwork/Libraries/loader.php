<?php
/**
 * Contem a classe Loader
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
 * Class Input required
 */
Zeanwork::import(LIBS, 'input');

/**
 * Class View required
 */
Zeanwork::import(LIBS, 'view');

/**
 * Class Component required
 */
Zeanwork::import(LIBS, 'component');

/**
 * Class Model required
 */
Zeanwork::import(LIBS, 'model');

/**
 * Classe para carregar diversas coisas do Zeanwork e do aplicativo
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Classes requireds View, Component, Helper, Model and Inflector
 */
class Loader extends Zeanwork {

	/**
	 * Carrega uma view
	 * Options:
	 * autoLayout boolean
	 * params array
	 * layout string
	 * helpers array
	 * data array
	 * 
	 * @param string $action Caminho na view ex: home/index.html
	 * @param object array $options [optional] Opções (autoLayout = false, params, layout, helpers, data)
	 * @return string contendo a view
	 */
	public function view($action, $options = array()){
		$layout = 'default';
		$view = Zeanwork::getInstance('View');	
		if(is_array($options)){
			if(array_key_exists('autoLayout', $options))
				$view->autoLayout = $options['autoLayout'];
			else
				$view->autoLayout = false;
			if(array_key_exists('params', $options))
				$view->params = (array)$options['params'];
			if(array_key_exists('layout', $options))
				$view->layout = $layout = $options['layout'];
			if(array_key_exists('helpers', $options))
				$view->helpers = (array)$options['helpers'];
			if(array_key_exists('data', $options))
				$view->data = (array)$options['data'];
		}
		$view->beforeRender();
		return $view->render($action, $layout);
	}
	
	/**
	 * Carrega um elemento de view
	 * @param string $element Nome do elemento a ser carregado
	 * @param array $params [optional] Parametros para o elemento
	 * @return string
	 */
    public function element($element, $params = array()){
		$view = Zeanwork::getInstance('View');
		return $view->element($element, $params);
	}
	
	/**
	 * Carrega um component
	 * @param string $component Nome do componente a ser carregado
	 * @param object $object [optional] Objeto onde criará a valiavel contendo o componente, se false retornará a instância
	 * @param boolean $componentsEvent Executar aqui os eventos do componente. (será executado somente se for chamado em uma função)
	 * @return boolean
	 */
	public function component($component, $object = null, $componentsEvent = true){
		if(!is_object($object) && $object !== false){
			$called = debug_backtrace();
			if(array_key_exists('object', $called[1])){
				$object = $called[1]['object'];
			}elseif(array_key_exists('object', $called[2]))
				$object = $called[2]['object'];
			else
				$object = null;
		}
		if(is_array($component)){
			foreach($component as $component_){
				$this->component($component_, $object, $componentsEvent);
			}
		}else{
			$component = Inflector::lowerCamelize($component);
			if($component != null){
				if($this->{$component} = Zeanwork::getInstance('Component')->getComponent($component)){
					if($componentsEvent){
						foreach(array('initialize', 'startup') as $event){
							if(method_exists($this->{$component}, $event)){
								$controller = false;
								if(is_object($object)){
									if(strpos(get_class($object), 'Controller') !== false)
										$controller = $object;
									elseif(strpos(get_class($object), 'View') !== false && isset($object->controller))
										$controller = $object->controller;
									else
										$controller = false;
								}
								$this->{$component}->{$event}($controller);
							}else{
								$calledFrom = debug_backtrace();
								echo Debugger::errorTrigger(E_USER_WARNING, 'Não foi possível chamar o método ' . $event . ' em ' . $component, $calledFrom[0]['file'], $calledFrom[0]['line']);
							}
						}
					}
					if(is_object($object) && method_exists($object, 'renderLoad')){
						$object->renderLoad($component);
						return true;
					}elseif($object === false)
						return $this->{$component};
				}
				return false;
			}
		}
		return false;
	}
						
	/**
	 * Carrega um helper
	 * @param string $helper Nome do ajudante a ser carregado
	 * @param object $object [optional] Objeto onde criará a valiavel contendo o ajudante, se false retornará a instância
	 * @return boolean
	 */
	public function helper($helper, $object = null){
		if(!is_object($object) && $object !== false){
			$called = debug_backtrace();
			if(array_key_exists('object', $called[1])){
				$object = $called[1]['object'];
			}elseif(array_key_exists('object', $called[2]))
				$object = $called[2]['object'];
			else
				$object = null;
		}
		if(is_array($helper)){
			foreach($helper as $helper_){
				$this->helper($helper_, $object);
			}
		}else{
			$helper = Inflector::lowerCamelize($helper);
			if($helper != null){
				$instanceHelper = Zeanwork::getInstance('Helper');
				if($instanceHelper->getHelper($helper)){
					foreach($instanceHelper->getHelpersLoadeds() as $key => $value){
						$this->{$key} = $value;
						if(is_object($object) && method_exists($object, 'renderLoad')){
							$object->renderLoad($key);
						}
					}
					if($object === false)
						return $this->{$helper};

				}
				return false;
			}
		}
		return false;
	}
	/**
	 * Carrega um model
	 * @param string $model Nome do modelo a ser carregado
	 * @param object $object [optional] Objeto onde criará a valiavel contendo o modelo, se false retornará a instância
	 * @return boolean
	 */
	public function model($model, $object = null){
		if(!is_object($object) && $object !== false){
			$called = debug_backtrace();
			if(array_key_exists('object', $called[1])){
				$object = $called[1]['object'];
			}elseif(array_key_exists('object', $called[2]))
				$object = $called[2]['object'];
			else
				$object = null;
		}
		if(is_array($model)){
			foreach($model as $model_){
				$this->model($model_, $object);
			}
		}else{
			$model = Inflector::lowerCamelize($model);
			if($model != null){
				if($this->{$model} = Zeanwork::getInstance('Model')->getModel($model)){
					if(is_object($object)){
						$this->{$model}->input->setData(array_merge($this->{$model}->input->getData(), array('get' => $object->input->get())));
					}
					if(is_object($object) && method_exists($object, 'renderLoad')){
						$object->renderLoad($model);
						return true;
					}elseif($object === false)
						return $this->{$model};
				}
				return false;
			}
		}
		return false;
	}
	
	/**
	 * Carrega um arquivo
	 * @param string $file Caminho do arquivo
	 * @return 
	 */
	public function file($file){
		$basename = basename($file);
		$ext = end(explode('.', $basename));
		return Zeanwork::import(dirname($file) . DS, str_replace('.' . $ext, '', $basename), $ext);
	}
	
	public function extension($extension){
		if(is_array($extension)){
			foreach($extension as $value){
				$this->extension($value);
			}
		}else{
			$extension = Inflector::lowerCamelize($extension);
			if(Zeanwork::pathTreated('Extension', $extension)){
				Zeanwork::import(Zeanwork::pathTreated('Extension'), $extension);
			}else{
				Zeanwork::fatalError('Extension', array(
													  'extension' 	=> $extension
													)
									);
			}
		}
	}
	
	/**
	 * Cria variaveis com os keys da array com seu perpectivo valor na view
	 * @param array $array Array para criar as variaveis
	 * @return boolean
	 */
	public function vars($array){
		$called = debug_backtrace();
		if(array_key_exists('object', $called[1])){
			$object = $called[1]['object'];
		}elseif(array_key_exists('object', $called[2]))
			$object = $called[2]['object'];
		else
			$object = null;
		$object->varsCached = array_merge($array, $object->varsCached);
		return true;
	}
}