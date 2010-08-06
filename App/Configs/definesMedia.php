<?php
/**
 * Define as constantes para as mídias, tais como: CSS, JS, IMG e SWF
 * 
 * Edite o valor das constantes caso você não esteja utizando (mod_rewrite)
 * 
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.App.Config
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 161 $
 * @lastModified	$LastChangedDate: 2010-04-10 11:34:29 -0300 (Sáb, 10 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * URL dos css
 * @var string
 */
define('CSS',  APP_HOST . 'Css' . '/');

/**
 * URL dos javascript
 * @var string
 */
define('JS', APP_HOST . 'Scripts' . '/');

/**
 * URL das imagens
 * @var string
 */
define('IMG', APP_HOST . 'Images' . '/');

/**
 * URL dos swf`s
 * @var string
 */
define('SWF', APP_HOST . 'Swf' . '/');
