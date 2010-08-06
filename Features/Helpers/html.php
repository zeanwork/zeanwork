<?php
/**
 * Contem a classe HtmlHelper
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
 * @version 		$LastChangedRevision: 223 $
 * @lastModified	$LastChangedDate: 2010-07-16 14:12:32 -0300 (Sex, 16 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Classe para manipulação de HTML's
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Helpers
 * @obs				Class required Util, Configure, Validation
 */
class HtmlHelper extends Helper {
	
	/**
	 * Helpers dependentes
	 * @var array
	 */
	var $helpers = array();
	
	/**
	 * Tags HTML
	 * @var array
	 */
	public $tags = array(
		'meta' => '<meta%s />',
		'metaLink' => '<link%s href="%s"/>',
		'link' => '<a href="%s"%s>%s</a>',
		'mailto' => '<a href="mailto:%s" %s>%s</a>',
		'formStart' => '<form%s>',
		'formEnd' => '</form>',
		'input' => '<input name="%s"%s />',
		'tetarea' => '<textarea name="%s"%s>%s</textarea>',
		'selectStart' => '<select name="%s"%s>',
		'selectOption' => '<option value="%s"%s>%s</option>',
		'selectEnd' => '</select>',
		'optionGroupStart' => '<optgroup label="%s"%s>',
		'optionGroupEnd' => '</optgroup>',
		'password' => '<input type="password" name="%s" %s/>',
		'file' => '<input type="file" name="%s" %s/>',
		'image' => '<img src="%s"%s />',
		'tableHeader' => '<th%s>%s</th>',
		'tableHeaderRow' => '<tr%s>%s</tr>',
		'tableCell' => '<td%s>%s</td>',
		'tableRow' => '<tr%s>%s</tr>',
		'divBlock' => "\r\n<div%s>\r\n %s\r\n</div>\r\n",
		'divStart' => '<div%s>',
		'divEnd' => '</div>',
		'tag' => '<%s%s>%s</%s>',
		'tagStart' => '<%s%s>',
		'tagEnd' => '</%s>',
		'para' => '<p%s>%s</p>',
		'paraStart' => '<p%s>',
		'label' => '<label for="%s"%s>%s</label>',
		'fieldset' => '<fieldset%s>%s</fieldset>',
		'fieldsetStart' => '<fieldset><legend>%s</legend>',
		'fieldsetEnd' => '</fieldset>',
		'legend' => '<legend>%s</legend>',
		'css' => '<link rel="%s" type="text/css" href="%s" %s/>',
		'styleBlock' => '<style type="text/css">%s</style>',
		'styleStart' => '<style type="text/css">',
		'styleEnd' => '</style>',
		'charset' => '<meta http-equiv="Content-Type" content="text/html; charset=%s" />',
		'ul' => '<ul%s>%s</ul>',
		'ol' => '<ol%s>%s</ol>',
		'li' => '<li%s>%s</li>',
		'error' => '<div%s>%s</div>',
		'javascriptBlock' => '<script type="text/javascript">%s</script>',
		'javascriptReady' => '<script type="text/javascript">$(document).ready(function(){%s});</script>',
		'javascriptStart' => '<script type="text/javascript">',
		'javascriptLink' => '<script type="text/javascript" src="%s"></script>',
		'javascriptEnd' => '</script>'
	);
	
	/**
	 * Armazena o que for default
	 * @var array
	 */
	var $defaults = array(
						  'class' => array(
						  					  'input' => array(
															  'all'		=> null
															, 'button'	=> null
															, 'checkbox'=> null
															, 'file'	=> null
															, 'hidden'	=> null
															, 'image'	=> null
															, 'password'=> null
															, 'radio'	=> null
															, 'reset'	=> null
															, 'submit'	=> null
															, 'text'	=> null
															, 'textarea'=> null
															, 'select'	=> null
														)
											, 'div' => array(
															  'all' 	=> null
														)
											, 'zeanwork' => array(
																  'debugger' 	=> 'zeanworkDebug'
																, 'link'		=> 'zeanworkLink'
																, 'debugQuery'	=> 'ZeanworkDebugQuery'
															)
						  			)
						
					);
	
	/**
	 * Não são atributos
	 * @var array
	 */
	var $notsAttrs = array(
						  'label'
						, 'div'
						, 'useClassDefaultOf'
						, 'divForLabel'
						, 'divForInput'
					);
	
