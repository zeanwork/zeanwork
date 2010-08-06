<?php
/**
 * Contem a classe Log
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
 * Class File required
 */
Zeanwork::import(LIBS, 'file');

/**
 * Controla os Log's
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Class required File
 */
class Log extends Zeanwork {
	
	/**
	 * Niveis dos log's
	 * @var
	 */
	public static $levels = array(
								  LOG_WARNING 	=> false
								, LOG_NOTICE	=> false
								, LOG_INFO 		=> false
								, LOG_DEBUG 	=> false
								, LOG_ERR 		=> false
								, LOG_ERROR 	=> false
								, LOG_SECURITY 	=> false
							);
	
	/**
	 * Escreve no arquivo de log, acrecentando a mensagem.
	 * @param number $type Tipo do log
	 * @param string $msg mendagem a ser escrita do log
	 * @return boolean
	 */
	public static function write($type, $msg, $calledFrom = true){
		$addMsg = null;
		
		if(!is_int($type))
			$type = LOG_NOTICE;
		
		if(array_key_exists($type, self::$levels)){
			if(self::$levels[$type] === false){
				return false;
			}
		}else
			return false;
		
		$levels = array(
						  LOG_WARNING 	=> 'warning'
						, LOG_NOTICE 	=> 'notice'
						, LOG_INFO 		=> 'info'
						, LOG_DEBUG 	=> 'debug'
						, LOG_ERR 		=> 'error'
						, LOG_ERROR		=> 'error'
						, LOG_SECURITY 	=> 'security'
					);
		
		if($calledFrom === true){
			$calledFrom = debug_backtrace();
			$addMsg = ' in ' . $calledFrom[0]['file'] . ' on line ' . $calledFrom[0]['line'];
		}elseif(is_array($calledFrom)){
			if(!array_key_exists(0, $calledFrom))
				$calledFrom = debug_backtrace();
			if(!array_key_exists('file', $calledFrom[0]) || !array_key_exists('line', $calledFrom[0]))
				$calledFrom = debug_backtrace();
			$addMsg = ' in ' . $calledFrom[0]['file'] . ' on line ' . $calledFrom[0]['line'];
		}

		if(is_int($type) && isset($levels[$type])){
			$type = $levels[$type];
		}
		if($type == 'error' || $type == 'warning'){
			$filename = PATH_LOGS . 'error.log';
		}elseif($type == 'security'){
			$filename = PATH_LOGS . 'security.log';
		}elseif (in_array($type, $levels)){
			$filename = PATH_LOGS . 'debug.log';
		}else{
			$filename = PATH_LOGS . $type . '.log';
		}
		$output = date('d/m/Y H:i:s') . ' ' . ucfirst($type) . ': ' . $msg . $addMsg . "\r\n";
		$log = new File($filename, true);
		return $log->append($output);
	}
}