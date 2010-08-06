<?php
/**
 * Contem a classe Database
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
 * Controla o banco de dados
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Database extends Zeanwork {
	
	/**
	 * Nome do datasource
	 * @var string
	 */
	private static $datasource = null;
	
	/**
	 * Nome do banco de dados selecionado
	 * @var string
	 */
	private static $databaseSelected = null;

	/**
	 * Retorna o nome da data base
	 * @return string
	 */
	public static function getDatabase(){
	    return self::getConfigure('database');
	}
	
	/**
	 * Retorna o nome do host
	 * @return string
	 */
	public static function getHost(){
	    return self::getConfigure('host');
	}
	
	/**
	 * Retorna a senha
	 * @return string
	 */
	public static function getPassword(){
	    return self::getConfigure('password');
	}
	
	/**
	 * Retorna o nome do usuário
	 * @return string
	 */
	public static function getUser(){
	    return self::getConfigure('user');
	}
	
	/**
	 * Retorna o nome do driver
	 * @return string
	 */
	public static function getDrive(){
	    return self::getConfigure('drive');
	}

	/**
	 * Retorna o charser
	 * @return string
	 */
	public static function getCharset(){
	    return self::getConfigure('charset');
	}
	
	/**
	 * Retorna o nome do bando de dados
	 * @return string
	 */
	public static function getDatabaseSelected(){
	    return self::$databaseSelected;
	}
	
	/**
	 * Seta um banco de dados selecionado
	 * @param string $database Nome da database selecionada
	 */
	public static function setDatabaseSelected($database){
	    self::$databaseSelected = $database;
	}
	
	/**
	 * Carrega um driver de datacouce
	 * @return boolean
	 */
	public static function loadDatasource(){
		$drive = self::getDrive();
		if($drive == null){
			trigger_error('Não foi possível localizar a configuração do drive ao banco de dados. Verifique /App/Config/database.php', E_USER_WARNING);
		}
		$classDatasource = strtolower($drive) . 'Datasource';
		$fileDatasource = strtolower($drive);
	    if(!class_exists($classDatasource) && Zeanwork::pathTreated('Datasource', $fileDatasource)){
	        Zeanwork::import(DATASOURCE, $fileDatasource);
	        self::$datasource = $classDatasource;
	    	return true;
	    }elseif(!class_exists($classDatasource)){
	    	Zeanwork::fatalError('Datasource', array(
												  'datasource' 	=> $fileDatasource . '.php'
											)
						);
			return false;
	    }
	}
	
	/**
	 * Retorna o nome do driver do datasource
	 * @return string
	 */
	public static function getDatasource(){
		if(self::$datasource == null){
			self::loadDatasource();
		}
		return self::$datasource;
	}
	
	/**
	 * Retorna uma configuração
	 * @param string $who [optional] Nome da configuração desejada
	 * @return value of configuration
	 */
	public static function getConfigure($who = null){
		if(array_key_exists(Configure::read('environment'), Configure::read('database')) || array_key_exists('all', Configure::read('database'))){
			$config = Configure::read('database');
			$config = $config[Configure::read('environment')];
			if($who == null)
				return $config;
			else{
				if(array_key_exists($who, $config))
					return $config[$who];
				else{
					$calledFrom = debug_backtrace();
					echo Debugger::errorTrigger(E_USER_NOTICE, 'Configuração não encontrada em Configure::$configs["database"]["'.Configure::read('environment').'"]', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			}
		}else{
			echo Debugger::errorTrigger(E_USER_WARNING, 'Não foi possível encontrar as configurações de banco de dados para o ambiente selecionado ('.Configure::read('environment').'). Verifique /App/Config/database.php', __FILE__, __LINE__);
			exit();
		}
	}
}