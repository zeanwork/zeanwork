<?php
/**
 * Contem a class Helper
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
 * Controla os ajudantes
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Class required Inflector
 */
class Helper extends Zeanwork {
	
	/**
	 * Helpers já carregados
	 * @var array
	 */
	protected $loaded = array();
	
	/**
	 * Instância da classe Loader
	 * @var object
	 */
	public $load = null;
	
	/**
	 * Construct
	 * @return 
	 */
	public function __construct(){
		$this->load =& Zeanwork::getInstance('Loader');
	}
	
	/**
	 * Carrega um helper e cria a instância do mesmo
	 * @param string $helper Nome do Helper ex: 'html'
	 * @return boolean
	 */
	public function loadHelper($helper){
		if($helper != null){
			$helper = Inflector::lowerCamelize($helper);
			if(!array_key_exists($helper, $this->loaded) || !isset($this->loaded[$helper])){
				
				if(Zeanwork::import(Zeanwork::pathTreated('Helper'), $helper, 'php', false))
					$imported = Zeanwork::pathTreated('Helper', $helper);
				else
					$imported = false;
									
				if($imported !== false){
					if(!class_exists($helper . 'Helper')){
							Zeanwork::fatalError('Helper', array(
																	  'helper' 	=> $helper
																	, 'className'	=> $helper
																	, 'type' 		=> 'class'
																	, 'file'		=> $imported
																)
									);
					}
					$this->loaded[$helper] =& Zeanwork::getInstance($helper . 'Helper');
					$loadeds = array();
					if(is_array($this->loaded[$helper]->helpers)){
						foreach($this->loaded[$helper]->helpers as $value){
							$loadeds[$value] = $this->load->helper($value, false);
						}
					}elseif(isset($this->loaded[$helper]->helpers))
						$loadeds[$value] = $this->load->helper($this->loaded[$helper]->helpers, false);
					
					foreach($loadeds as $key => $value){
						$this->loaded[$helper]->{$key} = $value;
					}
					return true;
				}else{
					Zeanwork::fatalError('Helper', array(
															  'helper' 	=> $helper
															, 'className'	=> $helper
															, 'type' 		=> 'file'
															, 'file'		=> array(Zeanwork::pathTreated('Helper', $helper), Zeanwork::pathTreated('HelperZeanwork', $helper))
														)
							);
				}
			}else
				return $this->loaded[$helper];
		}
		return false;
	}
	
	/**
	 * Retorna um helper, se o helper não estiver instânciado será criado o mesmo 
	 * @param string $helper Nome do Helper ex: 'html'
	 * @return boolean
	 */
	public function getHelper($helper){
		$helper = Inflector::lowerCamelize($helper);
		if($this->loadHelper($helper))
			return $this->loaded[$helper];
		return false;
	}
	
	/**
	 * Retorna todos os helpers já carregados
	 * @return 
	 */
	public function getHelpersLoadeds(){
		return $this->loaded;
	}
	
	/**
	 * Retorna um tempo em microtime $TIME_START - $TIME_END
	 * @param object $TIME_START
	 * @param object $fortComent [optional]
	 * @return string
	 */
	public static function getTimeEnd($TIME_START, $fortComent = false){
		$TIME_END = round(microtime() - $TIME_START, 4);
		
		if($fortComent)
			return "\r\n<!-- Runtime: " . $TIME_END . "s. Developed with Zeanwork Framework PHP -->";
		else
			return $TIME_END . "s";
	}
	
	/**
	 * Renderisa um helper carregado pelo $this->load
	 * @param string $attribute Nome do helper (Nome da variavel que será criada da classe)
	 * @return boolean
	 */
	function renderLoad($attribute){
		if(is_object($this->load->{$attribute})){
			$this->{$attribute} =& $this->load->{$attribute};
			unset($this->load->{$attribute});
			return true;
		}
		return false;
	}
}