	/**
	 * Verefica se é uma array, caso não seja, retornará uma array vazia
	 * @param variable $options Variavel a ser verificada
	 * @return array
	 */
	public function isArray($arr){
		if ($arr === null) {
			$arr = array();
		} elseif (is_string($arr)) $arr = array();
		return $arr;
	}
	
	/**
	 * Gera um atributo em HTML (class="example")
	 * @param array $options [optional] Opções a ser gerado os atributos
	 * @param string $key [optional] Nome do key
	 * @return string or boolean
	 */
	public function attr($options = array(), $key = null) {
		$options = $this->isArray($options);
		
		if (is_array($options) && array_key_exists($key, $options)) {
			
			if($options[$key] === false || $options[$key] === null)
				return false;
			else return ' '.$key.'="'.$options[$key].'"';
		}else return false;
	}

	/**
	 * Retorna o attr tratado Ex: (class="text")
	 * @param array $options [optional] Opções
	 * @param boolean $useClassDefaultOf [optional] Usar um classe (html) padrão de:
	 * @param string $key [optional]
	 * @return string or boolean
	 */
	public function __class($options = array(), $useClassDefaultOf = null, $key = 'class') {	
		$options = $this->isArray($options);
		$class = null;
		
		/** Se $useClassDefaultOf for diferente de null ele faz o seguinte procedimento */
		if($useClassDefaultOf !== null){
			
			/** Se existir o indice no $this->defaults['class'] que foi solicitado pela variavel $useClassDefaultOf ele atribuirá os class padroes */
			if(array_key_exists($useClassDefaultOf, $this->defaults['class'])){
				
				/** Se existir o indice all para o $useClassDefaultOf solicitado ele terá esta class padrão para todos que for solicitado */
				if(array_key_exists('all', $this->defaults['class'][$useClassDefaultOf])){
					/** Se o indice all tiver o o valor null não terá estilo */
					if($this->defaults['class'][$useClassDefaultOf]['all'] !== null) $class .= $this->defaults['class'][$useClassDefaultOf]['all'].' ';
				}
				
				/** Se existir o existir o indice type no $options, quer dizer que é um imput*/
				if(array_key_exists('type', $options)){
					
					/** Se existir o indice para o type do input ele terá a class estipulada */
					if(array_key_exists($options['type'], $this->defaults['class'][$useClassDefaultOf])){
						
						/** Se o varlor no $this->defaults['class'][$useClassDefaultOf][$options['type'] for null, então não terá estilo  */
						if($this->defaults['class'][$useClassDefaultOf][$options['type']] !== null) $class .= $this->defaults['class'][$useClassDefaultOf][$options['type']].' ';
					}
				}
			}
		}
		
		/** SE o option é uma array e se existir o indice que foi solicitado pelo '$key' faz o seguinte procedimento */
		if (is_array($options) && array_key_exists($key, $options)) {
			
			/** Se false não exibe o class mesmo que tenha default */
			if($options[$key] !== false){
				
				/** Se $options[$key] for um array ele trata para ser varios class */
				if(is_array($options[$key])) $class .= join(' ', $options[$key]); else $class .= $options[$key];
				return ' class="'.$class.'"';
			}else return false;
		} else return false;
	}

	/**
	 * Retorna os atributos tratados com suas devidas function, se não tiver function ele utilizará a function (attr)
	 * @param array $options [optional] Opções
	 * @param array $notOption Os valores aqui informados serão tratados como não sendo atributos [optional]
	 * @return string
	 */
	public function getAttrs($options = array(), $notOption = array()){
		$options = $this->isArray($options);
		if(!is_array($notOption)) $notOption = array();
		
		$attrs = null;
		foreach($options as $key => $value){
			
				if(in_array($key, $notOption)) {}
				elseif($key === 'class'){
					if(array_key_exists('useClassDefaultOf', $options)) $useClassDefaultOf = $options['useClassDefaultOf']; else $useClassDefaultOf = null;
					$attrs .= $this->__class($options, $useClassDefaultOf);
				}
				//Verifica se existe uma function dentro desta class
				elseif(is_callable(array($this, $key))) {
					$attrs .= $this->{$key}($options, $key);
				}else{
					$attrs .= $this->attr($options, $key);
				}
		}
		return $attrs;
	}
	
