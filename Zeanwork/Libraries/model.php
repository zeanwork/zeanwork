<?php
/**
 * Contem a classe Model
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
 * @version 		$LastChangedRevision: 237 $
 * @lastModified	$LastChangedDate: 2010-07-28 11:29:51 -0300 (Qua, 28 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class Database required
 */
Zeanwork::import(LIBS, 'database');

/**
 * Class oConn required
 */
Zeanwork::import(LIBS, 'oConn');

/**
 * Controla os Models
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @obs				Classes requireds Database, oConn, Inflector
 */
class Model extends Database {
	
	/**
	 * ID do registro a ser alterado
	 * @var string
	 */
    public $id = null;
	
	/**
	 * Estrutura da tabela
	 * @var array
	 */
    public $schema = array();
	
	/**
	 * Nome da tabela
	 * @var string
	 */
    public $table = null;
	
	/**
	 * Nome do campo que é a primary key da tabela
	 * @var string
	 */
    public $primaryKey = null;
	
	/**
	 * Condições (WHERE)
	 * @var array
	 */
    public $conditions = array();
	
	/**
	 * Order by
	 * @var string
	 */
    public $orderBy = null;
	
	/**
	 * Limite de resultados
	 * @var numeric or string
	 */
    public $limit = null;
	
	/**
	 * Banco de dados onde executará as querys (se null, então utilizará a configuração default)
	 * @var string
	 */
	public $database = null;
	
	/**
	 * Instância do driver
	 * @var object
	 */
	private $instance = null;
	
	/**
	 * Dados
	 * @var array
	 */
	public $data = array();
	
	/**
	 * Parametros
	 * @var array
	 */
	public $params = array(
						  'conditions' 	=> array()
						, 'fields'		=> array()
						, 'data'		=> array()
						, 'join' 		=> array()
						, 'innerJoin' 	=> array()
						, 'leftJoin' 	=> array()
						, 'rightJoin' 	=> array()
						, 'orderBy' 	=> null
						, 'groupBy' 	=> null
						, 'limit' 		=> null
						, 'keyResult' 	=> null
					);
	
	/**
	 * Parametros padrão
	 * @var array
	 */
	public $paramsDefaults = array(
								  'conditions' 	=> array()
								, 'fields'		=> array()
								, 'data'		=> array()
								, 'join' 		=> array()
								, 'innerJoin' 	=> array()
								, 'leftJoin' 	=> array()
								, 'rightJoin' 	=> array()
								, 'orderBy' 	=> null
								, 'groupBy' 	=> null
								, 'limit' 		=> null
								, 'keyResult' 	=> null
							);
	
	/**
	 * Models já carregados
	 * @var array
	 */
	public $loaded = array();
	
	/**
	 * Valores padrão para a paginação
	 * @var array
	 */
	public $pagination = array(
							  'totalRecords' => 0
							, 'totalPages' => 1
							, 'recordsInPage' => 25
							, 'page' => false
							, 'startRecord' => 0
						);
	
	/**
	 * Campos a serem validados
	 * @var array
	 */
	public $validates = array();
	
	/**
	 * Mensagens de erro das validações
	 * @var array
	 */
	public $errorMessages = array(); 
	
	/**
	 * Retorna as mensagens de error das validações
	 */
	public function getErrorMessages(){
		return $this->errorMessages;
	}
	
	/**
	 * Executa uma query
	 * @param string $query [optional] Query a ser executada
	 * @param string $database [optional] Databese onde será executada a query
	 * @return boolean
	 */
	public function query($query = null, $database = null){
		if($database === null && $this->database !== null)
			$database = $this->database;
		return $this->instance->query($query, $database, debug_backtrace());
	}
	
	/**
	 * Retorna o numero de linhas afetadas de um delete/update
	 * @return numeric
	 */
	public function getNumRowsAffected(){
		return $this->instance->getNumRowsAffected();
	}
	
	/**
	 * Retorna o numero de linhas retornadas da consulta
	 * @return numeric
	 */
	public function getNumRows(){
		return $this->instance->getNumRows();
	}
	
	/**
	 * Executa um fecth no resultado da consulta
	 * @param string $position [optional] Coluda de um resultado
	 * @return array or string
	 */
	public function fetch($position = null){
		return $this->instance->fetch($position);
	}
	
