<?php
/**
 * Apartir daqui que as coisas acontecem, esta é a página que importará os dados das view e comtrollers.
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Public
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 48 $
 * @lastModified	$LastChangedDate: 2010-02-27 16:18:59 -0300 (Sáb, 27 Fev 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Inclui o Zeanwork, importando todas as configurações do mesmo e do aplicativo, para seu perfeito funcionamento.
 */
include_once('../Zeanwork/initialize.php');

Zeanwork::getInstance('Dispatcher')->dispatch();