	/**
	 * Se houver um indice chamado 'label' retorna um label (<label for=""></labe>), 
	 * se este indice houver um array e conter um outro indice chamado 'label' retorna o label,
	 * se dentro desta array houver um indice chamado 'attrs' ou 'options', ele trata isso como atributos para o label
	 * @param array $attr [optional]
	 * @return string
	 */
	public function labelInArray($attr = array()){
		$out = null;
		if(array_key_exists('label', $attr)) {
			$attrsForLabel = array();
			$label = $attr['label'];
			if(is_array($attr['label'])){
				if(array_key_exists('label', $attr['label'])) 
					$label = $attr['label']['label'];
				if(array_key_exists('attrs', $attr['label'])) $attrsForLabel = $attr['label']['attrs'];
				elseif(array_key_exists('options', $attr['label']) )$attrsForLabel = $attr['label']['options'];
			}
			$out .= $this->label($attr['id'], $label, $attrsForLabel);
		}
		return $out;
	}
	
	/**
	 * Monta um label Html (<label for="textExample" class="classForExample">Example</label>) tratando seus devidos atributos
	 * @param string $for [optional] Id do input
	 * @param string $text [optional] Texto para o label
	 * @param array $attr [optional] Atributos para o label
	 * @return string
	 */
	public function label($for = null, $text = null, $attr = array()){
		$attrs = null;
		if(!is_array($attr)) $attr = array();
		foreach($attr as $key => $value){
			$attrs .= $this->attr($attr, $key);
		}
		return sprintf($this->tags['label'], $for, $attrs, $text);
	}
	
	/**
	 * Monta uma div em torno da label Html
	 * @param array $attr Atributos para a div
	 * @return string or boolean
	 */
	public function divForLabel($attr){
		$this->isArray($attr);
		$label = null;
		if(array_key_exists('label', $attr)){
			if(array_key_exists('divForLabel', $attr)){
				if($attr['divForLabel'] !== false || is_array($attr['divForLabel'])){
					if($attr['divForLabel'] === true) $attr['divForLabel'] = null;
					$label = $this->div($this->labelInArray($attr), $attr['divForLabel']);
				}elseif($attr['divForLabel'] == false) $label = $this->labelInArray($attr);
			}else $label = $this->labelInArray($attr);
		}
		return $label;
	}
	
	/**
	 * Monta uma div Html tratando seus devidos atributos (<div id="test" class="classForExample">Example of div</div>)
	 * @param string $text Conteudo para a div
	 * @param array $attr array [optional] Atributos para a div
	 * @return string
	 */
	public function div($text, $attr = array()){
		if(is_string($attr)){
			if($attr == null) $attr = array(); else $attr = array('class' => $attr); 
		}else {
			if($attr === false) $attr = array();
		}
		
		$output = null;
		$output .= sprintf($this->tags['divBlock'], $this->getAttrs($attr, $this->notsAttrs), $text);
		return $output;
	}
	
	/**
	 * Retorna o attr tratado Ex: (style="text")
	 * @param array $attr [optional] Atributos
	 * @param string $key [optional] Key
	 * @return string or boolean
	 */
	public function style($attr = array(), $key = 'style') {
		return $this->attr($attr, $key);
	}
	
	/**
	 * Monta um link com a logo completa do zeanwork
	 * @return string
	 */
	public function zeanworkAllLogo(){
		$output = null;
		$output .= '<a href="'.Configure::read('zeanworkWebSite').'" target="blank"><img src="'.IMG.'Zeanwork/allLogo.png" border="0" title="Zeanwork"></a>';
		return $output;
	}
	
	/**
	 * Monta um link com uma imagem do Zeanwork
	 * @return string
	 */
	public function zeanworkPoweredBy(){
		$output = null;
		$output .= '<a href="'.Configure::read('zeanworkWebSite').'" target="blank"><img src="'.IMG.'Zeanwork/poweredBy.png" border="0" title="Zeanwork Framework PHP"></a>';
		return $output;
	}
	

	/**
	 * Monta um link com a imagem de 'Zeanwork | Power'.
	 * @param array $attrImg Atributos para a imagem
	 * @param array $attrLink Atributos para o link
	 * @return string
	 */
	public function zeanworkPower($attrImg = array('style' => 'margin-left:5px; vertical-align: top;'), $attrLink = array('title' => 'Zeanwork Framework PHP')){
		$output = null;
		$output .= $this->imgLink(Configure::read('zeanworkWebSite'), 'Zeanwork/zeanworkPower.gif', $attrLink, $attrImg);
		return $output;
	}
	
