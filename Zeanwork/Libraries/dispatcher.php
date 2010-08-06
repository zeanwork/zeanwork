<?php
/**
 * Contem a classe Dispatcher
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
 * @version 		$LastChangedRevision: 236 $
 * @lastModified	$LastChangedDate: 2010-07-28 08:59:55 -0300 (Qua, 28 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class Router required
 */
Zeanwork::import(LIBS, 'router');

/**
 * Class Controller required
 */
Zeanwork::import(LIBS, 'controller');

/**
 * Dispacha o controller e a action solicitadas pela URL
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Classes requireds Router, Controller, Inflector
 */
class Dispatcher extends Zeanwork {
		
	
	/**
	 * Chama o controller e a action solicitadas pela URL
	 * @return
	 */
	public static function dispatch(){
		$path 				= Router::parse();
		$controller 		= Inflector::lowerCamelize($path['controller']);
		$path['controller'] = ucwords(Inflector::lowerCamelize($path['controller']));
		$controllerName 	= $controller . 'Controller';
		$controllerFile 	= $controller;
		$path['action'] 	= Inflector::lowerCamelize($path['action']);

		if(!Zeanwork::import(Zeanwork::pathTreated('Controller'), 'app', 'php', false)){
			Zeanwork::import(Zeanwork::pathTreated('ControllerZeanwork'), 'app');
		}
		
		if(Zeanwork::import(Zeanwork::pathTreated('Controller'), $controllerFile, 'php', false)){
			if(!class_exists($controllerName)){
				Zeanwork::fatalError('Controller', array(
												  'controller' 	=> $path['controller']
												, 'extension' 	=> $path['extension']
											)
						);
				return false;
			}
			$controller =& Zeanwork::getInstance($controllerName);
			if(!Zeanwork::isCalledMethod($controller, $path['action'])){
				if(Zeanwork::pathTreated('View', $path['controller'] . DS . $path['action'] . '.' . $path['extension']) || Zeanwork::pathTreated('ViewZeanwork', $path['controller'] . DS . $path['action'] . '.' . $path['extension'])){
				}else{
					Zeanwork::fatalError('Action', array(
													  'controller' 	=> $path['controller']
													, 'action'		=> $path['action']
												)
							);
					return false;
				}
			}
		}else{
			if(Zeanwork::pathTreated('View', $path['controller'] . DS . $path['action'] . '.' . $path['extension']) || Zeanwork::pathTreated('ViewZeanwork', $path['controller'] . DS . $path['action'] . '.' . $path['extension']))
				$controller =& Zeanwork::getInstance('AppController');
			else{
				Zeanwork::fatalError('Controller', array(
												  'controller' 	=> $path['controller']
												, 'extension' 	=> $path['extension']
											)
						);
				return false;
			}
		}
		
		$controllerCache = Inflector::underscore($controllerFile);
		$actionCache = Inflector::underscore($path['action']);
		$cacheLife = false;
		$useCache = false;
		$createCache = false;
		$useExistingCache = false;
		if(count($controller->cache) > 0){
			foreach($controller->cache as $key => $value){
				$controller->cache[Inflector::underscore($key)] = $value;
			}
			if(isset($controller->cache[$actionCache])){
				$cacheLife = $controller->cache[$actionCache];
				if($cacheLife !== false)
					$useCache = true;
			}
		}
		if($useCache == false){
			if(count($controller->defaultCache) > 0){
				foreach($controller->defaultCache as $key => $value){
					$controller->defaultCache[Inflector::underscore($key)] = $value;
				}
				if(isset($controller->defaultCache[$controllerCache])){
					if(isset($controller->defaultCache[$controllerCache][$actionCache])){
						$cacheLife = $controller->defaultCache[$controllerCache][$actionCache];
						if($cacheLife !== false)
							$useCache = true;
					}
				}
			}
		}
		
		if($useCache == true){
			Zeanwork::import(LIBS, 'cache');
			$cache = Zeanwork::getInstance('Cache');
			
			if($content = $cache->read($controllerCache, $actionCache))
				$useExistingCache = true;
			else
				$createCache = true;
			
			if($file = $cache->exists($controllerCache, $actionCache)){
				$arrCache = $cache->interpretNameFile($file);
				if($arrCache['expires'] == 0){
					if($arrCache['expires'] != $cacheLife){
						$createCache = true;
						$useExistingCache = false;
						$cache->delete($controllerCache, $actionCache);
					}
				}
			}
			if($useExistingCache)
				$controller->output = $content;
		}
		
		$controller->setParams($path);
		$controller->componentsEvent('initialize');
		$controller->beforeExecutionZeanwork();
		$controller->beforeExecution();
		$controller->componentsEvent('startup');
		
		if(!$useExistingCache){
			if((in_array($path['action'], $controller->getMethods())) && Zeanwork::isCalledMethod($controller, $path['action'])){
				$params = $path['params'];
				if(!is_null($path['id']))
					$params = array_merge(array($path['id']), $params);
			    call_user_func_array(array(&$controller, $path['action']), $params);
			}
			if($controller->autoRender)
				$controller->render();
			
			if($createCache == true)
				$cache->create($controllerCache, $actionCache, $controller->output, $cacheLife);
		}
		
		$controller->componentsEvent('shutdown');
		echo $controller->output;
		$controller->afterExecution();
		$controller->afterExecutionZeanwork();
		
		/*
		 * Tempo de execução da página
		 */
		if($controller->writeTimeExecution == true){
			global $TIME_START;
			echo Helper::getTimeEnd($TIME_START, true);
		}
		return $controller;
	}
}