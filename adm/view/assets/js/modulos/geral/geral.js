//FUNÇÕES GERAIS
require(['funcoes_gerais','config_modulo','config_mensagens','funcoes_geral'], function (funcoes,config,config_msg,geral) {
  var funcoes    = new funcoes(path_raiz, path);
  var config     = new config(path_raiz, path);
  var config_msg = new config_msg(path_raiz, path);

//***************************************************************************************************************************************************************
//CONFIGURAÇÕES GERAIS
//***************************************************************************************************************************************************************

    //CHAMO REQUISIÇÃO AJAX JQUERY
    function jquery_ajax_return(funcao, path, type, type2, serialize, campo_load, img, data_dados) {
        funcoes.jquery_ajax_return(funcao, path_raiz + path, type, type2, serialize, campo_load, img, data_dados);
    }

    //=================================================================
    //INCLUDES GERAIS
    require(['jquery_ui']);
    require(['app']);
  	require(['sweetalert']);
    require(['feedback'], function (feedback) {
        feedback = new feedback();
    }); //feedback

    //=================================================================
    //INCLUO ATALHOS TECLADO
    require(['jquery-hotkeys'], function () {

        //CAMPO PESQUISA
        $(document).bind('keydown', 'alt+p', function(evt) {
            $('#campo_pesquisa').focus();
            evt.stopPropagation( );
            evt.preventDefault( );
            return false;
        });
        //CAMPO DESLOGAR
        $(document).bind('keydown', 'alt+l', function(evt) {
            var logoff = $('.params').attr('logoff');
            funcoes.redireciono(logoff);
            evt.stopPropagation( );
            evt.preventDefault( );
            return false;
        });

    });



//***************************************************************************************************************************************************************
//LISTAGEM, PESQUISA AVANÇADA, EXPORTAR, IMPRIMIR, DETALHAMENTO DE REGISTRO, EXCLUIR, ATIVAR, DESATIVAR
//***************************************************************************************************************************************************************

    //=================================================================
    //CHAMO A LISTAGEM
    require(['funcoes_geral'], function (geral) {
        geral.listagem('');
        //geral.listagem(''); //Outras listagens
    });

    //=================================================================
    //OUTROS JAVASCRIPT
    jquery_ajax_return(
        outros_js, //função de retorno
        $('.params').attr('outros_js'), //url
        "json", //tipo
        "POST", //method
        "" //serialize
    );
    function outros_js(result) {
        for (var p in result) {
            //LISTAGEM
            if(result[p]['tipo'] == 'listagem'){
              require(['funcoes_geral'], function (geral) {
                  geral.listagem(result[p]['param_listagem']);
              });
            }
            funcoes.loading_geral('hide','', ''); // oculto loading
        }
    }

    //=================================================================
    //OCULTO TELA DE CARREGANDO
    $('.fakeloader').fadeOut(200);
});
