<?php
/**
 * Class AppController
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Controllers
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 187 $
 * @lastModified	$LastChangedDate: 2010-05-11 16:00:22 -0300 (Ter, 11 Mai 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class AppController extends Controller {
	
	/**
	 * Helpers padrões para toda aplicação
	 * @var array
	 */
	public $defaultsHelpers = array();
	
	/**
	 * Components padrões para toda aplicação
	 * @var array
	 */
	public $defaultsComponents = array();
	
	/**
	 * Extensions padrões para toda aplicação
	 * @var array
	 */
	public $defaultsExtensions = array();
	
	/**
	 * Informações padrões para a geração de cache
	 * @var array
	 */
	public $defaultCache = array(
							/* Example:
								'ControllerName' => array(
														'ActionName' => 'LifeCache (in seconds)'
													)
							*/
							);
	
}
