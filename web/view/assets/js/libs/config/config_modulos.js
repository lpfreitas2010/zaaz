define(['jquery'], function ($) {
    function config(path_raiz, path) {

        //=================================================================
        //PAGINAS DA APLICAÇÃO
        this.paginas = function () {
            var paginas = [];
            paginas[0] = 'index';
            paginas[1] = 'erro';

            return paginas;
        };

        //=================================================================
        //CONTROLLES DA APLICAÇÃO MONTADO
        this.controllers = function () {
            var controller = [];

            //CONTROLLERS
            controller[0] = 'coreController.php';

            return controller;
        };

        //=================================================================
        //RETORNO PATCH CONTROLLER
        this.path_controller = function ($param) {
            if($param === undefined || $param === ''){
              return "web/controller/";
            }else{
              return path_raiz+"web/controller/";
            }
        };



    }
    //Retorno modulo
    return config;
});
