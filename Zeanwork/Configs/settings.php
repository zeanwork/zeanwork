<?php
/**
 * Configurações, difinições e incluções do Zeanwork.
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Config
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 214 $
 * @lastModified	$LastChangedDate: 2010-06-24 21:34:56 -0300 (Qui, 24 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/*
 * Includes do Zeanwork
 */
include_once(LIBS . "zeanwork.php");
$timeStartImportsZeanwork = microtime();
	Zeanwork::import(LIBS, array(
								  'validation'
								, 'configure'
								, 'log'
								, 'error'
								, 'util'
								, 'dispatcher'
							)
					);
Configure::write('infos.time.importsZeanwork', Helper::getTimeEnd($timeStartImportsZeanwork));

/*
 * Verção do Zeanwork
 */
Configure::write('zeanworkVersion', '0.1.0');

/*
 * Link do site do Zeanwork
 */
Configure::write('zeanworkWebSite', 'http://zeanwork.com.br');

/*
 * Link da documentação do Zeanwork
 */
Configure::write('zeanworkDoc', 'http://zeanwork.com.br/doc');

/*
 * Extenção de arquivos padrão (é acrescentado .php em cada arquivo solicidado pelo zeanwork)
 */
Configure::write('defaultExtension', 'html');

/*
 * Chama o depurador de erros do Zeanwork
 */
Debugger::invoke();
