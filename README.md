<h1>Zaaz Mini framework</h1>

O Zaaz é um mini framework php que foi concebido para facilitar o desenvolvimento de projetos, padronização de pastas e módulos das aplicações, foi desenvolvido com o conceito MVC, Controller, Model e View. Inclui como padrão o Módulo Administrativo com o controle de usuários;

***

<h1>Versão Atual - 1.0.0</h1>
**BUGS CONHECIDOS**
* ADMIN - O plugin jquery de select personalizado não funciona corretamente em requisições dinâmicas;
* ADMIN - A restrição de gráficos não foi totalmente tratada;
* ADMIN - As vezes ao tentar logar o sistema autentica e redireciona para a página de lockscreen;
***

<h2>DADOS DE ACESSO</h2>
* username: **desenvolvedor**
* senha: **acd410dfgt50**

***

<h2>REQUISITOS:</h2>
* PHP 5.2 ou superior;
* Banco de Dados Mysql 5.0 ou superior;
* Servidor: Apache;
* SO: Linux;

***

<h2>SCRIPTS EXTERNOS</h2>
* Controle de template: **smarty php**;
* Controle de disparo de e-mails: **phpmailer**;
* Controle de disparo de sms: **zenvia gateway**;
* Controle de geração de pdf: **mpdf**;
* Controle de redimensionamento de imagens: **WideImage**;
* Controle de geração de gráficos: **google chart**;
* Controle de javascripts: **requirejs**;
* Controle de CSS: **SASS e Compass**;
* Template do Admin: **AdminLTE https://almsaeedstudio.com/ **

***

<h2>PASTAS DO CORE</h2>
* **/adm** - Módulo administrativo do projeto;
* **/web** - Módulo do site do projeto;
* **/documentos** - Pasta com o banco de dados em sql, der e arquivo com instruções de utilização de alguns helpers do sistema;
* **/files** - Pastas com os arquivos upados do sistema. (imagens e documentos).
* **/libs** - Pasta com os arquivos do core do sistema.
* **/libs/core** - Pasta com o controlador principal do sistema e onde estão as configurações de toda a aplicação (banco de dados, smtp e outros).
* **/libs/helper** - Helpers são códigos que auxiliam o controller na execução de tarefas especificas. (ex: logs, view, upload ...).
* **/libs/inc** - Pasta com os códigos de terceiros. (phpmailer, mdpf, zenvia ...).
* **/libs/logs** - Pasta com os logs do sistema em txt. (logs de erros do banco de dados).

***

<h2>O QUE ESTA INCLUSO NO BANCO DE DADOS</h2>
* Tabelas do módulo administrativo;
* Tabelas de logs do administrativo;
* Tabelas de Estados e Cidades do Brasil;

***

<h2>FUNCIONALIDADES DO CORE</h2>
* Duas pastas de aplicação padrão adm e web, podendo ser criadas outras pastas de aplicação;
* Controle geral da aplicação em um único arquivo **(arquivo: libs/core/config.php)**;
* Controle de tempo de sessão ativa no sistema **(arquivo: libs/core/config.php)**;
* Controle de tempo que o usuário online é destruido do sistema **(arquivo: libs/core/config.php)**;
* Controle do status dos erros do sistema **(arquivo: libs/core/config.php)**;
* Utilização da função de criptografia mcrypt_encrypt do php **(arquivo: libs/core/config.php)**;
* Permite o cadastro e utilização de multiplas conexões smtp **(arquivo: libs/core/config.php)**;
* Permite o cadastro e utilização de multiplas conexões mysql pdo **(arquivo: libs/core/config.php)**;
* Urls amigáveis em toda a aplicação;
* Utilização do GZIP para compactação do conteúdo da página;
* Helper de backup que gera backups do banco de dados e backup de pasta e arquivos do projeto;
* Helper de conexão com o banco de dados que facilita as consultas e inserções no banco de dados;
* Helper de e-mail que facilita o envio de e-mails com o phpmailer;
* Helper de sms que facilita o envio de sms pelo gateway de sms da zenvia;
* Helper com funções gerais, funções de data, funções de validação de formulários, funções de autenticação e outras;
* Helper de logs, grava os logs das ações realizadas no banco de dados;
* Helper que monta diversos códigos html na tela. formulários, tabelas e outros;
* Helper que identifica o navegador e so utilizados;
* Helper de upload permite a validação e upload de imagens e documentos. Redução do tamanho dos upload das imagens em 3 tamanhos pequeno, médio e grande, o plugin para reduzir as imagens é o WideImage;
* Helper de controle de usuários online e controle de tempo de sessão;
* Helper de controle da view, o plugin de templates utilizado é o smarty php;

***

