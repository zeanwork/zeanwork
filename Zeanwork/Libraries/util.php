<?php
/**
 * Contem a classe Util e funções corespondente
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
 * @version 		$LastChangedRevision: 229 $
 * @lastModified	$LastChangedDate: 2010-07-21 10:48:17 -0300 (Qua, 21 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Funções uteis para o trabalho no dia a dia
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Util extends Zeanwork {
	
    /**
     * Faz o tratamento de dados de entrada para evitar SQL Injection, Se $sql for um array (um $_POST ou $_GET
     * por exemplo) ele retornará o mesmo array tratando todos os campo, Se for uma string retorna a string
     * tratada
     * @param string $sql array ou var
     * @param boolean $addSlashes
     * @param boolean $sanitize
     * @return string
     */
  	public static function antInjection($sql, $addSlashes = true, $sanitize = true){
		if(is_array($sql)) {
			$ai = new ArrayIterator($sql);
			while ($ai->valid()) {
				$sql[$ai->key()] = preg_replace(sql_regcase("/(#|\*|--|\\\\)/"), "", $sql[$ai->key()]);
				$sql[$ai->key()] = trim($sql[$ai->key()]); //limpa espaços vazio
				if($addSlashes)
				$sql[$ai->key()] = addslashes($sql[$ai->key()]);
				if($sanitize)
				$sql[$ai->key()] = strip_tags($sql[$ai->key()]); //tira tags html e php
				$ai->next();
			}
			if($sanitize){
				if(function_exists('filter_var_array'))
					$sql = filter_var_array($sql, FILTER_SANITIZE_STRING); //Required PHP Version >= 5.2
			}
		}else{
			$sql = preg_replace(sql_regcase("/(#|\*|--|\\\\)/"), "", $sql);
			$sql = trim($sql); //limpa espaços vazio
			if($addSlashes)
			$sql = addslashes($sql); //Adiciona barras invertidas a uma string
			if($sanitize){
				if(function_exists('filter_var'))
					$sql = filter_var($sql, FILTER_SANITIZE_STRING); //Required PHP Version >= 5.2
				$sql = strip_tags($sql); //tira tags html e php
				}
			}
		return $sql;
    }

	/**
	 * Interpreta uma scring com tags de bbcode
	 * @param string $text String a ser interpretado
	 * @return string
	 */
	public static function bbcode($text){
		$text = trim($text);
		if(!function_exists('__codeEscape')){
			function __codeEscape($str){
				global $text;
				$text = strip_tags($text);
				$code = $str[1];
				$code = htmlspecialchars($code);
				$code = str_replace('[', '&#91;', $code);
				$code = str_replace(']', '&#93;', $code);
				return '<pre class="code"><code>'.$code.'</code></pre>';
			}
		}
		$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "__codeEscape", $text);
		$tags = array(
					  array(
							  '/\[b\](.*?)\[\/b\]/ms'
							, '/\[i\](.*?)\[\/i\]/ms'
							, '/\[u\](.*?)\[\/u\]/ms'
							, '/\[img\](.*?)\[\/img\]/ms'
							, '/\[email\](.*?)\[\/email\]/ms'
							, '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms'
							, '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms'
							, '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms'
							, '/\[quote](.*?)\[\/quote\]/ms'
							, '/\[list\=(.*?)\](.*?)\[\/list\]/ms'
							, '/\[list\](.*?)\[\/list\]/ms'
							, '/\[\*\]\s?(.*?)\n/ms'
						)
					, array(
							  '<strong>\1</strong>'
							, '<em>\1</em>'
							, '<u>\1</u>'
							, '<img src="\1" alt="\1" />'
							, '<a href="mailto:\1">\1</a>'
							, '<a href="\1">\2</a>'
							, '<span style="font-size:\1%">\2</span>'
							, '<span style="color:\1">\2</span>'
							, '<blockquote>\1</blockquote>'
							, '<ol start="\1">\2</ol>'
							, '<ul>\1</ul>'
							, '<li>\1</li>'
						)
				);
		$text = preg_replace($tags[0], $tags[1], $text);
		$text = nl2br($text);
		
		if(!function_exists('__removeTagBr')){
			function __removeTagBr($str){
				$str[0] = str_replace("<br />", '', $str[0]);
				return str_replace("<br>", '', $str[0]);
			}
		}	
		$text = preg_replace_callback('/<pre class="code">(.*?)<\/pre>/ms', "__removeTagBr", $text);
		$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "__removeTagBr", $text);
		return $text;
	}
	
	/**
	 * Retorna o browser que o usuário esta utilizando e a sua versão corespondente
	 * @return array [browser, version]
	 */
	public static function getBrowser(){
		$info = array();
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$info['browser'] = 'OTHER';
		$info['version'] = null;
		$bots = array(
					  'GOOGLEBOT'
					, 'MSNBOT'
					, 'SLURP'
				);
		foreach($bots as $bot){
			if(strpos(strtoupper($userAgent), $bot) !== false){
				return $info;
			}
		}
		$browsers = array(
						  'MSIE'
						, 'OPERA'
						, 'FIREFOX'
						, 'MOZILLA'
						, 'NETSCAPE'
						, 'SAFARI'
						, 'LYNX'
						, 'KONQUEROR'
					);
		foreach($browsers as $browser){
			$pos = strpos(strtoupper($userAgent), $browser);
			$len = $pos + strlen($browser);
			$version = substr($userAgent, $len, 5);
			$version = preg_replace('/[^0-9,.]/', '', $version);
			if(strpos(strtoupper($userAgent), $browser) !== false){
				$info['browser'] = $browser;
				$info['version'] = $version;
				return $info;
			}
		}
		return $info;
	}    
	
	/**
	 * Faz um alert em javascript
	 * @param string $string
	 * @return string
	 */
	public static function alert($string = null){
		$alerta = '<script>alert("'.$string.'");</script>';
		return $alerta;
	}
	
	/**
	 * Imprime um array em uma tag pre
	 * @param array $array
	 * @return no return
	 */
	public static function printPre($array){
		echo '&nbsp<pre>';
		print_r($array);
		echo '</pre>';
	}
	
	/**
	 * Recarrega a pagina 'document.location.reload()'.
	 * @return string
	 */
	public static function reload(){
		return '<script type="text/javascript">document.location.reload();</script>';
	}

	/**
	 * Redireciona para a pagina solicitada (Javascript) 'document.location.href="index.php"'.
	 * @param object $pag
	 * @return string
	 */
	public static function location($pag){
		return '<script type="text/javascript">document.location.href="'.$pag.'";</script>';
	}
	
	/**
	 * Retorna o conteudo de uma pagina por include.
	 * @param string $filename
	 * @return string
	 */
	public static function getIncludeContents($filename){
	    if (is_file($filename)) {
	        ob_start();
	        include $filename;
	        $contents = ob_get_contents();
	        ob_end_clean();
	        return $contents;
	    }
	    return false;
	}
	
	/**
	 * Retorna o IP do Usuário
	 * @return string
	 */
	public static function getIp(){
		if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
			return getenv("HTTP_CLIENT_IP");
		}elseif(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
			return getenv("HTTP_X_FORWARDED_FOR");
		}elseif(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")){
			return getenv("REMOTE_ADDR");
		}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")){
			return $_SERVER['REMOTE_ADDR'];
		}else{
			return "unknown";
		}
	}
	
	/**
	 * Retorna o nome da página atual
	 * @return string
	 */
	public static function getThisPageName(){
		return end(explode('/', $_SERVER['PHP_SELF']));
	}
	
	/**
	 * Retorna um '<br />'
	 * @param nember $num [optional] Numeros de brs
	 * @return string
	 */
	public static function br($num = 1){
		$output = null;
		for($i = 0; $i < (int)$num; ++$i){
			$output .= "<br />\r\n";
		}
		return $output;
	}
	
	/**
	 * Retorna um '&nbsp;'
	 * @param number $num [optional] Numeros de '&nbsp;'
	 * @return string
	 */
	public static function nbsp($num = 1){
		$output = null;
		for($i = 0; $i < (int)$num; ++$i){
			$output .= '&nbsp;';
		}
		return $output;
	}
	
	/**
	 * Faz um var_dump com as tags <pre>
	 * @param variable $var
	 * @return no return
	 */
	public static function pr_dump($var){
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}
	
	/**
	 * Retorna informações úteis
	 * @return string
	 */
	public static function info(){
		$output = null;
		global $TIME_START;
		/**
		 * Class Html required
		 */
		Zeanwork::import(Zeanwork::pathTreated('Helper'), 'html');
		Zeanwork::import(LIBS, 'helper');
		Zeanwork::import(LIBS, 'router');
		$html =& Zeanwork::getInstance('HtmlHelper');
		
		$tag = array(
					'divInfo' => $html->tag('div', "%s", array('style' => 'border:2px solid #444; padding:0px 5px 5px 5px; margin:0px 0px 5px 0px; color:#FFFFFF'))
				);
		
		$output .= $html->tagOpen('div', array('style' => 'background:#494949; border:2px solid #232222; padding:5px; margin:10px; color:#FFFFFF; font-family: Arial, Helvetica, sans-serif; font-size:12px;'));
		$output .= $html->h('Informa&ccedil;&otilde;es &uacute;teis', 1, array('style' => ' color:#FFFFFF'));
		$output .= br();
		$output .= sprintf(
							  $tag['divInfo']
							, $html->h('Execu&ccedil;&atilde;o:', 3, array('style' => ' color:#FFFFFF')) . 
							  'Defini&ccedil;&atilde;o das constantes do Zeanwork: '. Configure::read('infos.time.defines') . br() .
							  'Importa&ccedil;&atilde;o das classes do Zeanwork: '. Configure::read('infos.time.importsZeanwork') . br() .
							  'Importa&ccedil;&atilde;o das cofigura&ccedil;&otilde;es do Zeanwork e do aplicativo: '. Configure::read('infos.time.importsCofigurations') . br() .
							  'Memor&iacute;a Usada: '. number_format(memory_get_usage()) . ' bytes' . br() .
							  'Tempo at&eacute; aqui: '. Helper::getTimeEnd($TIME_START) . br()
					);
		$router = Router::parse();
		$output .= sprintf(
							  $tag['divInfo']
							, $html->h('URL', 3, array('style' => ' color:#FFFFFF')) .
							  'Estou Aqui: ' . $router['here'] . br() . 
							  (($router['prefix'] != null) ? 'Prefixo (Prefix): ' . $router['prefix'] . br() : '') .
							  'Controlador (Controller): ' . $router['controller'] . br() .
							  'A&ccedil;&atilde;o (Action): ' . $router['action'] . br() .
							  ((isset($router['id'])) ? 'Id: ' . $router['id'] . br() : '')
					);
		$output .= sprintf(
							  $tag['divInfo']
							, $html->h('Zeanwork', 3, array('style' => ' color:#FFFFFF')) .
							  'Vers&atilde;o: ' . Configure::read('zeanworkVersion') . br() .
							  'Web Site: ' . $html->link(Configure::read('zeanworkWebSite'), Configure::read('zeanworkWebSite'), array('style' => 'color: #F0F0F0')) . br() .
							  'Documenta&ccedil;&atilde;o/Manual: ' . $html->link(Configure::read('zeanworkDoc'), Configure::read('zeanworkDoc'), array('style' => 'color: #F0F0F0')) . br() .
							  'Local: ' . ZEANWORK . br() .
							  'Biblioteca: ' . LIBS . br()
							  
					);
		if(Configure::exist('aplicationVersion')){
			$aplicationVersion = 'Vers&atilde;o: ' . Configure::read('aplicationVersion') . br();
		}else{
			$aplicationVersion = null;
		}
		
		if(Configure::exist('database')){
			$db = Configure::read('database');
			if(array_key_exists(Configure::read('environment'), $db))
				$dbConfig = 'Configurado';
			else
				$dbConfig = 'N&atilde;o Configurado';
		}else{
			$dbConfig = 'N&atilde;o Configurado';
		}
		
		if(Configure::exist('logs')){
			$db = Configure::read('logs');
			if(array_key_exists(Configure::read('environment'), $db))
				$logsConfig = 'Configurado';
			else
				$logsConfig = 'N&atilde;o Configurado';
		}else{
			$logsConfig = 'N&atilde;o Configurado';
		}
		
		if(Configure::exist('pathLanguages'))
			$folderLanguage = 'Pasta de Idiomas: '. ROOT . Configure::read('pathLanguages') . DS . br();
		else
			$folderLanguage = null;
		
		$output .= sprintf(
							  $tag['divInfo']
							, $html->h('Aplicativo', 3, array('style' => ' color:#FFFFFF')) .
							  $aplicationVersion .
							  'Host: ' . $html->link(APP_HOST, APP_HOST, array('style' => 'color: #F0F0F0')) . br() .
							  'Local: ' . APP . br() .
							  'Pasta de Log\'s: ' . PATH_LOGS . br() .
							  $folderLanguage .
							  'Ambiente: ' . Configure::read('environment') . br() .
							  'Mostrar Erros: ' . ((Debugger::getDebugger() == true) ? 'Sim' : 'N&atilde;o') . br() .
							  'Bando de Dados: ' . $dbConfig . br() .
							  'Log\'s: ' . $logsConfig
					);
		
		$output .= sprintf(
							  $tag['divInfo']
							, $html->h('PHP', 3, array('style' => ' color:#FFFFFF')) .
							  'Vers&atilde;o: ' . phpversion() . br() .
							  'Sistema (OS): ' . php_uname() . br() .
							  'Manual: ' . $html->link(ini_get('docref_root'), ini_get('docref_root'), array('style' => 'color: #F0F0F0')) . br()
					);
		$output .= $html->tag('div', $html->zeanworkPoweredByLogo(), array('style' => 'text-align:right;'));
		$output .= $html->tagClose('div');
		return $output;
	}
	
}


/**
 * This function is alias Util::br();
 * @see Util::br();
 * @param object $num [optional]
 * @return 
 */
function br($num = 1){
	return Util::br($num);
}

/**
 * This function is alias Util::nbsp();
 * @see Util::nbsp();
 * @param object $num [optional]
 * @return 
 */
function nbsp($num = 1){
	return Util::nbsp($num);
}

/**
 * This function is alias Util::printPre();
 * @see Util::printPre();
 * @param array $array
 * @return 
 */
function pr($array){
	return Util::printPre($array);
}

/**
 * This function is alias Util::printPre();
 * @see Util::printPre();
 * @param array $array
 * @return 
 */
function printPre($array){
	return Util::printPre($array);
}

/**
 * This function is alias Util::alert();
 * @see Util::alert();
 * @param array $array
 * @return 
 */
function alert($string = null){
	return Util::alert($string);
}

/**
 * This function is alias Util::pr_dump();
 * @see Util::pr_dump();
 * @param variable $var
 * @return 
 */
function pr_dump($var){
	return Util::pr_dump($var);
}

/**
 * Retorna a data e a hora
 * @return string
 */
function now(){
	return date('Y-m-d H:i:s');
}
