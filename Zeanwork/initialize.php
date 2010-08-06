<?php
/**
 * Configurações, difinições e incluções do Zeanwork e do Aplicativo.
 * Nota: Para a execução correta de todo o frameworks é necessário as constantes aqui definidas. 
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 246 $
 * @lastModified	$LastChangedDate: 2010-07-30 20:16:37 -0300 (Sex, 30 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/*
 * Compara a verção do PHP
 */
if(version_compare(PHP_VERSION, "5.0") < 0){
	echo '  <div style="text-align:center; width:900px; margin:auto; border: 3px solid #666;">
				<h1>Ops!</h1>
				<p>Para que o Zeanwork possa ser executado corretamente, &eacute; necess&aacute;rio que a vers&atilde;o do PHP seja igual ou maior que 5.0 (sua vers&atilde;o &eacute; '.PHP_VERSION.').</p>
				<div style="text-align:right; font-size:11px;"><a href="http://zeanwork.com.br">Zeanwork Framework PHP</a></div>
			</div>';
	exit();
}

/**
 * Seta o X-Powered-By para "Zeanwork Framework PHP"
 */
header('X-Powered-By: Zeanwork Framework PHP');

/**
 * Hora de inicio de execução da página
 * @var microtime do início da execução da página
 */
$TIME_START = microtime();

/**
 * Separador de pasta ex: / 'DIRECTORY_SEPARATOR'
 * @var string
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * O caminho completo para o diretório que contém todo o aplicativo e o Zeanwork
 * @var string
 */
define('ROOT', dirname(dirname(__FILE__)) . DS );

/**
 * O caminho completo para o diretório que contém todo o aplicativo do Zeanwork
 * @var string
 */
define('ZEANWORK', ROOT . 'Zeanwork' . DS);

/**
 * O caminho completo para o diretório que contém todo a bilblioteca de class do Zeanwork
 * @var string
 */
define('LIBS', ZEANWORK . 'Libraries' . DS);

/**
 * O caminho completo para o diretório que contém todo a bilblioteca de class do Zeanwork
 * @var string
 */
define('DATASOURCE', ZEANWORK . 'Datasource' . DS);

/**
 * O caminho completo para o diretório que contém tudo do aplicativo
 * @var string
 */
define('APP', ROOT . 'App' . DS);

/**
 * O caminho completo para o diretório do WebRoot
 * @var string
 */
define('PUBLIC_ROOT', ROOT . 'Public' . DS);

/**
 * O caminho completo para o diretório que contém todas as classes do aplicativo
 * @var string
 */
define('APP_LIBS', APP . 'Libraries' . DS);

/**
 * Pasta onde localiza-se as traduções
 * @var string
 */
define('LANGUAGES', APP . 'Languages' . DS);

/**
 * Pasta onde localiza-se os cache`s
 * @var string
 */
define('CACHE', APP . 'Cache' . DS);

/**
 * Pasta onde localiza-se os recursos posiveis a ser usado, tais como: Components, Helpers, Extensions
 * @var string
 */
define('FEATURES', ROOT . 'Features' . DS);

/**
 * Current OS
 * @var string
 */
define('CUR_OS', substr(php_uname(), 0, 7 ) == 'Windows' ? 'Win' : '_Nix');

/**
 * Current Host name ex: http://localhost/
 * @var string
 */
define('HOST', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/');

if(strcmp('Win', CUR_OS) == 0){
    $path = '/' . str_replace( "\\", "/", str_replace( "C:\\", '', dirname(dirname(__FILE__))));
	$documentRoot = str_replace( 'C:', '', $_SERVER['DOCUMENT_ROOT']);
}else{
    $path = dirname(dirname(__FILE__));
	$documentRoot = $_SERVER['DOCUMENT_ROOT'];
}

/**
 * Current APP_HOST ex: http://localhost/Zeanwork/
 * @var string
 */
define('APP_HOST', rtrim(HOST.ltrim(str_replace($documentRoot, null, $path), '/'), '/') . '/');

/**
 * Pasta para os Log's, apartir do 'ROOT'
 * @var string
 */
define('PATH_LOGS', APP . 'Logs' . DS);


if (!defined('LOG_ERROR')){
	/**
	 * Nível do Log para 'Error'
	 * @var numeric
	 */
	define('LOG_ERROR', LOG_ERR);
}
if (!defined('LOG_SECURITY')){
	/**
	 * Nível do Log para 'Security'
	 * @var numeric
	 */
	define('LOG_SECURITY', 1001);
}

if(!defined('E_RECOVERABLE_ERROR')){
	/**
	 * @var numeric
	 */
	define('E_RECOVERABLE_ERROR', 4096);
}

$TIME_END_DEFINES = microtime();

/*
 * Includes das páginas de configuração
 */
require_once(ZEANWORK . 'Configs'. DS .'settings.php');
$timeStartImportsCofigurations = microtime();
Zeanwork::import(APP . 'Configs'. DS, 'definesMedia');
Zeanwork::import(APP . 'Configs'. DS, 'database');
Zeanwork::import(APP . 'Configs'. DS, 'routers');
Zeanwork::import(APP . 'Configs'. DS, 'doctypes');
Zeanwork::import(APP . 'Configs'. DS, 'settings');
Zeanwork::import(APP . 'Configs'. DS, 'languages');
Configure::write('infos.time.importsCofigurations', Helper::getTimeEnd($timeStartImportsCofigurations));
Configure::write('infos.time.defines', round($TIME_END_DEFINES - $TIME_START, 4) . 's');

if(Configure::read('magic_quotes_gpc') == false){
	if(get_magic_quotes_gpc()){
		function stripslashes_gpc(&$value){
		$value = stripslashes($value);
		}
		array_walk_recursive($_GET, 'stripslashes_gpc');
		array_walk_recursive($_POST, 'stripslashes_gpc');
		array_walk_recursive($_COOKIE, 'stripslashes_gpc');
		array_walk_recursive($_REQUEST, 'stripslashes_gpc');
	}
}