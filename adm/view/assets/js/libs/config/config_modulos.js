define(['jquery'], function ($) {
    function config(path_raiz, path) {

        //=================================================================
        //PAGINAS DA APLICAÇÃO
        this.paginas = function () {
            var paginas = [];
            return paginas;
        };

        //=================================================================
        //CONTROLLES DA APLICAÇÃO MONTADO
        this.controllers = function () {
            var controller = [];

            //CONTROLLERS
            controller[0] = '';

            return controller;
        };

        //=================================================================
        //RETORNO PATCH CONTROLLER
        this.path_controller = function ($param) {
            if($param === undefined){
              return "controller/";
            }else{
              return path_raiz+"adm/controller/";
            }
        };


    }
    //Retorno modulo
    return config;
});
