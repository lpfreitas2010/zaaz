define(['jquery'], function ($) {
    function funcoes(path_raiz, path) {


        //Busca Ajax autocomplet
        $("#suggesstion-box").hide();
        /*      $("#busca > input").keyup(function () {
         $.ajax({
         type: "POST",
         url: "readCountry.php",
         data: 'keyword=' + $(this).val(),
         beforeSend: function () {
         $("#busca > input").css("background", "#FFF");
         },
         success: function (data) {
         $("#suggesstion-box").show();
         //                    $("#suggesstion-box").html(data);
         $("#busca > input").css("background", "#FFF");
         }
         });
         });*/

        /*      $("#busca > input").blur(function () {
         $("#suggesstion-box").hide();
         });
         //To select country name
         function selectCountry(val, selectCountry) {
         $("#busca > input").val(val);
         $("#suggesstion-box").hide();
         }
         ;*/


//***************************************************************************************************************************************************************
//NOTIFICAÇÕES
//***************************************************************************************************************************************************************

        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE ERRO GERAL DE $.AJAX
        function mostrar_error_geral(jqXHR, textStatus, errorMessage) {
            require(['funcoes_notificacao','config_modulo'], function (notificacao,config) {
               var notificacao1 = new notificacao();
               var config = new config(path_raiz, path);

               //CHAMO FUNÇÃO DE ERRO GERAL
               notificacao1.mostrar_error_geral(errorMessage);
               console.log(jqXHR);

               //ERRO DE CONEXÃO
               if (textStatus == 'timeout') { //testo a conexão
                   notificacao1.mostrar_error_geral(config.mensagens()[12]);
               }
            });
        }
        this.mostrar_error_geral = function (jqXHR, textStatus, errorMessage) {
            mostrar_error_geral(jqXHR, textStatus, errorMessage);
        };

        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE ERRO SIMPLES
        function mostrar_error(errorMessage) {
          require(['funcoes_notificacao'], function (notificacao) {
             var notificacao1 = new notificacao();
             notificacao1.mostrar_error(errorMessage);
          });
        }
        this.mostrar_error = function (errorMessage) {
            mostrar_error(errorMessage);
        };

        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE SUCESSO
        function mostrar_sucesso(sucessoMessage) {
          require(['funcoes_notificacao'], function (notificacao) {
             var notificacao1 = new notificacao();
             notificacao1.mostrar_sucesso(sucessoMessage);
          });
        }
        this.mostrar_sucesso = function (sucessoMessage) {
            mostrar_sucesso(sucessoMessage);
        };



//***************************************************************************************************************************************************************
//FUNÇÕES GERAIS
//***************************************************************************************************************************************************************

        //=================================================================
        //PEGO O LINK E REDIRECIONO
        $('.link').click(function (e) {
            window.location = $(this).attr('link');
        });
        this.link_redirect = function () {
            $('.link').click(function (e) {
                window.location = $(this).attr('link');
            });
        };

        //=================================================================
        //CAMPO INPUT CAIXA ALTA
        $(".input_maiusculo").keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });

        //=================================================================
        //CAMPO INPUT CAIXA BAIXA
        $(".input_minusculo").keyup(function () {
            $(this).val($(this).val().toLowerCase());
        });

        //=================================================================
        //TRAVO BTN ENTER
        $('.block_enter').bind("keypress", function (e) {
            if (e.keyCode == 13)
                return false;
        });

        //=================================================================
        //FUNÇÃO - FOCO CAMPO
        function foco_campo(campo) {
            $('#' + campo + '').focus();
        }
        this.foco_campo = function (campo) {
            foco_campo(campo);
        };

        //=================================================================
        //FUNÇÃO - LIMPO CAMPOS DE FORMULÁRIO
        function limpa_form(campo) {
            $('#' + campo + '').each(function () {
                this.reset();
            });
            $('iframe').contents().find('body').empty(); //limpo campos ckeditor
            subir_topo(); //subo p/ o topo
        }
        this.limpa_form = function (campo) {
            limpa_form(campo);
        };

        //=================================================================
        //FUNÇÃO - PASSO DADOS PARA FORM
        function seto_dados_form(tipo_set, campo_set_form, valor_campo_set) {
            if (tipo_set == 'input') { //input
                $('#' + campo_set_form + '').val('' + valor_campo_set + '');
            }
            if (tipo_set == 'textarea') { //textarea
                $("textarea[name='" + campo_set_form + "']").val('' + valor_campo_set + '');
            }
            if (tipo_set == 'select') { //select
                $('#' + campo_set_form + ' option').each(function () {
                    if ($(this).val() == '' + valor_campo_set + '') {
                        $(this).attr('selected', true);
                    }
                });
                //$('#'+campo_set_form+' option[value='+valor_campo_set+']').attr('selected', true);
            }
            if (tipo_set == 'checkbox') { //checked
                if (valor_campo_set == 'on') {
                    $('#' + campo_set_form + '').attr('checked', 'checked');
                } else {
                    $('#' + campo_set_form + '').removeAttr('checked');
                }
            }
        }
        this.seto_dados_form = function (tipo_set, campo_set_form, valor_campo_set) {
            seto_dados_form(tipo_set, campo_set_form, valor_campo_set);
        };

        //=================================================================
        //FUNÇÃO - FUNÇÃO DE IR ATÉ UM ID
        function ir_ate(campo) {
            $('html, body').animate({
                scrollTop: $('#' + campo).offset().top
            }, 2000);
        }
        this.ir_ate = function (campo) {
            ir_ate(campo);
        };

        //=================================================================
        //FUNÇÃO - SUBIR PARA O TOPO
        function subir_topo() {
            $('html, body').animate({
                scrollTop: $('body').offset().top
            }, 1000);
        }
        this.subir_topo = function () {
            subir_topo();
        };

        //=================================================================
        //FUNÇÃO - BLOQUEO E DESBLOQUEO CAMPO DO FORM
        function bloqueo_desbloqueo_form(campo, valor) {
            if (valor == "true") { //Bloqueo
                $(campo).attr('disabled', true);
            }
            if (valor == "false") { //Desbloqueo
                $(campo).attr('disabled', false);
            }
        }
        this.bloqueo_desbloqueo_form = function (campo, valor) {
            bloqueo_desbloqueo_form(campo, valor);
        };

        //=================================================================
        //FUNÇÃO - GERO COOKIE
        function gerarCookie(chave, value) {
            var expira = new Date();
            expira.setTime(expira.getTime() + 87600000); //expira dentro de 24h
            document.cookie = chave + '=' + value + ';expires=' + expira.toUTCString();
        }
        this.gerarCookie = function (chave, value) {
            gerarCookie(chave, value);
        };

        //=================================================================
        //FUNÇÃO - LEIO O COOKIE
        function lerCookie(chave) {
            var ChaveValor = document.cookie.match('(^|;) ?' + chave + '=([^;]*)(;|$)');
            return ChaveValor ? ChaveValor[2] : null;
        }
        this.lerCookie = function (chave) {
            return lerCookie(chave);
        };

        //=================================================================
        //FUNÇÃO - APAGO COOKIE
        function apagarCookie(c_name) {
            gerarCookie(c_name, '', -1);
        }
        this.apagarCookie = function (c_name) {
            apagarCookie(c_name);
        };

        //=================================================================
        //FUNÇÃO - REDIRECIONO
        function redireciono(url_pagina) {
            window.location = url_pagina;
        }
        this.redireciono = function (url_pagina) {
            redireciono(url_pagina);
        };

        //=================================================================
        //LOADING GERAL
        this.loading_geral = function (status, campo_load, img) {
            loading_geral(status, campo_load, img);
        };
        function loading_geral(status, campo_load, img) {
            if (status == "show") {
                $(campo_load).html("<img src='" + path + "/assets/img/" + img + ".gif'>");
            } else {
                $(campo_load).html('');
            }
        }

        //=================================================================
        //CARREGO IAMGEM QUANDO DA UM ERRO DE CARREGAMENTO
        this.imagem_error = function (imagem){
           imagem_error(imagem);
        };
        function imagem_error (imagem){
         $('img').error(function(){
            var imagems = $(this).attr("src");
            $(this).attr("src",path+"/assets/img/error/"+imagem);
          });
        }

        //=================================================================
        //UNSERIALIZE
        this.serializedString = function(serializedString){
        		var str = decodeURI(serializedString);
        		var pairs = str.split('&');
        		var obj = {}, p, idx, val;
        		for (var i=0, n=pairs.length; i < n; i++) {
        			p = pairs[i].split('=');
        			idx = p[0];

        			if (idx.indexOf("[]") == (idx.length - 2)) {
        				// Eh um vetor
        				var ind = idx.substring(0, idx.length-2)
        				if (obj[ind] === undefined) {
        					obj[ind] = [];
        				}
        				obj[ind].push(p[1]);
        			}
        			else {
        				obj[idx] = p[1];
        			}
        		}
        		return obj;
       };



//***************************************************************************************************************************************************************
//FUNÇÕES DE LISTAGEM GERAL $.AJAX
//***************************************************************************************************************************************************************

        //=================================================================
        //TRANSFORMO RESULT EM OBJETO
        this.result_var_jquery_ajax = function () {
            return result_var_jquery_ajax();
        };
        function result_var_jquery_ajax() {
            var result = jquery_ajax_return();
            return result;
        }

        //=================================================================
        //FUNÇÃO GENERICA AJAX
        this.jquery_ajax_return = function (callback, url, datatype, type, serialize_id, campo_load, img) {
            return jquery_ajax_return(callback, url, datatype, type, serialize_id, campo_load, img);
        };
        function jquery_ajax_return(callback, url, datatype, type, serialize_id, campo_load, img) {
            loading_geral('show', campo_load, img);
            $.ajax({
                url: url,
                dataType: datatype,
                type: type,
                data: $("#" + serialize_id).serialize(),
                success: callback,
                error: function (jqXHR, textStatus, errorMessage) {
                    mostrar_error_geral(jqXHR, textStatus, errorMessage);
                    loading_geral('hide', campo_load, img);
                }
            });
        }



//***************************************************************************************************************************************************************
//FUNÇÕES GERAIS DAS PÁGINAS
//***************************************************************************************************************************************************************

        //=================================================================
        //FUNÇÃO DE MINIATURAS
        this.informacao_miniatura = function (url) {
            $(".informacao_miniatura").on('mouseenter', function () {

                //PEGO OS DADOS
                var id_geral = $(this).attr('id_geral'); //Campos id geral
                var tabela = $(this).attr('tabela'); //Campo tabela
                var id = $(this).attr('id_miniatura'); //id resultado
                $("#profile-miniatura_"+ id + " > img").removeClass("hidden"); //oculto classe

                //AJAX
                $.ajax({
                    url: url + '&id=' + id_geral + '&tabela=' + tabela,
                    dataType: "json",
                    success: function (obj) {

                        $(".profile-miniatura").html("");
                        $("#profile-miniatura_" + id).fadeIn( 400 ).html(obj);
                        $(".card_miniatura").hover(function() {
                          $(".profile-miniatura").html("");
                          $(".profile-miniatura-miniatura").html("<img class='hidden load-miniatura' src='"+ path + "/assets/img/loading.gif'>");

                        });
                        imagem_error("thumbs-up.png");
                    },
                    error: function (jqXHR, textStatus, errorMessage) {
                        mostrar_error_geral(jqXHR, textStatus, errorMessage);
                    }
                });

      /*          $('.informacao_miniatura').on('mouseleave', function () {
                    var id = $(this).attr('id_miniatura'); //id resultado
                    $(".profile-miniatura").html("");
                    $("#profile-miniatura_"+id).html("<img class='hidden load-miniatura' src='"+ path + "/assets/img/loading.gif'>");
                });*/

                $('body').on('click', function () {
                    $(".profile-miniatura").html("");
                    $(".profile-miniatura").html("<img class='hidden load-miniatura' src='"+ path + "/assets/img/loading.gif'>");
                });



            });

            $('.informacao_miniatura').mouseout(function() {
                $(".profile-miniatura").html("");
                $(".profile-miniatura").html("<img class='hidden load-miniatura' src='"+ path + "/assets/img/loading.gif'>");
            });

            $('.card').mouseover(function() {
                $(".profile-miniatura").html("");
                $(".profile-miniatura").html("<img class='hidden load-miniatura' src='"+ path + "/assets/img/loading.gif'>");
            });


        };

        //=================================================================
        //SUBMIT INLINE HTML COM RETORNO EM JSON
        $('.submit_inline_json').click(function (e) {

            //RECEBO DADOS
            var resul_sucesso = $(this).attr('resul_sucesso'); //Campos Sucesso
            var resul_erro = $(this).attr('resul_erro'); //Campo Erro
            var campo_load = $(this).attr('campo_load'); //Campo loading
            var msg_load = $(this).attr('msg_load'); //Mensagem loading
            var oculto_campo = $(this).attr('oculto_campo'); //Oculto campo

            //OCULTO CAMPOS
            $(campo_load).hide(); //oculto campo

            //LOADING
            $(campo_load).show("fast"); //mostro campo
            $(campo_load).html(msg_load); //mostro a mensagem no loading

            //OCULTO CAMPO
            if (oculto_campo == 'true') {
                $(this).hide();
            }

            //AJAX
            $.ajax({
                url: $(this).attr('link'),
                dataType: "json",
                type: 'POST',
                data: $(this).serialize(),
                success: function (json) {

                    //MONTO OS DADOS AUTOMATICAMENTE
                    function showObject(obj) {
                        var result = "";
                        for (var p in obj) {
                            if (obj.hasOwnProperty(p)) {

                                //MENSAGEM DE SUCESSO
                                if (p == "sucesso") {
                                    mostrar_sucesso(obj[p]);
                                }

                                //MENSAGEM DE ERRO
                                if (p == "erro") {
                                    mostrar_error(obj[p]);
                                }

                            }//fim if
                        }//fim for
                        return result;
                    }
                    showObject(json);
                    $(campo_load).hide();
                }, error: function (jqXHR, textStatus, errorMessage) {
                    mostrar_error_geral(jqXHR, textStatus, errorMessage);
                }
            });
        });

        //=================================================================
        //COMBOBOX
        this.dropdown_bd = function (url, campo, descricao, descricao_sing, seleciono_campo) {
            $('' + campo + '').html('<option>Carregando ' + descricao + ' aguarde...</option>');
            $.ajax({
                url: url,
                dataType: "json",
                type: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    $('' + campo + '').html('<option value="">Selecione um ' + descricao_sing + ' </option> <optgroup label="----------"></optgroup>');
                    for ($i = 0; $i < data.length; $i++) {
                        $('' + campo + '').append('<option value="' + data[$i].campo1 + '">' + data[$i].campo2 + '</option>');
                    }
                    if (seleciono_campo !== "") {
                        $('' + campo + ' option[value="' + seleciono_campo + '"]').attr({selected: 'selected'});
                    }

                }, error: function () {
                    $('' + campo + '').html('<option>Erro ao carregar ' + descricao + '</option>');
                }
            });
        };

        //=================================================================
        //CHANGCOMBOBOX
        this.chang_box_dropdown_bd = function (url, campo, campo_change, descricao, descricao_sing, estado_id_s, cidade_id) {
            if (estado_id_s !== "") {
                var estado_id = estado_id_s;
                $('' + campo_change + '-campo').show();
                //$(''+campo_change+'').focus();
                $('' + campo_change + '').html('<option>Carregando ' + descricao + ' aguarde...</option>');
                $.ajax({
                    url: url,
                    dataType: "json",
                    type: 'POST',
                    data: $(this).serialize() + '&estado_id=' + estado_id,
                    success: function (data) {
                        $('' + campo_change + '').html('<option value="">Selecione uma ' + descricao_sing + ' </option> <optgroup label="----------"></optgroup>');
                        for ($i = 0; $i < data.length; $i++) {
                            $('' + campo_change + '').append('<option value="' + data[$i].campo1 + '">' + data[$i].campo2 + '</option>');
                        }
                        if (cidade_id !== "") {
                            $('' + campo_change + ' option[value="' + cidade_id + '"]').attr({selected: 'selected'});
                        }

                    }, error: function () {
                        $('' + campo_change + '').html('<option>Erro ao carregar ' + descricao + '</option>');
                    }
                });
            } else {
                $('' + campo + '').change(function (e) {
                    var estado_id = $('select' + campo + ' option').filter(":selected").val();
                    $('' + campo_change + '-campo').show();
                    $('' + campo_change + '').focus();
                    $('' + campo_change + '').html('<option>Carregando ' + descricao + ' aguarde...</option>');
                    $.ajax({
                        url: url,
                        dataType: "json",
                        type: 'POST',
                        data: $(this).serialize() + '&estado_id=' + estado_id,
                        success: function (data) {
                            $('' + campo_change + '').html('<option value="">Selecione uma ' + descricao_sing + ' </option> <optgroup label="----------"></optgroup>');
                            for ($i = 0; $i < data.length; $i++) {
                                $('' + campo_change + '').append('<option value="' + data[$i].campo1 + '">' + data[$i].campo2 + '</option>');
                            }

                        }, error: function () {
                            $('' + campo_change + '').html('<option>Erro ao carregar ' + descricao + '</option>');
                        }
                    });
                });
            }
        };

        //=================================================================
        //REQUISIÇÃO COM RESPOSTA INLINE HTML
        this.submit_inline_json_msg = function (url3, path) {

            //SUBMIT INLINE HTML COM RETORNO EM JSON
            saving_record = 0; //contador para evitar submit duplo
            $('.submit_inline_json_msg').click(function (e) {

              if (saving_record === 0) {

                    //RECEBO DADOS
                    e.preventDefault();
                    saving_record = 1; //contador para evitar submit duplo
                    var resul_erro = $(this).attr('resul_erro'); //Campo Erro
                    var campo = $(this).attr('campo_resul'); //Campo resposta

                    //LOADING
                    $(campo).html('<img class="loading_branco" src=' + path + '/assets/img/loading_branco.gif>'); //mostro a mensagem no loading

                    //AJAX
                    $.ajax({
                        url: url3 + $(this).attr('link'),
                        dataType: "json",
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function (json) {

                            //MONTO OS DADOS AUTOMATICAMENTE
                            function showObject(obj) {
                                var result = "";
                                for (var p in obj) {
                                    if (obj.hasOwnProperty(p)) {

                                        //MENSAGEM DE SUCESSO
                                        if (p == "sucesso") {
                                            $(campo).html(obj[p]);
                                        }
                                        if (p == "link") {
                                            $(campo).attr('link', obj[p]);
                                        }
                                        if (p == "title") {
                                            $(campo).attr('title', obj[p]);
                                        }
                                        if (p == "addclass") {
                                            $(campo).addClass(obj[p]);
                                        }
                                        if (p == "removeclass") {
                                            $(campo).removeClass(obj[p]);
                                        }
                                        saving_record = 0; //contador para evitar submit duplo

                                    }//fim if
                                }//fim for
                                return result;
                            }
                            showObject(json);
                        }, error: function (jqXHR, textStatus, errorMessage) {
                            $(campo).html(':(');
                            saving_record = 0; //contador para evitar submit duplo
                            mostrar_error_geral(jqXHR, textStatus, errorMessage);
                        }
                    });

                  }
              });
        };


    }

    //Retorno modulo
    return funcoes;
});