	/**
	 * Monta um link do zeanwork com a logo
	 * @return string
	 */
	public function zeanworkPoweredByLogo(){
		$output = null;
		$output .= '<a href="'.Configure::read('zeanworkWebSite').'" target="blank"><img src="'.IMG.'Zeanwork/poweredByLogo.png" border="0" title="Zeanwork Framework PHP"></a>';
		return $output;
	}
	
	/**
	 * Layout do PHP Error
	 * @param string $error Conteúdo do erro
	 * @return string
	 */
	public static function error($error){
		$output = null;
		$output .= '
		<div style="background:#494949; border:2px solid #232222; padding:5px; margin:10px; color:#FFFFFF; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
			<span style="font-size:30px; font-weight:bold; color:#8A8A8A">Ops!</span>
			'.$error.'
			<div style="text-align:right;">'.self::zeanworkPoweredByLogo().'</div>
		</div>';
		return $output;
	}
	
	/**
	 * Gera um br em html (Alias Util::br())
	 * @see Util::br();
	 * @param object $num [optional]
	 * @return string
	 */
	public function br($num = 1){
		return Util::br($num);
	}
	
	/**
	 * Gera um espaço em hml (Alias Util::nbsp())
	 * @see Util::nbsp();
	 * @param object $num [optional]
	 * @return string
	 */
	public function nbsp($num = 1){
		return Util::nbsp($num);
	}
	
	/**
	 * Monta uma tag img
	 * @param string $srcImg Caminho da imagem
	 * @param array $attr [optional] Atributos para a tag
	 * @return string
	 */
	public function img($srcImg, $attr = array()){
		if(is_string($attr)){
			if($attr == null) $attr = array(); else $attr = array('class' => $attr); 
		}else{
			if($attr === false) $attr = array();
		}
		
		if(strpos($srcImg, '://') === false){
			if($srcImg[0] !== '/'){
				$srcImg = IMG . $srcImg;
			}
		}
		
		$output = null;
		$output .= sprintf($this->tags['image'], $srcImg, $this->getAttrs($attr, $this->notsAttrs));
		return $output;
	}
	
	/**
	 * Monta um link html (<a href=""></a>)
	 * @param string $displayText Texto que será exibido
	 * @param string $href [optional] Link
	 * @param array $attr [optional] Atributos para o link
	 * @return string
	 */
	public function link($displayText, $href = null, $attr = array()){
		if(is_string($attr)){
			if($attr == null) $attr = array(); else $attr = array('class' => $attr); 
		}else{
			if($attr === false) $attr = array();
		}
		
		$output = null;
		$output .= sprintf($this->tags['link'], $href, $this->getAttrs($attr, $this->notsAttrs), $displayText);
		return $output;
	}
	
	/**
	 * Monta uma tag meta
	 * @param string $name [optional] Nome da meta
	 * @param string $content [optional] Valor para a meta
	 * @param string $type [optional] Tipo da meta (http-equiv or name)
	 * @return string
	 */
	public function meta($name = null, $content = null, $type = 'name'){
		$attrs = array();
		$output = null;
		if(!is_array($name)){
			$attrs['name'] = $name;
			$attrs['http-equiv'] = null;
			$attrs['type'] = $type;
			$attrs['content'] = $content;
			$attrs = array($attrs);
		}else{
			if(isset($name['name'])){
				$attrs = array($name);
			}else{
				$attrs = $name;
			}
		}
		$count = count($attrs);
		foreach($attrs as $attr){
			if(!array_key_exists('name', $attr))
				$attr['name'] = null;
			if(!array_key_exists('http-equiv', $attr))
				$attr['http-equiv'] = null;
			if(!array_key_exists('content', $attr))
				$attr['content'] = null;
			if(!array_key_exists('type', $attr))
				$attr['type'] = 'name';
			
			$_name = ($attr['type'] == 'name') ? 'name' : 'http-equiv';
			$attr[$_name] = $attr['name'];
			if($_name != 'name')
				unset($attr['name']);
			else
				unset($attr['http-equiv']);
			unset($attr['type']);
			$output .= sprintf($this->tags['meta'], $this->getAttrs($attr, $this->notsAttrs));
			if($count > 1){
				$output .= "\r\n";
			}
		}
		return $output;
	}
	
	/**
	 * Monta uma meta tag para o idioma
	 * @return String
	 */
	public function metaLanguage(){
		return $this->meta('Content-Language', str_replace('_', '-', Inflector::underscore(Configure::read('toLanguage'))), 'equiv');
	}
	
