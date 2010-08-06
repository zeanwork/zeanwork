<?php
/**
 * Contem a classe FormHelper
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
 * @version 		$LastChangedRevision: 211 $
 * @lastModified	$LastChangedDate: 2010-06-23 12:37:42 -0300 (Qua, 23 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Class Html required
 */
Zeanwork::import(Zeanwork::pathTreated('Helper'), 'html');


/**
 * Manipulação de formulários 'form';
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Helpers
 * 
 * @ex
 * $form = new FormHelper();
 *	echo $form->start('FormOfExample', array('action' => 'expamle.php', 'method' => 'post'));
 *	echo $form->input('textExample',  array(
 *											  'type' => 'text'
 *											, 'divForLabel' => true
 *											, 'divForInput' => 'classDivInput'
 *											, 'label' => 'Exemplo'
 *											, 'class' => 'classExample'
 *											, 'id' => 'textExample'
 *											, 'useClassDefaultOf' => true
 *											, 'maxlength' => 60
 *										)
 *					);
 *	echo '<br />';
 *	echo $form->input('tese', 
 *						array(
 *							  'type' => 'select'
 *							, 'options' => array(
 *												  array('optgroup' => true, 'label' => 'grupo')
 *													, array('value' => 'valor1', 'text' => 'option 1', 'disabled' => 'disabled')
 *													, array('value' => 'valor2', 'text' => 'option 2')
 *												, array('optgroup' => 'start', 'label' => 'grupo 2')
 *													, array('value' => 'valor3', 'text' => 'option 3', 'selected' => true)
 *												, array('optgroup' => 'end')
 *												, array('value' => 'valor4', 'text' => 'option 4')
 *												, array('value' => 'valor5', 'text' => 'option 5')
 *											)
 *							, 'class' => 'classCss'
 *							, 'label' => 'Teste de select'
 *							, 'divForLabel' => true
 *							, 'divForInput' => true
 *						)
 *					);
 *	echo $form->input('submit', array('type' => 'submit', 'id' => false));
 *	echo '<br />';
 *	echo $form->end();
 * 
 */
class FormHelper extends HtmlHelper {
	
	/**
	 * Helpers dependentes
	 * @var array
	 */
	var $helpers = array();
	
	/**
	 * Possiveis tipos de inputs
	 * @var array
	 */
	var $inputsType = array(
					  'button'
					, 'checkbox'
					, 'file'
					, 'hidden'
					, 'image'
					, 'password'
					, 'radio'
					, 'reset'
					, 'submit'
					, 'text'
					, 'textarea'
					, 'select'
				);
	