	/**
	 * Executa todos os fecth possivel no resultado da consulta
	 * @return array
	 */
	public function fetchAll(){
		return $this->instance->fetchAll();
	}
	
	/**
	 * Retorna o result da query
	 * @return object
	 */
	public function getResult(){
	    return $this->instance->getResult();
	}
	
	/**
	 * Retorna o último erro gerado pelo banco de dados
	 * @return string
	 */
	public function getError(){
		return $this->instance->getError();
	}
	
	/**
	 * Retorna a query executada ou ainda não executada
	 * @return string
	 */
	public function getQuery(){
	    return $this->instance->getQuery();
	}
	
	/**
	 * Seta uma query a ser executada
	 * @param string $query
	 * @return boolean
	 */
	public function setQuery($query){
	    $this->instance->setQuery($query);
		return true;
	}
	
	/**
	 * Retorna o último id inserido na database
	 * @return numeric
	 */
	public function getInsertId(){
        return $this->instance->getInsertId();
    }
	
	/**
	 * Inícia uma transação SQL
	 * @param string $database [optional] Nome da database
	 * @return boolean
	 */
    public function begin($database = null){
        return $this->instance->begin($database);
    }
	
	/**
	 * Finaliza e salva uma transação SQL
	 * @param string $database [optional] Nome da database
	 * @return boolean
	 */
    public function commit($database = null){
        return $this->instance->commit($database);
    }
	
	/**
	 * Finaliza e não salva uma transação SQL
	 * @param string $database [optional] Nome da database
	 * @return boolean
	 */
    public function rollback($database = null){
        return $this->instance->rollback($database);
    }
	
	/**
	 * Carrega um model
	 * @param string $model Nome do model a ser carregado
	 * @return boolean
	 */
	public function loadModel($model){
		if($model != null){
			$model = Inflector::lowerCamelize($model);
			if(!class_exists('appModel')){
				if(!Zeanwork::import(Zeanwork::pathTreated('Model'), 'appModel', 'php', false)){
					Zeanwork::import(Zeanwork::pathTreated('ModelZeanwork'), 'appModel');
				}
			}
			
			if(Zeanwork::import(Zeanwork::pathTreated('Model'), $model, 'php', false)){
				if(!class_exists($model)){
						Zeanwork::fatalError('Model', array(
															  'model' => $model
														)
								);
				}
				$this->loaded[$model] =& Zeanwork::getInstance($model);
				
				return true;
			}else{
				Zeanwork::fatalError('Model', array(
													  'model' => $model
												)
						);
			}
		}
		return false;
	}
	
	/**
	 * Construct
	 * @return 
	 */
    public function __construct(){
    	$this->instance =& Zeanwork::getInstance(Database::getDatasource());
    	
    	$classes = array('input' => 'Input');
		foreach($classes as $var => $class){
			$this->{$var} =& Zeanwork::getInstance($class);
		}
		
		$data = array('post' => $_POST, 'files' => $_FILES);
		$this->input->setData($data);
    }
	
	/**
	 * Retorna um model já carregado, se não estiver carregado, carregará e retornará
	 * @param string $model Nome do model
	 * @return booelan or object
	 */
	public function getModel($model){
		$model = Inflector::lowerCamelize($model);
		if($this->loadModel($model))
			return $this->loaded[$model];
		return false;
	}
	
	/**
	 * Seta o nome da tabela
	 * @param string $table Nome da tabela
	 * @return this object
	 */
	public function table($table){
		$this->table = $table;
		return $this;
	}
	
	/**
	 * Seta o nome da tabela [alias $this->table()]
	 * @param string $table Nome da tabela
	 * @return this object
	 */
	public function from($table){
		return $this->table($table);
	}
	
