define(['jquery'], function ($) {
    function notificacao() {


        //=================================================================
        //CHAMO FUNÇÃO DE NOTIFICAÇÃO
        function notify(from, align, icon, type, animIn, animOut, title, message, timer) { //from, align, icon, type, animIn, animOut, title, message, timer
              require(['bootstrap-growl'], function (growl) {
                  $.growl({
                      icon: icon,
                      title: title,
                      message: message,
                      url: ''
                  }, {
                      element: 'body',
                      type: type,
                      allow_dismiss: true,
                      placement: {
                          from: from,
                          align: align
                      },
                      offset: {
                          x: 20,
                          y: 85
                      },
                      spacing: 10,
                      z_index: 1031,
                      delay: 2500,
                      timer: timer,
                      url_target: '_blank',
                      mouse_over: false,
                      animate: {
                          enter: animIn,
                          exit: animOut
                      },
                      icon_type: 'class',
                      template: '<div data-growl="container" class="alert" role="alert">' +
                              '<button type="button" class="close" data-growl="dismiss">' +
                              '<span aria-hidden="true">&times;</span>' +
                              '<span class="sr-only">Close</span>' +
                              '</button>' +
                              '<span data-growl="icon"></span>' +
                              '<span data-growl="title"></span>' +
                              '<span data-growl="message"></span>' +
                              '<a href="#" data-growl="url"></a>' +
                              '</div>'
                });
           });
        }


        //=================================================================
        //FUNÇÃO DE NOTIFICACAO DO REQUIRE
        this.notificacao = function (from, align, icon, type, animIn, animOut, title, message, timer) {
            require(['bootstrap-growl'], function (growl) {
                function notify(from, align, icon, type, animIn, animOut, title, message, timer) {
                    $.growl({
                        icon: icon,
                        title: title,
                        message: message,
                        url: ''
                    }, {
                        element: 'body',
                        type: type,
                        allow_dismiss: true,
                        placement: {
                            from: from,
                            align: align
                        },
                        offset: {
                            x: 20,
                            y: 85
                        },
                        spacing: 10,
                        z_index: 1031,
                        delay: 2500,
                        timer: timer,
                        url_target: '_blank',
                        mouse_over: false,
                        animate: {
                            enter: animIn,
                            exit: animOut
                        },
                        icon_type: 'class',
                        template: '<div data-growl="container" class="alert" role="alert">' +
                                '<button type="button" class="close" data-growl="dismiss">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '<span class="sr-only">Close</span>' +
                                '</button>' +
                                '<span data-growl="icon"></span>' +
                                '<span data-growl="title"></span>' +
                                '<span data-growl="message"></span>' +
                                '<a href="#" data-growl="url"></a>' +
                                '</div>'
                    });
                }
                notify(from, align, icon, type, animIn, animOut, title, message, timer);
            });
        };

        //=================================================================
        //MOSTRA NOTIFICAÇÃO ERRO GERAL
        function mostrar_error_geral(errorMessage) {
            notify('top', 'right', 'zmdi zmdi-mood-bad zmdi-hc-lg m-r-10', 'danger', null, null, '', errorMessage, '200000');
        }
        this.mostrar_error_geral = function (errorMessage) {
            mostrar_error_geral(errorMessage);
        };

        //=================================================================
        //MOSTRA NOTIFICAÇÃO ERRO SIMPLES
        function mostrar_error(errorMessage) {
            notify('top', 'right', 'zmdi zmdi-mood-bad zmdi-hc-lg m-r-10', 'danger', null, null, '', errorMessage, '10000');
        }
        this.mostrar_error = function (errorMessage) {
            mostrar_error(errorMessage);
        };

        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE SUCESSO
        function mostrar_sucesso(sucessoMessage) {
            notify('top', 'right', 'zmdi zmdi-mood zmdi-hc-lg m-r-10', 'success', null, null, '', sucessoMessage, '10000');
        }
        this.mostrar_sucesso = function (sucessoMessage) {
            mostrar_sucesso(sucessoMessage);
        };


    }
    //Retorno modulo
    return notificacao;
});
