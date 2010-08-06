<?php
/**
 * Contem a classe Cache
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
 * Faz o tratamendo dos arquivos de cache
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Cache extends Zeanwork {
	
	/**
	 * Lista dos arquivos de cache
	 * @var array
	 */
	var $files = array();
	
	/**
	 * Se já foi carregados os arquivos de cache será true
	 * @var boolean
	 */
	var $loadedFiles = false;
	
	/**
	 * Construtor
	 * @return no return
	 */
	public function __construct(){
		$this->folder =& new Folder(CACHE, true);
	}
	
	/**
	 * Verifica de o cache já foi expirado
	 * @param string $file Nome do arquivo a ser verificado 
	 * @return boolean
	 */
	public function expired($file = false){
		if($file == false)
			return true;
		$arrFile = $this->interpretNameFile($file);
		if(is_array($arrFile)){
			$expires = (integer)$arrFile['expires'];
			return ($expires !== 0 && $expires <= time());
		}
		return false;
	}
	
	/**
	 * Deleta o arquivo de cache para o controller e a action especificada
	 * @param string $controller Nome do controller
	 * @param string $action Nome da action
	 * @return boolean
	 */
	public function delete($controller, $action){
		if($file = $this->exists($controller, $action)){
			if(!@unlink(CACHE . $file))
				Log::write(LOG_ERROR, 'Cache: Não foi possivel deletar o arquivo de cache: ' . CACHE . $file);
			return true;
		}
		return false;
	}
	
	/**
	 * Dedela todos os arquivos de cache
	 * @return boolean
	 */
	public function deleteAll(){
		$this->loadFiles();
		if(count($this->files) == 0)
			return false;
		
		foreach($this->files as $file){
			if(!@unlink(CACHE . $file))
				Log::write(LOG_ERROR, 'Cache: Não foi possivel deletar o arquivo de cache: ' . CACHE . $file);
		}
		return true;
	}
	
	/**
	 * Verifica se existe o arquivo de cache para o controller e a action especificada
	 * @param string $controller Nome do controller
	 * @param string $action Nome da action
	 * @return false or string contendo o nome do arquivo de cache
	 */
	public function exists($controller, $action){
		$this->loadFiles();
		if(count($this->files) == 0)
			return false;
		
		$files = $this->interpretNameFile($this->files);
		
		if(Configure::read('useMultiLanguage') == true)
			$lang = Configure::read('toLanguage');
		else
			$lang = null;
		
		foreach($files as $file){
			if($controller == $file['controller'] && $action == $file['action'] && $lang == $file['lang']){
				return $file['name'];
			}
		}
		return false;
	}
	
	/**
	 * Retorna o conteudo do cache para o controller e a action especificada, casso o cache já tenha expirado ele deletará o arquivo existente
	 * @param string $controller Nome do controller
	 * @param string $action Nome da action
	 * @return false or conteudo do cache
	 */
	public function read($controller, $action){
		if($file = $this->exists($controller, $action)){
			if($this->expired($file)){
				$this->delete($controller, $action);
				return false;
			}else{
				return @file_get_contents(CACHE . $file);
			}
		}
		return false;
	}
	
	/**
	 * Cria um arquivo de cache para o controller e a action especificada 
	 * @param string $controller Nome do controller
	 * @param string $action Nome da action
	 * @param string $content Conteudo para o cache
	 * @param numeric $lifeTime Tempo de vida do cache (em segundos)
	 * @return boolean
	 */
	public function create($controller, $action, $content = null, $lifeTime = 0){
		if($lifeTime == 0)
			$expires = 0;
		else
			$expires = $lifeTime + time();
		if(Configure::read('useMultiLanguage') == true)
			$lang = '~' . Configure::read('toLanguage');
		else
			$lang = null;
		
		$file = new File(CACHE . 'cz~' . $controller . '~' . $action . $lang . '~' . (integer)$expires . '.htm', true);
		$file->write($content);
		$file->close();
		return true;
	}
	
	/**
	 * Carrega os arquivos de cache
	 * @param boolean $force Forçar o carregamento
	 * @return array
	 */
	public function loadFiles($force = false){
		if($this->loadedFiles == false || $force == true){
			$this->files = $this->folder->find("([c][z][~])(.+)");
			$this->loadedFiles = true;
		}
		return $this->files;
	}
	
	/**
	 * Interpleta o nome do arquivo de cache
	 * @param string or array $name Nome do arquivo ou uma array contendo os nomes dos arquivos
	 * @return array contendo as informações do nome do arquivo
	 */
	public function interpretNameFile($name){
		$output = array();
		if(is_array($name)){
			foreach($name as $value){
				$output[] = $this->interpretNameFile($value);
			}
		}else{
			$arrReturn = array(
								  'name' => $name
								, 'controller' => null
								, 'action' => null
								, 'lang' => null
								, 'expires' => null
						);
			$arrName = explode('~', $name);
			if(isset($arrName[1]))
				$arrReturn['controller'] = $arrName[1];
			
			if(isset($arrName[2]))
				$arrReturn['action'] = $arrName[2];
				
			if(isset($arrName[3])){
				$arrName[3] = str_replace('.htm', null, $arrName[3]);
				if(is_numeric($arrName[3]))
					$arrReturn['expires'] = $arrName[3];
				else
					$arrReturn['lang'] = $arrName[3];
			}
			
			if(isset($arrName[4])){
				$arrName[4] = str_replace('.htm', null, $arrName[4]);
				$arrReturn['expires'] = $arrName[4];
			}
			$output = $arrReturn;
		}
		
		return $output;
	}
	
	
}