	/**
	 * Seta os campos de uma consulta SQL
	 * @param array $fields [optional] Campos a ser setado 
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function fields($fields = null, $allParams = false){
		if($allParams === true){
			if(array_key_exists('fields', $fields))
				$fields = $fields['fields'];
		}
		
		if($fields == null)
			$this->params['fields'] = '*';
		else{
			if(is_array($fields))
				$this->params['fields'] = $fields;
			else{
				$this->params['fields'] = array($fields);
			}
		}
		return $this;
	}
	
	/**
	 * Condições de uma query a ser executada
	 * @param array $conditions [optional] Condições a ser setado
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function conditions($conditions = array(), $allParams = false){
		if($allParams === true){
			if(array_key_exists('conditions', $conditions))
				$conditions = $conditions['conditions'];
		}
		$this->params['conditions'] = array_merge($this->conditions, (array)$conditions);
		return $this;
	}
	
	/**
	 * Condições de uma query a ser executada (Alian Model::conditions())
	 * @param array $conditions [optional] Condições a ser setado
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function where($conditions = array(), $allParams = false){
		return $this->conditions($conditions, $allParams);
	}
	
	/**
	 * Seta o key do result (Serve para gerar o key da array de saída de uma consulta)
	 * @param string $keyResult Nome do key
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function keyResult($keyResult, $allParams = false){
		if($allParams === true){
			if(array_key_exists('keyResult', $keyResult))
				$keyResult = $keyResult['keyResult'];
		}
		$this->params['keyResult'] = $keyResult;
		return $this;
	}
	
	/**
	 * Join para a query
	 * @param string $join Nome da tabela
	 * @param array $conditions [optional] Condições para a join (primeiro parametro é uma condição entre tabelas e a segunda é condições normais)
	 * @param string $type [optional] Tipo da join (LEFT JOIN, RIGHT JOIN, JOIN)
	 * @return this object
	 */
	public function join($join, $conditions = array(), $type = 'join'){
		if($join != null){
			if($type != 'join' && $type != 'leftJoin' && $type != 'rightJoin' && $type != 'innerJoin')
				$type = 'join';
			if(!is_array($this->params[$type]))
				$this->params[$type] = array();
			
			if(!is_array($conditions))
				$conditions = array();
			$this->params[$type][$join] = $conditions;
		}
		return $this;
	}
	
	/**
	 * Inner join para a query
	 * @param string $join Nome da tabela
	 * @param array $conditions [optional] Condições para a join (primeiro parametro é uma condição entre tabelas e a segunda é condições normais)
	 * @return this object
	 */
	public function innerJoin($join, $conditions = array()){
		return $this->join($join, $conditions, 'innerJoin');
	}
	
	/**
	 * Left join para a query
	 * @param string $join Nome da tabela
	 * @param array $conditions [optional] Condições para a join (primeiro parametro é uma condição entre tabelas e a segunda é condições normais)
	 * @return this object
	 */
	public function leftJoin($join, $conditions = array()){
		return $this->join($join, $conditions, 'leftJoin');
	}
	/**
	 * Right join para a query
	 * @param string $join Nome da tabela
	 * @param array $conditions [optional] Condições para a join (primeiro parametro é uma condição entre tabelas e a segunda é condições normais)
	 * @return this object
	 */
	public function rightJoin($join, $conditions = array()){
		return $this->join($join, $conditions, 'rightJoin');
	}
	
	/**
	 * Dados para um insert/update
	 * @param array $data Dados a ser setado 
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function data($data, $allParams = false){
		if($data != null){
			if(!is_array($data))
				$data = array();
			if($allParams === true){
				if(array_key_exists('data', $data))
					$data = $data['data'];
			}
			$this->params['data'] = (array)$data;
		}
		return $this;
	}
	
	/**
	 * Dados para um insert/update (Alian Model::data())
	 * @param array $data Dados a ser setado 
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function values($data, $allParams = false){
		return $this->data($data, $allParams);
	}
	
	/**
	 * Order by para a query a ser executada
	 * @param string $order [optional] Ordenar por:
	 * @param string $direction [optional] Direção da ordem (ASC, DESC)
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function orderBy($order = null, $direction = 'ASC', $allParams = false){
		if($allParams === true){
			if(array_key_exists('orderBy', $order))
				$order = $order['orderBy'];
		}
		if($order != null){
			$direction = strtoupper($direction);
			if($direction != 'ASC' && $direction != 'DESC' && $direction != null)
				$direction = 'ASC';
			if(!is_array($order)){
				$this->params['orderBy'] = trim((string)$order);
			}else{
				foreach($order as $key => $value){
					$order[$key] = trim((string)$value);
					if(trim((string)$value) == null)
						unset($order[$key]);
				}
				$this->params['orderBy'] = implode(', ', $order);
			}
			$this->params['orderBy'] .= ($direction != null) ? (' ' . $direction) : '';
		}else{
			if(trim((string)$this->orderBy) != null){
				$this->params['orderBy'] = trim((string)$this->orderBy);
			}
		}
		return $this;
	}
	
	/**
	 * Group by para a query a ser executada
	 * @param string or array $group [optional] Agrupar por:
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return this object
	 */
	public function groupBy($group = null, $allParams = false){
		if($group != null){
			if($allParams === true){
				if(array_key_exists('groupBy', $group))
					$group = $group['groupBy'];
			}
			if(!is_array($group)){
				$this->params['groupBy'] = trim((string)$group);
			}else{
				foreach($group as $key => $value){
					$group[$key] = trim((string)$value);
					if(trim((string)$value) == null)
						unset($group[$key]);
				}
				$this->params['groupBy'] = implode(', ', $group);
			}
		}
		return $this;
	}
	
