<?php
/**
 * Arquivo de view
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Views.Errors
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 206 $
 * @lastModified	$LastChangedDate: 2010-06-18 20:15:40 -0300 (Sex, 18 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

$this->pageTitle = 'Element não encontrado';

?>

<p>O arquivo de elemento solicitado não foi encontrado.
Verifique se existe o arquivo <code><?php echo basename($data['url']); ?></code> em <code><?php echo str_replace('\\', '/', str_replace(ROOT, '', dirname($data['url']))); ?></code>

</p>
<br />
<div style="text-align:right; font-size:10px; color:#999">
	Se você quiser personalizar essa mensagem de erro, crie o arquivo <code style="color:#999">element.<?php echo Configure::read('defaultExtension') ?>.php</code> em <code style="color:#999">App/Views/Errors/</code>
</div>