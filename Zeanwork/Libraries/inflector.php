<?php
/**
 * Contem a classe Inflector
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
 * @version 		$LastChangedRevision: 156 $
 * @lastModified	$LastChangedDate: 2010-04-08 19:50:57 -0300 (Qui, 08 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Faz Filtros
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Inflector extends Zeanwork {
	
	/**
	* Transforma uma string para o formato UperCamelCase.
	* @example Inflector::camelize('Zeanwork é um Framework PHP'); // Saída => ZeanworkÉUmFrameworkPHP
	* @param string $string
	* @return string
	*/
    public static function camelize($string){
        return str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', $string)));
    }
	
    /**
	* Transforma uma string para o formato lowerCamelCase.
	* @example Inflector::camelize('Zeanwork é um Framework PHP'); // Saída => zeanworkÉUmFrameworkPHP
	* @param string $string
	* @return string
	*/
	public static function lowerCamelize($string){
        $string = self::camelize($string);
        return strtolower($string[0]).substr($string,1);
    }
    
	/**
	 * Transforma uma string para o formato humanizado.
	 * @example Inflector::humanize('Zeanwork-é-um-Framework-PHP'); // Saída => Zeanwork É Um Framework PHP
	 * @param string $string
	 * @return string
	 */
    public static function humanize($string){
        return ucwords(str_replace(array('_', '-'), ' ', $string));
    }
	
    /**
     * Substitui os espaços de uma string pelo "_" e converte as letras para minúsculas.
     * @example Inflector::underscore('Zeanwork É Um Framework PHP'); // Saída => zeanwork é um framework php
     * @param string $string
     * @return strign
     */
    public static function underscore($string = ""){
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '\\1', $string));
    }
	
	/**
	 * Limpa uma string, transforma a string em minúsculas, com espaços substituídos por hífens, com a remocao de caracteres acentuados e especiais, deixando apenas letras minúsculas.
	 * @param strign $string
	 * @param strign $replace [optional] default value => '-'
	 * @return strign
	 */
    public static function cleanString($string, $replace = '-'){
        $map = array(
		              "/À|à|Á|á|å|Ã|â|Ã|ã/" => 'a'
		            , "/È|è|É|é|ê|ê|Ë|ë/" 	=> 'e'
		            , "/Ì|ì|Í|í|Î|î/" 		=> 'i'
		            , "/Ò|ò|Ó|ó|Ô|ô|ø|Õ|õ/" => 'o'
		            , "/Ù|ù|Ú|ú|Û|û|Ü|ü/" 	=> 'u'
		            , "/ç|Ç/" 				=> 'c'
		            , "/ñ|Ñ/" 				=> 'n'
		            , "/ä|æ/" 				=> 'ae'
		            , "/Ö|ö/" 				=> 'oe'
		            , "/Ä|ä/" 				=> 'Ae'
		            , "/Ö/" 				=> 'Oe'
		            , "/ß/" 				=> 'ss'
		            , "/[^\w\s]/" 			=> ' '
		            , "/\\s+/" 				=> $replace
		            , "/^{$replace}+|{$replace}+$/" => ''
        		);
        return strtolower(preg_replace(array_keys($map), array_values($map), $string));
	}
	
	/**
	 * Troca - por _
	 * @param string $string [optional]
	 * @return string
	 */
	public static function hyphenToUnderscore($string = null){
        return str_replace("-", "_", $string);
    }
	
	/**
	 * Converte a primeira letra de uma string para minúscula
	 * @param string $string String a ser modificada
	 * @return string
	 */
	public static function firstLetterToLower($string){
		return strtolower((string)$string[0]).substr((string)$string, 1);
	}
	
	/**
	 * Reduz uma string sem cortar palavras ao meio.
	 * @param string $str String a ser reduzida
	 * @param numeric $maxLength Numeros de caracteres maximos
	 * @param string $append [optional] String a ser adicionada quando atingir o maximo de catacters
	 * @param boolean $stripTags [optional] Tirar as tags html
	 * @return string
	 */
	public static function strReduce($str, $maxLength, $append = '...', $stripTags = false){
		if((boolean)$stripTags === true)
			$str = strip_tags($str);
		if(Configure::exist('charset'))
			$charset = Configure::read('charset');
		else
			$charset = 'ISO-8859-1';
		$str = html_entity_decode($str, ENT_COMPAT, $charset);
		$str = preg_replace("/\s+/s", " ", trim($str));
		if(strlen($str) <= $maxLength)
			return htmlentities($str, ENT_COMPAT, $charset);
		$reduced = preg_replace("/^(.{0," . $maxLength . "}\s).*?$/s", "\\1", $str);
		
		while((strlen($reduced) + strlen($append)) > $maxLength){
			$reduced = preg_replace("/^(.*?\s)[^\s]+\s?$/s", "\\1", $reduced);
		}
		$reduced = trim($reduced);
		$reduced .= $append;
		return htmlentities($reduced, ENT_COMPAT, $charset);
	}
	
}