	/**
	 * Limite de registros da query
	 * @param numeric or string $limit [optional] Numero de registro a ser limitado
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return boolean
	 */
	public function limit($limit = null, $allParams = false){
		if($allParams === true){
			if(array_key_exists('limit', $limit))
				$limit = $limit['limit'];
		}
		if($limit != null){
			if(!is_array($limit)){
				$this->params['limit'] = trim($limit);
			}else{
				foreach($limit as $key => $value){
					$limit[$key] = trim($value);
					if(trim($value) == null)
						unset($limit[$key]);
				}
				$this->params['limit'] = implode(', ', $limit);
			}
		}else{
			if(trim($this->limit) != null){
				$this->params['limit'] = trim($this->limit);
			}
		}
		return $this;
	}
	
	/**
	 * Filtra os parametros
	 * @param array $params Parametros a ser filtrado
	 * @return array
	 */
	public function filterParams($params){
		$this->conditions($params, true);
		$this->fields($params, true);
		$this->data($params, true);
		$this->orderBy($params, null, true);
		$this->groupBy($params, true);
		$this->limit($params, true);
		$this->keyResult($params, true);
		if(is_array($params['join'])){
			if(count($params['join'] > 0)){
				foreach($params['join'] as $key => $value){
					$this->join($key, $value);
				}
			}
		}else{
			$this->join($params['join']);
		}
		if(is_array($params['innerJoin'])){
			if(count($params['innerJoin'] > 0)){
				foreach($params['innerJoin'] as $key => $value){
					$this->innerJoin($key, $value);
				}
			}
		}else{
			$this->innerJoin($params['innerJoin']);
		}
		if(is_array($params['leftJoin'])){
			if(count($params['leftJoin'] > 0)){
				foreach($params['leftJoin'] as $key => $value){
					$this->leftJoin($key, $value);
				}
			}
		}else{
			$this->leftJoin($params['leftJoin']);
		}
		if(is_array($params['rightJoin'])){
			if(count($params['rightJoin'] > 0)){
				foreach($params['rightJoin'] as $key => $value){
					$this->rightJoin($key, $value);
				}
			}
		}else{
			$this->rightJoin($params['rightJoin']);
		}
		return $this->params;
	}
	
	/**
	 * Faz um insert
	 * @param array $data Dados a ser inserido
	 * @return boolean
	 */
	public function insert($data){
		return $this->instance->create($this->table, (array)$data, $this->database);
	}
	
	/**
	 * Faz um insert
	 * @param array $data Dados a ser inserido
	 * @return boolean
	 */
	public function create($data){
		return $this->instance->create($this->table, (array)$data, $this->database);
	}
	
	/**
	 * Faz um update
	 * @param numeric or boolean $id Id do registro a ser feito o update (se number: criará automáticamente a condição para a primary key, se false: não criará a condição automáticamente)
	 * @param array $params [optional] Parametros do update
	 * @param array $data [optional] Dados do update
	 * @return boolean
	 */
	public function update($id, $params = array(), $data = array()){
		$this->clearParams();
		if($id !== false)
			$pk = array($this->primaryKey => $id);
		else
			$pk = array();
        $params = array_merge(
            					array_merge($this->paramsDefaults, array('conditions' => $pk))
            				  , (array)$params
       			);
		$params['data'] = array_merge($params['data'], (array)$data);		
        return $this->instance->update($this->table, $this->filterParams($params), $this->database);
    }
	
