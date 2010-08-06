<?php
/**
 * Contem a class XmlCreatorComponent
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Components
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 223 $
 * @lastModified	$LastChangedDate: 2010-07-16 14:12:32 -0300 (Sex, 16 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class File required
 */
Zeanwork::import(LIBS, 'file');

/**
 * Criador de xml
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Components
 */
class XmlCreatorComponent extends Component {
	
	/**
	 * Componentes dependentes
	 * @var array
	 */
	public $components = array();
	
	/**
	 * Conteúdo do xml
	 * @var string
	 */
	public $xml = null;
	
	/**
	 * Indentação do xml
	 * @var string
	 */
	public $indent = null;
	
	/**
	 * Nomes
	 * @var array
	 */
	public $childName = array();
	
	/**
	 * Começa a escrever o xml
	 * @param string $indent [optional] Indentar o xml com '	'
	 * @return boolean
	 */
	public function write($indent = "	"){
		$this->xml = '<?xml version="1.0" encoding="utf-8"?>'."\n";
		$this->indent = $indent;
		return true;
	}
	
	/**
	 * Identa a próxima linha
	 * @return boolean
	 */
	public function _indent(){
		for ($i = 0; $i < count($this->childName); $i++) {
            $this->xml .= $this->indent;
        }
		return true;
	}
	
	/**
	 * Começa um novo nó
	 * @param string $childName Nome do child
	 * @param array $attrs [optional] Atributos para este child
	 * @return boolean
	 */
	public function childStart($childName, $attrs = array()){
		$this->_indent();
        $this->xml .= '<'.$childName;
        foreach ($attrs as $key => $value) {
            $this->xml .= ' '.$key.'="'.htmlentities($value).'"';
        }
        $this->xml .= ">\n";
		$this->childName[] = $childName;
		return true;
	}
	
	/**
	 * Termina o nó
	 * @return boolean
	 */
	public function childEnd(){
		$childName = array_pop($this->childName);
        $this->_indent();
        $this->xml .= "</$childName>\n";
		return true;
	}
	
	/**
	 * Cria um elemento
	 * @param string $elementName Nome do elemento
	 * @param string $content Conteudo para o elemento
	 * @param array $attrs [optional] Atributos para este elemento
	 * @return boolean
	 */
	public function element($elementName, $content, $attrs = array()){
        $this->_indent();
        $this->xml .= '<'.$elementName;
        foreach ($attrs as $key => $value) {
            $this->xml .= ' '.$key.'="'.htmlentities($value).'"';
        }
        $this->xml .= '>'.$content.'</'.$elementName.'>'."\n";
		return true;
    }
	
	/**
	 * Retorna o xml
	 * @return string
	 */
	public function getXml(){
		return $this->xml;
	}
	
	/**
	 * Salva os dados do xml em um arquivo
	 * @param string $path Pasta e arquivo onde salvar o xml
	 * @return boolean
	 */
	public function save($path){
		$file = new File($path, true);
		return $file->write($this->getXml());
	}
	
	/**
	 * Inicializa o componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
	public function initialize(&$controller){
        return true;
    }
	
	/**
	 * Faz as operações para a inicialização do componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
    public function startup(&$controller){
        return true;
    }
	
	/**
	 * Finaliza o componente
	 * @param object $controller Controller object
	 * @return boolean
	 */
    public function shutdown(&$controller){
        return true;
    }
}