<?php
/**
 * @author Josemar Davi Luedke <josemar@tca.com.br>
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
 * Define o charset a ser utilizado como padrão
 */
Configure::write('charset', 'UTF-8');


/*
 * 'AUTO'
 * 'REQUEST_URI' - Default
 * 'PATH_INFO'
 * 'QUERY_STRING'
 * 'ORIG_PATH_INFO'
 */
Configure::write('uriProtocol', 'REQUEST_URI');

/*
 * Se sua instalação do PHP não tem suporte a short tag,
 * basta habilitar a configuração "rewriteShortTags" e o Zeanwork reescreverá as tags da sua aplicação,
 * permitindo assim utilizar esta sintaxe.
 */
Configure::write('rewriteShortTags', false);

/*
 * Configurações para o cookie (Não nessesário)
 */
Configure::write('cookie.path', '/');
Configure::write('cookie.secure', false);
Configure::write('cookie.prefix', 'Zeanwork.');
Configure::write('cookie.domain', null);
Configure::write('cookie.expires', null);

/*
 * Configura para gerar erros na tela
 */
Configure::write('debugger', array(
								  'development' => true
								, 'published' => true
							)
				);

/*
 * Configura o sistema para gerar log's (Ativa os log's), definindo o ambiente onde esta configuração será aplicada
 */
Configure::write('logs', array('development' => array(
													  LOG_WARNING => true
													, LOG_DEBUG => true
													, LOG_ERR => true
													, LOG_ERROR => true
													, LOG_SECURITY => true
												),
								'published' => array(
													  LOG_WARNING => false
													, LOG_DEBUG => false
													, LOG_ERR => false
													, LOG_ERROR => false
													, LOG_SECURITY => true
												)
							));
/*
 * Seta as configurações de segurança
 */
Configure::write('security', array('encrypts' => array(
														'dataStart' => 'Kp4LU1S9DkFpRkCrKFQoGi3F4'
													  , 'dataEnd' => '1S9DkFpRkCrQoi34'
													)
								)
			);
/*
 * Seta o padrão de converção de datas
 */
Configure::write('dateFormat', 'dd/mm/aaaa');

/*
 * Seta o autoLayout quando for uma aquisição de ajax 'XMLHttpRequest'
 * Caso seje false, não será renderizado automaticamente o layout; caso seje true, deixará o padrão do controller.
 */
Configure::write('autoLayout.isAjax', false);

/*
 * Diz se erá utilizar multi idiomas
 */
Configure::write('useMultiLanguage', false);

/*
 * Seta o idioma 'FROM' (apartir deste idioma para a tradução)
 */
Configure::write('fromLanguage', 'pt-BR');

/*
 * Seta o idioma para a tradução
 */
Configure::write('toLanguage', 'pt-BR');

/*
 * Verção do Aplicativo
 */
Configure::write('aplicationVersion', '1');

/*
 * Desabilida o magic_quotes_gpc (false = desbilitado, true = deicha o padrão do servidor)
 */
Configure::write('magic_quotes_gpc', false);

/*
 * Seleciona o ambiente de trabalho
 */
Configure::write('environment', 'development');