	/**
	 * Faz um delete
	 * @param numeric or boolean $id Id do registro a ser deletado
	 * @param array $params [optional] Parametros para o delete
	 * @return boolean
	 */
	public function delete($id, $params = array()){
		$this->clearParams();
		if($id !== false)
			$pk = array($this->primaryKey => $id);
		else
			$pk = array();
        $params = array_merge(
            					array_merge($this->paramsDefaults, array('conditions' => $pk))
							  , (array)$params
        		);
        return $this->instance->delete($this->table, $this->filterParams($params), $this->database);
	}
	
	/**
	 * Faz um select
	 * @param array $params [optional] Parametros para o select
	 * @param numeric $id [optional] Id do registro a ser buscado
	 * @return array
	 */
	public function read($params = array(), $id = false){
		$this->clearParams();
		if($id !== false)
			$pk = array($this->primaryKey => $id);
		else
			$pk = array();
        $params = array_merge(
            					array_merge($this->paramsDefaults, array('conditions' => $pk))
							  , (array)$params
        		);
        $return = $this->instance->read($this->table, $this->filterParams($params), $this->database);
        $this->clearParams();
        return $return;
	}
	
	/**
	 * Faz um select (Alias Model::reade())
	 * @param array $params [optional] Parametros para o select
	 * @param numeric $id [optional] Id do registro a ser buscado
	 * @return array
	 */
	public function select($params = array(), $id = false){
		return $this->read($params, $id);
	}
	
	/**
	 * Verefica se existe um registro
	 * @param numeric $id Id do registro a ser buscado
	 * @return boolean
	 */
	public function exists($id){
		$this->clearParams();
		$params = array_merge($this->paramsDefaults, array(
															  'conditions' => array(
																				$this->primaryKey => $id
																			)
															, 'fields' => array(1)
															)
									);
		$params = $this->filterParams($params);
		$row = $this->read($params);
		return (count($row) > 0) ? true : false;
	}
	
	/**
	 * Limpa os parametros setados
	 * @return boolean
	 */
	public function clearParams(){
		$this->params = $this->paramsDefaults;
		return true;
	}
	
	/**
	 * Executa um select e retorna a quantidade de registro retornado da consulta
	 * @param array $params [optional] Parametros a ser usado na consulta 
	 * @param numeric $id [optional] Id do registro a ser buscado
	 * @return 
	 */
	public function count($params = array(), $id = false){
		$this->clearParams();
		if($id !== false)
			$pk = array($this->primaryKey => $id);
		else
			$pk = array();
		$params = array_merge(
						array_merge($this->paramsDefaults, array('conditions' => $pk))
							  , (array)$params
				);
        return $this->instance->count($this->table, $this->filterParams($params), $this->database);
	}
	
	/**
	 * Executa um select
	 * @return array
	 */
	public function executeRead(){
		$result = $this->instance->read($this->table, $this->params, $this->database);
		$this->clearParams();
		return $result;
	}
	
	/**
	 * Executa um select
	 * @return array
	 */
	public function executeSelect(){
		$result = $this->instance->read($this->table, $this->params, $this->database);
		$this->clearParams();
		return $result;
	}
	
	/**
	 * Executa um update
	 * @return boolean
	 */
	public function executeUpdate(){
		$result = $this->instance->update($this->table, $this->params, $this->database);
		$this->clearParams();
		return $result;
	}
	
	/**
	 * Executa um delete
	 * @return boolean
	 */
	public function executeDelete(){
		$result = $this->instance->delete($this->table, $this->params, $this->database);
		$this->clearParams();
		return $result;
	}
	
	/**
	 * Executa um insert
	 * @return boolean
	 */
	public function executeInsert(){
		$result = $this->instance->create($this->table, $this->params, $this->database, true);
		$this->clearParams();
		return $result;
	}
	
	/**
	 * Executa uma paginação
	 * @return array or boolean (Resultado do select)
	 */
	public function executePaginate(){
		$params = $this->params;
		$this->clearParams();
		return $this->paginate($params);
	}
	
