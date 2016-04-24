define(['jquery'], function ($) {
    function config_msg(path_raiz, path) {


        //=================================================================
        //MENSAGENS DA APLICAÇÃO
        this.mensagens = function () {
            var mensagens = [];

            //GERAL
            mensagens[0] = 'Não foi possível se conectar a internet. Verifique sua conexão!';


            return mensagens;
        };


    }
    //Retorno modulo
    return config_msg;
});
