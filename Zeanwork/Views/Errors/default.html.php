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
 * @version 		$LastChangedRevision: 153 $
 * @lastModified	$LastChangedDate: 2010-04-07 21:04:50 -0300 (Qua, 07 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

$this->pageTitle = 'Error '.$data['type'];

?>

<p>
	<?php 
		if(array_key_exists('description', $data)){
			echo $data['description'];
		}else{
			echo 'Hove um erro inesperado, e não podemos continuar!';
		}
	?>
</p>
<br />
<div style="text-align:right; font-size:10px; color:#999">
	Se você quiser personalizar essa mensagem de erro, crie o arquivo <code style="color:#999"><?php echo $data['type'] . '.' . Configure::read('defaultExtension') ?>.php</code> em <code style="color:#999">App/Views/Errors/</code>
</div>