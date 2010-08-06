<?php
/**
 * Contem a classe Folder
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
 * Controla pastas
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Folder extends Zeanwork {
	
	/**
	 * Caminho da pasta
	 * @var
	 */
	public $path = null;
	
	/**
	 * Permição padrão para criação de novas pastas
	 * @var
	 */
	public $mode = 0777;
	
	/**
	 * Diretórios
	 * @var
	 */
	public $directories = null;
	
	/**
	 * Arquivos
	 * @var
	 */
	public $files = null;
	
	/**
	 * Erros
	 * @var
	 */
	public $errors = array();
	
	/**
	 * Mensagens
	 * @var
	 */
	public $messages = array();
	
	/**
	 * Construct, create = true ele criará a pasta
	 * @param string or boolean $path [optional] Nome da pasta
	 * @param boolean $create [optional] Se não existir a pasta ela pode ser criada.
	 * @param numeric $mode [optional] Permição da pasta
	 * @return 
	 */
	public function __construct($path = false, $create = false, $mode = false) {
		
		if($path){
			$this->path = $path;
		}
		if($mode){
			$this->mode = $mode;
		}
		
		if(!file_exists($path) && $create === true){
			$calledFrom = debug_backtrace();
			$this->create($path, $this->mode, $calledFrom);
		}

		if(!Folder::isAbsolute($path)){	
			$path = realpath($path);
		}
	}

	/**
	 * Seta um caminho
	 * @param string $path
	 */
	public function setPath($path){
		$this->path = $path;
	}
	
	/**
	 * Retorna o caminho
	 * @return string
	 */
	public function getPath(){
		return $this->path;
	}
	
	/**
	 * Cria uma pasta
	 * @param string $pathName Nome da pasta
	 * @param numeric $mode [optional] Permição da pasta
	 * @param object $calledFrom [optional] De onde foi chamado 'debug_backtrace()'
	 * @return boolean
	 */
	public function create($pathName, $mode = false, $calledFrom = null){
		if(is_dir($pathName) || empty($pathName)){
			return true;
		}
		
		if(!$mode){
			$mode = $this->mode;
		}
		
		if(is_file($pathName)){
			if(!is_array($calledFrom)) $calledFrom = debug_backtrace();
			echo Debugger::errorTrigger(E_WARNING, $pathName.' é um arquivo, e não pode ser criado uma pasta apartir do mesmo', $calledFrom[0]['file'], $calledFrom[0]['line']);
			return false;
		}
		
		if(!file_exists($pathName)){
			$old = umask(0);
			if(mkdir($pathName, $mode)){
				 umask($old);
				$this->messages[] = $pathName.' criado';
				return true;
			}else{
				umask($old);
				$this->errors[] = $pathName.' n&atilde;o criado';
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Deleta uma pasta
	 * @param string $path [optional] Nome da pasta
	 * @return boolean
	 */
	public function delete($path = null){
		if(!$path){
			$path = $this->path;
		}
		$path = Folder::path($path);
		if(is_dir($path) === true){
			$normalFiles = glob($path . '*');
			$hiddenFiles = glob($path . '\.?*');
			$normalFiles = $normalFiles ? $normalFiles : array();
			$hiddenFiles = $hiddenFiles ? $hiddenFiles : array();

			$files = array_merge($normalFiles, $hiddenFiles);
			if(is_array($files)){
				foreach($files as $file){
					if (preg_match('/(\.|\.\.)$/', $file)) {
						continue;
					}
					if(is_file($file) === true){
						if(@unlink($file)){
							$this->messages[] = $file.' deletado';
						}else{
							$this->errors[] = $file.' n&atilde;o deletado';
						}
					}elseif(is_dir($file) === true && $this->delete($file) === false){
						return false;
					}
				}
			}
			$path = substr($path, 0, strlen($path) - 1);
			if(rmdir($path) === false){
				$this->errors[] = $path.' n&atilde;o deletado';
				return false;
			}else{
				$this->messages[] = $path.' deletado';
				return true;
			}
		}
		return true;
	}
	
	
	/**
	 * Pega todos os arquivos de uma pasta
	 * @param boolean $sort [optional] Ordenar por
	 * @param boolean $exceptions [optional]  Extenções
	 * @param boolean $fullPath [optional] Toda a pasta
	 * @return array
	 */
	function read($sort = true, $exceptions = false, $fullPath = false) {
		
		$dirs = $files = array();		
		if (is_array($exceptions)) {
			$exceptions = array_flip($exceptions);
		}
		$skipHidden = isset($exceptions['.']) || $exceptions === true;

		if (false === ($dir = @opendir($this->path))) {
			return array($dirs, $files);
		}

		while (false !== ($item = readdir($dir))) {
			if ($item === '.' || $item === '..' || ($skipHidden && $item[0] === '.') || isset($exceptions[$item])) {
				continue;
			}

			$path = Folder::addPathElement($this->path, $item);
			if (is_dir($path)) {
				$dirs[] = $fullPath ? $path : $item;
			} else {
				$files[] = $fullPath ? $path : $item;
			}
		}

		if ($sort) {
			sort($dirs);
			sort($files);
		}

		closedir($dir);
		return array($dirs, $files);
	}
	
	/**
	 * 
	 * @param object $path
	 * @param object $element
	 * @return 
	 */
	function addPathElement($path, $element) {
		return Folder::path($path) . $element;
	}
	
	/**
	 * Procura algo em uma pasta
	 * @param string $regexpPattern [optional]
	 * @param boolean $sort [optional]
	 * @return array result
	 */
	function find($regexpPattern = '.*', $sort = false) {
		list($dirs, $files) = $this->read($sort);
		return array_values(preg_grep('/^' . $regexpPattern . '$/i', $files)); ;
	}
	
	/**
	 * Lista todos os arquivos da pasta
	 * @param boolean $sort [optional]
	 * @param boolean $exceptions [optional]
	 * @return array
	 */
	function ls($sort = true, $exceptions = false) {
		return $this->read($sort, $exceptions);
	}
	
	/**
	 * Se o caminho é absoluto
	 * @param string $path Caminho da pasta (nome da pasta)
	 * @return 
	 */
	public function isAbsolute($path){
		$match = preg_match('/^\\//', $path) || preg_match('/^[A-Z]:\\\\/i', $path);
		return $match;
	}

	/**
	 * Retorna \\ se for windows se não retorna /
	 * @param string $path
	 * @return string
	 */
	public function correctPath($path){
		if(Folder::isWindowsPath($path)){
			return '\\';
		}
		return '/';
	}

	/**
	 * Retorna o caminha da pasta corretamente
	 * @param string $path
	 * @return string
	 */
	public function path($path){
		if(Folder::isPath($path)){
			return $path;
		}
		return $path . Folder::correctPath($path);
	}

	/**
	 * Verifica se é uma pasta
	 * @param string $path
	 * @return boolean
	 */
	function isPath($path){
		$lastChar = $path[strlen($path) - 1];
		return $lastChar === '/' || $lastChar === '\\';
	}
	
	/**
	 * Verifica se é windows
	 * @param string $path
	 * @return boolean
	 */
	function isWindowsPath($path){
		if(preg_match('/^[A-Z]:\\\\/i', $path)){
			return true;
		}
		return false;
	}
}