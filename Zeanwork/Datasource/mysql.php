<?php
/**
 * Contem a classe MysqlDatasource
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Datasource
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 235 $
 * @lastModified	$LastChangedDate: 2010-07-27 22:19:30 -0300 (Ter, 27 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */
 
/**
 * Class Datasource required
 */
Zeanwork::import(LIBS, 'datasource');

/**
 * MySql Datasource
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Datasource
 * @obs				Class required Datasource
 */
class MysqlDatasource extends Datasource {
	
	/**
	 * Estrutura da tabela
	 * @var array
	 */
    protected $schema = array();
	
	/**
	 * Lista das tabelas contidas no banco de dados.
	 * @var array
	 */
    protected $tables = array();
	
	/**
	 * Lista dos bancos de dados
	 * @var array
	 */
	protected $databases = array();

	/**
	 * Métodos de comparação utilizados nas SQLs.
	 * @var array
	 */
    protected $comparison = array("=", "<>", "!=", "<=", "<", ">=", ">", "<=>", "LIKE", "REGEXP");
	
	/**
	 * Métodos de lógica utilizados nas SQLs.
	 * @var array
	 */
    protected $logic = array("OR", "OR NOT", "||", "XOR", "AND", "AND NOT", "&&", "NOT");

	/**
	 * Armazena a query
	 * @var string
	 * @access private
	 */
	private $query;
	
	/**
	 * Armazena o resultado da query
	 * @var object
	 * @access private
	 */
	private $result;
	
	/**
	 * Armazena o total de linhas que resultou a query
	 * @var numeric
	 * @access private
	 */
	private $numRows = 0;
	
	/**
	 * Armazeda o total de linhas afetadas de um insert, update, delete, replace
	 * @var numeric
	 * @access private
	 */
	private $numRowsAffected = 0;
	
	/**
	 * A transação foi iníciada
	 * @var boolean
	 */
	protected $transactionStarted = false;
	
	/**
	 * Parametros padrão
	 * @var array
	 */
	public $paramsDefaults = array(
								      'table' 		=> null
									, 'conditions' 	=> array()
									, 'fields'		=> array()
									, 'data'		=> array()
									, 'join' 		=> array()
									, 'innerJoin'	=> array()
									, 'leftJoin' 	=> array()
									, 'rightJoin' 	=> array()
									, 'orderBy' 	=> null
									, 'groupBy' 	=> null
									, 'limit' 		=> null
									, 'keyResult' 	=> null
							);
	/**
	 * Conecta com o servidor de banco de dados
	 * @return boolean
	 */
	public function connect(){
		if(Database::getConfigure('persistent') == true)
			return @mysql_pconnect(Database::getHost(), Database::getUser(), Database::getPassword());
		else
			return @mysql_connect(Database::getHost(), Database::getUser(), Database::getPassword());
	}
	
	/**
	 * Desconecta do servidor de banco de dados
	 * @return boolean
	 */
	public function disconnect(){
		return mysql_close(oConn::getConn());
	}
	
	/**
	 * Retorna o último erro gerado pelo banco de dados
	 * @return string
	 */
	public function getError(){
		if(oConn::getConn())
			return mysql_error(oConn::getConn());
		else
			return mysql_error();
	}
	
	/**
	 * Executa um query
	 * @param string $query [optional] Query a ser executada
	 * @param string $database [optional] Databese onde será executada a query
	 * @param object $calledFrom [optional] Da onde foi executado esta query (debug_backtrace())
	 * @return boolean
	 */
	public function query($query = null, $database = null, $calledFrom = null){
		if($query != null)
			$this->setQuery($query);
		
		if($database != null){
			if(Database::getDatabaseSelected() !== $database){
				oConn::selectDatabase($database);
			}
		}else{
			if(Database::getDatabaseSelected() !== Database::getDatabase()){
				oConn::selectDatabase(Database::getDatabase());
			}
		}
		
		$this->result = mysql_query($this->getQuery());
		if($this->getResult()){
			//se é um select salva o total do resultado da query no $this->numRows
 			if(strstr(strtoupper($this->getQuery()), 'SELECT'))
 				$this->numRows =  @mysql_num_rows($this->getResult());
 				
			elseif(
					strstr(strtoupper($this->getQuery()), 'INSERT') || 
					strstr(strtoupper($this->getQuery()), 'UPDATE') || 
					strstr(strtoupper($this->getQuery()), 'DELETE') || 
					strstr(strtoupper($this->getQuery()), 'REPLACE')
				  )
				//se é um insert, update, delete ou replece salva o total do resultado da query no $this->numRowsAffected
				$this->numRowsAffected = @mysql_affected_rows(oConn::getConn());
			return true;
		}else{
			if(!is_array($calledFrom)) $calledFrom = debug_backtrace();
			echo Debugger::errorTrigger('SQL_ERROR', mysql_error(), $calledFrom[0]['file'], $calledFrom[0]['line'], $this->query);
			$this->numRows = 0;
			$this->numRowsAffected = 0;
			return false;
		}
		return false;
	}
	
