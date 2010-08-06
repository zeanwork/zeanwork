<?php
/**
 * Contem á classe Validation
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
 * Validações basicas
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Validation extends Zeanwork {
	
	/**
	 * Expreções regulares.
	 * ip
	 * hostname
	 * alphanumeric
	 * creditCard
	 * date
	 * 
	 * @var
	 */
    public static $regs = array(
        						  'ip' 			 => '(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])'
								, 'hostname' 	 => '(?:[a-z0-9][-a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)'
								, 'alphanumeric' => '[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+'
								, 'creditCard'   => '(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})'
								, 'date'		 => '(?:(?:31(\\/|-|\\.|\\x20)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)(\\/|-|\\.|\\x20)(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29(\\/|-|\\.|\\x20)0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])(\\/|-|\\.|\\x20)(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})'
    						);
	
    /**
     *  Valida um valor alfanumérico (letras e números).
     *  @param string
     *  @return boolean
     */
    public static function alphanumeric($value){
        return (boolean)preg_match("/^".self::$regs['alphanumeric']."$/mu", $value);
    }
	
    /**
     *  Valida um número ou comprimento de uma string que esteja entre dois outros valores especificados.
     *  @param string $value
     *  @param integer $min
     *  @param integer $max
     *  @return boolean
     */
    public static function between($value, $min, $max){
        if(!is_numeric($value)){
            $value = strlen($value);
        }
        return $value >= $min && $value <= $max;
    }
	
    /**
      *  Verefica se um valor é vazio.
      *  @param string $value Valor a ser validado
      *  @return boolean Verdadeiro caso o valor seja válido
      */
    public static function blank($value){
        return (boolean)!preg_match("/[^\s]/", $value);
    }
	
    /**
     *  Valida um valor booleano (true, false, 0 ou 1).
     *  @param string $value
     *  @return boolean
     */
    public static function boolean($value){
        $boolean = array(0, 1, '0', '1', true, false);
        return in_array($value, $boolean, true);
    }
	
    /**
      *  Valida um número de cartão de crédito.
      *  @param string $value
      *  @return boolean
      */
    public static function creditCard($value){
        return (boolean)preg_match("/^".self::$regs['creditCard']."$/", $value);
    }
	
    /**
     *  Valida valores através de comparação.
     *  @param string $value1
     *  @param string $operator
     *  @param string $value2
     *  @return boolean
     */
    public static function comparison($value1, $operator, $value2){
        switch($operator){
            case '>':
            case 'greater':
                return $value1 > $value2;
            case '<':
            case 'less':
                return $value1 < $value2;
            case '>=':
            case 'greaterorequal':
                return $value1 >= $value2;
            case '<=':
            case 'lessorequal':
                return $value1 <= $value2;
            case '==':
            case 'equal':
                return $value1 == $value2;
            case '!=':
            case 'notequal':
                return $value1 != $value2;
        };
        return false;
    }
	
    /**
      *  Valida um valor de acordo com uma expressão regular personalizada.
      *  @param string $value
      *  @param string $regex
      *  @return boolean
      */
    public static function regex($value, $regex){
        return (boolean)preg_match($regex, $value);
    }
	
    /**
      *  Valida uma data no formato d/m/y.
      *  @param string $value
      *  @return boolean
      */
    public static function date($value){
        $regex = "%^".self::$regs['date']."$%";
        return (boolean)preg_match($regex, $value);
    }
	
    /**
     *  Valida um número decimal.
     *  @param string $value
     *  @param integer $places - Número de casas decimais
     *  @return boolean
     */
    public static function decimal($value, $places = null){
        if(is_null($places)){
            $regex = "/^[+-]?[\d]+\.[\d]+([eE][+-]?[\d]+)?$/";
        }else{
            $regex = "/^[+-]?[\d]+\.[\d]{" . $places . "}$/";
        }
        return (boolean)preg_match($regex, $value);
    }
	
    /**
      *  Valida um endereço de e-mail.
      *  @param string $value
      *  @param boolean $checkHost
      *  @return boolean
      */
    public static function email($value, $checkHost = false){
        $match = preg_match("/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@" . self::$regs["hostname"] . "$/i", $value);
        if($match && $checkHost){
            preg_match("/@(" . self::$regs["hostname"] . ")$/i", $value, $reg);
            $host = gethostbynamel($reg[1]);
            return is_array($host);
        }
        return (boolean)$match;
    }
	
	/**
	 * Valida se um valor é igual a outro valor pré-definido.
	 * @param string $value
	 * @param string $compare
	 * @return boolean
	 */
    public static function equal($value, $compare){
        return $value === $compare;
    }
	
    /**
     * Verefica se o valor é um IP válido.
     * @param string $value
     * @return boolean
     */
    public static function ip($value){
        return (boolean)preg_match("/^" . self::$regs['ip'] . "$/", $value);
    }
	
    /**
     * Valida se um valor tem um tamanho mínimo.
     * @param string $value
     * @param integer $length
     * @return boolean
     */
    public static function minLength($value, $length){
        $valueLength = strlen($value);
        return $valueLength >= $length;
    }
	
    /**
     * Valida se um valor tem um tamanho máximo.
     * @param string $value
     * @param integer $length
     * @return boolean
     */
    public static function maxLength($value, $length){
        $valueLength = strlen($value);
        return $valueLength <= $length;
    }
	
    /**
     * Valida se um valor pertence a uma lista pré-definida (in_array).
     * @param string $value
     * @param array $list
     * @return boolean
     */
    public static function inList($value, $list){
    	if(is_array($list))
        	return in_array($value, $list);
		else
			return false;
    }
	
    /**
     * Valida um valor numérico (is_numeric).
     * @param string $value
     * @return boolean
     */
    public static function numeric($value){
        return is_numeric($value);
    }
	
    /**
     * Valida um valor não-vazio.
     * @param string $value
     * @return boolean
     */
    public static function notEmpty($value){
        return (boolean)preg_match("/[^\s]+/m", $value);
    }
	
    /**
     * Valida se o valor passado é um horário.
     * @param string $value
     * @return boolean
     */
    public static function time($value){
        $regex = "/^([01]\d|2[0-3])(:[0-5]\d){1,2}$"
               . "|^(0?[1-9]|1[0-2])(:[0-5]\d){1,2}\s?[AaPp]m$/";
        return (boolean)preg_match($regex, $value);
    }
	
    /**
     * Valida uma URL válida.
     * @param string $value
     * @param boolean $strict - Limitar a URL a protocolos válidos
     * @return boolean
     */
    public static function url($value, $strict = false){
        $chars = '([' . preg_quote('!"$&\'()*+,-.@_:;=') . '\/0-9a-z]|(\%[0-9a-f]{2}))';
        $regex = "(?:(?:https?|ftps?|file|news|gopher)://)?"
               . "(?:" . self::$regs["ip"] . "|" . self::$regs["hostname"] . ")"
               . "(?::[1-9][0-9]{0,3})?"
               . "(?:/?|/{$chars}*)?"
               . "(?:\?{$chars}*)?"
               . "(?:#{$chars}*)?";
        return (boolean)preg_match("%^{$regex}$%i", $value);
    }
}