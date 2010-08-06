<?php
/**
 * Contem a classe TableHelper
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Helpers
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 153 $
 * @lastModified	$LastChangedDate: 2010-04-07 21:04:50 -0300 (Qua, 07 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class Html required
 */
Zeanwork::import(Zeanwork::pathTreated('Helper'), 'html');

/**
 * Criar o método de geração de Tabelas para HTML, faz a construção da tabela 
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Helpers
 * @ex 
 * $Table = new TableHelper();
 * $Table->start(900,'idTabela',0,'classTabela');
 * $Table->setClassTitle('classTitulo');
 * $Table->setClassLine('classCssTr', 'classCssTr2');
 * $Table->addColumn('Titulo da primeira coluna', '500px');
 * $Table->addColumn('Titulo da segunda coluna', '400px');
 * $Table->addLine('id_TR_1', 'coluna 1 - lina 1', 'coluna 2 - lina 1');
 * $Table->addLine('id_TR_2', 'coluna 1 - lina 2', 'coluna 2 - lina 2');
 * $Table->printTable();
 */
class TableHelper extends HtmlHelper {
	
	/**
	 * Helpers dependentes
	 * @var array
	 */
	var $helpers = array();
	
	/**
	 * Armazena o id da tabela
	 * @var string
	 * @access private 
	 */
	private static $tableId = null;
	
	/**
	 * Armazena a largura da tabela
	 * @var string
	 * @access private 
	 */
	private static $tableWidth = null;
	
	/**
	 * Armazena o largura da borda da tabela
	 * @var numeric
	 * @access private 
	 */
	private static $tableBorder = 0;
	
	/**
	 * Armazena o spaço da celula
	 * @var numeric
	 * @access private 
	 */
	private static $tableCellspacing = 0;
	
	/**
	 * Armazena o padding da celula
	 * @var numeric
	 * @access private 
	 */
	private static $tableCellpadding = 0;
	
	/**
	 * Armazena a class (CSS) da tabela
	 * @var string
	 * @access private 
	 */
	private static $tableClass = null;
	
	/**
	 * Armazena a class (CSS) da primeira linha (será o título da tabela)
	 * @var string
	 * @access private 
	 */
	private static $classTitle = null;
	
	/**
	 * Armazena o título da coluda da tabela
	 * @var string
	 * @access private 
	 */
	private static $columnTitle = array();
	
	/**
	 * Armazena a largura da coluna da tabela
	 * @var string
	 * @access private 
	 */
	private static $coumnWidth = array();
	
	/**
	 * Armazena os valores para a linha da tabela
	 * @var string
	 * @access private 
	 */
	private static $line = array();
	
	/**
	 * Armazena o id da linha (TR)
	 * @var string
	 * @access private 
	 */
	private static $lineId = array();
	
	/**
	 * Armazena a class (CSS) da linha (TR)
	 * @var string
	 * @access private 
	 */
	private static $classLine = array();
	
	/**
	 * Armazena a class (CSS) da celula (TD)
	 * @var string
	 * @access private 
	 */
	private static $classCell = null;
	
	/**
	 * Armazena os scripts da linha da tabela
	 * @var string
	 * @access private 
	 */
	private static $scriptLine = null;
	
	/**
	 * Armazena true ou false, para exibir a barra de titulo de uma tabela
	 * @var boolean
	 * @access private 
	 */
	private static $titleDisplay = true;
	
	/**
	 * Retorna true para exibir o titulo da tabela ou false para não exibir o titulo da tabela	 *
	 * @see TableValues::$titleDisplay
	 */
	public function getTitleDisplay(){
	    return $this->titleDisplay;
	}
	
	/**
	 * Seta true ou false para exibir o titulo da tabela
	 * @param string $titleDisplay Título
	 * @see TableValues::$titleDisplay
	 */
	public function setTitleDisplay($titleDisplay){
	    $this->titleDisplay = $titleDisplay;
	}
	
	
	/**
	 * Sets $tableBorder.
	 *
	 * @param object $tableBorder
	 * @see TableValues::$tableBorder
	 */
	public function setTableBorder($tableBorder){
	    $this->tableBorder = $tableBorder;
	}
	
	/**
	 * Sets $tableCellpadding.
	 *
	 * @param object $tableCellpadding
	 * @see TableValues::$tableCellpadding
	 */
	public function setTableCellpadding($tableCellpadding){
	    $this->tableCellpadding=$tableCellpadding;
	}
	
	/**
	 * Sets $tableCellspacing.
	 *
	 * @param object $tableCellspacing
	 * @see TableValues::$tableCellspacing
	 */
	public function setTableCellspacing($tableCellspacing){
	    $this->tableCellspacing=$tableCellspacing;
	}
	
