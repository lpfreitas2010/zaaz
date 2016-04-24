define(['jquery'], function ($) {
    function form_validacao(path_raiz, path) {

            //=================================================================
            //FUNÇÃO QUE MOSTRA A MENSAGEM DE ERRO CAMPO INPUT
            $('.form-control').focus(function () {
                var id_erro_input = $(this).attr('id');
                var texto = $('#msg_erro_'+id_erro_input).text();
                $('.msg_erro_form').hide();
                if(texto != ""){
                    $('#msg_erro_'+id_erro_input).show();
                    $('#msg_erro_'+id_erro_input).addClass('area-error-inputt');
                }
            });
            $('.form-control').hover(function () {
                var id_erro_input = $(this).attr('id');
                var texto = $('#msg_erro_'+id_erro_input).text();
                $('.msg_erro_form').hide();
                if(texto != ""){
                    $('#msg_erro_'+id_erro_input).show();
                    $('#msg_erro_'+id_erro_input).addClass('area-error-inputt');
                }
            }, function() {
                var id_erro_input = $(this).attr('id');
                $('#msg_erro_'+id_erro_input).hide();
                $('#msg_erro_'+id_erro_input).removeClass('area-error-inputt');
            });

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
                    submit_focar
                    ) {

                //INCLUDE DE NOTIFICAÇÃO
                require(['funcoes_notificacao'], function (notificacao) {
                var notificacao1 = new notificacao();

                //VARIAVEIS
                var action = $(id_form).attr('url');

                //SUBMIT AO CLICAR NO BOTÃO ENVIAR
                saving_record = 0; //contador para evitar submit duplo
                $(id_form).on('submit', function(e){
                    e.preventDefault();
                    submit_form_json();
                });

                //SUBMIT AO FOCAR BOTÃO
                if (submit_focar !== "") {
                    $(submit_focar).blur(function (e) {
                        e.preventDefault();
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
                            $(".remove-error-input").removeClass("has-error animated pulse"); //remove campo com linha vermelha
							$(".oculto-icone-error").hide();

                            //MONTO OS DADOS AUTOMATICAMENTE
                            var cont = 0;
                            for (var p in obj) {
                                if (obj.hasOwnProperty(p)) {

                                    cont += 1; //Contador

                                    //CONTEUDO
                                    conteudo = conteudo;
                                    var conteudo = [obj[p]];

                                    //MOSTRO MENSAGENS DE ERROS na linha
                                    var mensagem = conteudo;
                                    var substitui = mensagem.join('<br />');
                                    var final = substitui.split(",");
                                    var final = final.join('<br />');
                                    if (final !== "") {
                                        if(cont == 1){
                                            $('#'+p).focus();
                                        }
                                        $('#msg_erro_' + p).html(final);
										$('#icon_error_'+p).show();
                                        $('#input_error_' + p).addClass("has-error animated pulse"); //add campo com linha vermelha
                                    }

                                    //MENSAGEM DE SUCESSO
                                    if (p == "sucesso") {
                                        notificacao1.mostrar_sucesso(conteudo);
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
                                        var tempo = conteudo;
                                    }
                                    if (p == "redireciono") { //Redireciono página
                                        window.setTimeout("location.href='" + conteudo + "'", tempo);
                                    }

                                    //MENSAGEM DE ERRO
                                    if (p == "erro") {
                                        notificacao1.mostrar_error(conteudo);
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
                            if(errorMessage == ""){
                                errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
                            }
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
							$(".oculto-icone-error").hide();

                            //MONTO OS DADOS AUTOMATICAMENTE
                            function showObject(obj) {
                                var result = "";
                                var cont1 = 0;
                                for (var p in obj) {
                                    if (obj.hasOwnProperty(p)) {

                                        cont1 += 1; //Contador

                                        //CONTEUDO
                                        conteudo = conteudo;
                                        var conteudo = [obj[p]];

                                        //MOSTRO MENSAGENS DE ERROS na linha
                                        var mensagem = conteudo;
                                        var substitui = mensagem.join('<br />');
                                        var final = substitui.split(",");
                                        var final = final.join('<br />');
                                        if (final !== "") {
                                            if(cont1 == 1){
                                                $('#'+p).focus();
                                            }
                                            $('#msg_erro_' + p).html(final);
											$('#icon_error_'+p).show();
                                            $('#input_error_' + p).addClass("input-error"); //add campo com linha vermelha
                                        }

                                        //MENSAGEM DE SUCESSO
                                        if (p == "sucesso") {

                                            notificacao1.mostrar_sucesso(conteudo);
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
                                            var tempo = conteudo;
                                        }
                                        if (p == "redireciono") { //Redireciono página
                                            window.setTimeout("location.href='" + conteudo + "'", tempo);
                                        }

                                        //MENSAGEM DE ERRO
                                        if (p == "erro") {
                                            notificacao1.mostrar_error(conteudo);
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
                            if(errorMessage == ""){
                                errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
                            }
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

                                        //CONTEUDO
                                        conteudo = conteudo;
                                        var conteudo = obj[p];

                                        //NOME ARQUIVO
                                        if(p == "nome_arquivo"){
                                            if(conteudo != ""){

                                                //PARAMTEROS
                                                $(id_form).hide(); //Oculto form
                                                $('#btn-fechar').hide(); //oculto btn fechar modal
                                                $(id_foto).hide(); //Oculto foto
                                                $(foto_result).html('<img id="imagem_nova" class="img_cortar" src="'+caminho_foto+conteudo+'">'); //Mostro foto que acabou de ser upada
                                                $('input[name="nome_img"]').val(conteudo);

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
                                            var tempo = conteudo;
                                        }
                                        if (p == "redireciono") { //Redireciono página
                                            window.setTimeout("location.href='" + conteudo + "'", tempo);
                                        }

                                        //MENSAGEM DE ERRO
                                        if (p == "erro") {
                                            notificacao1.mostrar_error(conteudo);
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
                            if(errorMessage == ""){
                                errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
                            }
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
