<?php
/**
 * Contem a classe oConn
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
 * @version 		$LastChangedRevision: 205 $
 * @lastModified	$LastChangedDate: 2010-06-18 15:17:48 -0300 (Sex, 18 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controla a conexão com banco de dados
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Classes requireds Database
 */
class oConn extends Database {
	
	/**
	 * Link da conexão com o banco
	 * @var
	 */
	private static $oConn = null;
	
	/**
	 * Já esta conectado ou não
	 * @var
	 */
	private static $oConnected = false;
	
	/**
	 * Abre a conexão
	 * @return boolean
	 */
	public static function open(){
		if(self::$oConnected !== true){
			$db = Zeanwork::getInstance(Database::getDatasource());
			self::$oConn = $db->connect();
			if(self::$oConn){
				self::$oConnected = true;
				self::selectDatabase(self::getDatabase());
				if(method_exists($db, 'setCharset')){
					$db->setCharset(Database::getCharset());
				}
				return true;
			}else{
				$calledFrom  = debug_backtrace();
				$addId = round(microtime(), 8);
				$errMsg = "<br /><b>
					<a class\"zeanworkLink\" style=\"color:#FFFFFF\" href=\"javascript:void(0);\" onclick=\"document.getElementById('contentQuery_".$addId."').style.display = (document.getElementById('contentQuery_".$addId."').style.display == 'none' ? '' : 'none')\" >
					Erro do Banco de dados:</a></b> 
					<pre style=\"display:none; width:100%;\" id=\"contentQuery_".$addId."\" class\"ZeanworkDebugQuery\"> " . $db->getError() . "</pre>";
				echo Debugger::errorTrigger('DB_ERROR', 'Não é possível conectar ao banco de dados!'.$errMsg, $calledFrom[0]['file'], $calledFrom[0]['line']);
				self::$oConnected = false;
				exit();
			}
		}
		return false;
	}
	
	/**
	 * Fecha a conexão
	 * @return boolean
	 */
	public static function close(){
		if(self::$oConnected === true){
			if(Zeanwork::getInstance(Database::getDatasource())->disconnect()){
				self::$oConnected = false;
				self::$oConn = null;
				return true;
			}
			return false;
		}
		return false;
	}
	
	/**
	 * Seleciona uma database
	 * @param string $database Database a ser conectada
	 * @return boolean
	 */
	public static function selectDatabase($database){
		if(self::isConnected()){
			if(!(Zeanwork::getInstance(Database::getDatasource())->selectDb($database))){
				$calledFrom = debug_backtrace();
				echo Debugger::errorTrigger('DB_ERROR', 'Não é possível conectar ao banco de dados especificado!', $calledFrom[0]['file'], $calledFrom[0]['line']);
				exit();
			}else{
				Database::setDatabaseSelected($database);
				return true;
			}
		}else{
			$calledFrom = debug_backtrace();
			echo Debugger::errorTrigger('DB_ERROR', 'Você não está conectado com o banco de dados!', $calledFrom[0]['file'], $calledFrom[0]['line']);
			exit();
		}
		return false;
	}
	
	/**
	 * Se já esta conectado com o banco de dados
	 * @return boolean
	 */
	public static function isConnected(){
		return self::$oConnected;
	}
	
	/**
	 * Retorna o link para a conexão com o banco
	 * @return 
	 */
	public static function getConn(){
		return self::$oConn;
	}
}