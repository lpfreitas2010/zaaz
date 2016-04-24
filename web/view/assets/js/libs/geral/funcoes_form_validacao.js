define(['jquery'], function ($) {
    function form_validacao(path_raiz, path) {


            //=================================================================
            //SUBMETO FORMULÁRIO JQUERY SIMPLES ( RETORNO EM JSON )
            this.submit_form_json = function (
                    id_form,
                    id_submit,
                    text_btn_loading,
                    class_loading,
                    html_loading,
                    class_resultado_sucesso,
                    class_resultado_erro,
                    autohidden_sucesso,
                    autohidden_erro,
                    tempo_hidden,
                    autoclean,
                    subir_topo,
                    submit_focar) {

                //INCLUDE DE NOTIFICAÇÃO
                require(['funcoes_notificacao'], function (notificacao) {
                var notificacao1 = new notificacao();

                //VARIAVEIS
                var action = $(id_form).attr('action');
                $(id_form).attr('action', 'javascript:void(0);');

                //SUBMIT AO CLICAR NO BOTÃO ENVIAR
                $(id_form).submit(function () {
                    submit_form_json();
                });

                //SUBMIT AO FOCAR BOTÃO
                if (submit_focar !== "") {
                    $(submit_focar).blur(function () {
                        submit_form_json();
                    });
                }

                //FUNÇÃO DE SUBMIT
                function submit_form_json() {

                    //VARIAVEIS
                    var value_btn = $(id_submit).html(); //Pego texto do botão submit
                    $(id_submit).attr('disabled', 'disabled'); //Desativo botão submit
                    $(id_submit).html(text_btn_loading); //Altero texto do botão submit
                    $(class_loading).fadeIn(500);
                    $(class_loading).html(html_loading);
                    $(id_form + '_js_control').val('true'); //Controle js do form

                    //JQUERY AJAX
                    $.ajax({
                        url: action,
                        dataType: "json",
                        type: 'POST',
                        data: $(id_form).serialize(),
                        success: function (obj) {

                            //LIMPO CAMPOS
                            $(class_resultado_erro).html(''); //Oculto mensagem de erro
                            $(class_resultado_sucesso).html(''); //Oculto mensagem de sucesso
                            $('.msg_erro_form').html(''); //Oculto mensagens de erro na linha
                            $(".remove-error-input").removeClass("input-error"); //remove campo com linha vermelha

                            //MONTO OS DADOS AUTOMATICAMENTE
                            for (var p in obj) {
                                if (obj.hasOwnProperty(p)) {

                                    //MOSTRO MENSAGENS DE ERROS na linha
                                    var mensagem = obj[p];
                                    var substitui = mensagem.join('<br />');
                                    var final = substitui.split(",");
                                    if (final !== "") {
                                        $('#msg_erro_' + p).html(final);
                                        $('#input_error_' + p).addClass("input-error"); //add campo com linha vermelha
                                    }

                                    //MENSAGEM DE SUCESSO
                                    if (p == "sucesso") {
                                        notificacao1.mostrar_sucesso(obj[p]);
                                        if (autohidden_sucesso == 'true') { //oculto campo depois de um tempo
                                            setTimeout(function () {
                                                $(class_resultado_sucesso).html('');
                                            }, tempo_hidden);
                                        }
                                        if (autoclean == 'true') { //limpo campos do form
                                            $(id_form).each(function () {
                                                this.reset();
                                            });
                                        }
                                    }
                                    if (p == "tempo") { //Redireciono página depois de um certo tempo
                                        var tempo = obj[p];
                                    }
                                    if (p == "redireciono") { //Redireciono página
                                        window.setTimeout("location.href='" + obj[p] + "'", tempo);
                                    }

                                    //MENSAGEM DE ERRO
                                    if (p == "erro") {
                                        notificacao1.mostrar_error(obj[p]);
                                        if (autohidden_erro == 'true') { //oculto campo depois de um tempo
                                            setTimeout(function () {
                                                $(class_resultado_erro).html('');
                                            }, tempo_hidden);
                                        }
                                    }

                                }
                            }

                            //SUBIR PARA O TOPO
                            if (subir_topo === true) {
                                $('html, body').animate({scrollTop: $(body).offset().top}, 1000);
                            }

                            //LIBERO BTN DE SUBMIT
                            $(class_loading).fadeOut(500);
                            $(id_submit).removeAttr('disabled').html(value_btn);

                        }, error: function (jqXHR, textStatus, errorMessage) {
                            notificacao1.mostrar_error_geral(jqXHR, textStatus, errorMessage);
                        }
                    });
                }
              });
            };

            //=================================================================
            //SUBMETO FORMULÁRIO JQUERY UPLOAD ( RETORNO EM JSON )
            this.submit_form_json_upload = function (
                    id_form,
                    id_submit,
                    text_btn_loading,
                    class_loading,
                    html_loading,
                    class_resultado_sucesso,
                    class_resultado_erro,
                    autohidden_sucesso,
                    autohidden_erro,
                    tempo_hidden,
                    autoclean,
                    subir_topo) {

                //INCLUDE DE NOTIFICAÇÃO
                require(['funcoes_notificacao'], function (notificacao) {
                var notificacao1 = new notificacao();

                //VARIAVEIS
                var action = $(id_form).attr('action');
                $(id_form).attr('action', 'javascript:void(0);');

                //SUBMIT
                $(id_form).submit(function () {

                    //VARIAVEIS
                    var value_btn = $(id_submit).html(); //Pego texto do botão submit
                    $(id_submit).attr('disabled', 'disabled'); //Desativo botão submit
                    $(id_submit).html(text_btn_loading); //Altero texto do botão submit
                    $(class_loading).fadeIn(500);
                    $(class_loading).html(html_loading);
                    $(id_form + '_js_control').val('true'); //Controle js do form
                    var formData = new FormData(this); //dados

                    //JQUERY AJAX
                    $.ajax({
                        url: action,
                        dataType: "json",
                        type: 'POST',
                        data: formData,
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (json, textStatus, jqXHR) {

                            //LIMPO CAMPOS
                            $(class_resultado_erro).html(''); //Oculto mensagem de erro
                            $(class_resultado_sucesso).html(''); //Oculto mensagem de sucesso
                            $('.msg_erro_form').html(''); //Oculto mensagens de erro na linha
                            $(".remove-error-input").removeClass("input-error"); //remove campo com linha vermelha

                            //MONTO OS DADOS AUTOMATICAMENTE
                            function showObject(obj) {
                                var result = "";
                                for (var p in obj) {
                                    if (obj.hasOwnProperty(p)) {

                                        //MOSTRO MENSAGENS DE ERROS na linha
                                        var mensagem = obj[p];
                                        var substitui = mensagem.join('<br />');
                                        var final = substitui.split(",");
                                        if (final !== "") {
                                            $('#msg_erro_' + p).html(final);
                                            $('#input_error_' + p).addClass("input-error"); //add campo com linha vermelha
                                        }

                                        //MENSAGEM DE SUCESSO
                                        if (p == "sucesso") {

                                            notificacao1.mostrar_sucesso(obj[p]);
                                            if (autohidden_sucesso == 'true') { //oculto campo depois de um tempo
                                                setTimeout(function () {
                                                    $(class_resultado_sucesso).html('');
                                                }, tempo_hidden);
                                            }
                                            if (autoclean == 'true') { //limpo campos do form
                                                $(id_form).each(function () {
                                                    this.reset();
                                                });
                                            }
                                        }
                                        if (p == "tempo") { //Redireciono página depois de um certo tempo
                                            var tempo = obj[p];
                                        }
                                        if (p == "redireciono") { //Redireciono página
                                            window.setTimeout("location.href='" + obj[p] + "'", tempo);
                                        }

                                        //MENSAGEM DE ERRO
                                        if (p == "erro") {
                                            notificacao1.mostrar_error(obj[p]);
                                            if (autohidden_erro == 'true') { //oculto campo depois de um tempo
                                                setTimeout(function () {
                                                    notificacao1.mostrar_error('');
                                                }, tempo_hidden);
                                            }
                                        }

                                    }
                                }
                                return result;
                            }
                            showObject(json);

                            //SUBIR PARA O TOPO
                            if (subir_topo === true) {
                                $('html, body').animate({scrollTop: $(body).offset().top}, 1000);
                            }

                            //OCULTO
                            $(class_loading).fadeOut(500);
                            $(id_submit).removeAttr('disabled').html(value_btn);
                            
                        }, error: function (jqXHR, textStatus, errorMessage) {
                            notificacao1.mostrar_error_geral(jqXHR, textStatus, errorMessage);
                        }
                    });
                });
                });
            };

            //=================================================================
            //SUBMETO FORMULÁRIO JQUERY UPLOAD ( RETORNO EM JSON )
            this.submit_form_json_upload_img_crop = function (
                    id_form,
                    id_submit,
                    text_btn_loading,
                    class_loading,
                    html_loading,
                    class_resultado_sucesso,
                    class_resultado_erro,
                    autohidden_sucesso,
                    autohidden_erro,
                    tempo_hidden,
                    autoclean,
                    subir_topo,
                    id_foto,
                    foto_result,
                    caminho_foto,
                    id_submit_crop

                    ) {

                //INCLUDE DE NOTIFICAÇÃO
                require(['funcoes_notificacao'], function (notificacao) {
                var notificacao1 = new notificacao();

                //VARIAVEIS
                var action = $(id_form).attr('action');
                $(id_form).attr('action', 'javascript:void(0);');

                //SUBMIT
                $(id_form).submit(function () {

                    //VARIAVEIS
                    var value_btn = $(id_submit).html(); //Pego texto do botão submit
                    $(id_submit).attr('disabled', 'disabled'); //Desativo botão submit
                    $(id_submit).html(text_btn_loading); //Altero texto do botão submit
                    $(class_loading).fadeIn(500);
                    $(class_loading).html(html_loading);
                    $(id_form + '_js_control').val('true'); //Controle js do form
                    var formData = new FormData(this); //dados

                    //JQUERY AJAX
                    $.ajax({
                        url: action,
                        dataType: "json",
                        type: 'POST',
                        data: formData,
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (json, textStatus, jqXHR) {

                            //LIMPO CAMPOS
                            $(class_resultado_erro).html(''); //Oculto mensagem de erro
                            $(class_resultado_sucesso).html(''); //Oculto mensagem de sucesso
                            $('.msg_erro_form').html(''); //Oculto mensagens de erro na linha
                            $(".remove-error-input").removeClass("input-error"); //remove campo com linha vermelha

                            //MONTO OS DADOS AUTOMATICAMENTE
                            function showObject(obj) {
                                var result = "";
                                for (var p in obj) {
                                    if (obj.hasOwnProperty(p)) {

                                        //NOME ARQUIVO
                                        if(p == "nome_arquivo"){
                                            if(obj[p] != ""){

                                                //PARAMTEROS
                                                $(id_form).hide(); //Oculto form
                                                $('#btn-fechar').hide(); //oculto btn fechar modal
                                                $(id_foto).hide(); //Oculto foto
                                                $(foto_result).html('<img id="imagem_nova" class="img_cortar" src="'+caminho_foto+obj[p]+'">'); //Mostro foto que acabou de ser upada
                                                $('input[name="nome_img"]').val(obj[p]);

                                                //CARREGO CÓDIGO DE CROOP
                                                var width  =  Math.round(300); //Calculo widht e height
                                                var height =  Math.round(300); //Calculo widht e height
                                                console.log(width);
                                                $(id_submit_crop).show(); //Mostro btn de crop
                                                require(['imgareaselect'], function (imgAreaSelect) {
                                                    $('#imagem_nova').imgAreaSelect({
                                                        onSelectEnd: function (img, selection) {
                                                            $('input[name="x1"]').val(selection.x1);
                                                            $('input[name="y1"]').val(selection.y1);
                                                            $('input[name="x2"]').val(selection.width);
                                                            $('input[name="y2"]').val(selection.height);
                                                        },
                                                        x1: 0, y1: 0, x2: width, y2: width,
                                                        minWidth: 300,
                                                        minHeight: 300,
                                                        maxWidth: 650,
                                                        maxHeight: 650,
                                                        aspectRatio: '1:1',
                                                        handles: true
                                                    });
                                                });
                                            }
                                        }

                                        //MENSAGEM DE SUCESSO
                                        if (p == "sucesso") {
                                            if (autohidden_sucesso == 'ok') { //oculto campo depois de um tempo
                                                setTimeout(function () {
                                                    $(class_resultado_sucesso).html('');
                                                }, tempo_hidden);
                                            }
                                            if (autoclean == 'true') { //limpo campos do form
                                                $(id_form).each(function () {
                                                    this.reset();
                                                });
                                            }
                                        }
                                        if (p == "tempo") { //Redireciono página depois de um certo tempo
                                            var tempo = obj[p];
                                        }
                                        if (p == "redireciono") { //Redireciono página
                                            window.setTimeout("location.href='" + obj[p] + "'", tempo);
                                        }

                                        //MENSAGEM DE ERRO
                                        if (p == "erro") {
                                            notificacao1.mostrar_error(obj[p]);
                                            if (autohidden_erro == 'true') { //oculto campo depois de um tempo
                                                setTimeout(function () {
                                                    notificacao1.mostrar_error('');
                                                }, tempo_hidden);
                                            }
                                        }

                                    }
                                }
                                return result;
                            }
                            showObject(json);

                            //SUBIR PARA O TOPO
                            if (subir_topo === true) {
                                $('html, body').animate({scrollTop: $(body).offset().top}, 1000);
                            }

                            //LIBERO O BOTÃO DE SUBMIT
                            $(class_loading).fadeOut(500);
                            $(id_submit).removeAttr('disabled').html(value_btn);

                        }, error: function (jqXHR, textStatus, errorMessage) {
                            notificacao1.mostrar_error_geral(jqXHR, textStatus, errorMessage);
                        }
                    });
                });
                });
            };


    }
    //Retorno modulo
    return form_validacao;
});
