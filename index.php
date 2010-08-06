<?php
/**
 * Apartir daqui que as coisas acontecem, esta é a página que importará os dados das view e comtrollers.
 * 
 * Esta página somente irá ser executada caso você não esteja utizando (mod_rewrite).
 * Caso você não esta utilizando o mod_rewrite, edite as constantes das mídias (App/Configs/definesMedia.php)
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 224 $
 * @lastModified	$LastChangedDate: 2010-07-18 17:05:21 -0300 (Dom, 18 Jul 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Inclui o Zeanwork, importando todas as configurações do mesmo e do aplicativo, para seu perfeito funcionamento.
 */
include_once('Zeanwork/initialize.php');

Zeanwork::getInstance('Dispatcher')->dispatch();