	/**
	 * Monta um input com o type definido pelo $options['type'], chamando as suas perpectivas funções, tratando todos os $options informados.
	 * 
	 * @ex
	 * echo $form->input('textExample',  array(
	 *		 								  'type' => 'text' //Type do input, aceita os seguintes valores: 'button', 'checkbox', 'file', 'hidden', 'image', 'password', 'radio', 'reset', 'submit', 'text', 'textarea', 'select'; **Mais documentáção individual
	 *		 								, 'divForLabel' => true //Terá uma div para o label, você pode setar mais valores como id, class.... ex: 'divForLabel' => array('class' => 'testLabel', 'id' => 'idDivLabel'
	 *		 								, 'divForInput' => 'classDivInput' //Terá uma div para o input, você pode setar mais valores como id, class.... ex: 'divForInput' => array('class' => 'test', 'id' => 'idDivInput')
	 *		 								, 'label' => 'Exemplo' //Label do input
	 *		 								, 'class' => 'classExample' //Class Css
	 *		 								, 'id' => 'textExample' //Se não informado, o id será o $fieldName, se não quiser utilizar id, só setar como false
	 *		 								, 'useClassDefaultOf' => true //Default value input and true
	 *		 								, 'maxlength' => 60
	 *									)
	 *			);
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function input($fieldName, $options = array()){
		$out = null;
		
		//Não foi informado o tipo do input
		if(!isset($options['type'])){
			$options['type'] = 'text';
		}
		
		if(isset($options['type'])){
			if(in_array($options['type'], array('psword', 'passwd', 'password')))
				$options['type'] = 'password';
			elseif(in_array($options['type'], array('options')))
				$options['type'] = 'select';
			elseif(!in_array($options['type'], $this->inputsType)) $options['type'] = 'text';
		}
		switch($options['type']){
			case 'checkbox': 
				$out .= $this->checkbox($fieldName, $options);
			break;
			case 'radio': 
				$out .= $this->radio($fieldName, $options);
			break;
			case 'text':
				$out .= $this->text($fieldName, $options);
			break;
			case 'password':
				$out .= $this->password($fieldName, $options);
			break;
			case 'hidden':
				$out .= $this->hidden($fieldName, $options);
			break;
			case 'submit':
				$out .= $this->submit($fieldName, $options);
			break;
			case 'reset':
				$out .= $this->reset($fieldName, $options);
			break;
			case 'button':
				$out .= $this->button($fieldName, $options);
			break;
			case 'file':
				$out .= $this->file($fieldName, $options);
			break;
			case 'image':
				$out .= $this->image($fieldName, $options);
			break;
			case 'textarea':
				$out .= $this->textarea($fieldName, $options);
			break;
			case 'select':
				$out .= $this->select($fieldName, $options);
			break;
			
		}
		return $out;	
	}

	/**
	 * Monta um input com o type text, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function text($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'text' ou se não for 'text', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'text';
		elseif($options['type'] != 'text')
			$options['type'] = 'text';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type password, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function password($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'password' ou se não for 'password', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'password';
		elseif($options['type'] != 'password')
			$options['type'] = 'password';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type hidden, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function hidden($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'hidden' ou se não for 'hidden', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'hidden';
		elseif($options['type'] != 'hidden')
			$options['type'] = 'hidden';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type submit, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function submit($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'submit' ou se não for 'submit', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'submit';
		elseif($options['type'] != 'submit')
			$options['type'] = 'submit';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type reset, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function reset($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'reset' ou se não for 'reset', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'reset';
		elseif($options['type'] != 'reset')
			$options['type'] = 'reset';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type button, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function button($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'button' ou se não for 'button', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'button';
		elseif($options['type'] != 'button')
			$options['type'] = 'button';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type file, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function file($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'file' ou se não for 'file', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'file';
		elseif($options['type'] != 'file')
			$options['type'] = 'file';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type image, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function image($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'image' ou se não for 'image', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'image';
		elseif($options['type'] != 'image')
			$options['type'] = 'image';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type checkbox, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function checkbox($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'checkbox' ou se não for 'checkbox', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'checkbox';
		elseif($options['type'] != 'checkbox')
			$options['type'] = 'checkbox';
		
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input com o type radio, tratando todos os $options informados, Esta funcão é um apelido para simpleInput()
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function radio($fieldName, $options = array()){
		/** Se não foi informado o type deste input receberá 'radio' ou se não for 'radio', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'radio';
		elseif($options['type'] != 'radio')
			$options['type'] = 'radio';
			
		return $this->simpleInput($fieldName, $options);
	}
	
	/**
	 * Monta um input (simple = checkbox, radio, text, password, hidden, submit, reset, button, file, image), tratando todos os $options informados.
	 *  É tratado com especialidades os seguintes keys do options:
	 * class
	 * useClassDefaultOf [default = true]
	 * id
	 * value
	 * disabled
	 * label
	 * divForLabel
	 * divForInput
	 * maxlength
	 * Entre outros...
	 * 
	 * @ex
	 * echo $form->input('simpleInputExample',  array(
	 *								  'type' => 'text'
	 *								, 'divForLabel' => true
	 *								, 'divForInput' => 'classDivInput'
	 *								, 'label' => 'Exemplo para simpleInput'
	 *								, 'class' => 'classInput'
	 *							)
	 *			);
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function simpleInput($fieldName, $options = array()){
		$out = null;
		$label = null;
		$input = null;
		
		/** Se não foi informado o type deste input receberá 'text'*/
		if(!array_key_exists('type', $options))
			$options['type'] = 'text';
		
		/** Se não existir o indice id, ele receberá o nome do input 'fieldName' */
		if(!array_key_exists('id', $options)) $options['id'] = $fieldName;
		
