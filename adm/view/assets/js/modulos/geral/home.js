//FUNÇÕES GERAIS
require(['funcoes_gerais','config_modulo','config_mensagens'], function (funcoes,config,config_msg) {
  var funcoes    = new funcoes(path_raiz, path);
  var config     = new config(path_raiz, path);
  var config_msg = new config_msg(path_raiz, path);

  //***************************************************************************************************************************************************************
  //CONFIGURAÇÕES GERAIS
  //***************************************************************************************************************************************************************

  //CHAMO REQUISIÇÃO AJAX JQUERY
  function jquery_ajax_return(funcao, path, type, type2, serialize, campo_load) {
    funcoes.jquery_ajax_return(funcao, path_raiz + path, type, type2, serialize, campo_load);
  }

    //=================================================================
    //INCLUDES GERAIS
    require(['jquery_ui']);
    require(['bootstrap'], function (tooltip) {
        $('[data-title="tooltip"]').tooltip();
    });
    require(['app']);
	require(['slimScroll']);
    require(['feedback'], function (feedback) {
        feedback = new feedback();
    }); //feedback

    //=================================================================
    //CHAMO A LISTAGEM
    require(['funcoes_geral'], function (geral) {
        geral.listagem('');
        //geral.listagem(''); //Outras listagens
    });

  //OCULTO TELA DE CARREGANDO
  $('.fakeloader').fadeOut(200);

});
