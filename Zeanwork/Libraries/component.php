<?php
/**
 * Contem a classe Component
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
 * @version 		$LastChangedRevision: 213 $
 * @lastModified	$LastChangedDate: 2010-06-24 11:15:00 -0300 (Qui, 24 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Controle dos componentes
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Component extends Zeanwork {
	
	/**
	 * Instância dos componentes já carregados
	 * @var
	 */
	public $loaded = array();
	
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
	 * Carrega um componente
	 * @param string $component Nome do componente
	 * @return Instância do componente
	 */
	public function loadComponent($component){
		if($component != null){
			$component = Inflector::lowerCamelize($component);
			if(!array_key_exists($component, $this->loaded) || !isset($this->loaded[$component])){
				if(Zeanwork::import(Zeanwork::pathTreated('Component'), $component, 'php', false))
					$imported = Zeanwork::pathTreated('Component', $component);
				else
					$imported = false;
				
				if($imported !== false){
					if(!class_exists($component . 'Component')){
							Zeanwork::fatalError('Component', array(
																	  'component' 	=> $component
																	, 'className'	=> $component
																)
									);
					}
					$this->loaded[$component] =& Zeanwork::getInstance($component . 'Component');
					$loadeds = array();
					if(is_array($this->loaded[$component]->components)){
						foreach($this->loaded[$component]->components as $value){
							$loadeds[$value] = $this->load->component($value, false);
						}
					}elseif(isset($this->loaded[$component]->components))
						$loadeds[$value] = $this->load->component($this->loaded[$component]->components, false);
					
					foreach($loadeds as $key => $value){
						$this->loaded[$component]->{$key} = $value;
					}
					return true;
				}else{
					Zeanwork::fatalError('Component', array(
															  'component' 	=> $component
														)
							);
				}
			}else
				return $this->loaded[$component];
		}
		return false;
	}
	
	/**
	 * Retorna um componente, já carregado ou não
	 * @param string $component Nome do componente
	 * @return Instância do componente
	 */
	public function getComponent($component){
		$component = Inflector::lowerCamelize($component);
		if($this->loadComponent($component))
			return $this->loaded[$component];
		return false;
	}

}