	/**
	 * Retorna a descrição de uma tabela
	 * @param string $table [optional] Nome da tabela
	 * @return array
	 */
	public function describe($table = null){
		if($table == null)
			$table = $this->table;
		return $this->instance->describe($table, $this->database);
	}
	
	/**
	 * Retorna uma array com os campos de uma tabela, com o valor null
	 * @param string $table [optional] Nome da tabela
	 * @return array
	 */
	public function getArrayFieldsEmpty($table = null){
		if($table == null)
			$table = $this->table;
		return $this->instance->getArrayFieldsEmpty($table, $this->database);
	}
	
	/**
	 * Lista as tabetas existente em uma database
	 * @param string $database [optional] Nome da database
	 * @return array
	 */
	public function listTables($database = null){
		return $this->instance->listTables($database);
	}
	
	/** 
	 * Lista as tabetas existente em uma database
	 * @param string $database [optional] Nome da database
	 * @return array
	 */
	public function showTables($database = null){
		return $this->instance->listTables($database);
	}
	
	/**
	 * Lista as batabases existentes
	 * @return array
	 */
	public function listDatabases(){
		return $this->instance->listDatabases();
	}
	
	/**
	 * Lista as batabases existentes
	 * @return array
	 */
	public function showDatabases(){
		return $this->instance->listDatabases();
	}
	
	/**
	 * Procura e retorna todas as linhas de dados, criando uma condição com o field
	 * @param string $field Campo para a condição
	 * @param string $value Valor para a condição
	 * @param array $conditions Condições acrecentais
	 * @param array $params Outros parametros
	 */
	public function findAllBy($field = 'id', $value = null, $conditions = array(), $params = array()){
		if(isset($params['conditions']))
			$params['conditions'][$field] = $value;
		else
			$params = array_merge(array('conditions' => array_merge(array($field => $value), (array)$conditions)), (array)$params);
		return $this->read($params);
	}
	
	/**
	 * Procura e retorna somente uma linha de dados, criando uma condição com o field
	 * @param string $field Campo para a condição
	 * @param string $value Valor para a condição
	 * @param array $conditions Condições acrecentais
	 * @param array $params Outros parametros
	 */
	public function findBy($field = 'id', $value = null, $conditions = array(), $params = array()){
		if(!isset($params['limit']))
			$params['limit'] = 1;
		if(isset($params['conditions']))
			$params['conditions'][$field] = $value;
		else
			$params = array_merge(array('conditions' => array_merge(array($field => $value), (array)$conditions)), (array)$params);
		$result = $this->read($params);
		if(isset($result[0]))
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Method mágico
	 */
	public function __call($method, $params){
		$params = array_merge($params, array(null, array(), array()));
		if(preg_match("/findAllBy(.*)/", $method, $field)){
			$field = Inflector::lowerCamelize($field[1]);
			return $this->findAllBy($field, $params[0], $params[1], $params[2]);
		}elseif(preg_match("/findBy(.*)/", $method, $field)){
			$field = Inflector::lowerCamelize($field[1]);
			return $this->findBy($field, $params[0], $params[1], $params[2]);
		}else{
			trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'() in '.__FILE__.' on line '.__LINE__, E_USER_WARNING);
		}
	}
	
	/**
	 * seta os parâmetros para a páginação.
	 * @param array $params
	 * @return this object
	 */
	public function paginationParams($params = array()){
		$this->params = array_merge($this->params, (array)$params);
		return $this;
	}
	
	/**
	 * Executa um select com restrições para paginação
	 * @param array $params Parametros para o select e para a paginação
	 * @return array or boolean (Resultado do select)
	 */
	public function paginate($params = array()){
		$recordsInPage = (isset($params['recordsInPage']) && $params['recordsInPage'] != 0) ? $params['recordsInPage'] : $this->pagination['recordsInPage'];
		$page = (isset($params['page'])) ? $params['page'] : $this->pagination['page'];
		
		if($page === false)
			if($this->input->get('page'))
				$page = $this->input->get('page');
		
		$page = ($page != 0) ? $page : 1;
		settype($page, 'integer');
		$startRecord = ($page -1) * $recordsInPage;

		$totalRecords = $this->count(array_merge($params, array('fields' => 1)));

		$params['limit'] = $startRecord . ', ' . $recordsInPage;
		
		$data = $this->read($params);
		
		$this->pagination = array(
							  'totalRecords' => $totalRecords
							, 'totalRecordsInPage' => $this->getNumRows()
							, 'totalPages' => ceil($totalRecords / $recordsInPage)
							, 'recordsInPage' => $recordsInPage
							, 'page' => $page
							, 'startRecord' => $startRecord
						);
		return $data;
	}
	
