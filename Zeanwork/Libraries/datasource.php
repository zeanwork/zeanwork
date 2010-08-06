<?php
/**
 * Contem a class Datasource
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
 * Controla os datasouces (abstract class)
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
abstract class Datasource extends Database {
	
	/**
	 * Conecta com o banco de dados
	 * @return boolean
	 */
	abstract public function connect();
	
	/**
	 * Disconecta com o banco de dados
	 * @return boolean
	 */
    abstract public function disconnect();
	
	/**
	 * Executa um query
	 * @param string $query [optional] Query a ser executada
	 * @param string $database [optional] Databese onde será executada a query
	 * @param object $calledFrom [optional] Da onde foi executado esta query (debug_backtrace())
	 * @return boolean
	 */
    abstract public function query($query = null, $database = null, $calledFrom = null);
	
	/**
	 * Seleciona um banco de dados
	 * @param string $database Nome do banco de dados
	 * @return boolean
	 */
	abstract public function selectDb($database);
	
	/**
	 * Executa um fecth no resultado da consulta
	 * @param string $position [optional] Coluda de um resultado
	 * @return array or string
	 */
	abstract public function fetch($who = null);
	
	/**
	 * Retorna o numero de linhas afetadas de um delete/update
	 * @return numeric
	 */
	abstract public function getNumRows();
	
	/**
	 * Retorna o numero de linhas afetadas de um delete/update
	 * @return numeric
	 */
	abstract public function getNumRowsAffected();
}