	/**
	 * Monta uma meta tag para o charset
	 * @return String
	 */
	public function metaCharset(){
		return $this->meta('Content-Type', 'text/html; charset='.strtolower(Configure::read('charset')), 'equiv');
	}
	
	/**
	 * Monta uma meta tag de link
	 * @param string $href Link
	 * @param array $attr [optional] Atributos para o link
	 * @return string
	 */
	public function metaLink($href, $attr = array()){
		return sprintf($this->tags['metaLink'], $this->getAttrs($attr, $this->notsAttrs), $href);
	}
	
	/**
	 * Gera a declaração do tipo da página (DOCTYPE)
	 * XHTML 1.1  				xhtml11
	 * XHTML 1.0 Strict 		xhtml1-strict
	 * XHTML 1.0 Transitional 	xhtml1-trans
	 * XHTML 1.0 Frameset 		xhtml1-frame
	 * HTML 5 					html5
	 * HTML 4 Strict 			html4-strict
	 * HTML 4 Transitional 		html4-trans
	 * HTML 4 Frameset 			html4-frame
	 * @param string $type [optional] Tipo do doctype (default XHTML 1.0 Strict)
	 * @return string if exist type in cofiguration else return false
	 */
	public function doctype($type = 'xhtml1-strict'){
		$doctypes = Configure::read('doctypes', ', check if exist cofiguration in /App/Config/doctypes.php');
		if($doctypes === false)
			return false;
		if(is_array($doctypes)){
			if(array_key_exists($type, $doctypes))
				return $doctypes[$type]."\r";
		}
		return false;
	}
	
	/**
	 * Monta um link para um css
	 * @param string $href Link para o css
	 * @param string $rel [optional] Rel
	 * @param array $attr [optional] Atributos para o link do css
	 * @return string
	 */
	public function css($href, $rel = 'stylesheet', $attr = array(), $ext = 'css'){
		if($attr === false || !is_array($attr))
			$attr = array();
		
		if(strpos($href, '://') === false){
			if($href[0] !== '/'){
				$href = CSS . $href;
			}

			if(strpos($href, '?') === false){
				if(strpos($href, '.' . $ext) === false){
					$href .= '.' . $ext;
				}
			}
		}
		
		$output = null;
		$output .= sprintf($this->tags['css'], $rel, $href, $this->getAttrs($attr, $this->notsAttrs));
		return $output;
	}
	
	/**
	 * Monta um bloco para css
	 * @param string $style Css para o bloco de css
	 * @return string
	 */
	public function cssBlock($style){
		$output = null;
		$output .= sprintf($this->tags['styleStart'])."\r\n";
		$output .= $style;
		$output .= "\r\n".sprintf($this->tags['styleEnd']);
		return $output;
	}
	
	/**
	 * Monta um link para um javascript
	 * @param string $src Link para o javascript
	 * @return string
	 */
	public function js($src, $ext = 'js'){
		$output = null;
		if(strpos($src, '://') === false){
			if($src[0] !== '/'){
				$src = JS . $src;
			}

			if(strpos($src, '?') === false){
				if(strpos($src, '.' . $ext) === false){
					$src .= '.' . $ext;
				}
			}
		}
		$output .= sprintf($this->tags['javascriptLink'], $src);
		return $output;
	}
	
	/**
	 * Monta um bloco javascript
	 * @param string $script Script para o javascript
	 * @param boolean $newLine Quebrar a lina no início e no fim do bloco?
	 * @return string
	 */
	public function jsBlock($script, $newLine = true){
		if($newLine === true)
			$line = "\r\n";
		else
			$line = null;
		$output = null;
		$output .= sprintf($this->tags['javascriptStart']).$line;
		$output .= $script;
		$output .= $line.sprintf($this->tags['javascriptEnd']);
		return $output;
	}
	
	/**
	 * Monta um bloco javascript com o jQuery Ready
	 * @param string $script Script para o javascript
	 * @param boolean $newLine Quebrar a lina no início e no fim do bloco?
	 * @return string
	 */
	public function jsReady($script, $newLine = true){
		if($newLine === true)
			$line = "\r\n";
		else
			$line = null;
		$output = null;
		$output .= sprintf($this->tags['javascriptStart']).$line;
		$output .= '$(document).ready(function(){'.$line;
		$output .= $script;
		$output .= '});'.$line;
		$output .= $line.sprintf($this->tags['javascriptEnd']);
		return $output;
	}
	