	/**
	 * Salva um registro no banco de dados
	 * @param array $data
	 */
	public function autoSave($data = array()){
		if($this->input->post()){
			$autoData = array();
			$describe = $this->describe();

			foreach($describe as $key => $value){
				if($this->input->post($key) !== false){
					$autoData[$key] = $this->input->post($key);
				}else{
					if($key == 'created' && ($value['type'] == 'datetime' || $value['type'] == 'date'))
						$created = now();
					if($key == 'modified' && ($value['type'] == 'datetime' || $value['type'] == 'date'))
						$modified = now();
				}
			}
			$data = array_merge($autoData, $data);
			
			$isInsert = false;
			$isUpdate = false;
			
			if(isset($data[$this->primaryKey]) && !is_null($data[$this->primaryKey])){
				$id = $data[$this->primaryKey];
				if($this->exists($id)) $isUpdate = true; else $isInsert = true;
			}else
				$isInsert = true;
			
			if($isInsert && isset($created))
				$data['created'] = $created;
			elseif($isUpdate && isset($modified))
				$data['modified'] = $modified;
			
			if($this->validate($data)){
				if($isInsert)
					return $this->insert($data);
				elseif($isUpdate)
					return $this->update($id, array(), $data);
			}else
				return false;
		}
		return false;
	}
	
	/**
	 * Seta a validação
	 * @return this object
	 */
	public function validates($validates){
		$this->validates = $validates;
		return $this;
	}
	
	/**
	 * Faz as validações
	 * @param $data
	 * @return boolean
	 */
	public function validate($data){
		$this->errorMessages = array();
		if(!is_array($data))
			return true;
		$defaults = array(
						  'required' => false
						, 'message' => null
					);
		foreach($this->validates as $field => $rules){
			if(!is_array($rules) || (is_array($rules) && isset($rules["rule"]))){
				$rules = array($rules);
			}
			foreach($rules as $rule){
				if(isset($this->errorMessages[$field]))
					break;
				if(!is_array($rule))
					$rule = array("rule" => $rule);
				$rule = array_merge($defaults, $rule);
				$required = !(array_key_exists($field, $data)) && $rule['required'];
				
				if($required)
					$this->errorMessages[$field] = is_null($rule['message']) ? $rule['rule'] : $rule['message'];
				elseif(array_key_exists($field, $data)){
					if(!$this->callValidationMethod($rule['rule'], $data[$field])){
						$this->errorMessages[$field] = is_null($rule['message']) ? $rule['rule'] : $rule['message'];
					}
				}
			}
		}
		return empty($this->errorMessages);
	}
	
	/**
	 * Chama um método para a validação
	 * @param array | string $params Nome do método a ser chamado e parâmetros
	 * @param string $value Valor a ser validado
	 * @return boolean
	 */
	public function callValidationMethod($params, $value){
		if(is_array($params)){
			if(isset($params[0]))
				$method = $params[0];
			else{
				$keys = array_keys($params);
				$method = array_shift($keys);
				if(is_array($params[$method])){
					$i = 1;
					foreach($params[$method] as $v){
						$params[$i] = $v;
						$i++; 
					}
					unset($params[$method]);
				}
			}
		}else
			$method = $params;
		
		$class = method_exists($this, $method) ? $this : 'Validation';
		
		if(is_array($params)){
			$params[0] = $value;
			ksort($params);
			if(method_exists($class, $method))
				return call_user_func_array(array($class, $method), $params);
			else
				trigger_error('Call to undefined method '.$class.'::'.$method.'() in '.__FILE__.' on line '.__LINE__, E_USER_ERROR);
		}else{
			if($class === 'Validation'){
				if(method_exists($class, $params))
					return Validation::$params($value);
				else
					trigger_error('Call to undefined method Validation::'.$method.'() in '.__FILE__.' on line '.__LINE__, E_USER_ERROR);
			}else
				return false;
		}
		return false;
	}
}