	/**
	 * Sets $tableClass.
	 *
	 * @param object $tableClass
	 * @see TableValues::$tableClass
	 */
	public function setTableClass($tableClass){
	    $this->tableClass = $tableClass;
	}
	
	/**
	 * Sets $tableId.
	 *
	 * @param object $tableId
	 * @see TableValues::$tableId
	 */
	public function setTableId($tableId){
	    $this->tableId = $tableId;
	}
	
	/**
	 * Sets $tableWidth.
	 *
	 * @param object $tableWidth
	 * @see TableValues::$tableWidth
	 */
	public function setTableWidth($tableWidth){
	    $this->tableWidth = $tableWidth;
	}	
	

	/**
	 * Sets $line.
	 *
	 * @param object $line
	 * @see TableValues::$line
	 */
	public function setLine($line){
	    $this->line=$line;
	}
	
	/**
	 * Sets $lineId.
	 *
	 * @param object $lineId
	 * @see TableValues::$lineId
	 */
	public function setLineId($lineId){
	    $this->lineId=$lineId;
	}

	/**
	 * Retorna a largura da tabela
	 * @return $tableWidth
	 */
	public function getTableWidth(){
		return $this->tableWidth;
	}

	/**
	 * Retorna io id da tabela
	 * @return $tableId
	 */
	public function getTableId(){
		return $this->tableId;
	}

	/**
	 * Retorna a largura da borda da tabela
	 * @return $tableBorder
	 */
	public function getTableBorder(){
		return $this->tableBorder;
	}

	/**
	 * Retorna a class (CSS) da tabela
	 * @return $tableClass
	 */
	public function getTableClass(){
		return $this->tableClass;
	}
	
	/** 
	 * Retorna o spaço da celula 
	 * @return $tableCellspacing
	 */
	public function getTableCellspacing(){
		return $this->tableCellspacing;
	}

	/**
	 * Retorna o padding da celula
	 * @return $tableCellpadding
	 */
	public function getTableCellpadding(){
		return $this->tableCellpadding;
	}
	
	/**
	 * Retorna os títulos das colunas
	 * @return array $columnTitle
	 */
	public function getColumnTileArray(){
		return $this->columnTitle;
	}

	/**
	 * Retorna o título de uma coluna
	 * @return array $columnTitle
	 * @param object $position
	 */
	public function getColumnTile($position){
		return $this->columnTitle[$position];
	}

	/**
	 * Retorna a largura de uma coluna
	 * @return array $coumnWidth
	 * @param object $position
	 */
	public function getColumnWidth($position){
		return $this->coumnWidth[$position];
	}

	/**
	 * Retorna uma linha da tabela
	 * @return array $line
	 * @param object $position
	 */
	public function getLine($position){
		return $this->line[$position];
	}

	/**
	 * Retorna todas as linhas da tabela
	 * @return array $line 
	 */
	public function getLineArray(){
		return @$this->line;
	}

	/**
	 * Retorna o id da linha da tabela
	 * @return array $lineId
	 * @param object $position
	 */
	public function getLineId($position){
		return $this->lineId[$position];
	}	

	/**
	 * Retorna a class (CSS) da linha do título
	 * @return $classTitle
	 */
	public function getClassTitle(){
		return $this->classTitle;
	}

	/**
	 * Retorna a class (CSS) das linhas da tabela
	 * @return array $classLine
	 * @param object $position
	 */
	public function getClassLine($position){
		return $this->classLine[$position];
	}
	
	/**
	 * Retorna a class (CSS) das linhas da tabela
	 * @return array $classLine
	 * @param object $position
	 */
	public function getClassLineArray(){
		return $this->classLine;
	}

	/**
	 * Retorna os scripts das linhas da tabela
	 * @return string $scriptLine
	 */
	public function getScriptLine(){
		return $this->scriptLine;
	}

	/**
	 * Seta os scripts para os linhas
	 * @return null
	 * @param object $script
	 */
	public function setScriptLine($script){
		$this->scriptLine = $script;
	}

	/**
	 * Seta a classes de css para o titulo
	 * @return 
	 * @param object $class
	 */
	public function setClassTitle($class){
		$this->classTitle = $class;
	}

	/**
	 * Seta as classes de css para as linhas
	 * @return 
	 * @param object $class
	 * @param object $classSecundaria
	 */
	public function setClassLine($class, $classSecundaria){
		$this->classLine[0] = $class;
		$this->classLine[1] = $classSecundaria;
	}

