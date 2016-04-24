//FUNÇÕES GERAIS
require(['funcoes_gerais'], function (funcoes) {
  var funcoes = new funcoes(path_raiz, path);

    //=================================================================
    //INCLUDES GERAIS
    require(['bootstrap']);
	require(['bootstrap-growl']),//bootstrap-growl para notificacoes

    //FOCO CAMPO USUARIO
    $('#username').focus();

    //INCLUDE DE FORM VALIDAÇÃO
    require(['funcoes_form_validacao'], function (form_validacao) {
        form_validacao = new form_validacao(path_raiz, path);

        //=================================================================
        //SUBMIT DE FORM COM RETORNO EM JSON (FORM SIMPLES) LOGIN
        form_validacao.submit_form_json(
          "#form-login", //id do form
          "#button-enviar-login", //id do botão submit
          "<i class=\"fa fa-refresh fa-pulse\"></i> PROCESSANDO ... ", //Texto no botão submit ao carregar
          "", //Classe que carrega a imagem de carregando
          "", //Imagem de carregando
          '#resul_sucesso_login', //Mensagem de Sucesso
          '#resul_erro_login', //Mensagem de Erro
          'false', //Auto hidden sucesso (true or false)
          'false', //Auto hidden erro (true or false)
          '15000', //Tempo hidden
          'false', //autoclean somente em sucesso (true or false)
          'false' //Subir para o topo a cada submit (true or false)
         // '.group-erros-login' //submit ao focar
        );

        //----------------------------------------------------------------------------
        //SUBMIT DE FORM COM RETORNO EM JSON (FORM SIMPLES) CADASTRO
        form_validacao.submit_form_json(
          "#form-esqueci_senha", //id do form
          "#button_esqueci", //id do botão submit
          "<i class=\"fa fa-refresh fa-pulse\"></i> PROCESSANDO ... ", //Texto no botão submit ao carregar
          "", //Classe que carrega a imagem de carregando
          "", //Imagem de carregando
          '#resul_sucesso_esqueci_senha', //Mensagem de Sucesso
          '#resul_erro_esqueci_senha', //Mensagem de Erro
          'false', //Auto hidden sucesso (true or false)
          'false', //Auto hidden erro (true or false)
          '15000', //Tempo hidden
          'true', //autoclean somente em sucesso (true or false)
          'false' //Subir para o topo a cada submit (true or false)
          //'.group-erros-cadastro' //submit ao focar
        );
    });

    //OCULTO TELA DE CARREGANDO
    $('.fakeloader').fadeOut(200);
});
