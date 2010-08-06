<?php
/**
 * Contem a classe Debugger
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
 * Class Html required
 */
Zeanwork::import(LIBS, 'helper');

/**
 * Controla os erros e é o debugador
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Debugger extends Zeanwork {

	/**
	 * Retorna true se é para mostrar os erros
	 * @return $debug
	 */
	public static function getDebugger(){
		$displayErrors = ini_get('display_errors');
		if($displayErrors == 1)
			return true;
		else
			return false;
	}
	
	/**
	 * Ativa os erros, (ini_set e error_reporting)
	 * @param boolean $debug
	 * @return null
	 */
	public static function setDebugger($debug){
		if($debug === true || $debug === 1){
			ini_set('display_errors', 1);
			error_reporting(E_ERROR | E_PARSE | E_WARNING | E_COMPILE_WARNING | E_ALL);
		}else{
			ini_set('display_errors', 0);
			error_reporting(0);
		}
	}
	
	/**
	 * Tipos de errors
	 * @var array
	 */
	public static $errorType = array(
								  E_ERROR				=> 'PHP - Error'
								, E_WARNING				=> 'PHP - Warning'
								, E_PARSE				=> 'PHP - Parsing Error'
								, E_NOTICE				=> 'PHP - Notice'
								, E_CORE_ERROR			=> 'PHP - Core Eror'
								, E_CORE_WARNING		=> 'PHP - Core Warning'
								, E_COMPILE_ERROR		=> 'PHP - Compile Error'
								, E_COMPILE_WARNING		=> 'PHP - Compile Warning'
								, E_USER_ERROR			=> 'PHP - User Error'
								, E_USER_WARNING		=> 'PHP - User Warning'
								, E_USER_NOTICE			=> 'PHP - User Notice'
								, E_STRICT				=> 'PHP - Strict Notice'
								, E_RECOVERABLE_ERROR	=> 'PHP - Recoverable Error'
								, 'SQL_ERROR'			=> 'SQL Error'
								, 'DB_ERROR'			=> 'Database Error'
							);
	
	/**
	 * Constructor
	 * @return 
	 */
	public function __construct(){
		/*
		 * Retorna a documentação de referencia dos erros
		 */
		$docRef = ini_get('docref_root');
		
		if(empty($docRef)){
			/*
			 * Seta a documentação de referencia para os erros
			 */
			ini_set('docref_root', 'http://php.net/');
		}
	}
	
	/**
	 * Manipulação dos erros
	 * @param numeric $errCode
	 * @param string $errStr
	 * @param string $errFile
	 * @param numeric $errLine
	 * @return no return
	 */
	public static function handleError($errCode, $errStr, $errFile, $errLine){
		if(error_reporting() == 0 || $errCode === 2048 || $errCode === 8192){
			return;
		}
		echo self::errorTrigger($errCode, $errStr, $errFile, $errLine);
	    return true;
	}
	
	/**
	 * É chamado quando houve um erro, tambem é possivel 'gerar' um erro, passando os seus parametros.
	 * @param numeric $errCode
	 * @param string $errStr
	 * @param string $errFile
	 * @param numeric $errLine
	 * @param boolean or string $query [optional]
	 * @return 
	 */
	public static function errorTrigger($errCode, $errStr, $errFile, $errLine, $query = false){
		if (error_reporting() == 0 || $errCode === 2048 || $errCode === 8192) {
			return;
		}
		if($errCode == 'SQL_ERROR' || $errCode == 'DB_ERROR'){
			if(!self::getDebugger())
				return;
		}
		if (array_key_exists($errCode, self::$errorType))
	        $err = self::$errorType[$errCode];
	    else
	        $err = 'PHP - Caught Exception';

		/*
		 * Criando a mensagem de erro
		 */
	    $errMsg = "
				<div class\"zeanworkDebug\">
					<br />
					<b>" . $err . ":</b> " . $errStr . "
					<br />
					<b>Page:</b> " . $errFile . "
					<br />
					<b>Line:</b> " . $errLine . "
					<br />
				";
		if($query !== false){
			$addId = round(microtime(), 8);
			$errMsg .= "<b>
			<a class\"zeanworkLink\" style=\"color:#FFFFFF\" href=\"javascript:void(0);\" onclick=\"document.getElementById('contentQuery_".$addId."').style.display = (document.getElementById('contentQuery_".$addId."').style.display == 'none' ? '' : 'none')\" >
			Query:</a></b> 
			<pre style=\"display:none; width:100%;\" id=\"contentQuery_".$addId."\" class\"ZeanworkDebugQuery\">
			" . $query . "
			</pre>";
		}
		$errMsg .= "</div>";
		if(!class_exists('HtmlHelper')){
			/*
			 * Class Html required
			 */
			Zeanwork::import(Zeanwork::pathTreated('Helper'), 'html');
		}
	    return HtmlHelper::error($errMsg);
	}
	
	
	/**
	 * Definido como o manipulador de erro Debugger::handleError();
	 * @return no return
	 */
	public static function invoke() {
		set_error_handler(array(new Debugger(),'handleError'));
	}
}