		/** Se não existir o indice class, ele receberá um class vazio para utilização das class defauts*/
		if(!array_key_exists('class', $options)) $options['class'] = null;
		
		/** Se existir o indice label, o nosso input terá um label, 
		 * Se houver um outro indice chamado divForLabel e não for false, o nosso label terá uma div só para ela.
		 */
		if(array_key_exists('label', $options)){
			if(array_key_exists('divForLabel', $options)){
				$label = $this->divForLabel($options);
			}else $label = $this->labelInArray($options);
		}
		$out .= $label;
		
		/** Se não existir o indice useClassDefaultOf ele irá adicionar este indice com o valor 'input', que será utilizado para class default */
		if(!array_key_exists('useClassDefaultOf', $options))
			$options = array_merge(array('useClassDefaultOf' => 'input'), $options);
		else{
			if($options['useClassDefaultOf'] === true)
				$options['useClassDefaultOf'] = 'input';
			elseif($options['useClassDefaultOf'] === false)
				unset($options['useClassDefaultOf']);
		}
		
		/** Se hover um $options com o checked ele rerá tratado */
		if(array_key_exists('checked', $options)){
			
			/** Se o valor do checked for igual a true então $options['checked'] irá receber checked se não será destroida o indice checked do $options */
			if($options['checked'] === true) 
				$options['checked'] = 'checked'; 
			elseif($options['checked'] === false) 
				unset($options['checked']);
			elseif($options['checked'] !== 'checked') unset($options['checked']);
		}
		
		$input = sprintf($this->tags['input'], $fieldName, $this->getAttrs($options, $this->notsAttrs));
		
		/**
		 * Se existir o indice divForInput, o nosso input terá uma div para ele.
		 */
		if(array_key_exists('divForInput', $options)){
			if($options['divForInput'] !== false || is_array($options['divForInput'])){
					if($options['divForInput'] === true) $options['divForInput'] = null;
					$input = $this->div($input, $options['divForInput']);
				}elseif($options['divForInput'] == false) $input = $input;
		}
		