<h2>FUNCIONALIDADES DO ADMINISTRATIVO</h2>
* LOGIN - Possibilidade de realizar login no sistema por username, telefone ou email;
* LOGIN - Ao errar a senha por mais de três vezes o usuário receberá um sms e um e-mail avisando da atividade suspeita em sua conta;
* LOGIN - Ao errar a senha por mais de seis vezes o usuário será forçado a alterar sua senha de acesso;
* ESQUECI A SENHA - Ao clicar no link esqueci meus dados de acesso o usuário será redirecionado a uma página onde ele deverá informar o username, telefone ou o e-mail cadastrados no sistema, ao digitar as informações ele será redirecionado a uma outra página que solicitara um código, este código será enviado sms e no e-mail do usuário. Assim que ele digitar o código ele será direcionado a página onde ele podera cadastrar uma nova senha.
* GERAL - Ao digitar uma url que não existe o usuário será redirecionado para uma página de erro e a página que ele tentou acessar ficara grava nos logs do sistema;
* GERAL - Área para o usuário comunicar um erro no sistema, assim que o usuário comunica um erro todos os usuários do cargo desenvolvedor receberão e-mail e uma notificação sobre o feedback do usuário;
* GERAL - Área com as notificações do sistema, ao clicar em uma notificação ela será marcada como lida e será redirecionada para a página relacionada aquela notificação. O Usuário tem também a opção de ver todas as suas notificações em uma listagem com a possibilidade de alterar o status desta notificação através da listagem;
* GERAL - Na página inicial como padrão temos as seguintes informações: Links úteis, sms enviados e e-mails enviados no dia, semana e mês, listagem com os usuários on-line, listagem com as notificações não lidas, gráficos: navegadores utilizados, so utilizados, páginas acessadas, acessos dos usuários e login e logoff;
* GERAL - Página inicial e Menu lateral as opções são carregados de acordo com as restrições que os usuários tem no sistema;
* GERAL - Ao sair do sistema o usuário será redirecionado a página de tela de bloqueio onde para que ele possa retornar ao sistema basta ele digitar sua senha;
* CONFIGURAÇÕES - Menu Status - Temos os status do sistema. (não recomando mecher);
* CONFIGURAÇÕES - Menu Configs Admin - Temos as configurações do painel administrativo, configurações de envio de email, assinatura geral do email, configurações de envio de sms, configuração do tema do administrativo e outros;
* CONFIGURAÇÕES - Menu Backup - Realiza o backup do banco de dados, pasta de logs, pasta de arquivos e imagens;
* CONFIGURAÇÕES - Menu Cache do Sistema - Opção de excluir o cache do sistema, logs em txt do sistema;
* CONFIGURAÇÕES - Listagem com os feedbacks do sistema;
* RESTRIÇÕES ADMIN - Menu Módulos - Módulos do sistema de restrições;
* RESTRIÇÕES ADMIN - Menu Restrições Ações - Ações do sistema de restrições;
* RESTRIÇÕES ADMIN - Menu Restrições Default - Ações default que podem ser realizadas em cada módulo;
* USUÁRIOS ADMIN - Usuários - Gerencio os usuários do sistema.
* USUÁRIOS ADMIN - Usuários - Ao inativar um usuário do sistema ele automaticamente será deslogado do sistema;
* USUÁRIOS ADMIN - Usuários - Ao cadastar um usuário no sistema ele receberá um e-mail e um sms com o link para finalizar o cadastro;
* USUÁRIOS ADMIN - Usuários - Opção avançada: Botão forçar alteração da senha, ao clicar neste botão o usuário será obrigado a alterar sua senha de acesso no sistema;
* USUÁRIOS ADMIN - Usuários - Opção avançada: Botão Reenviar link para ativação, ao clicar neste botão o usuário recebera um SMS e um e-mail com o link para finalizar seu cadastro;
* USUÁRIOS ADMIN - Cargos - Listagem dos cargos dos usuários. Os cargos desenvolvedor e administrador são padrão e não podem ser excluidos e nem alterados no sistema;
* USUÁRIOS ADMIN - Restrições Usuários - Defino quais módulos e quais ações cada usuário tem acesso no sistema;
* USUÁRIOS ADMIN - Usuários online - Listagem com todos os usuários online no sistema, opção de deslogar um usuário do sistema;
* USUÁRIOS ADMIN - E-mails enviados - Listagem com todos os e-mails enviados pelo sistema;
* USUÁRIOS ADMIN - SMS enviados - Listagem com todos os SMS enviados pelo sistema;
* USUÁRIOS ADMIN - Logs dos Usuários - Listagem com os logs do sistema;
* USUÁRIOS ADMIN - Logs de Acesso dos Usuários - Listagem com os logs de acesso do sistema;

***

<h2>AÇÕES GERAIS DO ADMINISTRATIVO</h2>
* Geral - Criptografia nos parametros do controller;
* Geral - Menu lateral com a possibilidade de deixar ele aberto ou fechado;
* Geral - Layout responsivo;
* Geral - Lightbox em imagens do form e da listagem;
* Listagem - Listagem de registros do banco de dados;
* Listagem - Possibilidade de ter mais de 1 lista de listagem por página;
* Listagem - Pesquisa simples;
* Listagem - Filtro para pesquisa simples;
* Listagem - Pesquisa Avançada;
* Listagem - Ordenar registros da tabela com mais de 1 parametro;
* Listagem - Agrupar registros da tabela com mais de 1 parametro;
* Listagem - Escolher a quantidade de registros da tela;
* Listagem - Paginação dos registros;
* Listagem - Ir para uma página especifica;
* Listagem - Opção de checkar uma linha ou todas as linhas da tabela e executar uma ação;
* Ações - Acesso direto pela url a área do conteúdo detalhado (/detalho/id);
* Ações - Acesso direto pela url a área da pesquisa avançada (/pesquisa_avancada);
* Ações - Acesso direto pela url a área de adicionar um registro (/novo);
* Ações - Acesso direto pela url a área de editar registro (/editar/id);
* Ações - Acesso direto pela url a área de busca registro(/busca/algooo);
* Ações - Conteúdo detalhado de um registro da tabela;
* Ações - Excluir um registro do banco de dados;
* Ações - Adicionar um registro no banco de dados;
* Ações - Editar um registro no banco de dados;
* Ações - Ativar ou Desativar um registro do banco de dados;
* Ações - Montagem dos formulários automatizadas;
* Ações - Montagem das listagens de registros automatizadas;
* Campo form - Editor html com suporte a upload de imagens;
* Campo form - Máscara em campos dos formulários (livre, telefone, moeda e numeros);
* Campo form - Calendário em campos do formulário;
* Campo form - Listagem de arquivos upados com opção de exclusão;
* Campo form - 3 Opções para salvar (Salvar e editar, Salvar e Novo e Salvar e Fechar);
