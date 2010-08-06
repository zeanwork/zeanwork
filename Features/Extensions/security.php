<?php
/**
 * Contem a classe Security
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
 * Funções básicas de segurança
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Security extends Zeanwork {
	
	/**
	 * Cria um hash de uma string usando o método especificado
	 * @param string $data Valor a ser hasheado
	 * @param string $hash [optional] Nome do algoritmo de hash [md5, sha256, base64, crypt, sha1]
	 * @param string or boolean $dataStart [optional] Valor à ser acrescentado no início da string
	 * @return string
	 */
	public static function hash($data, $hash = null, $dataStart = false){
		$output = false;
        if($dataStart !== false){
            $data = $dataStart . $data;
        }
        switch($hash){
            case 'md5':
                $output = md5($data);
			break;
            case 'sha256':
                $output = bin2hex(mhash(MHASH_SHA256, $data));
			break;
			case 'base64':
                $output = base64_encode($data);
			break;
			case 'crypt':
                $output = crypt($data);
			break;
            case 'sha1':
            default:
                $output = sha1($data);
			break;
        }
        return $output;
    }
	
	/**
	 * Criptografa um valor
	 * @param string $data Valor a ser criptografado
	 * @param string $dataStart [optional] Valor à ser acrescentado no início da string
	 * @param string or boolean $dataEnd [optional] Valor à ser acrescentado no fim da string
	 * @return 
	 */
	public static function encrypt($data, $dataStart = null, $dataEnd = null){
		if($dataStart == null){
			$encrypts = Configure::read('security');
			if(!array_key_exists('dataStart', $encrypts['encrypts']))
				$dataStart = null;
			else
				$dataStart = $encrypts['encrypts']['dataStart'];
		}
		if($dataEnd == null){
			$encrypts = Configure::read('security');
			if(!array_key_exists('dataEnd', $encrypts['encrypts']))
				$dataEnd = '1S9DkFpRkCrQoi34';
			else
				$dataEnd = $encrypts['encrypts']['dataEnd'];
		}
		
		$output = $dataStart;
		$output .= base64_encode(base64_encode(base64_encode($data)));
		$output = str_replace('=', $dataEnd, $output);
		return $output;
	}
	
	/**
	 * Descriptografa um valor
	 * @param string $data Valor a ser descriptografado
	 * @param string $dataStart [optional] Valor à ser acrescentado no início da string
	 * @param string or boolean $dataEnd [optional] Valor à ser acrescentado no fim da string
	 * @return 
	 */
	public static function decrypt($data, $dataStart = null, $dataEnd = null){
		if($dataStart == null){
			$encrypts = Configure::read('security');
			if(!array_key_exists('dataStart', $encrypts['encrypts']))
				$dataStart = null;
			else
				$dataStart = $encrypts['encrypts']['dataStart'];
		}
		if($dataEnd == null){
			$encrypts = Configure::read('security');
			if(!array_key_exists('dataEnd', $encrypts['encrypts']))
				$dataEnd = '1S9DkFpRkCrQoi34';
			else
				$dataEnd = $encrypts['encrypts']['dataEnd'];
		}
		
		$data = str_replace($dataStart, '', $data);
		$data = str_replace($dataEnd, '=', $data);
		$output = base64_decode(base64_decode(base64_decode($data)));
		return $output;
	}
}