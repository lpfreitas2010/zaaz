define(['jquery'], function ($) {
    function notificacao() {

        //funcao de notificacao
        function notificacao(tipo,mensagem,icon,delay,animatedin,animatedout,position,icone){
            require(['Lobibox'], function (notify) {

                //default
                if(tipo == 'info'){
                    Lobibox.notify('info', {
                        sound: false,
                        size: 'normal',
                        title: '',
                        showClass: animatedin,
                        hideClass: animatedout,
                        delay: delay,
                        delayIndicator: true,
                        iconSource: 'fontAwesome',
                        position: position,
                        height: 'auto',
                        msg: mensagem
                    });
                }

                //default
                if(tipo == 'default'){
                    Lobibox.notify('default', {
                        sound: false,
                        size: 'normal',
                        title: '',
                        showClass: animatedin,
                        hideClass: animatedout,
                        delay: delay,
                        delayIndicator: true,
                        iconSource: 'fontAwesome',
                        position: position,
                        height: 'auto',
                        msg: mensagem
                    });
                }
                //warning
                if(tipo == 'warning'){
                    if(icone == undefined || icone == ""){
                        icone = 'fa fa-bell';
                    }
                    Lobibox.notify.OPTIONS = $.extend({}, Lobibox.notify.OPTIONS, {
                        icons: {
                            fontAwesome: {
                                warning: icone,
                            }
                        }
                    });
                    Lobibox.notify('warning', {
                        sound: false,
                        size: 'large',
                        title: '',
                        showClass: animatedin,
                        hideClass: animatedout,
                        delay: delay,
                        delayIndicator: true,
                        iconSource: 'fontAwesome',
                        position: position,
                        height: 'auto',
                        msg: mensagem,
                    });
                }
                //error
                if(tipo == 'error'){
                    Lobibox.notify('error', {
                        sound: false,
                        size: 'normal',
                        title: 'ERRO!',
                        showClass: animatedin,
                        hideClass: animatedout,
                        delay: delay,
                        delayIndicator: true,
                        iconSource: 'fontAwesome',
                        position: position,
                        height: 'auto',
                        msg: mensagem
                    });
                }
                //success
                if(tipo == 'success'){
                    Lobibox.notify('success', {
                        sound: false,
                        size: 'normal',
                        title: 'SUCESSO!',
                        showClass: animatedin,
                        hideClass: animatedout,
                        delay: delay,
                        delayIndicator: true,
                        iconSource: 'fontAwesome',
                        position: position,
                        height: 'auto',
                        msg: mensagem
                    });
                }


            });
        }




        //=================================================================
        //MOSTRA NOTIFICAÇÃO ERRO GERAL
        function mostrar_error_geral(errorMessage) {
            notificacao('error',errorMessage,'',40000,'slideInDown','fadeOutRight','top right','');
        }
        this.mostrar_error_geral = function (errorMessage) {
            mostrar_error_geral(errorMessage);
        };

        //=================================================================
        //MOSTRA NOTIFICAÇÃO ERRO SIMPLES
        function mostrar_error(errorMessage) {
            notificacao('error',errorMessage,'',15000,'slideInDown','fadeOutRight','top right','');
        }
        this.mostrar_error = function (errorMessage) {
            mostrar_error(errorMessage);
        };

        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE SUCESSO
        function mostrar_sucesso(sucessoMessage) {
            notificacao('success',sucessoMessage,'',12000,'slideInDown','fadeOutRight','top right','');
        }
        this.mostrar_sucesso = function (sucessoMessage) {
            mostrar_sucesso(sucessoMessage);
        };
        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE INFORMAÇÃO
        function mostrar_info(infoMessage,icone) {
            notificacao('warning',infoMessage,'',15000,'bounceInRight','fadeOutRight','top right',icone);
        }
        this.mostrar_info = function (infoMessage,icone) {
            mostrar_info(infoMessage,icone);
        };


    }
    //Retorno modulo
    return notificacao;
});
