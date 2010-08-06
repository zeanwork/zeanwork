<?php
/**
 * Contem a classe PaginationHelper
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
 * Manipulação da paginação
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Helpers
 */
class PaginationHelper extends Helper {
	
	/**
	 * Helpers dependentes
	 * @var array
	 */
	public $helpers = array('html'); 
	
	/**
	 * Objeto do model
	 * @var object
	 */
	private $model = false;
	
	/**
	 * Opções para a lista de numeros
	 * @var array
	 */
	public $numbersOptions = array(
								  'maxButtons' => 13
								, 'separator' => ' '
								, 'tag' => 'span'
								, 'current' => 'current'
							);
	
	/**
	 * Texto que será utilizado do função PaginationHelper::description();
	 * @var array
	 */
	public $textDescription = array(
								  'results' => 'Resultados'
								, 'of' => 'de'
								, 'pages' => 'Páginas'
							);
	
	/**
	 * Seta o nome do model que será utilizado para a paginação
	 * @param string $modelName Nome do model
	 */
	public function model($modelName){
		$this->model = Zeanwork::getInstance($modelName);
	}
	
	/**
	 * Retorna um html com a lista dos numeros com seus perspectivos links
	 * @param array $options Opções para a listagem dos numeros
	 */
	public function numbers($options = array()){
		if(!$this->model) return false;
		
		$maxButtons = (isset($options['maxButtons'])) ? $options['maxButtons'] : $this->numbersOptions['maxButtons'];
		$separator = (isset($options['separator'])) ? $options['separator'] : $this->numbersOptions['separator'];
		$tag = (isset($options['tag'])) ? $options['tag'] : $this->numbersOptions['tag'];
		$current = (isset($options['current'])) ? $options['current'] : $this->numbersOptions['current'];
		$page = $this->getPage();
		
		if($maxButtons < 2)
			$maxButtons = 2;
		
		if($tag == 'ul' || $tag == 'ol'){
			$specialTag = true;
			$_tag = $tag;
			$tag = 'span';
		}else
			$specialTag = false;
		
		$startFor = max($page - (floor(($maxButtons - 1) / 2)), 1);
		$endFor = min($page + (floor(($maxButtons - 1) / 2)), $this->getTotalPages());
		$numbers = array();
		
		for($i = $startFor; $i <= $endFor; $i++){
			if($page == $i){
				$attr = array('class' => $current);
				$displayNumber = $i;
			}else{
				$attr = array();
				$displayNumber = $this->html->link($i, $this->url($i));
			}
			$numbers[] = $this->html->tag($tag, $displayNumber, $attr);
		}
		
		if($specialTag == true){
			return $this->html->{$_tag}($numbers);
		}else{
			return join($separator, $numbers);
		}
	}
	
	/**
	 * Monta a url para onde irá apontar o link
	 * @param number $page Numero da página
	 * @return string
	 */
	public function url($page = 1){
		$url = array();
		foreach($_GET as $key => $value){
			if($key == 'page')
				$url[] = 'page=' . $page;
			else
				$url[] = $key . '=' . $value;
		}
		if(!in_array('page=' . $page, $url))
			$url[] = 'page=' . $page;
		return '?' . join('&', $url);
	}
	
	/**
	 * Retorna o numero da página que estou
	 * @return number or boolean
	 */
	public function getPage(){
		if($this->model)
			return $this->model->pagination['page'];
		return false; 
	}
	
	/**
	 * Retorna o total de páginas
	 * @return number or boolean
	 */
	public function getTotalPages(){
		if($this->model)
			return $this->model->pagination['totalPages'];
		return false; 
	}
	
	/**
	 * Retorna o total de registros que resultou a consulta
	 * @return number or boolean
	 */
	public function getTotalRecords(){
		if($this->model)
			return $this->model->pagination['totalRecords'];
		return false; 
	}
	
