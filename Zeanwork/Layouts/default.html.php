<?php
/**
 * Layout padrão
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
		<meta name="google-site-verification" content="VnjHl_CogIVxIAteQ07S10kAPz_EAG_h01s-39R0ZVE" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $this->pageTitle; echo ($this->pageTitle != null) ? ' -' : ''; ?> Bem-vindo ao Zeanwork!</title>
		<style type="text/css">
			body 						{color:#FFFFFF; font:12px Arial, Helvetica, sans-serif; background:#3C3C3C; margin:0px; padding:10px;}
			* 							{color:#333333;}
			#body 						{width:800px; margin:auto; margin-top:15px; color:#333333;}
			#content					{background:#FFFFFF; border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; padding-right:10px; padding-left:10px; color: #222222; font-size:12px; min-height:300px; padding-bottom:10px;}
			.Border * 					{background:#FFFFFF; display:block; height:1px; overflow:hidden;}
			.BorderLayer4				{background:#D3E0F0 none repeat scroll 0 0; border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 4px; padding:0 1px;}
			.BorderLayer3				{border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 3px; padding:0;}
			.BorderLayer2				{border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 2px;}
			.BorderLayer1				{border-left:1px solid #D3E0F0; border-right:1px solid #D3E0F0; margin:0 1px;}
			.clear 						{clear:both;}
			.footer 					{text-align:right; margin-top:5px; color:#FFFFFF;}
			.logo 						{float:right; width:55px; height:36px; background:url(<?php echo IMG; ?>Zeanwork/poweredByLogo.png) no-repeat; cursor:pointer;}
			.logoTop 					{margin-bottom:20px; width:196px; height:39px; background:url(<?php echo IMG; ?>Zeanwork/allLogo.png) no-repeat; cursor:pointer;}
			.welcome 					{color:#282828; font-family:'DroidSansRegular',"Trebuchet MS",Trebuchet,Verdana,sans-serif; font-size:35px; margin-bottom:30px; text-shadow:0 1px 2px #999999; text-align:center;}
			.title 						{color:#282828; font-family:'DroidSansRegular',"Trebuchet MS",Trebuchet,Verdana,sans-serif; font-size:20px; text-shadow:0 1px 2px #999999;}
			.line 						{border-top: 1px solid #CCC; padding-top: 10px; margin:10px 0 0 10px;}
			.intro 						{ text-align: justify; margin:0 30px 0 30px }
			ul.infos 					{list-style: none; margin: 0; padding: 0; margin-left: 10px}
			ul.infos li 				{float:left; width: 170px; margin-right: 10px; margin-bottom: 10px; border: 1px solid #F0F0F0; padding: 5px;}
			ul.infos li .title 			{font-size: 14px; font-weight: bold; margin-bottom: 5px;}
			ul.nav 						{list-style: none; padding: 2em 0; margin:0; padding-top: 5px;}
			ul.nav li 					{display: inline; padding-right: 1em; font-weight: bold}
			ul.nav li a 				{padding: 0.5em 1em; background: #4C4C4C; border: 1px solid #666; color: #FFFFFF; text-decoration: none;}
			ul.nav li a:hover 			{background: #767676;}
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
			<div style="float:left; width: 300px"><?php echo $this->html->link('<div class="logoTop"></div>', APP_HOST, array('title' => 'Zeanwork Framework PHP')); ?></div>
			<div style="float:right; width: 300px; text-align: right;">
				<span style="color: #999; font-weight: bold;">Versão</span>
				<br />
				<span style="color: #FFF; font-size: 18px"><?php echo Configure::read('zeanworkVersion')?></span>
			</div>
			<div class="clear"></div>
			<center>
				<ul class="nav">
					<li><a href="<?php echo Configure::read('zeanworkWebSite'); ?>" target="_black">Página inicial</a></li>
					<li><a href="<?php echo Configure::read('zeanworkWebSite'); ?>/doc?version=<?php echo Configure::read('zeanworkVersion');?>" target="_black">Documentação</a></li>
					<li><a href="<?php echo Configure::read('zeanworkWebSite'); ?>/tutoriais" target="_black">Tutoriais</a></li>
					<li><a href="<?php echo Configure::read('zeanworkWebSite'); ?>/forum" target="_black">Fórum</a></li>
					<li><a href="<?php echo Configure::read('zeanworkWebSite'); ?>/blog" target="_black">Blog</a></li>
					<li><a href="<?php echo Configure::read('zeanworkWebSite'); ?>/license" target="_black">Licença</a></li>
					<li><a href="<?php echo Configure::read('zeanworkWebSite'); ?>/about" target="_black">Sobre</a></li>
					<li style="padding-right:0px;"><a href="http://twitter.com/Zeanwork" target="_black">Twitter</a></li>
				</ul>
			</center>
			<b class="Border">
				<b class="BorderLayer4"></b>	
				<b class="BorderLayer3"></b>
				<b class="BorderLayer2"></b>
				<b class="BorderLayer1"></b>	
			</b>
			<div id="content">
				<?php echo $this->contentForLayout; ?>
			</div>
			<b class="Border">
				<b class="BorderLayer1"></b>
				<b class="BorderLayer2"></b>
				<b class="BorderLayer3"></b>
				<b class="BorderLayer4"></b>
			</b>
			<div class="footer">
				<?php echo $this->html->link('<div class="logo"></div>', Configure::read('zeanworkWebSite'), array('target' => '_blank', 'title' => 'Zeanwork Framework PHP')); ?>
				<div class="clear"></div>
			</div>
		</div>
	</body>
</html>