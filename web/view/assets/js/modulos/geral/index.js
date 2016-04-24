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

    //PEGO OS CMDS DO CONTROLLER DO PHP
    jquery_ajax_return(geral,config.path_controller()+config.controllers()[0]+'?cmd=LlyLtke69THje1bRy4U3P7eiTmxyyTjGW65RBSAW1LA=',"json","GET");
    function geral(cmds_controller){

        //MONTO O CONTROLLER
        function monto_cmds_controller(param1,param2){
              return '?cmd='+cmds_controller[param1][param2];
        }

        //MONTO O CONTROLLER COMPLETO
        function monto_controller_completo(param_path_raiz,param_controller,param_cmd_controller,param_cmd_controller2,param_cmd_controller3){ //path_raiz , controller, cmd_controller name, cmd_controller indice, outros parametros get
            if(param_cmd_controller3 === undefined || param_cmd_controller3 === ''){
                return config.path_controller(param_path_raiz) + config.controllers()[param_controller] + monto_cmds_controller(param_cmd_controller,param_cmd_controller2);
            }else{
                return config.path_controller(param_path_raiz) + config.controllers()[param_controller] + monto_cmds_controller(param_cmd_controller,param_cmd_controller2) + param_cmd_controller3;
            }
        }


//***************************************************************************************************************************************************************
//INCLUDES GERAIS
//***************************************************************************************************************************************************************

    //=================================================================
    //INCLUDES GERAIS




  }
});