	/**
	 * Gera um link com uma imagem dentro do link
	 * @param string $href Link do link
	 * @param string $srcImg Link da imagem
	 * @param array $attrLink [optional] Atributos para o link
	 * @param array $attrImg [optional] Atributos para a imagem
	 * @return string
	 */
	public function imgLink($href, $srcImg, $attrLink = array(), $attrImg = array()){
		return self::link(self::img($srcImg, $attrImg), $href, $attrLink);
	}
	
	/**
	 * Abre uma tag
	 * @param string $tag Nome da tag
	 * @param array $attr [optional] Atributos para a tag
	 * @return string
	 */
	public function tagOpen($tag, $attr = array()){
		if(is_string($attr)){
			if($attr == null) $attr = array(); else $attr = array('class' => $attr); 
		}else{
			if($attr === false) $attr = array();
		}
		
		$output = null;
		$output .= sprintf($this->tags['tagStart'], $tag, $this->getAttrs($attr, $this->notsAttrs));
		return $output;
	}
	
	/**
	 * Fecha uma tag
	 * @param string $tag Nome da tag
	 * @return string
	 */
	public function tagClose($tag){
		$output = null;
		$output .= sprintf($this->tags['tagEnd'], $tag);
		return $output;
	}
	
	/**
	 * Abre e fecha uma tag, colocando o conteudo dentro da tag
	 * @param string $tag Nome da tag
	 * @param string $content [optional] Conteúdo para a tag
	 * @param array $attr [optional] Atributos para a tag
	 * @return string
	 */
	public function tag($tag, $content = null, $attr = array()){
		return self::tagOpen($tag, $attr) . "\r\n" . $content . "\r\n" . self::tagClose($tag);
	}
	
	/**
	 * Gera uma tag h ex: <h1>h1</h1>
	 * @param string $text Texto para o h
	 * @param string $num [optional] Numeto do h (1-6)
	 * @param array $attr [optional] Atributos para o h
	 * @return string
	 */
	public function h($text, $num = 1, $attr = array()){
		if(!Validation::between((int)$num, 1, 6))
			$num = 1;
		return self::tag('h'.$num, $text, $attr);
	}
	
	/**
	 * Monta uma div com um style = clear:both;
	 * @return string
	 */
	public function clear(){
		return $this->div(null, array('style' => 'clear:both;'));
	}
	
	/**
	 * Cria uma lista atravez de uma array
	 * @param string $type Tipo da list (ol, ul)
	 * @param array $list Array contendo os dados para criar a lista
	 * @param array $attr Atributos para o ul/ol
	 * @param array $attrLi Atributos para o li
	 * @param number $level Nível
	 */
	private function _list($type = 'ul', $list = array(), $attr = array(), $attrLi = array(), $level = 0){
		if(!is_array($list)){
			return $list;
		}
		$output = str_repeat("\t", $level);
		$type = ($type != 'ul' && $type != 'ol') ? 'ul' : $type;
		$output .= $this->tagOpen($type, $attr) . PHP_EOL;
		foreach($list as $value){
			$output .= str_repeat("\t", $level + 1) . $this->tagOpen('li', $attrLi);
			if(is_array($value)){
				$output .= PHP_EOL;
				$output .= $this->_list($type, $value, array(), array(), $level + 2);
				$output .= str_repeat("\t", $level + 1);
			}else{
				$output .= $value;
			}
			$output .= $this->tagClose('li') . PHP_EOL;
		}
		$output .= str_repeat("\t", $level) . $this->tagClose($type) . PHP_EOL;
		return $output;
	}
	
	/**
	 * Cria uma lista 'ul' atravez de uma array
	 * @param array $list Array contendo os dados para criar a lista
	 * @param array $attr Atributos para o ul
	 * @param array $attrLi Atributos para o li
	 */
	public function ul($list = array(), $attr = array(), $attrLi = array()){
		return $this->_list($type = 'ul', $list, $attr, $attrLi);
	}
	
	/**
	 * Cria uma lista 'ol' atravez de uma array
	 * @param array $list Array contendo os dados para criar a lista
	 * @param array $attr Atributos para o ol
	 * @param array $attrLi Atributos para o li
	 */
	public function ol($list = array(), $attr = array(), $attrLi = array()){
		return $this->_list($type = 'ol', $list, $attr, $attrLi);
	}
}