	/**
	 * Seleciona um banco de dados
	 * @param string $database Nome do banco de dados
	 * @return boolean
	 */
	public function selectDb($database){
		return mysql_select_db($database, oConn::getConn());
	}
	
	/**
	 * Executa um fecth no resultado da consulta
	 * @param string $position [optional] Coluda de um resultado
	 * @return array or string
	 */
	public function fetch($who = null){
		if($this->getResult())
					$row = mysql_fetch_assoc($this->getResult());
		if(is_null($who))
			return $row;
		else
			return $row[$who];
	}
	
	public function setCharset($charset){
		return $this->query('SET NAMES ' . $charset);
	}

	/**
	 * Retorna o numero de linhas afetadas de um delete/update
	 * @return numeric
	 */
	public function getNumRows(){
		return $this->numRows;
	}
	
	/**
	 * Retorna o numero de linhas afetadas de um delete/update
	 * @return numeric
	 */
	public function getNumRowsAffected(){
		return $this->numRowsAffected;
	}
	
	/**
	 * Retorna a query executada ou ainda não executada
	 * @return string
	 */
	public function getQuery(){
	    return $this->query;
	}
	
	/**
	 * Seta uma query a ser executada
	 * @param string $query
	 * @return boolean
	 */
	public function setQuery($query){
	    $this->query = $query;
	}
	
	/**
	 * Retorna o result da query
	 * @return object
	 */
	public function getResult(){
	    return $this->result;
	}
	
	/**
	 * Retorna o ultimo id que foi feito o insert
	 * @return numeric
	 */
	public function getInsertId() {
        return mysql_insert_id(oConn::getConn());
    }
	
	/**
	 * Inícia uma transação SQL
	 * @param string $database [optional] Nome da database
	 * @return boolean
	 */
	public function begin($database = null){
        return $this->transactionStarted = $this->query('START TRANSACTION', $database);
    }
	
	/**
	 * Finaliza e salva uma transação SQL
	 * @param string $database [optional] Nome da database
	 * @return boolean
	 */
    public function commit($database = null){
        $this->transactionStarted = !$this->query('COMMIT', $database);
        return !$this->transactionStarted;
    }
	 
	/**
	 * Finaliza e não salva uma transação SQL
	 * @param string $database [optional] Nome da database
	 * @return boolean
	 */
    public function rollback($database = null){
        $this->transactionStarted = !$this->query('ROLLBACK', $database);
        return !$this->transactionStarted;
    }
	
