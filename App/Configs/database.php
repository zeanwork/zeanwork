<?php
/**
 * Configurações do banco de dados, todas de acordo com um determinado ambiente de trabalho. Podendo definir varios ambientes.
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
 * @version 		$LastChangedRevision: 48 $
 * @lastModified	$LastChangedDate: 2010-02-27 16:18:59 -0300 (Sáb, 27 Fev 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/*
 * Configuração do banco de dados 
 */
Configure::write('database', array(
								    'development' => array(
														  'persistent' => false
														, 'drive' => 'MySql'
														, 'host' => 'localhost'
														, 'user' => 'root'
														, 'password' => 'root'
														, 'database' => 'zeanwork'
														, 'prefix' => null
														, 'charset' => 'utf8'
												    )
							)
				);

/*
 * Auto conectar com o banco de dados
 */
Configure::write('auto.connect.database', false);

/*
 * Auto desconectar com o banco de dados
 */
Configure::write('auto.disconnect.database', false);
