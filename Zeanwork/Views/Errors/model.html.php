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

$this->pageTitle = 'Model não encontrado';

?>

<p>O modelo solicitado não foi encontrado.
Verifique se o arquivo <code><?php echo $data['model']; ?>.php</code> existe em <code>App/Models/</code> e se a classe <code><?php echo ucwords($data['model']); ?></code> esteja declarada no mesmo.
</p>
<p>
	Caso o arquivo não exista ou a classe não esteja declarada, crie em <code>App/Models/<?php echo $data['model']; ?>.php</code> com a seguinte estrutura abaixo.
</p>
<?php
highlight_string('<?php
class ' . ucwords($data['model']) . ' extends AppModel {
	
	public $table = \'' . $data['model'] . '\';
	
	public $primaryKey = \'id' . $data['model'] . '\';
	
	
}
');
?>
<br />
<div style="text-align:right; font-size:10px; color:#999">
	Se você quiser personalizar essa mensagem de erro, crie o arquivo <code style="color:#999">model.<?php echo Configure::read('defaultExtension') ?>.php</code> em <code style="color:#999">App/Views/Errors/</code>
</div>