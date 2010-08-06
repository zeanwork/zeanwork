<?php
/**
 * Contem a classe File
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
 * @version 		$LastChangedRevision: 203 $
 * @lastModified	$LastChangedDate: 2010-06-16 20:24:28 -0300 (Qua, 16 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */


/**
 * Class Folder required
 */
Zeanwork::import(Zeanwork::pathTreated('Lib'), 'folder');

/**
 * Controle arquivos
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Class required Folder
 */
class File extends Zeanwork {
	
	/**
	 * Instancia da class Folder()
	 * @var
	 */
	public $Folder = null;
	
	/**
	 * Nome do arquivo
	 * @var
	 */
	public $name = null;
	
	/**
	 * Caminho da pasta
	 * @var
	 */
	public $path = null;
	
	/**
	 * @var
	 */
	public $handle = null;
	
	/**
	 * @var
	 */
	public $lock = null;
	
	/**
	 * Construct, se create = true, ele criará o arquivo, se a pasta não existir ele criará tambem
	 * @param string $path Nome da pasta (Caminho da pasta)
	 * @param boolean $create [optional] Criar a paste e o arquivo se não existir
	 * @param numeric $mode [optional] Permição da pasta e do arquivo
	 * @return 
	 */
	public function __construct($path = false, $create = false, $mode = 0777){
		if($path !== false){
			$this->Folder = new Folder(dirname($path), $create, $mode);
			if(!is_dir($path)){
				$this->name = basename($path);
			}
			if($path){
				$this->path = $path;
			}
			if(!$this->exists()){
				if($create === true){
					if($this->safe($path) && $this->create() === false){
						return false;
					}
				} else {
					return false;
				}
			}
		}
	}
	
	/**
	 * Apelido para close();
	 * @return 
	 */
	public function __destruct(){
		$this->close();
	}

	/**
	 * Fecha o arquivo
	 * @return boolean
	 */
	public function close(){
		if(!is_resource($this->handle)){
			return true;
		}
		return fclose($this->handle);
	}
	
	/**
	 * Cria um arquivo
	 * @return boolean
	 */
	public function create(){
		$dir = $this->Folder->getPath();		
		if(is_dir($dir) && is_writable($dir) && !$this->exists()){
			$old = umask(0);
			
			if(touch($this->path)){
				umask($old);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Deleta um arquivo
	 * @return boolean
	 */
	public function delete(){
		clearstatcache();
		if($this->exists()){
			return unlink($this->path);
		}
		return false;
	}
	
	/**
	 * Abre um arquivo
	 * @param string $mode [optional] Modo de abrir o arquivo
	 * @param boolean $force [optional] Forças o abrimento do arquivo
	 * @return boolean
	 */
	public function open($mode = 'r', $force = false){
		if(!$force && is_resource($this->handle)){
			return true;
		}
		clearstatcache();
		if($this->exists() === false){
			if($this->create() === false){
				return false;
			}
		}

		$this->handle = fopen($this->path, $mode);
		if(is_resource($this->handle)){
			return true;
		}
		return false;
	}

	/**
	 * Verifica se existe o arquivo e se é um arquivo
	 * @return boolean
	 */
	public function exists(){
		return (file_exists($this->path) && is_file($this->path));
	}
	
	/**
	 * Verifica se a pasta onde o arquivo se encontra é seguro
	 * @param string $name [optional] Nome do arquivo
	 * @param string $ext [optional] Extenção do arquivo
	 * @return 
	 */
	public function safe($name = null, $ext = null){
		if(!$name){
			$name = $this->name;
		}
		return preg_replace( "/(?:[^\w\.-]+)/", "_", basename($name, $ext));
	}
	
	/**
	 * Escreve do arquivo apagando tudo que já existe
	 * @param string $data Dados a serem escrito no arquivo
	 * @param string $mode [optional] Mode de abrir o arquivo
	 * @param boolean $force [optional] Forçar o abrimendo do arquivo 
	 * @return boolean
	 */
	public function write($data, $mode = 'w', $force = false){
		$success = false;
		if($this->open($mode, $force) === true){
			if($this->lock !== null){
				if(flock($this->handle, LOCK_EX) === false){
					return false;
				}
			}

			if(fwrite($this->handle, $data) !== false){
				$success = true;
			}
			if($this->lock !== null){
				flock($this->handle, LOCK_UN);
			}
		}
		return $success;
	}
	
	/**
	 * Acrecenta algo no arquivo
	 * @param string $data Dado a ser acrescentado no arquivo
	 * @param object $force [optional]
	 * @return boolean
	 */
	public function append($data, $force = false){
		return $this->write($data, 'a', $force);
	}
}