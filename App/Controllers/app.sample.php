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
 * @subpackage		Zeanwork.App.Controllers
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 186 $
 * @lastModified	$LastChangedDate: 2010-05-10 17:41:33 -0300 (Seg, 10 Mai 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class AppController extends Controller {
		
	/**
	 * Components padrões para toda aplicação
	 * @var array
	 */
	public $defaultsComponents = array();
	
	/**
	 * Helpers padrões para toda aplicação
	 * @var array
	 */
	public $defaultsHelpers = array();
	
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
