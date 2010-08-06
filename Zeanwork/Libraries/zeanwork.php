<?php
/**
 * Contem a classe Zeanwork
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
 * Zeanwork
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Zeanwork {
	
	static $classInstantiated = array();
	
	/**
	 * Instância uma classe
	 * @param string $className Nome da class
	 * @return Instância
	 */
	public static function &getInstance($className){
		if(!array_key_exists($className, Zeanwork::$classInstantiated)){
			Zeanwork::$classInstantiated[$className] = new $className();
		}
		return Zeanwork::$classInstantiated[$className];
    }
	
	/**
	 * Importa um arquivo
	 * @param string or constant $path [optional] (default value = ZEANWORK(constant)) Pasta onde encontra o arquivo a ser carregado
	 * @param string $file [optional] Nome do arquivo a ser carregado
	 * @param string $ext [optional] (default value = php) Extenção do arquivo a ser carregado 
	 * @param boolean $displayErrors [optional] Mostrar erros de arquivo não encontrado
	 * @return boolean
	 */
	public static function import($path = ZEANWORK, $file = null, $ext = 'php', $displayErrors = true){
		if(is_array($file)){
			foreach($file as $__file){
				 $include = self::import($path, $__file, $ext);
			}
		}else{
			$lastChar = $path[strlen($path) - 1];
			if(($lastChar === '/' || $lastChar === '\\')){
				if(file_exists($path . $file . '.' . $ext)){
					return (boolean)include_once($path . $file . '.' . $ext);
				}else{
					if($displayErrors === true) trigger_error('File '.$file.'.'.$ext.' doesn\'t exists in '.$path, E_USER_WARNING);
					return false;
				}
			}else{
				if($displayErrors === true) trigger_error('Path of the incorrect', E_USER_WARNING);
				return false;
			}
		}
        return false;
    }
	
	/**
	 * Se hover o parametro $type e os outros não, então retornará o caminho da pasta completo, 
	 * caso seje informado o parametro $file, ele verificará se existe o arquivo solicitado e retornará o caminho completo para o mesmo.
	 * @param string $type [optional] Qual pasta de dados
	 * @param string $file [optional] Nome no arquivo
	 * @param string $ext [optional] Extenção do arquivo
	 * @return string
	 */
	public static function pathTreated($type = 'Zeanwork', $file = null, $ext = 'php'){
		$paths = array(
						  'Zeanwork'			=> ZEANWORK
						, 'Lib'					=> LIBS
						, 'Datasource'			=> DATASOURCE
						, 'App'					=> APP
						, 'AppHost' 			=> APP_HOST
						, 'Host' 				=> HOST
						, 'Log'					=> PATH_LOGS
						, 'Controller'			=> APP . 'Controllers' . DS
						, 'Model'				=> APP . 'Models' . DS
						, 'View'				=> APP . 'Views' . DS
						, 'Element'				=> APP . 'Views' . DS . '_Elements' . DS
						, 'Layout'				=> APP . 'Layouts' . DS
						, 'Component'			=> FEATURES . 'Components' . DS
						, 'Helper'				=> FEATURES . 'Helpers' . DS
						, 'Extension'			=> FEATURES . 'Extensions' . DS
						, 'ViewZeanwork'		=> ZEANWORK . 'Views' . DS
						, 'LayoutZeanwork'		=> ZEANWORK . 'Layouts' . DS
						, 'ControllerZeanwork'	=> ZEANWORK . 'Controllers' . DS
						, 'ModelZeanwork'		=> ZEANWORK . 'Models' . DS
        		);
		if(!array_key_exists($type, $paths)){
			$pos = strrpos($type, 's');
			if($pos !== false)
				$type = substr($type, 0, -1);
				if(!array_key_exists($type, $paths)){	
					if(defined($type)){
						$paths[$type] = $type;
					}
					$paths[$type] = ZEANWORK;
				}
		}
		if($file != null){
			$filePath = $paths[$type]  . $file . '.' . $ext;
			if(file_exists($filePath))
				return $filePath;
			return false;
		}else
			return $paths[$type];
	}
	
	/**
	 * Gera um erro fatal, renderizando o layout de erro.
	 * @param string $type [optional] Tipo do erro
	 * @param array $details [optional] Detalhes do erro (para exibir um texto de erro personalizado, mande uma array com uma key igual á description)
	 * @return (no returns)
	 */
	public static function fatalError($type = null, $details = array()){
		$view = Zeanwork::getInstance('View');
		
		if(is_null($type)){
			$type = 'default';
			$details['type'] = $type;
		}
		$ext = Configure::read('defaultExtension');
		
		$file = Inflector::lowerCamelize($type);
		$fileView = Zeanwork::pathTreated('View', 'Errors' . DS . $file . '.' . $ext);

		if(!$fileView){
			$fileView = Zeanwork::pathTreated('ViewZeanwork', 'Errors' . DS . $file . '.' . $ext);
			if(!$fileView){
				$fileView = Zeanwork::pathTreated('ViewZeanwork', 'Errors' . DS . 'default.' . $ext);
				$details['type'] = $type;
			}
		}
		
        $content = $view->renderView($fileView, $details);
        echo $view->renderLayout($content, 'error', $ext);
		exit();
	}
	
	/**
	 * Verefica se existe o methodo no objeto e se ele é publico
	 * @param object $object
	 * @param string $method
	 * @return boolean
	 */
	public static function isCalledMethod($object, $method){
		if(method_exists($object, $method)){
			$method = new ReflectionMethod($object, $method);
			if($method->isProtected() || $method->isPrivate())
				return false;
			else
				return true;
		}
		return false;
	}
}