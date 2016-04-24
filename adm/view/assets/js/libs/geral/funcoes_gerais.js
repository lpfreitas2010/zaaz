define(['jquery'], function ($) {
    function funcoes(path_raiz, path) {

//***************************************************************************************************************************************************************
//NOTIFICAÇÕES
//***************************************************************************************************************************************************************

        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE ERRO GERAL DE $.AJAX
        function mostrar_error_geral(jqXHR, textStatus, errorMessage) {
            require(['funcoes_notificacao', 'config_modulo'], function (notificacao, config) {
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
            if(errorMessage == ""){
                errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
            }
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

        //=================================================================
        //MOSTRA NOTIFICAÇÃO DE INFO
        function mostrar_info(sucessoMessage,icone) {
            require(['funcoes_notificacao'], function (notificacao) {
                var notificacao1 = new notificacao();
                notificacao1.mostrar_info(sucessoMessage,icone);
            });
        }
        this.mostrar_info = function (sucessoMessage,icone) {
            mostrar_info(sucessoMessage,icone);
        };



//***************************************************************************************************************************************************************
//FUNÇÕES GERAIS
//***************************************************************************************************************************************************************

        //=================================================================
        //PEGO O LINK E REDIRECIONO
        redireciono_click();
        this.link_redirect = function () {
            redireciono_click();
        };
        function redireciono_click(){
            $('.link').click(function (e) {
                window.location = $(this).attr('link');
            });
        }

        //=================================================================
        //LOADING EM ELEMENTOS
        loading_elements();
        this.loading_elements = function () {
            loading_elements();
        };
        function loading_elements(){
            $('.load-elements').click(function (e) {
                $(this).attr("disabled", "disabled");
                $(this).html('<i class="fa fa-spinner fa-pulse"></i> Aguarde');
            });
        }

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
        function block_enter(){
            $('.block_enter').bind("keypress", function (e) {
                if (e.keyCode == 13)
                    return false;
            });
        }
        this.block_enter = function (campo) {
            block_enter();
        };

        //=================================================================
        //TRAVO BTN BLOCK PAST
        $(".block_past").bind({
           copy : function(){
              return false;
           },
           paste : function(){
             return false;
           },
           cut : function(){
              return false;
           }
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
        function limpa_form(campo,param) {
            if(param == 'true'){
                $(campo).each(function () { //Limpo campos form
                    this.reset();
                });
            }else{
                $('#'+ campo).each(function () { //Limpo campos form
                    this.reset();
                });
            }
            $('iframe').contents().find('body').empty(); //limpo campos ckeditor
            limpo_mensagens_erros(); //limpo mensagens de erros
            $(".select_multiple").trigger("chosen:updated"); //limpo campo chosen
            $(".select_personalizado").trigger("chosen:updated"); //limpo campo chosen
            $("select").find('option:selected').removeAttr("selected"); //limpo campos select
            subir_topo(); //subo p/ o topo
        }
        this.limpa_form = function (campo,param) {
            limpa_form(campo,param);
        };

        //=================================================================
        //FUNÇÃO LIMPO MENSAGENS DE ERROS DOS FORMS
        this.limpo_mensagens_erros = function () {
            limpo_mensagens_erros();
        };
        function limpo_mensagens_erros(){
            $('.msg_erro_form').html(''); //oculto campo de erro do form
            $(".remove-error-input").removeClass("has-error animated pulse"); //remove campo com linha vermelha
            $(".oculto-icone-error").hide();
        }

        //=================================================================
        //FUNÇÃO LIMPO CAMPOS HIDDENS
        this.limpo_campos_hidden = function () {
            limpo_campos_hidden();
        };
        function limpo_campos_hidden(){
            $('input[class="clear_hidden"]').val(''); //limpo campos hidden
        }

        //=================================================================
        //FUNÇÃO PEGO RESOLUÇÃO DA TELA
        this.getPageSize = function () {
            return getPageSize();
        };
        function getPageSize(){
        	var xScroll, yScroll;
        	if (window.innerHeight && window.scrollMaxY) {
        		xScroll = document.body.scrollWidth;
        		yScroll = window.innerHeight + window.scrollMaxY;
        	}
        	else if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac
        		xScroll = document.body.scrollWidth;
        		yScroll = document.body.scrollHeight;
        	}
        	else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        		xScroll = document.body.offsetWidth;
        		yScroll = document.body.offsetHeight;
        	}

        	var windowWidth, windowHeight;
        	if (self.innerHeight) { // all except Explorer
        		windowWidth = self.innerWidth;
        		windowHeight = self.innerHeight;
        	}
        	else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
        		windowWidth = document.documentElement.clientWidth;
        		windowHeight = document.documentElement.clientHeight;
        	}
        	else if (document.body) { // other Explorers
        		windowWidth = document.body.clientWidth;
        		windowHeight = document.body.clientHeight;
        	}
        	if (yScroll < windowHeight) {
        		pageHeight = windowHeight;
        	}
        	else {
        		pageHeight = yScroll;
        	}
        	if (xScroll < windowWidth) {
        		pageWidth = windowWidth;
        	}
        	else {
        		pageWidth = xScroll;
        	}
        	arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);
            return arrayPageSize;
        }

      //=================================================================
      //FUNÇÃO IDENTIFICA RESOLUÇÃO TELA
      function identifica_resolucao_mob(){
          if (screen.width<900){
              return true;
          }else{
              return false;
          }
      }

      //=================================================================
      //FUNÇÃO TABELA RESPONSIVAS
      function tabela_responsiva(){
          require(['tablesaw-master'], function () {
              //$('#tabela_responsiva').stacktable();
              $( document ).trigger( "enhance.tablesaw" );
          });
      }
      this.tabela_responsiva = function () {
          tabela_responsiva();
      }

       //=================================================================
       //SETO VALOR DO FILTRO NA PESQUISA GERAL
        $('.btn_param_pesquisa_focus2').click(function () {
            param_pesquisa = $(this).attr('param_pesquisa'); // Pego o parametro da pesquisa
            $('#input-pesq-geral').val(param_pesquisa);
            $('#input-pesq-geral').focus();
        });

        //=================================================================
        //SUBMETO A PESQUISA GERAL

        $( "#input-pesq-geral" ).on( "focusin", function(event) {
            $(this).addClass('input-pesq-geral-tg');
        });
        $( "#input-pesq-geral" ).on( "focusout", function(event) {
            $(this).removeClass('input-pesq-geral-tg');
        });
        $( "#input-pesq-geral" ).on( "keydown", function(event) {
              if(event.which == 13){
                  pesq = $(this).val(); // Pego o parametro da pesquisa
                  cont = $('#param_javascript').attr('action_pesq_geral'); // Pego o caminho do controller
                  if (pesq !== null && pesq !== undefined && pesq !== "" && pesq !== " " && pesq !== "  ") {
                      redireciono(cont+'&param='+pesq);
                  }
              }
        });
        $('.btn-pesq-geral').click(function () {
            pesq = $('#input-pesq-geral').val(); // Pego o parametro da pesquisa
            cont = $('#param_javascript').attr('action_pesq_geral'); // Pego o caminho do controller
            if (pesq !== null && pesq !== undefined && pesq !== "" && pesq !== " " && pesq !== "  ") {
                redireciono(cont+'&param='+pesq);
            }
        });

        //=================================================================
        //AUTOCOMPLETE PESQUISA GERAL
        if($('#input-pesq-geral').length){
            autocomplete($('#param_javascript').attr('action_autocomplete_pesq_geral'),'#input-pesq-geral');
        }

        //=================================================================
        //FUNÇÃO - PASSO DADOS PARA FORM
        function seto_dados_form(tipo_set, campo_set_form, valor_campo_set) {
            $('#info_form_editar').hide();
            if(valor_campo_set == null || valor_campo_set=="0"){
                valor_campo_set = "";
            }
            if (tipo_set == 'input') { //input
                $('#' + campo_set_form + '').val('' + valor_campo_set + '');
            }
            if (tipo_set == 'textarea') { //textarea
                $("textarea[name='" + campo_set_form + "']").val('' + valor_campo_set + '');
                //seto dados editor html
                 require(['tinymce'], function(){
                        tinymce.get(campo_set_form).setContent(valor_campo_set);
                 });
            }
            if (tipo_set == 'select') { //select
                $('#' + campo_set_form + ' option').each(function () {
                    if ($(this).val() == '' + valor_campo_set + '') {
                        $(this).attr('selected', true);
                    }
                });
                //$('#'+campo_set_form+' option[value='+valor_campo_set+']').attr('selected', true);
            }
            if (tipo_set == 'select_estatico') { //select2
                var teste = $('#'+campo_set_form).html();
                $('#'+campo_set_form).html(teste);
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
            if (tipo_set == 'show_hide') { //show_hide
                if(valor_campo_set == 'hide'){
                    $(campo_set_form).hide();
                }
                if(valor_campo_set == 'show'){
                    $(campo_set_form).show();
                }
            }
            if (tipo_set == 'attr_param') { //show_hide
                $(campo_set_form).attr('param_value',valor_campo_set);
            }
            if (tipo_set == 'disabled') { //disabled
                if(valor_campo_set == 'disabled'){
                    $('#' + campo_set_form + '').attr('disabled', 'disabled');
                }
                if(valor_campo_set == 'remove_disabled'){
                    $('#' + campo_set_form + '').removeAttr('disabled');
                }
            }
            if (tipo_set == 'info_editar') { //outras informacoes
                if(valor_campo_set != "" && valor_campo_set != undefined){
                    $('#info_form_editar').show();
                    $('#info_form_editar').html('<div class="box box-default"><div class="box-body p-b-5 p-t-15">'+valor_campo_set+'</div></div>');
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
            $('html,body').animate({scrollTop: 0},0);
        }
        this.subir_topo = function () {
            subir_topo();
        };
        $('.subir_topo').click(function (e) {
            subir_topo();
        });

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
            document.cookie = chave + '=' + value + ';expires=' + expira.toUTCString()+'; path=/"';
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
                $('.fakeloader').fadeIn();
                require(['fakeLoader'], function (fakeLoader) {
                    $(".fakeloader").fakeLoader({
                      //  timeToHide:1200, //Time in milliseconds for fakeLoader disappear
                        zIndex:"999",//Default zIndex
                        spinner:"spinner7",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
                        width:"0",
                        height:"0",
                        bgColor:"rgba(34, 45, 50, 0.0)", //Hex, RGB or RGBA colors
                        //imagePath:"yourPath/customizedImage.gif" //If you want can you insert your custom image
                      });
                })
                //$(campo_load).html("<img src='" + path + "/assets/img/" + img + ".gif'>");
            } else {
                //$(campo_load).html('');
                $('.fakeloader').fadeOut();
            }
        }

        //=================================================================
        //LOADING GERAL
        this.loading_geral2 = function (status, campo_load, img) {
            loading_geral2(status, campo_load, img);
        };
        function loading_geral2(status, campo_load, img) {
            if (status == "show") {
                $('.fakeloader').fadeIn();
                require(['fakeLoader'], function (fakeLoader) {
                    $(".fakeloader").fakeLoader({
                      //  timeToHide:1200, //Time in milliseconds for fakeLoader disappear
                        zIndex:"999",//Default zIndex
                        spinner:"spinner1",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
                        bgColor:"rgba(34, 45, 50, 0.7)", //Hex, RGB or RGBA colors
                        //imagePath:"yourPath/customizedImage.gif" //If you want can you insert your custom image
                      });
                })
                //$(campo_load).html("<img src='" + path + "/assets/img/" + img + ".gif'>");
            } else {
                //$(campo_load).html('');
                $('.fakeloader').fadeOut();
            }
        }

        //=================================================================
        //CARREGO IAMGEM QUANDO DA UM ERRO DE CARREGAMENTO
        this.imagem_error = function (imagem) {
            imagem_error(imagem);
        };
        function imagem_error(imagem) {
            $('img').error(function () {
                var imagems = $(this).attr("src");
                $(this).attr("src", path + "/assets/img/error/" + imagem);
            });
        }

        //=================================================================
        //ÁREA RESPONSIVO
        areas_resp();
        this.areas_resp = function () {
            areas_resp();
        }
        function areas_resp(){
            var getPageSize0 = getPageSize();
            if (getPageSize0[0]<992){

                //REMOVE
               $('.remove_area_responsive').remove();

               //MOSTRO
               $('.show_area_responsive1').show();
            }else{

               //MOSTRO
               $('.show_area_responsive').show();
            }
        }

        //=================================================================
        //ÁREA RESPONSIVO RETORNO TRUE SE FOR RESPONSIVO
        areas_resp_return();
        this.areas_resp_return = function () {
            return areas_resp_return();
        }
        function areas_resp_return(){
            var getPageSize1 = getPageSize();
            if (getPageSize1[0]<992){
                var returno = 'true';

            }else{
                var returno = 'false';
            }
            return returno;
        }

        //=================================================================
        //CONTROLO O MENU ENCOLHIDO
        menu_colapse();
        this.menu_colapse = function () {
            menu_colapse();
        }
        function menu_colapse(param){
            if(param != 'true'){
                if($('.params').attr('status_menu_lateral')==false){
                    $('body').addClass('sidebar-collapse');
                    $('body').addClass('sidebar-collapse-controle');
                    $('#menu-lateral').removeClass('main-sidebar-fixo');
                    $('#menu-lateral').addClass('main-sidebar');
                }
            }else{
                $('body').addClass('sidebar-collapse');
                $('body').addClass('sidebar-collapse-controle');
                $('#menu-lateral').removeClass('main-sidebar-fixo');
                $('#menu-lateral').addClass('main-sidebar');
            }
        }

        //=================================================================
        //ESCONDO MENU RESOLUÇÃO ABAIXO DE 1200
        escondo_menu_res();
        function escondo_menu_res(){
            var width = getPageSize();
            if(width[0]<1400){
                menu_colapse('true'); //deixo o menu pequeno
                $("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
                $(".sidebar-toggle-dois").addClass('main-sidebar').removeClass('main-sidebar-fixo');
                destroy_scroll(); //destruo scrool
            }else{
                scroll_menu_init();
            }
            if(width[0]<760){
                $('body').removeClass('sidebar-collapse');
                $('body').removeClass('sidebar-collapse-controle');
                $('#menu-lateral').addClass('main-sidebar-fixo');
                $('#menu-lateral').addClass('main-sidebar');
            }
        }

        //=================================================================
        //SCROOL MENU
        function destroy_scroll(){
            require(['slimScroll'], function (slimscroll) {
                $(".sidebar").slimScroll({destroy: true}).height("auto");
                //$("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
                //$(".sidebar-toggle-dois").addClass('main-sidebar').removeClass('main-sidebar-fixo');
                $( ".sidebar" ).removeAttr("style");
                $( ".slimScrollDiv" ).removeAttr("style");
                $( ".slimScrollBar" ).removeAttr("style");
                $( ".slimScrollRail").removeAttr("style");
                $( ".sidebar-relative").removeAttr("style");
            });
        }
        function scroll_menu_init(){
            require(['slimScroll'], function (slimscroll) {
                $(".sidebar").slimscroll({
                  height: ($(window).height() - $(".main-header").height()) + "px",
                  color: "#3c8dbc",
                  size: "3px"
                });
            });
        }




        //=================================================================
        //SELECT 2 PERSONALIZADO
        function select_personalizado(){
            require(['chosen'], function (chosen) {
                if($(".select_personalizado").html() != ""){
                    $(".select_personalizado").chosen({no_results_text: "Nenhum resultado encontrado!",allow_single_deselect: true});
                }
            });
        }

        //=================================================================
        //MOSTRO ÁREAS E OCULTO ÁREAS
        mostra_area();
        this.mostra_area = function () {
            mostra_area();
        }
        function mostra_area(){
            $('.mostra_area').click(function () {

                //RECEBO PARAMETROS
                var show_area  = $(this).attr('show_area');
                var show_area2 = $(this).attr('show_area2');
                var show_area3 = $(this).attr('show_area3');
                var show_area4 = $(this).attr('show_area4');
                var show_area5 = $(this).attr('show_area5');
                var hide_area  = $(this).attr('hide_area');
                var hide_area2 = $(this).attr('hide_area2');
                var hide_area3 = $(this).attr('hide_area3');
                var hide_area4 = $(this).attr('hide_area4');
                var hide_area5 = $(this).attr('hide_area5');
                var topo = $(this).attr('topo');
                var focus = $(this).attr('focus');
                var limpar_campos_form = $(this).attr('limpar_campos_form');

                //SUBIR TOPO
                if(topo == 'true'){
                  subir_topo();
                }

                //LIMPARA CAMPOS DO FORM
                if (limpar_campos_form !== null && limpar_campos_form !== undefined && limpar_campos_form !== "") {
                  limpa_form(limpar_campos_form);
                }

                //FOCO CAMPO
                if (focus !== null && focus !== undefined && focus !== "") {
                  foco_campo(focus);
                }

                //OCULTO E MOSTRO
                $(hide_area).hide();
                $(hide_area2).hide();
                $(hide_area3).hide();
                $(hide_area4).hide();
                $(hide_area5).hide();
                $(show_area).show();
                $(show_area2).show();
                $(show_area3).show();
                $(show_area4).show();
                $(show_area5).show();

            });
        }

        //=================================================================
        //TOGGLED LOGIN, ESQUECI SENHA
        if ($('.login-box')[0]) {
            $('body').addClass('login-box');
            $('body').on('click', '.login-navigation > li', function () {
                var z = $(this).data('block');
                var t = $(this).closest('.lc-block');
                t.removeClass('toggled');
                setTimeout(function () {
                    $(z).addClass('toggled');
                });
            });
        }

        //=================================================================
        //HOVER DE LINHA DA TABELA ZEBRADA
        tabela_zebrada();
        this.tabela_zebrada = function () {
            tabela_zebrada();
        }
        function tabela_zebrada(){
        }

        //=================================================================
        //SELECIONA TODAS AS LINHAS DA TABELA
        seleciona_linhas_tabela();
        this.seleciona_linhas_tabela = function () {
            seleciona_linhas_tabela();
        }
        function seleciona_linhas_tabela(){
            //SELECIONA TODAS AS LINHAS DA TABELA
            $("#check_all").change(function () {
              $('input:checkbox').prop('checked', this.checked);
              $('tr').toggleClass("selected_row", this.checked)
            });
            //SELECIONA LINHA DE TABELA
            $('table tbody :checkbox').change(function (event) {
              $(this).closest('tr').toggleClass("selected_row", this.checked);
            });
        }
        // Descomentar para selecionar a linha inteira
        // $('table tbody tr').click(function(event) {
        //   if (event.target.type !== 'checkbox') {
        //     $(':checkbox', this).trigger('click');
        //   }
        //   $("input[type='checkbox']").not('#check_all').change(function(e) {
        //     if($(this).is(":checked")){
        //       $(this).closest('tr').addClass("selected_row");
        //     }else{
        //       $(this).closest('tr').removeClass("selected_row");
        //     }
        //   });
        // });

        //=================================================================
        //SOMENTE NÚMEROS
        this.numeros_input = function () {
            numeros_input();
        }
        function numeros_input(){
            $(".numeros").keydown(function(event) {
        		/* Allow backspace, delete, tab, esc e enter */
        		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
        		/* Allow CTRL+A */
        		(event.keyCode == 65 && event.ctrlKey === true) ||
        		/* Allow CTRL+C */
        		(event.keyCode == 67 && event.ctrlKey === true) ||
        		/* Allow CTRL+X */
        		(event.keyCode == 88 && event.ctrlKey === true) ||
        		/* Allow CTRL+V */
        		(event.keyCode == 86 && event.ctrlKey === true) ||
        		/* Allow Command+A (Mac) */
        		(event.keyCode == 65 && event.metaKey === true) ||
        		/* Allow Command+C (Mac) */
        		(event.keyCode == 67 && event.metaKey === true) ||
        		/* Allow Command+X (Mac) */
        		(event.keyCode == 88 && event.metaKey === true) ||
        		/* Allow Command+V (Mac) */
        		(event.keyCode == 86 && event.metaKey === true) ||
        		/* Allow home, end, left e right keys */
        		(event.keyCode >= 35 && event.keyCode <= 39))
        		{
        			/* Boo */
        			return;
        		}
        		else
        	 	{
        			/* Stop key press */
        			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ))
        			{
        				event.preventDefault();
        			}
        		}
        	});
        }

        //=================================================================
        //FUNÇÃO DE AUTOCOMPLETE
        this.autocomplete = function (url0,campo){
            autocomplete(url0,campo);
        }
        function autocomplete(url0,campo){
            require(['jquery_ui'], function(){
                   var cache = {};
                   $(campo).autocomplete({
                         autoFocus: true,
                         minLength: 1,
                         source: function( request, response ) {
                               var term = request.term;
                               if(term != "" || term != " " || term != "  " || term != "   "){
                                   if ( term in cache ) {
                                         response( cache[ term ] );
                                         return;
                                   }
                                   $.getJSON( url0+'&term='+request.term, request, function( data, status, xhr ) {
                                         cache[ term ] = data;
                                         response( data );
                                   });
                               }
                         }
                   }).data("ui-autocomplete")._renderItem = function(ul, item) {
    				var $a = $("<a></a>").text(item.label);
    				highlightText(this.term, $a);
    				return $("<li></li>").append($a).appendTo(ul);
    			};
                function highlightText(text, $node) {
    				var searchText = $.trim(text).toLowerCase(), currentNode = $node.get(0).firstChild, matchIndex, newTextNode, newSpanNode;
    				while ((matchIndex = currentNode.data.toLowerCase().indexOf(searchText)) >= 0) {
    					newTextNode = currentNode.splitText(matchIndex);
    					currentNode = newTextNode.splitText(searchText.length);
    					newSpanNode = document.createElement("span");
    					newSpanNode.className = "highlight";
    					currentNode.parentNode.insertBefore(newSpanNode, currentNode);
    					newSpanNode.appendChild(newTextNode);
    				}
    			}
            });
        }

        //=================================================================
        //COMBOBOX
        function dropdown_bd(url, campo, descricao, descricao_sing, seleciono_campo, param_extra){
            $(campo).html('<option>Carregando ' + descricao + ' aguarde...</option>');
            if (param_extra !== null && param_extra !== undefined) {
               param_extra = param_extra;
            }else{
               param_extra = '';
            }
            $.ajax({
                url: url+param_extra,
                dataType: "json",
                type: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    $(campo).html('<option disabled selected>Selecione um(a) ' + descricao_sing + ' </option> <optgroup label="----------"></optgroup>');
                    for ($i = 0; $i < data.length; $i++) {
                        $(campo).append('<option value="' + data[$i].campo1 + '">' + data[$i].campo2 + '</option>');
                    }
                    if (seleciono_campo !== "") {
                        $(campo + ' option[value="' + seleciono_campo + '"]').attr({selected: 'selected'});
                    }
                    if ($('.select_personalizado').hasClass('dinamico')){
                        select_personalizado();
                    }
                }, error: function () {
                    $(campo).html('<option>Erro ao carregar ' + descricao + '</option>');
                }
            });
        }
        this.dropdown_bd = function (url, campo, descricao, descricao_sing, seleciono_campo, param_extra) {
            dropdown_bd(url, campo, descricao, descricao_sing, seleciono_campo, param_extra);
        };

        //=================================================================
        //CHANGCOMBOBOX
        this.chang_box_dropdown_bd = function (url, campo1, campo2, descricao, descricao_sing, descricao_sing2, valor_campo1, seleciono_campo) {
            $(campo2).html('<option>Selecione um(a) ' +descricao_sing2+ ' </option>');
            function change(){
                $(campo1).change(function (e) { //CARREGO COMBOBOX COM PARAMETROS DO CAMPO 1
                    $(campo2).focus();
                    dropdown_bd(url, campo2, descricao, descricao_sing, seleciono_campo,'&param='+$('select' +campo1+ ' option').filter(":selected").val());
                });
            }
            if (valor_campo1 !== "") {
                dropdown_bd(url, campo2, descricao, descricao_sing, seleciono_campo,'&param='+valor_campo1); //CARREGO COMBOBOX COM PARAMETROS DO CAMPO 1
                change(); //Chamo função change
            } else {
                change(); //Chamo função change
            }
        };

        //=================================================================
        //MONTA NOTIFICAÇÕES
        setInterval(function(){ notificacoes_adm() }, 500000);
        notificacoes_adm();
        function notificacoes_adm(){
            $.ajax({
                url:      $('#param_javascript').attr('action_notificacoes'),
                dataType: 'json',
                type:     'POST',
                success: function (result) {
                    $('#notificacoes_adm').html(result);
                    $('.not_lida').click(function (e) {
                        var link = $(this).attr('link');
                        $.ajax({
                            url:      $('#param_javascript').attr('action_notificacao_lida')+'&id='+$(this).attr('param_id'),
                            dataType: 'json',
                            type:     'POST',
                            success: function (result) {
                                redireciono(link);
                            },
                            error: function (jqXHR, textStatus, errorMessage) {
                                var sucesso = false;
                                if(errorMessage == ""){
                                    errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
                                }
                                mostrar_error_geral(jqXHR, textStatus, errorMessage);
                            }
                        });
                    });
                }
            });
        }

        //=================================================================
        //NOTIFICAÇÕES POPUP
        setInterval(function(){ notificacoes_popup() }, 500000);
        notificacoes_popup();
        function notificacoes_popup(){
            $.ajax({
                url:      $('#param_javascript').attr('action_notificacoes_popup'),
                dataType: 'json',
                type:     'POST',
                success: function (result) {
                    for (var i = 0; i < result.length; i++) {
                        if(result[i]['url_destino'] == ""){
                            mostrar_info('<strong style="font-size:20px;">NOVA NOTIFICAÇÃO!</strong> <span style="float:left;margin-top: 10px;width: 100%;"> '+result[i]['mensagem']+' </span>',result[i]['class_icon']);
                        }else{
                            if(result[i]['texto_botao_acao'] == ""){
                                mostrar_info('<strong style="font-size:20px;">NOVA NOTIFICAÇÃO!</strong>  <a href="'+path_raiz+result[i]['url_destino']+'"><span style="float:left;margin-top: 10px;width: 100%;"> '+result[i]['mensagem']+' </span></a> <span style="float:left;margin-top: 10px;width: 100%;"> <a style="color:#000 !important;;margin-top:-7px !important;"  href="'+path_raiz+result[i]['url_destino']+'" class="btn btn-default btn-xs hidden-sm"><i class="fa fa-external-link"></i> ABRIR</a></span>',result[i]['class_icon']);
                            }else{
                                mostrar_info('<strong style="font-size:20px;">NOVA NOTIFICAÇÃO!</strong>  <a href="'+path_raiz+result[i]['url_destino']+'"><span style="float:left;margin-top: 10px;width: 100%;"> '+result[i]['mensagem']+' </span></a> <span style="float:left;margin-top: 10px;width: 100%;"> <a style="color:#000 !important;;margin-top:-7px !important;"  href="'+path_raiz+result[i]['url_destino']+'" class="btn btn-default btn-xs hidden-sm"><i class="fa fa-external-link"></i> '+result[i]['texto_botao_acao']+'</a></span>',result[i]['class_icon']);
                            }
                        }
                        _notification(result[i]['mensagem'],'Zaaz framework',path_raiz+result[i]['url_destino']);
                    }
                }
            });
        }

        //=================================================================
        //NOTIFICAÇÃO DESKTOP
        var _notification = function(text, title, url){
          title = title || 'ichat.io'
          if (window.webkitNotifications) {
            if (window.webkitNotifications.checkPermission() != 0) {
              window.webkitNotifications.requestPermission();
            }
            window.webkitNotifications.createNotification('/favicon.ico', title, text).show();
          } else if ("Notification" in window) {
            // Firefox?
            if (Notification.permission === "granted") {
                var notification = new Notification(title, {
                  icon: '/favicon.ico',
                  body: text,
                });
                notification.onclick = function() {
                  window.open(url);
                }
            }
            else if (Notification.permission === 'default') {
              Notification.requestPermission(function (permission) {
                if (permission === "granted") {
                  // is this D.R.Y.?
                  var notification = new Notification(title, {
                    icon: '/favicon.ico',
                    body: text,
                  });
                  notification.onclick = function() {
                    window.open(url);
                  }
                }
              });
            }
            // else {} // permission === 'denied' → give up?
          } else {
            console.log("Notifications are not supported for this Browser/OS version yet.");
          }
    };

    //=================================================================
    //FUNÇÃO GENERICA JQUER AJAX LINK
    $('.ajax_jquery1').click(function (e) {
        e.preventDefault();
        var id_    = $(this).attr("id");
        var url    = $(this).attr("link");
        var url_id = $(this).attr("link_id");
        ajax_jquery_rapido(id_,url,url_id);
        return false;
    });
    this.ajax_jquery_rapido = function (id_,url,url_id){
        ajax_jquery_rapido(id_,url,url_id);
    }
    function ajax_jquery_rapido(id_,url,url_id){
         if (url_id == "true") {
              var id = $('input[name="id"]').val();
              url = url+'&id='+id;
         }
         var load_ = '.ajax_jquery_load';
         $(load_).html('<i class="fa fa-spinner fa-pulse"></i> PROCESSANDO ...');
         $("#"+id_).hide();
         $.ajax({
             url: url,
             dataType: 'json',
             type:     'POST',
             success: function (obj) {
                for (var p in obj) {
                     if (obj.hasOwnProperty(p)) {
                         var conteudo = [obj[p]];
                         if (p == "sucesso") {
                             require(['funcoes_notificacao'], function (notificacao) {
                                 var notificacao1 = new notificacao();
                                 notificacao1.mostrar_sucesso(conteudo);
                             });
                             $("#"+id_).show();
                             $(load_).html('');
                         }
                         if (p == "erro") {
                             require(['funcoes_notificacao'], function (notificacao) {
                                 var notificacao1 = new notificacao();
                                 notificacao1.mostrar_error(conteudo);
                             });
                             $("#"+id_).show();
                             $(load_).html('');
                         }

                     }
                 }
                 return false;
             }, error: function (jqXHR, textStatus, errorMessage) {
                 if(errorMessage == ""){
                     errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
                 }
                 require(['funcoes_notificacao'], function (notificacao) {
                     var notificacao1 = new notificacao();
                     notificacao1.mostrar_error_geral(jqXHR, textStatus, errorMessage);
                 });
                 return false;
             }
         });
     }

     //=================================================================
     //FUNÇÃO MONTA GRAFICO


     //function grafico_google(url,titulo,idarea,tipo){
     if($('#grafico_1').length || $('#grafico_2').length || $('#grafico_3').length || $('#grafico_4').length
    || $('#grafico_5').length || $('#grafico_6').length || $('#grafico_7').length || $('#grafico_8').length
    || $('#grafico_9').length || $('#grafico_10').length || $('#grafico_11').length || $('#grafico_12').length){
         grafico_google();
         this.grafico_google = function (){
             grafico_google();
         }
     }
     function grafico_google(){
         require(['chart_google'], function(){
             google.charts.load('current', {'packages':['corechart']});
             google.charts.setOnLoadCallback(drawChart);

              function drawChart(num) {

                //MONTO
                if($('#grafico_1').length){
                    var url    = $('#grafico_1').attr('url');
                    var titulo = $('#grafico_1').attr('titulo');
                    var tipo   = $('#grafico_1').attr('tipo');
                    var idarea = 'grafico_1';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_2').length){
                    var url    = $('#grafico_2').attr('url');
                    var titulo = $('#grafico_2').attr('titulo');
                    var tipo   = $('#grafico_2').attr('tipo');
                    var idarea = 'grafico_2';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_3').length){
                    var url    = $('#grafico_3').attr('url');
                    var titulo = $('#grafico_3').attr('titulo');
                    var tipo   = $('#grafico_3').attr('tipo');
                    var idarea = 'grafico_3';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_4').length){
                    var url    = $('#grafico_4').attr('url');
                    var titulo = $('#grafico_4').attr('titulo');
                    var tipo   = $('#grafico_4').attr('tipo');
                    var idarea = 'grafico_4';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_5').length){
                    var url    = $('#grafico_5').attr('url');
                    var titulo = $('#grafico_5').attr('titulo');
                    var tipo   = $('#grafico_5').attr('tipo');
                    var idarea = 'grafico_5';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_6').length){
                    var url    = $('#grafico_6').attr('url');
                    var titulo = $('#grafico_6').attr('titulo');
                    var tipo   = $('#grafico_6').attr('tipo');
                    var idarea = 'grafico_6';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_7').length){
                    var url    = $('#grafico_7').attr('url');
                    var titulo = $('#grafico_7').attr('titulo');
                    var tipo   = $('#grafico_7').attr('tipo');
                    var idarea = 'grafico_7';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_8').length){
                    var url    = $('#grafico_8').attr('url');
                    var titulo = $('#grafico_8').attr('titulo');
                    var tipo   = $('#grafico_8').attr('tipo');
                    var idarea = 'grafico_8';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_9').length){
                    var url    = $('#grafico_9').attr('url');
                    var titulo = $('#grafico_9').attr('titulo');
                    var tipo   = $('#grafico_9').attr('tipo');
                    var idarea = 'grafico_9';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_10').length){
                    var url    = $('#grafico_10').attr('url');
                    var titulo = $('#grafico_10').attr('titulo');
                    var tipo   = $('#grafico_10').attr('tipo');
                    var idarea = 'grafico_10';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_11').length){
                    var url    = $('#grafico_11').attr('url');
                    var titulo = $('#grafico_11').attr('titulo');
                    var tipo   = $('#grafico_11').attr('tipo');
                    var idarea = 'grafico_11';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }
                if($('#grafico_12').length){
                    var url    = $('#grafico_12').attr('url');
                    var titulo = $('#grafico_12').attr('titulo');
                    var tipo   = $('#grafico_12').attr('tipo');
                    var idarea = 'grafico_12';
                    var jsonPieChartData = ajax_s(url);
                    var piechartdata = new google.visualization.DataTable(jsonPieChartData);
                    var chart = recebe_tipo_grafico2(idarea);
                    var options = recebe_tipo_grafico(tipo);
                    chart.draw(piechartdata, options);
                }

                //VERIFICA FUNCOES
                function recebe_tipo_grafico(tipo){
                    if(tipo == 'pizza'){ var options = pizza(); }
                    if(tipo == 'pizza_3d'){ var options = pizza_3d(); }
                    if(tipo == 'anel'){ var options = anel(); }
                    if(tipo == 'barra_vertical'){ var options = barra_vertical(); }
                    if(tipo == 'barra_horizontal'){ var options = barra_horizontal(); }
                    return options;
                }

                //VERIFICA FUNCOES
                function recebe_tipo_grafico2(idarea){
                    if(tipo == 'pizza'){
                        var chart = new google.visualization.PieChart(document.getElementById(idarea));
                    }
                    if(tipo == 'pizza_3d'){
                        var chart = new google.visualization.PieChart(document.getElementById(idarea));
                    }
                    if(tipo == 'anel'){
                        var chart = new google.visualization.PieChart(document.getElementById(idarea));
                    }
                    if(tipo == 'barra_vertical'){
                        var chart = new google.visualization.BarChart(document.getElementById(idarea));
                    }
                    if(tipo == 'barra_horizontal'){
                        var chart = new google.visualization.ColumnChart(document.getElementById(idarea));
                    }
                    return chart;
                }

                //SUBMIT AJAX
                function ajax_s(url){
                      var jsonPieChartData = $.ajax({
                        url: url,
                        //data: "q="+num,
                        dataType:"json",
                        async: false
                      }).responseText;
                      return jsonPieChartData;
                }

                function pizza(){
                    var options = {
                        title: titulo,
                        chartArea: { left:"5%",top:"5%",width:"90%",height:"90%" },
                        bar: {groupWidth: "70%"},
                    };
                    return options;
                }
                function pizza_3d(){
                    var options = {
                        title: titulo,
                        chartArea: { left:"5%",top:"5%",width:"90%",height:"90%" },
                        'is3D':true,
                        bar: {groupWidth: "70%"},
                    };
                    return options;
                }

                function anel(){
                    var options = {
                        title: titulo,
                        chartArea: { left:"5%",top:"5%",width:"90%",height:"90%" },
                        pieHole: 0.4,
                        bar: {groupWidth: "70%"},
                    };
                    return options;
                }
                function barra_horizontal(){
                    var options = {
                        title: titulo,
                        bar: {groupWidth: "70%"},
                        legend: { position: "none" },
                        chartArea: { left:"10%",top:"7%",width:"87%",height:"80%" },
                    };
                    return options;
                }

                function barra_vertical(){
                    var options = {
                        title: titulo,
                        bar: {groupWidth: "70%"},
                        legend: { position: "none" },
                        chartArea: { left:"15%",top:"5%",width:"75%",height:"80%" },
                    };
                    return options;
                }

                //DOWNLOAD IMG
                /*var chart_div = document.getElementById(idarea);
                var chart = new google.visualization.BarChart(chart_div);

                google.visualization.events.addListener(chart, 'ready', function () {
                   chart_div.innerHTML = '<img src="' + chart.getImageURI() + '">';
                   console.log(chart_div.innerHTML);
                });
                chart.draw(piechartdata, options);*/
             }
         });
     }




//***************************************************************************************************************************************************************
//FUNÇÕES DE LISTAGEM GERAL $.AJAX
//***************************************************************************************************************************************************************

        //=================================================================
        //TRANSFORMO RESULT EM OBJETO
        this.result_var_jquery_ajax = function (url) {
            return result_var_jquery_ajax();
        };
        function result_var_jquery_ajax() {
            var result = jquery_ajax_return();
            return result;
        }

        //=================================================================
        //FUNÇÃO GENERICA AJAX
        this.jquery_ajax_return = function (callback, url, datatype, type, serialize_id, campo_load, img, data_dados) {
            return jquery_ajax_return(callback, url, datatype, type, serialize_id, campo_load, img, data_dados);
        };
        function jquery_ajax_return(callback, url, datatype, type, serialize_id, campo_load, img, data_dados) {
            if(campo_load != "false"){
                loading_geral('show', campo_load, img);
            }
            if (data_dados !== null && data_dados !== undefined) {
                var datas = data_dados;
            }else{
                var datas = $("#" + serialize_id).serialize();
            }
            //console.log(url);
            $.ajax({
                url: url,
                dataType: datatype,
                type: type,
                data: datas,
                success: callback,
                error: function (jqXHR, textStatus, errorMessage) {
                    if(errorMessage == ""){
                        errorMessage = "<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. ";
                    }
                    mostrar_error_geral(jqXHR, textStatus, errorMessage);
                    loading_geral('hide', campo_load, img);
                }
            });
        }



    }

    //Retorno modulo
    return funcoes;
});
