define(['jquery'], function ($) {
    function feedback(path_raiz, path) {

        require(['funcoes_gerais','config_modulo','config_mensagens'], function (funcoes,config,config_msg) {
          var funcoes    = new funcoes(path_raiz, path);
          var config     = new config(path_raiz, path);
          var config_msg = new config_msg(path_raiz, path);

        //=================================================================
        //SUBMETO FORMULÁRIO JQUERY SIMPLES ( RETORNO EM JSON )
        submeto_formulario1();
        function submeto_formulario1(){
            submit_form_json1(
              "#form_feedback_", //id do form
              ".btn_salvar_feedback", //id do botão submit
              "<i class=\"fa fa-refresh fa-pulse\"></i> PROCESSANDO ... ", //Texto no botão submit ao carregar
              'false', //Auto hidden sucesso (true or false)
              'false', //Auto hidden erro (true or false)
              '15000', //Tempo hidden
              'false', //autoclean somente em sucesso (true or false)
              'false', //Subir para o topo a cada submit (true or false)
              '', //id btn salvar e novo
              '' //id btn salvar e fechar
            );
        }

        //=================================================================
        //SUBMETO FORMULÁRIO JQUERY SIMPLES ( RETORNO EM JSON )
        function submit_form_json1 (
                id_form,
                id_submit,
                text_btn_loading,
                autohidden_sucesso,
                autohidden_erro,
                tempo_hidden,
                autoclean,
                subir_topo,
                id_submit2,
                id_submit3
                ) {

            //INCLUDE DE NOTIFICAÇÃO
            require(['funcoes_notificacao'], function (notificacao) {
            var notificacao1 = new notificacao();

            //VARIAVEIS
            var action = $(id_form).attr('url');
            var fechar_area_form = '';

            //SUBMIT AO CLICAR NO BOTÃO ENVIAR
            saving_record = 0; //contador para evitar submit duplo
            $(id_form).submit(function(e){
                e.preventDefault();
                $('html,body').animate({scrollTop: 0},0);
                $('input[name="op_salvar"]').val('sucesso_limpo');
                var value_btn = $(id_submit).html(); //Pego texto do botão submit
                submit_form_json(null,value_btn);
            });

            //SUBMIT AO CLICAR NO BTN SALVAR
            $(id_submit).click(function (e) {
                e.preventDefault();
                $('html,body').animate({scrollTop: 0},0);
                $('input[name="op_salvar"]').val('sucesso');

                //VARIAVEIS
                var value_btn = $(id_submit).html(); //Pego texto do botão submit
                $(id_submit).attr('disabled', 'disabled'); //Desativo botão submit
                $(id_submit2).attr('disabled', 'disabled'); //Desativo botão submit2
                $(id_submit3).attr('disabled', 'disabled'); //Desativo botão submit3
                $(id_submit).html(text_btn_loading); //Altero texto do botão submit
                $(id_form + '_js_control').val('true'); //Controle js do form
                funcoes.loading_geral2('show','', ''); // loading
                submit_form_json(null,value_btn);
            });

            //FUNÇÃO DE SUBMIT
            function submit_form_json(param,value_btn) {

                //ENVIO CONTEUDO EDITOR HTML
                require(['tinymce'], function(){
                    tinymce.triggerSave();
                });
                var form = $(id_form);
                var formdata = false;
                if (window.FormData){
                   formdata = new FormData(form[0]);
                }
                //JQUERY AJAX
                $.ajax({
                   /* url: action,
                    dataType: "json",
                    type: 'POST',
                    data: $(id_form).serialize(),*/
                    url: action,
                    dataType: "json",
                    type: 'POST',
                    data: formdata ? formdata : $(id_form).serialize(),
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (obj) {

                        //LIMPO CAMPOS
                        $('.msg_erro_form').html(''); //Oculto mensagens de erro na linha
                        $(".remove-error-input").removeClass("has-error "); //remove campo com linha vermelha
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
                                        if(funcoes.areas_resp_return()=='false'){
                                            $('#'+p).focus();
                                        }
                                    }
                                    $('#msg_erro_' + p).html(final);
                                    $('#icon_error_'+p).show();
                                    $('#input_error_' + p).addClass("has-error "); //add campo com linha vermelha
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
                                        funcoes.limpa_form(id_form,'true');
                                    }
                                    //LIBERO BTN DE SUBMIT
                                    $(id_submit).removeAttr('disabled').html(value_btn);
                                    $(id_submit2).removeAttr('disabled');
                                    $(id_submit3).removeAttr('disabled');
                                    funcoes.loading_geral2('hide','', ''); // loading
                                }
                                if (p == "tempo") { //Redireciono página depois de um certo tempo
                                    var tempo = conteudo;
                                }
                                if (p == "redireciono") { //Redireciono página
                                    window.setTimeout("location.href='" + conteudo + "'", tempo);
                                }
                                if (p == "limpo_campo") { //Limpo campos
                                    funcoes.limpa_form(id_form, 'true');
                                }

                                //MENSAGEM DE ERRO
                                if (p == "erro") {
                                    notificacao1.mostrar_error(conteudo);
                                    if (autohidden_erro == 'true') { //oculto campo depois de um tempo
                                        setTimeout(function () {
                                            $(class_resultado_erro).html('');
                                        }, tempo_hidden);
                                    }
                                    //LIBERO BTN DE SUBMIT
                                    $(id_submit).removeAttr('disabled').html(value_btn);
                                    $(id_submit2).removeAttr('disabled');
                                    $(id_submit3).removeAttr('disabled');
                                    funcoes.loading_geral2('hide','', ''); // loading
                                }

                            }
                        }

    

                        //SUBIR PARA O TOPO
                        if (subir_topo === true) {
                            funcoes.subir_topo();
                        }

                        //LIBERO BTN DE SUBMIT
                        $(id_submit).removeAttr('disabled').html(value_btn);
                        $(id_submit2).removeAttr('disabled');
                        $(id_submit3).removeAttr('disabled');
                        funcoes.loading_geral2('hide','', ''); // loading

                    }, error: function (jqXHR, textStatus, errorMessage) {
                        if(errorMessage == ""){
                            errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
                        }
                        notificacao1.mostrar_error_geral(jqXHR, textStatus, errorMessage);
                        funcoes.loading_geral2('hide','', ''); // loading
                    }
                });
            }
          });
        };

        //=================================================================
        //MOSTRA AREA
        funcoes.mostra_area();

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

    });

    }

    //Retorno modulo
    return feedback;
});