	/**
	 * Renderiza um SQL
	 * @param string $type Tipo do SQL (Insert, Update, Delete, Select)
	 * @param array $data [optional] Dados
	 * @param string $database [optional] Database
	 * @return string
	 */
	public function renderSql($type, $data = array(), $database = null){
		$table = $data['table'];
		$conditions = null;
		$fields = null;
		$data_ = null;
		$join = null;
		$orderBy = null;
		$groupBy = null;
		$limit = null;
		
		
		if($conditions = $this->sqlConditions($table, $data['conditions'], 'AND', $database)){
			$conditions = 'WHERE ' . (string)$conditions;
		}
		
		if(is_array($data['fields'])){
			foreach($data['fields'] as  $key => $value){
				$data['fields'][$key] = $this->filterField($value);
			}
			$fields = implode(', ', $data['fields']);
		}else{
			$fields = $data['fields'];
		}
		
		if(is_array($data['data'])){
			$schema = $this->describe($table, $database);
			foreach($data['data'] as $field => $value){
				$column = isset($schema[$this->filterField($field)]) ? $schema[$this->filterField($field)]['type'] : null;
				$data_[] = trim((string)$this->filterField($field)) . ' = ' . $this->value($value, $column);
			};
			if(count($data_) > 0)
				$data_ = implode(', ', (array)$data_);
		}else{
			$data_ = $data['data'];
		}
		
		if($data['join'] != null){
			if(is_array($data['join'])){
				foreach($data['join'] as $key => $value){
					if($key != null){
						$join .= 'JOIN ' . Database::getConfigure('prefix') . $key . ' ';
						if(is_array($value)){
							$conditionsJoin = $this->sqlConditions($key, $value, 'AND', $database, true);
							
							if($conditionsJoin){
								$join .= 'ON ' . $conditionsJoin . ' ';
							}
						}else{
							if($value != null){
								$join .= 'ON ' . $value . ' ';
							}
						}
					}
				}
			}
		}
		
		if($data['innerJoin'] != null){
			if(is_array($data['innerJoin'])){
				foreach($data['innerJoin'] as $key => $value){
					if($key != null){
						$join .= 'INNER JOIN ' . Database::getConfigure('prefix') . $key . ' ';
						if(is_array($value)){
							$conditionsJoin = $this->sqlConditions($key, $value, 'AND', $database, true);
							
							if($conditionsJoin){
								$join .= 'ON ' . $conditionsJoin . ' ';
							}
						}else{
							if($value != null){
								$join .= 'ON ' . $value . ' ';
							}
						}
					}
				}
			}
		}
		
		if($data['leftJoin'] != null){
			if(is_array($data['leftJoin'])){
				foreach($data['leftJoin'] as $key => $value){
					if($key != null){
						$join .= 'LEFT JOIN ' . Database::getConfigure('prefix') . $key . ' ';
						if(is_array($value)){
							$conditionsJoin = $this->sqlConditions($key, $value, 'AND', $database, true);
							
							if($conditionsJoin){
								$join .= 'ON ' . $conditionsJoin . ' ';
							}
						}else{
							if($value != null){
								$join .= 'ON ' . $value . ' ';
							}
						}
					}
				}
			}
		}
		
		if($data['rightJoin'] != null){
			if(is_array($data['rightJoin'])){
				foreach($data['rightJoin'] as $key => $value){
					if($key != null){
						$join .= 'RIGHT JOIN ' . Database::getConfigure('prefix') . $key . ' ';
						if(is_array($value)){
							$conditionsJoin = $this->sqlConditions($key, $value, 'AND', $database, true);
							
							if($conditionsJoin){
								$join .= 'ON ' . $conditionsJoin . ' ';
							}
						}else{
							if($value != null){
								$join .= 'ON ' . $value . ' ';
							}
						}
					}
				}
			}
		}
		
		if($data['orderBy'] != null)
			$orderBy = 'ORDER BY ' . $this->filterField(Util::antInjection($data['orderBy']));
		
		if($data['groupBy'] != null)
			$groupBy = 'GROUP BY ' . $this->filterField(Util::antInjection($data['groupBy']));
		
		if($data['limit'] != null)
			$limit = 'LIMIT ' . Util::antInjection($data['limit']);
		
		switch(strtolower($type)){
			case 'select':
				return 'SELECT ' . $fields . ' FROM ' . Database::getConfigure('prefix') . $table . ' ' . $join . ' ' . $conditions . ' ' . $groupBy . ' ' . $orderBy . ' ' . $limit;
			case 'delete':
				return 'DELETE FROM ' . Database::getConfigure('prefix') . $table . ' '. $conditions . ' ' . $orderBy . ' ' . $limit;
			case 'insert':
				return 'INSERT INTO ' . Database::getConfigure('prefix') . $table . ' SET ' . $data_ . ' ';
			case 'update':
				return 'UPDATE ' . Database::getConfigure('prefix') . $table . ' SET ' . $data_ . ' ' . $conditions . ' ' . $orderBy  . ' ' . $limit;
		}
	}
	
