define(['jquery'], function ($) {
    function config_msg(path_raiz, path) {

        //=================================================================
        //MENSAGENS DA APLICAÇÃO
        this.mensagens = function () {
            var mensagens = [];

            //POST
            mensagens[0] = 'Excluir Registro(s)';
            mensagens[1] = 'Tem certeza de que deseja excluir isso?';
            mensagens[2] = 'Excluir Registro(s)';
            mensagens[3] = 'Registro(s) deletado(s) com sucesso.';
            mensagens[4] = 'Tem certeza que deseja excluir todos os registros?  ATENÇÃO: Esta ação não poderá ser revertida após a exclusão!';
            mensagens[5] = 'Excluir Arquivo';
            mensagens[6] = 'Tem certeza que deseja excluir este arquivo?';
            mensagens[7] = '<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ';
            mensagens[8] = '';
            mensagens[9] = '';
            mensagens[10] = 'Confirmar';
            mensagens[11] = 'Sucesso!';
            mensagens[13] = 'Deletado!';
            mensagens[14] = 'ERRO!';

            //GERAL
            mensagens[12] = 'Não foi possível se conectar a internet. Verifique sua conexão!';

            return mensagens;
        };


    }
    //Retorno modulo
    return config_msg;
});
