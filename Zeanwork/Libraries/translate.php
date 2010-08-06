<?php
/**
 * Contem a classe Translate
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
 * @version 		$LastChangedRevision: 183 $
 * @lastModified	$LastChangedDate: 2010-05-07 20:38:40 -0300 (Sex, 07 Mai 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Faz o controle do idioma de um sistema, carregando as traduções de arquivos externos ou de tabelas em bancos de dados
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Translate extends Zeanwork {
	
	/**
	 * Tipo de armazenamento dos dados
	 * 1 = Arquivos
	 * 2 = Banco de dados
	 * @var numeric
	 */
	var $typeStorage = 2;
	
	/**
	 * Informação sobre a tabela a ser utilizada casso o tipo de armazenamento seja bando de dados
	 * @var string
	 */
	var $model = array(
						  'tableName' => 'languages'
						, 'fields' => array(
											  'primaryKey' => 'idLanguages'
											, 'language' => 'language'
											, 'key' => '`key`'
											, 'value' => 'value'
										)
				);
	
	/**	
	 * Extenção dos arquivos a ser utilizado casso o tipo de armazenamento seja arquivos
	 * @var string
	 */
	var $fileExtension = 'lang';
	
	/**
	 * Se true e o arquivo de idiomas não existir ele criará o arquivo automaticamente
	 * @var boolean
	 */
	var $autoCreateFile = true;
	
	/**
	 * Instância da classe Model
	 * @var object
	 */
	var $instanceModel = null;
	
	/**
	 * Retorna a instância da classe Translate
	 * @return object
	 */
    private static function &__getInstance(){
    	static $instance = array();
		if(!$instance){
			$instance[0] =& Zeanwork::getInstance('Translate');
		}
		return $instance[0];
    }
	
	/**
	 * Retorna a sigla do idioma para a tradução
	 * @return string
	 */
	public function getTo(){
		return Configure::read('toLanguage');
	}
	
	/**
	 * Retorna a sigla do meu idioma
	 * @return string
	 */
	public function getFrom(){
		return Configure::read('fromLanguage');
	}
	
	/**
	 * carrega os dados do banco de dados
	 * @return array
	 */
	public function loadOfDatabase(){
		$output = array();
		$this->instanceModel = Zeanwork::getInstance('Model');
		$this->instanceModel->table = $this->model['tableName'];
		$this->instanceModel->primaryKey = $this->model['fields']['primaryKey'];
		
		$arr = $this->instanceModel->select(array(
							'conditions' => array(
													$this->model['fields']['language'] => $this->getTo()
											)
						));
		foreach($arr as $value){
			$output[$value['key']] = $value['value'];
		}
		return $output;
	}
	
	/**
	 * carrega os dados do arquivo
	 * @return array
	 */
	public function loadOfFile(){
		$output = array();
		$file = LANGUAGES . $this->getTo() . '.' . $this->fileExtension;
		if(!file_exists($file) && $this->autoCreateFile == true){
			$newFile = new File($file, true);
		}
		
		$contentFileLang = file_get_contents($file);
		$lineFile = preg_split("/\n/", $contentFileLang);
		foreach($lineFile as $value){
			preg_match('/^(.+)([ ][=__=__=][ ])(.+)$/', $value, $result);
			if(!empty($result['1']))
				$output[$result['1']] = $result['3'];
		}
		return $output;
	}
	
	/**
	 * Carrega os dados, identificando qual tipo de armazenamento esta sendo utilizado
	 * @param boolean $force Força o carregamento das traduções
	 * @return boolean Verdadeiro se carregou os dados
	 */
	public function load($force = false){
		if(!isset($_SESSION)){
			Zeanwork::import(Zeanwork::pathTreated('Component'), 'session');
			Zeanwork::getInstance('SessionComponent')->start();
		}
		
		if(!array_key_exists('App', $_SESSION))
			$_SESSION['App'] = array();
			
		if(!array_key_exists('Languages', $_SESSION['App']))
			$_SESSION['App']['Languages'] = array();
		
		if(!array_key_exists($this->getTo(), $_SESSION['App']['Languages']) || $force === true){
			if($this->typeStorage == 1)
				$languages = $this->loadOfFile();
			else
				$languages = $this->loadOfDatabase();
			$_SESSION['App']['Languages'][$this->getTo()] = $languages;
			return true;
		}
		return false;
	}
	
	/**
	 * Retorna a tradução do texto solicitado
	 * @param string $text Texto a ser traduzido
	 * @return string
	 */
	public static function translator($text){
		$_this =& self::__getInstance();
		$_this->load();
		
		if(!array_key_exists(self::filterStringForKey($text), $_SESSION['App']['Languages'][$_this->getTo()])){
			$_SESSION['App']['Languages'][$_this->getTo()][self::filterStringForKey($text)] = self::filterString($text);
			
			if($_this->typeStorage == 1){
				$file = new File(LANGUAGES . $_this->getTo() . '.' . $_this->fileExtension);
				$file->append(self::filterStringForKey($text) . ' =__=__= ' . self::filterString($text) . "\n");
			}else{
				if(!is_object($_this->instanceModel))
					$_this->instanceModel = Zeanwork::getInstance('Model');
				
				$_this->instanceModel->table = $_this->model['tableName'];
				$_this->instanceModel->primaryKey = $_this->model['fields']['primaryKey'];
				$_this->instanceModel->insert(array(
													  $_this->model['fields']['language'] => $_this->getTo()
													, $_this->model['fields']['key'] => self::filterStringForKey($text)
													, $_this->model['fields']['value'] => self::filterString($text)
												)
										);
			}
		}
		return $_SESSION['App']['Languages'][$_this->getTo()][self::filterStringForKey($text)];
	}
	
	/**
	 * Retorna a tradução do texto solicitado (Alias of Translate::translator())
	 * @param string $text Texto a ser traduzido
	 * @return string
	 */
	public static function _($text){
		return self::translator($text);
	}
	
	/**
	 * Filtra uma string, tirando todos os espações em branco em exesivo
	 * @param string $string
	 * @return string
	 */
	public static function filterString($string){
		$string = preg_split("/\s+/", trim($string));
		return implode(" ", $string);
	}
	
	/**
	 * Subistitui os espaços por underlines
	 * @param string $string
	 * @return string
	 */
	public static function filterStringForKey($string){
		$string = self::filterString($string);
		return str_replace(' ', '_', $string);
	}
	
}