	/**
	 * Filtra um field para colocar o prifixo nas tabelas
	 * @param string $fieldFilter Field a ser filtrada
	 * @return string
	 */
	public function filterField($fieldFilter){
		$result = array();
		$arrFieldFilter = explode(',', $fieldFilter);
		foreach($arrFieldFilter as $fieldFilter){
			$fieldFilter = trim((string)$fieldFilter);
			$database = null;
			$table = null;
			$field = null;
			$result_ = null;
			if(preg_match("((.*)[(.)](.+))", $fieldFilter, $respReg)){
				if(preg_match("((.*)[(.)](.+))", $respReg[1], $respReg_)){
					$database = $respReg_[1]; 
					$table = $respReg_[2];
					
				}else{
					$table = $respReg[1];
				}
				$field = $respReg[2];
				if($database != null)
					$result_ .= $database . '.';
				if($table != null)
					$result_ .= Database::getConfigure('prefix') . $table . '.';
			}else{
				$field .= $fieldFilter;
			}
			$result_ .= $field;
			$result[] = $result_;
		}
		return implode(', ', $result);
	}
	
	/**
	 * Gera as condiçoes SQL
	 * @param string $table Tabela a ser usada
	 * @param array $conditions Condições
	 * @param string $logical [optional] Logica a ser usada
	 * @param string $database [optional]  Database a ser usada
	 * @param boolean $conditionForJoin [optional] Esta condição é para uma join?
	 * @return string
	 */
	public function sqlConditions($table, $conditions, $logical = 'AND', $database = null, $conditionForJoin = false){
		if(is_array($conditions)){
			$sql = array();
			foreach($conditions as $key => $value){
				if(is_numeric($key)){
					if(is_string($value)){
						$sql[] = $value;
						$value = Util::antInjection($value);
					}else{
						$sql[] = $this->sqlConditions($table, $value, $logical, $database);
					}
				}else{
					if(in_array($key, $this->logic)){
						$sql[] = "(" . $this->sqlConditions($table, $value, strtoupper($key), $database) . ")";
					}elseif(is_array($value)){
						foreach($value as $k => $v){
							if($conditionForJoin !== true)
								$value[$this->filterField($k)] = $this->value($v, null);
							else
								$value[$this->filterField($k)] = $this->filterField($v);
                        }
						if(preg_match("/([\w_]+) (BETWEEN)/", $key, $regex)){
							$condition = $regex[1] . " BETWEEN " . join(" AND ", $value);
						}else{
							$condition = $this->filterField($key) . " IN (" . join(",", $value) . ")";
						}
						$sql[] = $condition;
					}else{
						$originalKey = $key;
						$comparison = "=";
						if(preg_match("/([\w_]+) (" . join("|", $this->comparison) . ")/", $key, $regex)){
							list($regex, $key, $comparison) = $regex;
						}
						$key = str_replace($comparison, null, $originalKey);
						if($conditionForJoin !== true)
							$value = $this->value($value, $this->fieldType($table, $key, $database));
						else
							$value = $this->filterField($value);
						$sql[] = $this->filterField($key) . ' ' . $comparison . ' ' . $value;
					}
				}
			}
			$sql = join(" {$logical} ", $sql);
		}else{
			$sql = $conditions;
		}
		return $sql;
	}
	
	/**
	 * Coloca aspas ou não
	 * @param object $value Valor
	 * @param string $column [optional] Coluna
	 * @return string
	 */
	public function value($value, $column = null){
		switch($column){
			case 'boolean':
				if($value === true){
					return '1';
				}elseif($value === false){
					return '0';
				}else{
					return !empty($value) ? '1' : '0';
				}
			case 'integer':
			case 'float':
				if($value === '' or is_null($value)){
					return 'NULL';
				}elseif(is_numeric($value)){
					return $value;
				}
			default:
				if(is_null($value)){
					return 'NULL';
				}
			return "'" . mysql_real_escape_string($value, oConn::getConn()) . "'";
		}
	}
	