		$out .= $input;
		return $out;
	}
	
	
	/**
	 * Monta um textarea, tratando todos os $options informados
	 * @ex
	 * echo $form->input('textArea',  array(
	 *								  'type' => 'textarea'
	 * 								, 'rows' => '10'
	 *								, 'divForLabel' => true
	 *								, 'divForInput' => 'classDivInput'
	 *								, 'label' => 'Exemplo'
	 *								, 'class' => false
	 *							)
	 *			);
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @param string $value string [optional] Valor para o text area
	 * @return string
	 */
	public function textarea($fieldName, $options = array(), $value = null){
		$out = null;
		$label = null;
		$input = null;
		
		/** Se não foi informado o type deste input receberá 'textarea' ou se não for 'textarea', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'textarea';
		elseif($options['type'] != 'textarea')
			$options['type'] = 'textarea';
		
		/** Se não existir o indice id, ele receberá o nome do input 'fieldName' */
		if(!array_key_exists('id', $options)) $options['id'] = $fieldName;
		
		/** Se não existir o indice class, ele receberá um class vazio para utilização das class defauts */
		if(!array_key_exists('class', $options)) $options['class'] = null;
		
		/** Se existir o indice label, o nosso input terá um label, 
		 * Se houver um outro indice chamado divForLabel e não for false, o nosso label terá uma div só para ela.
		 */
		if(array_key_exists('label', $options)){
			if(array_key_exists('divForLabel', $options)){
				$label = $this->divForLabel($options);
			}else $label = $this->labelInArray($options);
		}
		$out .= $label;
		
		/** Se não existir o indice useClassDefaultOf ele irá adicionar este indice com o valor 'input', que será utilizado para class default */
		if(!array_key_exists('useClassDefaultOf', $options))
			$options = array_merge(array('useClassDefaultOf' => 'input'), $options);
		else{
			if($options['useClassDefaultOf'] === true)
				$options['useClassDefaultOf'] = 'input';
			elseif($options['useClassDefaultOf'] === false)
				unset($options['useClassDefaultOf']);
		}
		
		$notsAttrs = array_merge($this->notsAttrs, array('type', 'value'));
		
		if(!array_key_exists('value', $options)) $options['value'] = false;
		if($value !== null) $options['value'] = $value;
		
		$input = sprintf($this->tags['tetarea'], $fieldName, $this->getAttrs($options, $notsAttrs), $options['value']);
		
		/**
		 * Se existir o indice divForInput, o nosso input terá uma div para ele.
		 */
		if(array_key_exists('divForInput', $options)){
			if($options['divForInput'] !== false || is_array($options['divForInput'])){
					if($options['divForInput'] === true) $options['divForInput'] = null;
					$input = $this->div($input, $options['divForInput']);
				}elseif($options['divForInput'] == false) 
					$input = $input;
		}
		
		$out .= $input;
		return $out;
	} 

	/**
	 * Monta um select, tratando todos os $options informados
	 * @ex
	 * echo $this->input('fieldName', 
	 *			array(
	 *					  'type' => 'select'
	 *					, 'options' => array(
	 *										  array('optgroup' => true, 'label' => 'grupo')
	 *											, array('value' => 'valor1', 'text' => 'option 1', 'disabled' => 'disabled')
	 *											, array('value' => 'valor2', 'text' => 'option 2')
	 *										, array('optgroup' => 'start', 'label' => 'grupo 2')
	 *											, array('value' => 'valor3', 'text' => 'option 3', 'selected' => true)
	 *										, array('optgroup' => 'end')
	 *										, array('value' => 'valor4', 'text' => 'option 4')
	 *										, array('value' => 'valor5', 'text' => 'option 5')
	 *									)
	 *					, 'class' => 'classCss'
	 *					, 'label' => 'Teste de select'
	 *					, 'divForLabel' => true
	 *					, 'divForInput' => true
	 *				)
	 *			);
	 * @param string $fieldName Nome do input
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function select($fieldName, $options = array()){
		$out = null;
		$label = null;
		$input = null;
		$keyOfOptions = null;
		$group = false;
		$optionsForSelect = null;
		
		/** Se não foi informado o type deste input receberá 'select' ou se não for 'select', agora será */
		if(!array_key_exists('type', $options))
			$options['type'] = 'select';
		elseif($options['type'] != 'select')
			$options['type'] = 'select';
		
		/** Se não existir o indice id, ele receberá o nome do input 'fieldName' */
		if(!array_key_exists('id', $options)) $options['id'] = $fieldName;
		
		/** Se não existir o indice class, ele receberá um class vazio para utilização das class defauts */
		if(!array_key_exists('class', $options)) $options['class'] = null;
		
		/** Se existir o indice label, o nosso input terá um label, 
		 * Se houver um outro indice chamado divForLabel e não for false, o nosso label terá uma div só para ela.
		 */
		if(array_key_exists('label', $options)){
			if(array_key_exists('divForLabel', $options)){
				$label = $this->divForLabel($options);
			}else $label = $this->labelInArray($options);
		}
		$out .= $label;
		
		/** Se não existir o indice useClassDefaultOf ele irá adicionar este indice com o valor 'input', que será utilizado para class default */
		if(!array_key_exists('useClassDefaultOf', $options))
			$options = array_merge(array('useClassDefaultOf' => 'input'), $options);
		else{
			if($options['useClassDefaultOf'] === true)
				$options['useClassDefaultOf'] = 'input';
			elseif($options['useClassDefaultOf'] === false)
				unset($options['useClassDefaultOf']);
		}
		

		$notsAttrs = array_merge($this->notsAttrs, 
											array(
												  'type'
												, 'value'
												, 'optgroup'
												, 'group'
												, 'optionGroup'
												, 'text'
												, 'values'
												, 'options'
												, 'option'
											)
								);
		
		/** Pega o key que foi passado os valores para os options */
		if(array_key_exists('values', $options)) 
			$keyOfOptions = 'values';
		elseif(array_key_exists('options', $options))
			$keyOfOptions = 'options';
		elseif(array_key_exists('option', $options))
			$keyOfOptions = 'option';
		
		if($keyOfOptions !== null){
			if(is_array($options[$keyOfOptions])){
				foreach($options[$keyOfOptions] as $key => $value){
					$keyOfGroup = null;
					if(is_array($value)){
						
						if(array_key_exists('optgroup', $value)) 
							$keyOfGroup = 'optgroup';
						elseif(array_key_exists('group', $value))
							$keyOfGroup = 'group';
						elseif(array_key_exists('optionGroup', $value))
							$keyOfGroup = 'optionGroup';

						if(array_key_exists('label', $value)) 
							$label = $value['label'];
						elseif(array_key_exists('text', $value))
							$label = $value['text'];
						else
							$label = null;
						
						if($keyOfGroup !== null){
							if($value[$keyOfGroup] === true || $value[$keyOfGroup] == 'start'){
								if($group === true){
									$group = false;
									$optionsForSelect .= sprintf($this->tags['optionGroupEnd'])."\r\n";
								}
								$group = true;
								$optionsForSelect .= sprintf($this->tags['optionGroupStart'], $label, $this->getAttrs($value, $notsAttrs))."\r\n";
							}elseif($value[$keyOfGroup] === false || $value[$keyOfGroup] == 'end'){
								$group = false;
								$optionsForSelect .= sprintf($this->tags['optionGroupEnd'])."\r\n";
							}
						}else{
							if(array_key_exists('value', $value)) 
								$valueOption = $value['value']; 
							else 
								$valueOption = null;
							if($group === true) $space = '	'; else $space = null;
							
							if(array_key_exists('selected', $value)){
								if($value['selected'] === true) 
									$value['selected'] = 'selected';
								elseif($value['selected'] === false) 
									unset($value['selected']);
								elseif($value['selected'] == 'selected')
									$value['selected'] = 'selected';
								else
									unset($value['selected']);
							}
							
							if(array_key_exists('disabled', $value)){
								if($value['disabled'] === true) 
									$value['disabled'] = 'true';
								elseif($value['disabled'] === false) 
									unset($value['disabled']);
								elseif($value['disabled'] == 'disabled')
									$value['disabled'] = 'true';
								else
									unset($value['disabled']);
							}
							$optionsForSelect .= $space.sprintf($this->tags['selectOption'], $valueOption, $this->getAttrs($value, $notsAttrs), $label)."\r\n";
						}
					}
				}
				if($group === true){
					$group = false;
					$optionsForSelect .= sprintf($this->tags['optionGroupEnd']);
				}
			}elseif($options[$keyOfOptions] === false) $optionsForSelect = null;
		}
				
		$input .= sprintf($this->tags['selectStart'], $fieldName, $this->getAttrs($options, $notsAttrs))."\r\n";
		$input .= $optionsForSelect."\r\n";
		$input .= sprintf($this->tags['selectEnd'])."\r\n";
		
		/**
		 * Se existir o indice divForInput, o nosso input terá uma div para ele.
		 */
		if(array_key_exists('divForInput', $options)){
			if($options['divForInput'] !== false || is_array($options['divForInput'])){
					if($options['divForInput'] === true) $options['divForInput'] = null;
					$input = $this->div($input, $options['divForInput']);
				}elseif($options['divForInput'] == false) 
					$input = $input;
		}
		
		$out .= $input;
		return $out;
	}
	
	/**
	 * Monta o início do form
	 * @param string $formName [optional] Nome do form
	 * @param array $options [optional] Opções
	 * @return string
	 */
	public function start($formName = null, $options = array()){
		$options = $this->isArray($options);
		
		/** Se informado o $formName então $options['name'] será o valor informado */
		if($formName !== null) $options['name'] = $formName;
		
		/** Se não existir o indice name $options['name'] será null */
		if(!array_key_exists('name', $options)) $options['name'] = '';
		
		/** Se não existir o indice id, ele receberá o nome do input '$formName' */
		if(!array_key_exists('id', $options)) $options['id'] = $options['name'];		
	
		/** Se não existir o indice action, o action será null */
		if(!array_key_exists('action', $options)) $options['action'] = '';
		
		/** Se não existir o indice method, o method será post */
		if(!array_key_exists('method', $options)) $options['method'] = 'post';
		
		return sprintf($this->tags['formStart'], $this->getAttrs($options, $this->notsAttrs));
	}
	
	/**
	 * Monta o fim do form
	 * @return string
	 */
	public function end(){
		return sprintf($this->tags['formEnd']);
	}
}