	/**
	 * Adciona uma coluna
	 * @return null
	 * @param object $title
	 * @param object $Width
	 * @example addColumn('teste da classe', '100%');
	 */
    public function addColumn($title, $Width) {
    	$this->columnTitle[] = $title;
		$this->coumnWidth[] = $Width;    	
    }

	/**
	 * Adiciona uma linha na tabela
	 * @return null
	 * @param $args
	 * @example addLine('id_do_tr', 'texto da primeira coluna e da primeira linha');
	 */
	public function addLine(){
		$args = func_get_args();
		$this->lineId[] = $args[0];
        for( $i=1, $n=count($args); $i<$n; $i++ )
            $this->line[] = $args[$i];
	}
	/**
	 * Declara que não averá styles e nem scripts line
	 * @return 
	 */
	public function noneEffects(){
		$this->setClassTitle(null);
		$this->setClassLine(null, null);
		$this->setScriptLine(null);
		$this->setClassCell(null);
	}
	
	/**
	 * Returns $classCell.
	 *
	 * @see TableValues::$classCell
	 */
	public function getClassCell(){
	    return $this->classCell;
	}
	
	/**
	 * Sets $classCell.
	 *
	 * @param object $classCell
	 * @see TableValues::$classCell
	 */
	public function setClassCell($classCell){
	    $this->classCell=$classCell;
	}
	
	/**
	 * Seta os valores para a tabela
	 * @param object $width
	 * @param object $id [optional]
	 * @param object $border [optional]
	 * @param object $class [optional]
	 * @param object $titleDisplay [optional]
	 * @param object $cellspacing [optional]
	 * @param object $cellpadding [optional]
	 * @return 
	 */
    public function start($width, $id = null, $border = 0, $class = 'T', $titleDisplay = true, $cellspacing = 0, $cellpadding = 0) {
    	$this->setTableWidth($width);
		$this->setTableId($id);
		$this->setTableBorder($border);
		$this->setTableClass($class);
		$this->setTableCellspacing($cellspacing);
		$this->setTableCellpadding($cellpadding);
		$this->setTitleDisplay($titleDisplay);
    }

	/**
	 * Monta toda a extrutura da tabela
	 * @return string
	 */
	public function montaTable(){
		$html = null;

		/*
		 * Cria a Tabela
		 */
		$html .= "\r\n<table width=\"".$this->getTableWidth()."\" border=\"".$this->getTableBorder()."\" id=\"".$this->getTableId()."\"  class=\"".$this->getTableClass()."\" cellspacing=\"".$this->getTableCellspacing()."\" cellpadding=\"".$this->getTableCellpadding()."\">\r\n";
		/*
		 * Define os tamanhos das colunas
		 */
		for($i = 0; $i < count($this->getColumnTileArray()); $i++){
			$html .= "<col style=\"width:".$this->getColumnWidth($i).";\" />\r\n";
		}
		/*
		 * Inicia o corpo da tabela
		 */
		$html .= "<tbody>\r\n";
		
		if($this->getTitleDisplay()){
			/*
			 * Exibe a primeira linha da tabelas com os titulos - TR
			 */
			$html .= "<tr class=\"".$this->getClassTitle()."\">\r\n";
				for($i = 0; $i < count($this->getColumnTileArray()); $i++){
					$html .= "	<td>".$this->getColumnTile($i)."</td>\r\n";
				}
			$html .= "</tr>\r\n";
		}
		/*
		 * Exibe todas as linhas - TR
		 */
		$totalLines = count($this->getLineArray());
		$totalColumns = count($this->getColumnTileArray());
		$totalTRs = $totalLines / $totalColumns;
		for($i = 0; $i < $totalTRs; $i++){
			if(count($this->getClassLineArray())>0){
				$classLine = ($i % 2) ? $this->getClassLine(0) : $this->getClassLine(1);
			}else $classLine = null;
			$html .= "<tr id=\"".$this->getLineId($i)."\" class=\"".$classLine."\" ".@$this->getScriptLine().">\r\n";
				for($x = 0; $x < $totalColumns; $x++){
					if($i == 0 && $x == 0) $y = 0;
					else $y += 1;				
					$html .= "	<td class=\"".@$this->getClassCell()."\">".$this->getLine($y)."</td>\r\n";
				}
			$html .= "</tr>\r\n";
		}
		/*
		 * Finaliza o corpo da tabela
		 */
		$html .= "</tbody>\r\n";
		/*
		 * Termina a Tabela
		 */
		$html .= "</table>\r\n";
		return $html;
	}

	/**
	 * Retorna a tabela em uma string
	 * @return 
	 */
	public function returnTable(){
		return $this->montaTable();
	} 
	
	/**
	 * Imprime toda a tabela
	 * @return table
	 */
	public function printTable(){
		echo $this->montaTable();
	}
}