	/**
	 * Retorna o tipo de uma coluda
	 * @param string $table Nome da tabela
	 * @param string $field Campo da tabela
	 * @param string $database [optional] Database
	 * @return string
	 */
	public function fieldType($table, $field, $database = null){
		if($database != null){
			if(Database::getDatabaseSelected() !== $database){
				oConn::selectDatabase($database);
			}
		}else{
			if(Database::getDatabaseSelected() !== Database::getDatabase()){
				oConn::selectDatabase(Database::getDatabase());
			}
		}
		if(isset($this->schema[Database::getDatabaseSelected()]) && isset($this->schema[Database::getDatabaseSelected()][Database::getConfigure('prefix') . $table]) && isset($this->schema[Database::getDatabaseSelected()][Database::getConfigure('prefix') . $table][$field])){
			return $this->schema[Database::getDatabaseSelected()][Database::getConfigure('prefix') . $table][$field]['type'];
		}
		return null;
	}
	
	/**
	 * Retorna a descrição de uma tabela
	 * @param string $table Nome da tabela
	 * @param string $database [optional] Nome do banco de dados
	 * @return array
	 */
	public function describe($table, $database = null){
		if($database != null){
			if(Database::getDatabaseSelected() !== $database){
				oConn::selectDatabase($database);
			}
		}else{
			if(Database::getDatabaseSelected() !== Database::getDatabase()){
				oConn::selectDatabase(Database::getDatabase());
			}
		}
		if(!(array_key_exists(Database::getDatabaseSelected(), $this->schema)) || !array_key_exists(Database::getConfigure('prefix') . $table, $this->schema[Database::getDatabaseSelected()])){
			if(!$this->query('SHOW COLUMNS FROM ' . Database::getConfigure('prefix') . $table))
				return false;
			
			$columns = $this->fetchAll();
			$schema = array();
			foreach($columns as $column){
				$schema[$column['Field']] = array(
												  'type' => $this->column($column['Type'])
												, 'null' => $column['Null'] == 'YES' ? true : false
												, 'default' => $column['Default']
												, 'key' => $column['Key']
												, 'extra' => $column['Extra']
												, 'limit' => $this->column($column['Type'], true)
				);
			}
			$this->schema[Database::getDatabaseSelected()][Database::getConfigure('prefix') . $table] = $schema;
		}
		return $this->schema[Database::getDatabaseSelected()][Database::getConfigure('prefix') . $table];
	}
	
	/**
	 * Retorna uma array com os campos de uma tabela, com o valor null
	 * @param string $table Nome da tabela
	 * @param string $database [optional] Nome do banco de dados
	 * @return array
	 */
	public function getArrayFieldsEmpty($table, $database = null){
		$describe = $this->describe($table, $database);
		$arr = array();
		foreach($describe as $key => $value){
			$arr[$key] = null;
		}
		return $arr;
	}
	
	/**
	 * Retorna o tipo da coluda ou o limite do mesmo
	 * @param string $column Nome da coluna
	 * @param boolean $getLimit Retornar somente o limite do campo
	 * @return string
	 */
	public function column($column, $getLimit = false){
		preg_match("/([a-z]*)\(?([^\)]*)?\)?/", $column, $type);
		list($column, $type, $limit) = $type;
		if($getLimit === true)
			return $limit;
		if(in_array($type, array('date', 'time', 'datetime', 'timestamp'))){
		    return $type;
		}elseif(($type == 'tinyint' && $limit == 1) || $type == 'boolean'){
		    return 'boolean';
		}elseif(strstr($type, 'int')){
		    return 'integer';
		}elseif(strstr($type, 'char') || $type == 'tinytext'){
		    return 'string';
		}elseif(strstr($type, 'text')){
		    return 'text';
		}elseif(strstr($type, 'blob') || $type == 'binary'){
		    return 'binary';
		}elseif(in_array($type, array('float', 'double', 'real', 'decimal'))){
		    return 'float';
		}elseif($type == 'enum' || $type = 'set'){
		    return "{$type}($limit)";
		}
	}
	/**
	 * Verifica se a última consulta possui resultados
	 * @return boolean
	 */
	public function hasResult(){
		return is_resource($this->result);
	}
	
	/**
	 * Retorna todos os resultados de uma consulta SQL
	 * @param string $keyResult [optional] Key para o array
	 * @return mixed
	 */
	public function fetchAll($keyResult = null){
		if($this->hasResult()){
			$results = array();
			while($result = $this->fetch()){
				if($keyResult != null && array_key_exists($keyResult, $result))
					$results[$result[$keyResult]] = $result;
				else
					$results[] = $result;
			}
			return $results;
		}
		return null;
	}
	
