<?php
/**
 * Contem a class Configure
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
 * Class Debugger
 */
Zeanwork::import(LIBS, 'debugger');

/**
 * Seta as configurações do Zeanwork e/ou do aplicativo
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Configure extends Zeanwork {

	/**
	 * Configs
	 * @var boolean
	 */
	public static $configs = array();
	
	/**
	 * Retorna uma configuração já escrita
	 * @param string $who
	 * @param string $descriptErrorAdd [optional] Texto adicional para o erro
	 * @return 
	 */
	public static function read($who, $descriptErrorAdd = null){
		if(!array_key_exists($who, Configure::$configs)){
			$calledFrom = debug_backtrace();
			echo Debugger::errorTrigger(E_USER_NOTICE, 'Not found the requested configuration in Configure::$configs["'.$who.'"] ' . $descriptErrorAdd, $calledFrom[0]['file'], $calledFrom[0]['line']);
			return false;
		}else
			return Configure::$configs[$who];
	}
	
	/**
	 * Verifica se existe uma configuração salva
	 * @param string $who Nome da configuração
	 * @return boolean
	 */
	public static function exist($who){
		if(array_key_exists($who, Configure::$configs))
			return true;
		else
			return false;
	}
	
	/**
	 * Escreve as configurações do Zeanwork e do aplicativo, salvando todas as configurações aqui escritas na variavel Configure::$configs
	 * 
	 * Algumas configurações predefinidas para algumas classes do Zeanwork:
	 * debugger [optional]
	 * fromLanguage [required] if use class Tranlation
	 * toLanguage [required] if use class Tranlation
	 * logs [optional]
	 * dateFormat [optional]
	 * database [required] if use conection for database
	 * environment [required]
	 * 
	 * @param string $who Nome da configuração
	 * @param string $value Valor para a configuração
	 * @return 
	 */
	public static function write($who, $value){
		self::$configs[$who] = $value;
		
		$calledFrom = debug_backtrace();
		switch($who){
			case 'charset':
				ini_set('default_charset', self::$configs['charset']);
			break;
			case 'environment':
				if(array_key_exists('database', self::$configs)){
					if(array_key_exists($value, self::$configs['database'])){
						/* Carrega o drive para o banco de dados */
						Database::loadDatasource();
					}
				}
				if(array_key_exists('logs', self::$configs)){
					if(array_key_exists($value, self::$configs['logs'])){
						if(is_array(self::$configs['logs'][$value])){
							foreach(self::$configs['logs'][$value] as $k => $v){
								if(array_key_exists($k, Log::$levels)){
									if(is_bool($v)){
										Log::$levels[$k] = $v;
									}
								}
							}
						}
					}
				}
				if(array_key_exists('debugger', self::$configs)){
					if(array_key_exists($value, self::$configs['debugger'])){
						Debugger::setDebugger(self::$configs['debugger'][$value]);
					}
				}
			break;
			default:
				null;
			break;
		}
	}
}