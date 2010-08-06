<?php
/**
 * Neste arquivo, você criar rotas, prefixos e conexões entre URLs para sua aplicação.
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
 * Rotas são utilizadas para criar URLs mais amigaveis e assim `redirecionar` para determinadas areas da sua aplicação.
 * Os prefixos servem para separar diversas partes da sua aplicação, por exemplo: 
 * Você tem uma area administrativa em sua aplicação e deseja acessar pela url (www.seusite.com.br/admin),
 * para que você possa fazer isso sem criar uma pasta e colocar todos os arquivos do zeanwork e de sua aplicação novamente,
 * então podemos criar prefixos e assim poder utilizar controllers já existente em sua aplicação e somente criar novas actions para sue prefixo.
 * Conexões servem para conectar uma URL a outra e assim gerando URLs mais amigaveis, por exemplo:
 * Você tem um controlador de blog, para que o usuário visualize um post,
 * seria necessário acessar a seguinte url (www.seusite.com.br/blog/viewPost/?id=23&*) por exemplo, mas criando uma conexão entre URLs,
 * você pode gerar a seguinte url para que seu usuário acesse com facilidade (www.seusite.com.br/blog/23/?*)
 */ 

/*
 * Aqui você define o controller padrão de seu aplicativo, entrará em vigor, quando não informado o controller desejado
 */
Router::setRoot('home');

/*
 * Descomente a linha abaixo, para criar o prefixo 'admin', que seria útil para uma area administrativa. 
 * Você pode adicionar quantos prefixos você desejar.
 */ 
#Router::addPrefix('admin');

/*
 * Descomente a linha abaixo para criar uma conexão entre URLs de blog (Exemplo utilizado acima).
 * Você pode adicionar quantas conexões você desejar.
 */
#Router::connect("/blog/:num/:any", "/blog/viewPost/$1");

/*
 * Aqui você pode declarar filtros para os parametros nomeado Ex: id=23
 * Quando for informado um parametro com este nome (ex: id), será setado o valor para o tipo desejado (ex: integer)
 */
#Router::setFilterNamed('id', 'integer');