	/**
	 * Conta registros no banco de dados
	 * @param string $table [optional] Nome da tabela
	 * @param array $params [optional] Parametros para a consulta
	 * @param string $database [optional] Database
	 * @return numeric
	 */
	public function count($table = null, $params = array(), $database = null){
		$params = array_merge(
							  array_merge($this->paramsDefaults, array('table' => $table))
							, (array)$params
				);
		if(is_array($params['fields'])){
			$i = 0;
			foreach($params['fields'] as $key => $value){
				$i = $i+1;
				if($i != 1)
					unset($params['fields'][$key]);
			}
			foreach($params['fields'] as $key => $value){
				$params['fields'] = $params['fields'][$key];
			}
		}
		
		$params['fields'] = 'COUNT(' . $this->filterField($params['fields']) . ') AS count';
		$query = $this->renderSql('select', $params);
		$this->query($query, $database);
		$results = $this->fetchAll();
		if(isset($results[0]))
			return $results[0]['count'];
		else
			return 0;
	}
	
	/**
	 * Deleta registros do bando
	 * @param string $table [optional] Nome da tabela
	 * @param array $params [optional] Parametros
	 * @param string $database [optional] Database
	 * @return numeric
	 */
	public function delete($table = null, $params = array(), $database = null){
		$params = array_merge(
							  array_merge($this->paramsDefaults, array('table' => $table))
							, (array)$params
				);
		$query = $this->renderSql('delete', $params);
		$this->query($query, $database);
		return $this->getNumRowsAffected();
	}
	
	/**
	 * Atualiza registros no banco
	 * @param string $table [optional] Nome da tabela
	 * @param array $params [optional] Parametros
	 * @param string $database [optional] Database
	 * @return numeric
	 */
	public function update($table = null, $params = array(), $database = null){
		$params = array_merge(
							  array_merge($this->paramsDefaults, array('table' => $table))
							, (array)$params
				);
		$query = $this->renderSql('update', $params);
		$this->query($query, $database);
		return $this->getNumRowsAffected();
	}
	
	/**
	 * Faz um select
	 * @param string $table [optional] Nome da tabela
	 * @param array $params [optional] Parametros
	 * @param string $database [optional] Database
	 * @return array com os resultados da consulta
	 */
	public function read($table = null, $params = array(), $database = null){
		$params = array_merge(
							  array_merge($this->paramsDefaults, array('table' => $table))
							, (array)$params
				);
		$query = $this->renderSql('select', $params);
		$this->query($query, $database);
		
		if(array_key_exists('keyResult', $params))
			$keyResult = $params['keyResult'];
		else
			$keyResult = null;
		return $this->fetchAll($keyResult);
	}
	
	/**
	 * Cria registros no banco
	 * @param string $table [optional] Nome da tabela
	 * @param array $data [optional] Dados
	 * @param string $database [optional] Database
	 * @param boolean $allParams [optional] Se é todos os parametros
	 * @return numeric
	 */
	public function create($table = null, $data = array(), $database = null, $allParams = false){
		$params = array_merge($this->paramsDefaults, array('table' => $table));
		if($allParams !== true)
			$params['data'] = array_merge((array)$params['data'], (array)$data);
		else{
			$params = array_merge($params, $data);
		}
		$query = $this->renderSql('insert', $params);
		$this->query($query, $database);
		return $this->getNumRowsAffected();
	}
	
	/**
	 * Lista as tabelas
	 * @param string $database [optional] Nome do banco de dados
	 * @return array
	 */
	public function listTables($database = null){
		if(empty($this->tables)){
			$tables = $this->query('SHOW TABLES', $database);
			
			while($table = mysql_fetch_array($this->result)){
				$this->tables[] = $table[0];
			}
		}
		return $this->tables;
	}
	
	/**
	 * Lista os bancos de dados
	 * @return array
	 */
	public function listDatabases(){
		if(empty($this->databases)){
			$databases = $this->query('SHOW DATABASES');
			
			while($database = mysql_fetch_array($this->result)){
				$this->databases[] = $database[0];
			}
		}
		return $this->databases;
	}
}