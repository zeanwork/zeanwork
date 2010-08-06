<?php
/**
 * Layout padrão de erros do Zeanwork
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs.Layouts
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 208 $
 * @lastModified	$LastChangedDate: 2010-06-21 19:45:36 -0300 (Seg, 21 Jun 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

$this->load->helper('html');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $this->pageTitle; echo ($this->pageTitle != null) ? ' -' : ''; ?> Zeanwork Framework PHP</title>
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo IMG ?>Zeanwork/favicon.ico">
		<style type="text/css">
			body 						{color:#FFFFFF; font:12px Arial, Helvetica, sans-serif; background:#3C3C3C; margin:0px; padding:10px;}
			* 							{color:#FFFFFF;}
			#body 						{width:800px; margin:auto; margin-top:15px; color:#333333;}
			#content					{background:#FFFFFF; border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; padding-right:10px; padding-left:10px; color: #222222; font-size:11px; min-height:300px; padding-bottom:10px;}
			#content * 					{color:#333333;}
			.Border * 					{background:#FFFFFF; display:block; height:1px; overflow:hidden;}
			.BorderLayer4				{background:#D3E0F0 none repeat scroll 0 0; border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 4px; padding:0 1px;}
			.BorderLayer3				{border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 3px; padding:0;}
			.BorderLayer2				{border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 2px;}
			.BorderLayer1				{border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 1px;}
			.clear 						{clear:both;}
			.footer 					{text-align:right; margin-top:5px; color:#CCCCCC;}
			.footer a					{color: #888; text-decoration: none;}
			.logo 						{float:right; width:55px; height:36px; background:url(<?php echo IMG; ?>Zeanwork/poweredByLogo.png) no-repeat; cursor:pointer;}
			.logoTop 					{margin-bottom:20px; width:196px; height:39px; background:url(<?php echo IMG; ?>Zeanwork/allLogo.png) no-repeat; cursor:pointer;}
		</style>
		<!--[if IE 6]>
			<style type="text/css">
				.logoTop 				{background:none;filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo IMG; ?>Zeanwork/allLogo.png', sizingMethod='image');}
				.logo 					{background:none;filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo IMG; ?>Zeanwork/poweredByLogo.png', sizingMethod='image');}
			</style>
		<![endif]-->
	</head>
	<body>
		<div id="body">
			<?php echo $this->html->link('<div class="logoTop"></div>', Configure::read('zeanworkWebSite'), array('target' => '_blank', 'title' => 'Zeanwork Framework PHP')); ?>
			<div class="clear"></div>
			<b class="Border">
				<b class="BorderLayer4"></b>	
				<b class="BorderLayer3"></b>
				<b class="BorderLayer2"></b>
				<b class="BorderLayer1"></b>	
			</b>
			<div id="content">
				<span style="font-size: 55px; font-weight: bold; color:#8A8A8A;">Ops!</span>&nbsp;&nbsp;
				<span style="font-size: 35px; font-weight: bold; color:#8A8A8A;"><?php echo $this->pageTitle; ?>.</span><br /><br />
				<div style="border-bottom:1px solid #999; border-top:1px solid #999; padding:15px; width:90%; margin:auto;">
					<?php echo $this->contentForLayout; ?>
				</div>
				<br /><br />
			</div>
			<b class="Border">
				<b class="BorderLayer1"></b>
				<b class="BorderLayer2"></b>
				<b class="BorderLayer3"></b>
				<b class="BorderLayer4"></b>
			</b>
			<div class="footer">
				<?php echo $this->html->link('Zeanwork Framework PHP - '.date('Y'), Configure::read('zeanworkWebSite'), array('target' => '_blank', 'title' => 'Zeanwork Framework PHP')); ?>
				<div class="clear"></div>
			</div>
		</div>
	</body>
</html>