	/**
	 * Retorna o total de registros quue resultou para esta página
	 * @return number or boolean
	 */
	public function getTotalRecordsInPage(){
		if($this->model)
			return $this->model->pagination['totalRecordsInPage'];
		return false; 
	}
	
	/**
	 * Retorna o qual foi o primeiro registro
	 * @return number or boolean
	 */
	public function getStartRecord(){
		if($this->model)
			return $this->model->pagination['startRecord'];
		return false; 
	}
	
	/**
	 * Verifica se existe uma página seguinte
	 * @return boolean
	 */
	public function hasNext(){
		if($this->model)
			return $this->getPage() < $this->getTotalPages();
		return false; 
	}
	
	/**
	 * Verifica se existe uma página anterior
	 * @return boolean
	 */
	public function hasPrevious(){
		if($this->model)
			return $this->getPage() > 1;
		return false; 
	}
	
	/**
	 * Monta um link apontando para a próxima página
	 * @param string $text Texto a ser exibido
	 * @param array $attr Atributos para o link
	 */
	public function next($text = 'Próximo', $attr = array()){
		if($this->hasNext())
			return $this->html->link($text, $this->url($this->getPage() + 1), $attr);
		return false;
	}
	
	/**
	 * Monta um link apontando para a página anterior
	 * @param string $text Texto a ser exibido
	 * @param array $attr Atributos para o link
	 */
	public function previous($text = 'Anterior', $attr = array()){
		if($this->hasPrevious())
			return $this->html->link($text, $this->url($this->getPage() - 1), $attr);
		return false;
	}
	
	/**
	 * Monta um link apontando para a última página
	 * @param string $text Texto a ser exibido
	 * @param array $attr Atributos para o link
	 */
	public function last($text = 'Última', $attr = array()){
		if($this->hasNext())
			return $this->html->link($text, $this->url($this->getTotalPages()), $attr);
		return false;
	}
	
	/**
	 * Monta um link apontando para a promeira página
	 * @param string $text Texto a ser exibido
	 * @param array $attr Atributos para o link
	 */
	public function first($text = 'Primeira', $attr = array()){
		if($this->hasPrevious())
			return $this->html->link($text, $this->url(1), $attr);
		return false;
	}
	
	/**
	 * Monta uma descrição
	 * @param array $attr Atributos para a tag
	 * @param string $tag Nome da tag a ser utilizada
	 * @param array $text Textos a serem exibido
	 * @param string $separator Separador da primeira parte com a segunda
	 */
	public function description($attr = array(), $tag = 'div', $text = array(), $separator = '<br />'){
		$results = (isset($text['results'])) ? $text['results'] : $this->textDescription['results'];
		$of = (isset($text['of'])) ? $text['of'] : $this->textDescription['of'];
		$pages = (isset($text['pages'])) ? $text['pages'] : $this->textDescription['pages'];
		
		$output = $results . ': ' . $this->getStartRecord() . ' - ' . ($this->getStartRecord() + $this->getTotalRecordsInPage()) . ' ' . $of . ' ' . $this->getTotalRecords();
		$output .= $separator;
		$output .= $pages . ': <strong>' . 	$this->getPage() . '</strong> ' . $of . ' ' . $this->getTotalPages();

		return $this->html->tag($tag, $output, $attr);
	}
	
	/**
	 * Gera os links para as páginas, contendo: Próximo, Anterior, Última, Primeira, Numeros, Descrição
	 */
	public function generateAll(){
		$output = $this->html->tagOpen('div', array('style' => 'text-align: center; margin-top: 15px;', 'id' => 'paginationZeanwork'));
		$output .= $this->first('<<', array('style' => 'padding: 5px;'));
		$output .= $this->previous('<', array('style' => 'padding: 5px;'));
		$output .= $this->numbers();
		$output .= $this->next('>', array('style' => 'padding: 5px;'));
		$output .= $this->last('>>', array('style' => 'padding: 5px;'));
		$output .= $this->description(array('style' => 'text-align: center;'));
		$output .= $this->html->tagClose('div'); 
		return $output;
	}
}