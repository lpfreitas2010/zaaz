define(function(){
    return {

//***************************************************************************************************************************************************************
//LISTAGEM GERAL COM RETORNO EM JSON
//***************************************************************************************************************************************************************
          listagem: function(x){

              require(['funcoes_gerais','config_modulo','config_mensagens'], function (funcoes,config,config_msg) {
                var funcoes    = new funcoes(path_raiz, path);
                var config     = new config(path_raiz, path);
                var config_msg = new config_msg(path_raiz, path);

                      //=================================================================
                      //CHAMO REQUISIÇÃO AJAX JQUERY
                      function jquery_ajax_return(funcao, path, type, type2, serialize, campo_load, img, data_dados) {
                          funcoes.jquery_ajax_return(funcao, path_raiz + path, type, type2, serialize, campo_load, img, data_dados);
                      }

                      //======================================================================================================================
                      //FUNÇÃO DE LISTAGEM GERAL RETORNO DADOS EM HTML
                      //======================================================================================================================
                      listagem(x);
                      function listagem(param_out_list,ordenar,ordenar2,qtd_reg_pp,ir_pagina,pagina_btn_prox_anter,pesquisa,btn_limpar_pesq,serialize) {

                        //=================================================
                        //MONTO PARAMETROS
                        //=================================================

                        //OCULTO BOTOES DE AÇÕES
                        oculto_btns_acoes();

                        //VARIAVEIS
                        var PAGINA = 1;

                        //VALIDO O CAMPO PARAMETRO OUTRAS LISTAGENS
                        if (param_out_list !== null && param_out_list !== undefined) {
                           param_out_list = param_out_list;
                        }else {
                           param_out_list = "";
                        }

                        //MONTO ORDENAR PARAMETRO 1
                        if (ordenar !== null && ordenar !== undefined) {
                           ordenar = "&ord="+ordenar;
                        }else{
                          ordenar = '';
                        }
                        //MONTO ORDENAR PARAMETRO 2
                        if (ordenar2 !== null && ordenar2 !== undefined) {
                           ordenar2 = "&ord2="+ordenar2;
                        }else{
                           ordenar2 = '';
                        }

                        //MONTO TOTAL DE REGISTROS P/ PAGINA
                        if (qtd_reg_pp !== null && qtd_reg_pp !== undefined) {
                           qtd_reg_pp = "&qtd_reg="+qtd_reg_pp;
                        }else{
                           qtd_reg_pp = '';
                        }

                        //MONTO IR PARA PÁGINA
                        if (ir_pagina !== null && ir_pagina !== undefined) {
                           PAGINA = ir_pagina;
                        }

                        //PEGO A PÁGINA DOS BTNS PROXIMOS E ANTERIOR
                        if (pagina_btn_prox_anter !== null && pagina_btn_prox_anter !== undefined) {
                           PAGINA = pagina_btn_prox_anter;
                        }

                        //PEGO O PARAMETRO DA PESQUISA SIMPLES
                        if (pesquisa !== null && pesquisa !== undefined) {
                           pesquisa = "&pesq="+pesquisa;
                        }else {
                           pesquisa = "";
                        }

                        //PEGO O PARAMETRO PARA LIMPAR PESQUISA SIMPLES
                        if (btn_limpar_pesq !== null && btn_limpar_pesq !== undefined) {
                           btn_limpar_pesq = "&limp_pesq="+btn_limpar_pesq;
                        }else {
                           btn_limpar_pesq = "";
                        }

                        //PEGO O PARAMETRO PARA PESQUISA AVANÇADA
                        if (serialize !== null && serialize !== undefined) {
                           serialize = serialize;
                        }else {
                           serialize = "";
                        }

                        //=================================================
                        //=================================================

                        //console.log($('#'+param_out_list+'listagem').attr('url')+"&page="+PAGINA+ordenar+ordenar2+qtd_reg_pp+pesquisa+btn_limpar_pesq);
                        var action_listagem = $('#'+param_out_list+'listagem').attr('url');
                        if(action_listagem !== null && action_listagem !== undefined){

                            //MONTO JQUERY AJAX
                            jquery_ajax_return(
                              listagem, //função de retorno
                              $('#'+param_out_list+'listagem').attr('url')+"&page="+PAGINA+ordenar+ordenar2+qtd_reg_pp+pesquisa+btn_limpar_pesq,
                              "json", //tipo
                              "POST", //method
                              serialize //serialize
                            );
                        }else{
                            funcoes_gerais(param_out_list);
                        }

                        //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                        function listagem(result) {

                          //MOSTRO NA TELA
                          $('#'+param_out_list+'listagem').html(result);
                          funcoes.loading_geral('hide','', ''); // oculto loading

                          //---------------
                          //FUNÇÕES
                          funcoes_gerais(param_out_list);
                          funcoes.mostra_area();
                          funcoes.tabela_zebrada();
                          funcoes.seleciona_linhas_tabela();

                          //MOSTRO AREAS
                          $('#titulo-caminho-listagem').show();

                        }
                      }

                      //======================================================================================================================
                      //FUNÇÕES GERAIS
                      //======================================================================================================================
                      function funcoes_gerais(param_out_list){

                         //=================================================================
                         //PEGO O PARAMETRO DE OUTRAS LISTAGENS
                         if (param_out_list !== null && param_out_list !== undefined) {
                            param_out_list = param_out_list;
                         }else {
                            param_out_list = "";
                         }

                         //=================================================================
                          //FOCO O CAMPO INPUT DE PESQUISA
                          if($('#'+param_out_list+'parametros_listagem').attr('paginaativa') == 1){
                              if(funcoes.areas_resp_return()=='false'){
                                  $('#'+param_out_list+'campo_pesquisa').focus();
                              }
                          }

                          //=================================================================
                          //FOCO CAMPO #IRPAGINA AO PASSAR MOUSE
                          $('#'+param_out_list+'ir_pagina,#'+param_out_list+'ir_pagina2').click(function () {
                              $(this).focus();
                              $(this).select();
                          });

                          //=================================================================
                          //FUNÇÕES PARA ORDENAR
                          require(['bsmSelect-master'], function (bsmSelect) {
                                $('#'+param_out_list+'option_ordenar').bsmSelect({
                                  showEffect: function($el){ $el.fadeIn(); },
                                  hideEffect: function($el){ $el.fadeOut(function(){ $(this).remove();}); },
                                  plugins: [$.bsmSelect.plugins.sortable()],
                                  title: '',
                                  highlight: 'highlight',
                                  addItemTarget: 'original',
                                  removeLabel: '<strong>X</strong>',
                                  containerClass: 'bsmContainer',                // Class for container that wraps this widget
                                  listClass: 'bsmList-custom',                   // Class for the list ($ol)
                                  listItemClass: 'bsmListItem-custom',           // Class for the <li> list items
                                  listItemLabelClass: 'bsmListItemLabel-custom', // Class for the label text that appears in list items
                                  removeClass: 'bsmListItemRemove-custom'       // Class given to the "remove" link
                                });
                          });
                          $('#'+param_out_list+'option_ordenar_form').click(function () {
                              var val = new Array(); //Pego valores array
                              $('#'+param_out_list+'option_ordenar :selected').each(function(i, sel){
                                  val.push($(this).val());
                              });
                              listagem(param_out_list,val,null,null,null,null,null,null,null); // Chamo a listagem
                          });
                          $('#'+param_out_list+'order_select').click(function () {
                              $('#'+param_out_list+'order_select').hide();
                              $('#'+param_out_list+'area-ordenacao').show();
                              $('#bsmSelectbsmContainer0').focus();
                          });

                          //=================================================================
                          //FUNÇÃO PARA GROUP BY
                          require(['select2'], function (select2) {
                              $(".select_multi").select2({
                                 tags: true,
                                 tokenSeparators: [',', ' ']
                              })
                          });
                          select_multiple();
                          $('#'+param_out_list+'option_ordenar2').change(function () {
                              var val = new Array(); //Pego valores array
                              $('#'+param_out_list+'option_ordenar2 :selected').each(function(i, sel){
                                  val.push($(this).val());
                              });
                              listagem(param_out_list,null,val,null,null,null,null,null,null); // Chamo a listagem
                          });

                          //=================================================================
                          //FUNÇÃO PARA TOTAL DE REGISTROS P/ PÁGINA
                          $('#'+param_out_list+'total_registros_pp').change(function () {
                              listagem(param_out_list,null,null,$('#'+param_out_list+'total_registros_pp option:selected').val(),null,null,null,null,null); // Chamo a listagem
                          });

                          //=================================================================
                          //FUNÇÃO PARA TOTAL DE REGISTROS P/ PÁGINA
                          $('#'+param_out_list+'total_registros_pp2').change(function () {
                              listagem(param_out_list,null,null,$('#'+param_out_list+'total_registros_pp2 option:selected').val(),null,null,null,null,null); // Chamo a listagem
                          });

                          //=================================================================
                          //FUNÇÃO PARA IR PARA UMA PÁGINA ESPECIFICA
                          $('#'+param_out_list+'ir_pagina,#'+param_out_list+'ir_pagina2').change(function () {
                              listagem(param_out_list,null,null,null,$(this).val(),null,null,null,null); // Chamo a listagem
                          });

                          //=================================================================
                          //FUNÇÃO DE PRÓXIMO E ANTERIOR
                          $('#'+param_out_list+'btn_anterior_pag,#'+param_out_list+'btn_anterior_pag2').click(function () {
                              qtd_paginas = $('#'+param_out_list+'parametros_listagem').attr('valorqtdpaginas'); // Pego a qtd de páginas
                              pagina_ativa = $('#'+param_out_list+'parametros_listagem').attr('paginaativa'); // Pego o numero da página ativa
                              if(pagina_ativa > 1){
                                pagina_ativa -= 1; //Contador
                              }
                              listagem(param_out_list,null,null,null,null,pagina_ativa,null,null,null); // Chamo a listagem
                          });
                          $('#'+param_out_list+'btn_proxima_pag,#'+param_out_list+'btn_proxima_pag2').click(function () {
                              qtd_paginas = $('#'+param_out_list+'parametros_listagem').attr('valorqtdpaginas'); // Pego a qtd de páginas
                              pagina_ativa = $('#'+param_out_list+'parametros_listagem').attr('paginaativa'); // Pego o numero da página ativa
                              pagina_ativa = parseInt(pagina_ativa) + 1; //Contador
                              listagem(param_out_list,null,null,null,null,pagina_ativa,null,null,null); // Chamo a listagem
                          });

                          //=================================================================
                          //PESQUISA SIMPLES
                          $('#'+param_out_list+'campo_pesquisa').change(function () {
                              pesquisa = $('#'+param_out_list+'campo_pesquisa').val(); // Pego o parametro da pesquisa
                              listagem(param_out_list,null,null,null,null,1,pesquisa,null,null); // Chamo a listagem e seto a como sendo 1
                          });
                          $('#'+param_out_list+'btn_pesquisar').click(function () {
                              pesquisa = $('#'+param_out_list+'campo_pesquisa').val(); // Pego o parametro da pesquisa
                              listagem(param_out_list,null,null,null,null,1,pesquisa,null,null); // Chamo a listagem e seto a como sendo 1
                          });

                          //=================================================================
                          //FUNÇÃO PARA LIMPAR OS FILTROS DE PESQUISA SIMPLES
                          $('#'+param_out_list+'btn_limpar_filtros,#'+param_out_list+'btn_limpar_filtros2,#'+param_out_list+'btn_limpar_filtros3').click(function () {
                              listagem(param_out_list,null,null,null,null,1,null,true,null); // Chamo a listagem e seto a como sendo 1
                              listagem(param_out_list,null,null,null,null,1,null,true,null); // Chamo a listagem e seto a como sendo 1
                          });

                          //=================================================================
                          //PEGO O SERIALIZE DA PESQUISA AVANÇADA
                          $('#'+param_out_list+'btn_pesquisa_avancada').click(function () {
                              listagem(param_out_list,null,null,null,null,1,'Pesquisa Avançada',null,param_out_list+'form-pesq_avancada'); // Chamo a listagem e seto a como sendo 1
                          });

                          //=================================================================
                          //FUNÇÃO QUE PEGA O PARAMETRO DE UM LI PARA UMA PESQUISA SIMPLES - para usar coloque <a href="#" class="btn_param_pesquisa" param_pesquisa="string">...</a>
                          $('.btn_param_pesquisa').click(function () {
                              param_pesquisa = $(this).attr('param_pesquisa'); // Pego o parametro da pesquisa
                              listagem(param_out_list,null,null,null,null,1,param_pesquisa,null,null); // Chamo a listagem e seto a como sendo 1
                          });

						  //=================================================================
						  //FUNÇÃO QUE PEGA O PARAMETRO DE UM LI PARA UMA PESQUISA SIMPLES - para usar coloque <a href="#" class="btn_param_pesquisa_focus" param_pesquisa="string">...</a>
						  $('.btn_param_pesquisa_focus').click(function () {
							  param_pesquisa = $(this).attr('param_pesquisa'); // Pego o parametro da pesquisa
							  $('#campo_pesquisa').val(param_pesquisa);
							  $('#campo_pesquisa').focus();
						  });

                          //=================================================================
                          //FUNÇÃO DE EDITAR
                          $('.btn_editar').click(function () {
                              var url = $(this).attr('param_url') // Pego a URL
                              var id  = $(this).attr('param_id') // Pego o ID
                              editar(url,id); // Função de editar
                          });

                          //=================================================================
                          //FUNÇÃO DE EXCLUIR
                          $('.btn_excluir').click(function () {
                              var url = $(this).attr('param_url') // Pego a URL
                              var id  = $(this).attr('param_id') // Pego o ID
                              excluir(url,id); // Função de editar
                          });

                          //=================================================================
                          //FUNÇÃO DE EXCLUIR CHECKBOX
                          $('#btn_excluir_check,#btn_excluir_check_resp').click(function (e) {
                              e.preventDefault();
                              var val = new Array(); //Pego valores array
                              $('.checkbox1:checked').each(function(){ //Pego valores array
                                val.push($(this).val());
                              });
                              var url = $(this).attr('param_url') // Pego a URL
                              excluir(url,val); // Função de excluir
                          });

                          //=================================================================
                          //FUNÇÃO DE DETALHAMENTO
                          $('.btn_detalhamento').click(function () {
                              var id = $(this).attr('param_id') // Pego o ID
                              detalhamento(id); // Função de detalhamento
                          });

                          //=================================================================
                          //FUNÇÃO DE ATIVAR E DESATIVAR
                          $('.btn_ativar').click(function () {
                              var url = $(this).attr('param_url') // Pego a URL
                              var id  = $(this).attr('param_id') // Pego o ID
                              ativar(url,id); // Função de ativar e desativar
                          });

                          //=================================================================
                          //FUNÇÃO DE ATIVAR E DESATIVAR CHECKBOX
                          saving_record = 0; //contador para evitar submit duplo
                          $('#btn_ativar_check,#btn_ativar_check_resp,#btn_ativar_check2,#btn_ativar_check2_resp').click(function (e) {
                              if (saving_record === 0) {
                                  e.preventDefault();
                                  saving_record = 1; //contador para evitar submit duplo
                                  var val = new Array(); //Pego valores array
                                  $('.checkbox1:checked').each(function(){ //Pego valores array
                                    val.push($(this).val());
                                  });
                                  var url = $(this).attr('param_url') // Pego a URL
                                  ativar(url,val); // Função de ativar e desativar
                              }
                          });

                          //=================================================================
                          //FUNÇÃO DE PEGAR OS IDS DO CHECKBOX
                          $('.checkbox1').click(function () { // Checkbox das linhas
                              var checked = $(".checkbox1").is(":checked");
                              if(checked == true){
                                  $('.list_op2').show(); //mostro opções permitidas
                                  if($('.checkbox1:checked').length == 1){ //permitido o btn editar e o btn de detalhamento
                                     $('.list_op1').show(); //mostro opções permitidas
                                     var id_checkbox = $('.checkbox1:checked').val(); //pego o id
                                     $('#btn_editar').attr('param_id', id_checkbox); //Seto o valor do id no btn editar
                                     $('#btn_detalhamento').attr('param_id', id_checkbox); //Seto o valor do id no btn detalhamento
                                  }else{
                                    $('.list_op1').hide(); //oculto opções permitidas
                                  }
                              }else{
                                  $('.list_op1').hide(); //oculto opções permitidas
                                  $('.list_op2').hide(); //oculto opções permitidas
                              }
                          });
                          $('#check_all').click(function () { // Checbox selecionar tudo
                              $('.list_op1').hide(); //oculto opções permitidas
                              $('.list_op2').hide(); //oculto opções permitidas
                              var checked = $("#check_all").is(":checked");
                              if(checked == true){
                                  $('.list_op2').show(); //mostro opções permitidas
                              }else{
                                  $('.list_op1').hide(); //oculto opções permitidas
                                  $('.list_op2').hide(); //oculto opções permitidas
                              }
                          });

                          //=================================================================
                          //FUNÇÃO DE EXCLUIR TODOS OS REGISTROS
                          $('#btn_excluir_tudo,#btn_excluir_tudo2').click(function () {

                              //CONFIRMAÇÃO DE EXCLUSÃO
                              var url = $(this).attr('param_url');
                              swal({
                                  title: config_msg.mensagens()[0],
                                  text: config_msg.mensagens()[4],
                                  type: "error",
                                  showCancelButton: true,
                                  confirmButtonColor: "#DD6B55",
                                  confirmButtonText: config_msg.mensagens()[2],
                                  showConfirmButton: true,
                                  closeOnConfirm: true
                              }, function(){

                                  //EXECUTO URL
                                  jquery_ajax_return(
                                      excluir_tudo, //função de retorno
                                      url, //url
                                      "json", //tipo
                                      "GET", //method
                                      "", //serialize
                                      "", //campo load
                                      "" //nome img
                                  );
                                  //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                                  function excluir_tudo(result) {
                                    for (var p in result) {
                                      if (result.hasOwnProperty(p)) {
                                          if (p == "sucesso") {
                                              swal(config_msg.mensagens()[13], result[p], "success", "true"); // Mensagem de sucesso
                                              listagem('',null,null,null,null,$('#parametros_listagem').attr('paginaativa'),null,null,null); // Chamo a listagem
                                          }
                                          if (p == "erro") {
                                              swal(config_msg.mensagens()[14], result[p], "error", "false", "1"); // Mensagem de erro
                                              funcoes.mostrar_error(result[p]); // Mensagem de erro
                                              oculto_btns_acoes();
                                          }
                                          funcoes.loading_geral('hide','', ''); // oculto loading
                                      }
                                    }
                                  }
                              });
                          });

                          //=================================================================
                          //FECHAR E ABRIR MENSAGEM INFORMATIVA
                          $('.fechar_msg_info').click(function () { // Fechar mensagem com click
                              $('#'+param_out_list+'btn_area_msg_informativa').show(); // Mostro btn para reabrir mensagem
                              $('#'+param_out_list+'area_msg_informativa').hide(); // Oculto area mensagem
                          });
                          $('.abrir_msg_info').click(function () { // Abrir mensagem com click
                              funcoes.gerarCookie(param_out_list+'area_msg_informativa_'+paginajs, ''); //gravo cookie
                              $('#'+param_out_list+'btn_area_msg_informativa').hide(); // Oculto btn para reabrir mensagem
                              $('#'+param_out_list+'area_msg_informativa').show(); // Mostro area mensagem
                          });

                         //=================================================================
                         //HOVER MOSTRAR AÇÕES DA LINHA
                         /*$(".hover_acoes_td").hover(function(){
                              $(".hover_acoes").hide();
                              $("#area_acoes_"+$(this).attr('param_id')).show();
                              var x =  $("#area_acoes_td_"+$(this).attr('param_id')).position();
							  var res = 1 + x.top;
                              $("#area_acoes_"+$(this).attr('param_id')).css( "top", res);
							  $("#area_acoes_"+$(this).attr('param_id'))
								 .mouseenter(function() {
								   $("#area_acoes_td_"+$(this).attr('param_id')).css( "background-color", "#dbe1f1");
								 })
							   $("#area_acoes_"+$(this).attr('param_id'))
								 .mouseleave(function() {
								    $("#area_acoes_td_"+$(this).attr('param_id')).css( "background-color", "");
							 });
                         });
                        $(".col-md-12,.row,thead").hover(function(){
                            $(".hover_acoes").hide();
                        });*/

                         //=================================================================
                         //TABELA RESPONSIVA
                         funcoes.tabela_responsiva();

                         //=================================================================
                         //HOVER MOSTRAR AÇÕES DA LINHA 2
                         $( ".hover_acoes_td" ).hover(function() {
                            $(".hover_acoes2").show();
                          }, function() {
                            $(".hover_acoes2").hide();
                          });

                         //=================================================================
                         //PEGO O LINK E REDIRECIONO
                         $('.link').click(function (e) {
                             window.location = $(this).attr('link');
                         });

                         //=================================================================
                         //SELECT 2
                         select_multiple_ord();

                         //=================================================================
                         //INCLUO ATALHOS TECLADO
                         require(['jquery-hotkeys'], function () {

                             //SALVAR
                             $(document).bind('keydown', 'alt+s', function(evt) {
                                 listagem('');
                                 evt.stopPropagation( );
                                 evt.preventDefault( );
                                 return false;
                             });
                             //CAMPO NOVO
                             /*$(document).bind('keydown', 'alt+n', function(evt) {
                                   $('#area_btns_acoes').hide();
                                   $('#area_listagem').hide();
                                   $('#area-editar').hide();
                                   $('#area-adicionar').show();
                                   $('#area_btns_status_acoes_forms').show();
                                   $('#btn_salvar_novo_form').show();
                                   $('#area_formulario').show();
                                   funcoes.limpa_form('form_add_edd');
                                   monto_campos_form();
                                   seto_dados_form();
                                   evt.stopPropagation( );
                                   evt.preventDefault( );
                                   return false;
                             });*/
                         });

                         //=================================================================
                         //INCLUO MASCARA
                         require(['mask'], function (mask) {
                             var maskBehavior = function (val) {
                                 return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                             },
                             options = {onKeyPress: function (val, e, field, options) {
                                 field.mask(maskBehavior.apply({}, arguments), options);
                             }
                             };
                             $('.telefone').mask(maskBehavior, options);
                             $('.money').mask('000.000.000.000.000,00', {reverse: true});
                             $('.money2').mask("#.##0,00", {reverse: true});
                         });

                         //=================================================================
                         //SOMENTE NÚMEROS INPUT
                         funcoes.numeros_input();

                         //=================================================================
                         //LOADING AO CLICAR EM BTNS
                         funcoes.loading_elements();

                         //=================================================================
                         //INCLUO DATETIMEPICKER
                         require(['moment'], function (moment) {
                           moment.locale("pt-br");
                           require(['datetimepicker'], function (datetimepicker) {
                             if ($('.date-picker')[0]) {
                               $('.date-picker').datetimepicker({
                                 format: 'DD/MM/YYYY',
								//  inline: true,

                               });
                             }
                             if ($('.time-picker')[0]) {
                               $('.time-picker').datetimepicker({
                                 format: 'LT'
                               });
                             }
                           });
                         });

                         //=================================================================
                         //INCLUO LIGHBOX
                         require(['lightbox']);

                         //=================================================================
                         //INCLUO MOSTRA AREA
                         funcoes.mostra_area();

                         //=================================================================
                         //INCLUO AREAS RESPONSIVAS
                         areas_resp();

                         //=================================================================
                         //LINK
                         funcoes.link_redirect();

                        //=================================================================
                         //FUNÇÃO ABERTA COM CONFIRMAÇÃO
                         funcao_aberta();

						 //=================================================================
                         //MONTOS CAMPOS FORM
						 monto_campos_form();

                         //=================================================================
                         //INICIALIZO TOOLTIP
                         tooltip();


                      }

                      //=================================================================
                      //DESMARCO CHECKBOX DA TABELA
                      function oculto_btns_acoes(){
                          $('.list_op1').hide(); //oculto opções permitidas
                          $('.list_op2').hide(); //oculto opções permitidas
                      }

                      //=================================================================
                      //FUNÇÃO DO BTN EDITAR
                      function editar(url,id){

                          //OCULTO LISTAGEM
                          $('#area_listagem').hide();
                          $('#area_btns_acoes').hide();
                          $('#area-adicionar').hide();

                          //ABRIR FORM DE PESQUISA
                          funcoes.subir_topo();
                          $('#area-editar').show();
                          $('#area-editar1').hide();
                          $('#area_btns_status_acoes_forms').show();
                          $('#btn_salvar_novo_form').hide();

                          //SETO OS DADOS DO EDITAR
                          $('input[name="id"]').val(id);

                          //CHAMO FUNÇÃO QUE SETA OS DADOS
                          seto_dados_form(id);
                          listagem_arquivos();
                      }

                      //=================================================================
                      //FUNÇÃO DO BTN EXCLUIR
                      function excluir(url,id){

                          //CONFIRMAÇÃO DE EXCLUSÃO
                          swal({
                              title: config_msg.mensagens()[0],
                              text: config_msg.mensagens()[1],
                              type: "error",
                              showCancelButton: true,
                              confirmButtonColor: "#DD6B55",
                              confirmButtonText: config_msg.mensagens()[2],
                              showConfirmButton: true,
                              closeOnConfirm: true
                          }, function(){

                              //EXECUTO URL
                              jquery_ajax_return(
                                  excluir, //função de retorno
                                  url, //url
                                  "json", //tipo
                                  "POST", //method
                                  "", //serialize
                                  "", //campo load
                                  "", //nome img
                                  "id="+id //data
                              );
                              //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                              function excluir(result) {
                                for (var p in result) {
                                  if (result.hasOwnProperty(p)) {
                                      if (p == "sucesso") {
                                          swal(config_msg.mensagens()[13], result[p], "success", "true"); // Mensagem de sucesso
                                          listagem('',null,null,null,null,$('#parametros_listagem').attr('paginaativa'),null,null,null); // Chamo a listagem
                                      }
                                      if (p == "erro") {
                                          swal(config_msg.mensagens()[14], result[p], "error", "false", "1"); // Mensagem de erro
                                          funcoes.mostrar_error(result[p]); // Mensagem de erro
                                          oculto_btns_acoes();
                                      }
                                      funcoes.loading_geral('hide','', ''); // oculto loading
                                  }
                                }
                              }
                          });
                      }

                      //=================================================================
                      //FUNÇÃO DO BTN DETALHAMENTO
                      id_detalho = $('#detalho_conteudo').attr('param_id');
                      if(id_detalho != ""){
                          detalhamento(id_detalho);
                      }
                      function detalhamento(id){

                          //MOSTRO AREA DE DETALHAMENTO
                          funcoes.subir_topo();
                          $('#area_detalho').show();

                          //STATUS
                          if($('.params').attr('status_detalho') == true){
                              //EXECUTO URL
                              jquery_ajax_return(
                                  detalhamento, //função de retorno
                                  $('#detalho_conteudo').attr('url')+'&id='+id,
                                  "json", //tipo
                                  "POST", //method
                                  "" //serialize
                              );
                              //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                              function detalhamento(result) {
                                  $('#detalho_conteudo').html(result); // Mostro na tela
                                  funcoes.mostra_area();  //INCLUO MOSTRA AREA
                                  funcoes.link_redirect(); //INCLUO LINK
                                  areas_resp(); //AREA RESPONSIVAS
                                  require(['jQuery.printElement'], function () { //IMPRIMIR AREA
                                      $("#imprimir_area").click(function(){
                                         $("#area-imprimir").printElement({printMode: 'inframe'});
                                      });
                                   });
                                  funcoes.loading_geral('hide','', ''); // oculto loading
                              }
                          }
                      }

                      //=================================================================
                      //FUNÇÃO DO BTN ATIVAR
                      function ativar(url,id){
                          //EXECUTO URL
                          jquery_ajax_return(
                            ativar, //função de retorno
                            url, //url
                            "json", //tipo
                            "POST", //method
                            "", //serialize
                            "", //campo load
                            "", //nome img
                            "id="+id //data
                          );
                          //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                          function ativar(result) {
                            for (var p in result) {
                              if (result.hasOwnProperty(p)) {
                                  if (p == "sucesso") { // Mensagem de sucesso
                                      funcoes.mostrar_sucesso(result[p]);
                                      listagem('',null,null,null,null,$('#parametros_listagem').attr('paginaativa'),null,null,null); // Chamo a listagem
                                  }
                                  if (p == "erro") { // Mensagem de erro
                                      funcoes.mostrar_error(result[p]);
                                      oculto_btns_acoes();
                                  }
                                  funcoes.loading_geral('hide','', ''); // oculto loading
                              }
                            }
                          }
                      }

                      //=================================================================
                      //FUNÇÃO QUE MONTA OS CKEDITOR, COMBOBOX, CHANGBOX
                      monto_campos_form();
                      function monto_campos_form(){

                          //EXECUTO URL
                          jquery_ajax_return(
                              monto_campos_form_, //função de retorno
                              $('.params').attr('monto_campos_form'),
                              "json", //tipo
                              "POST", //method
                              "" //serialize
                          );
                          //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                          function monto_campos_form_(result) {
                            for (var p in result) {

                                //MONTO CKEDITOR
                                if(result[p]['tipo'] == 'ckeditor'){
                                    if ($("#"+result[p]['id_ckeditor']).length){
                                        monta_ckeditor_(result[p]['tipo_ckeditor'],result[p]['id_ckeditor']); // camp_fechado, camp_aberto, camp_basico, camp_inline - id do textarea do form
                                    }
                                }

                                //MONTO AUTOCOMPLETE
                                if(result[p]['tipo'] == 'autocomplete'){
                                    if ($("#"+result[p]['id_autocomplete']).length){
                                        autocomplete_(result[p]['tipo_autocomplete'],'#'+result[p]['id_autocomplete']); // tipo, id do #campo do form
                                    }
                                }

                                //MONTO COMBOBOX
                                if(result[p]['tipo'] == 'combobox'){
                                    if ($("#"+result[p]['id_combobox']).length){
                                        var campo1_id = $('#'+result[p]['id_combobox']+'_id').val();
                                    }
                                    if (campo1_id == null && campo1_id == undefined && campo1_id == "") { campo1_id = ''; }
                                    dropdown_bd_(result[p]['tipo_combobox'], '#'+result[p]['id_combobox'], result[p]['txt_plural_combobox'], result[p]['txt_sing_combobox'], campo1_id);  // param tipo, id do #campo, descricao, descricao_sing, seleciono_campo
                                }

                                //MONTO CHANGBOX
                                if(result[p]['tipo'] == 'changbox'){
                                  var campo1_id = $('#'+result[p]['id_combobox']+'_id').val();
                                  var campo2_id = $('#'+result[p]['id_changbox']+'_id').val();
                                  $('.limpo_id_editar').click(function () { //LIMPO CAMPO hidden QUANDO CLICAR EM NOVO
                                      $('#'+result[p]['id_combobox']+'_id').val('');
                                      $('#'+result[p]['id_changbox']+'_id').val('');
                                  });
                                  if (campo1_id == null && campo1_id == undefined && campo1_id == "") { campo1_id = ''; }
                                  if (campo2_id == null && campo2_id == undefined && campo2_id == "") { campo2_id = ''; }
                                  chang_box_dropdown_bd_(result[p]['tipo_changbox'], '#'+result[p]['id_combobox'], "#"+result[p]['id_changbox'], result[p]['txt_plural_changbox'], result[p]['txt_sing_changbox'], result[p]['txt_sing_combobox'],campo1_id, campo2_id); // param tipo, id do #campo1, id do #campo2, descricao, descricao_sing, descricao_sing2, valor do campo 1, valor do campo 2
                                }

                            }
                            //MOSTRO OS CAMPOS
                            if($('input[name="id"]').val() != ""){
                                $('#area_formulario').show();
                            }
                            funcoes.limpo_mensagens_erros(); //Limpo erros
                            funcoes.loading_geral('hide','', ''); // oculto loading
                          }
                      }
                      function monta_ckeditor_(campo,campo1){ //MONTO CKEDITOR
                          monta_ckeditor(campo,campo1); // camp_fechado, camp_aberto, camp_basico, camp_inline - id do textarea do form
                      }
                      function autocomplete_(campo1,campo2){ //MONTO AUTOCOMPLETE
                          funcoes.autocomplete($('.params').attr('autocomplete')+'&tipo='+campo1,campo2); // Url da requisição ajax, id do #campo do form
                      }
                      function dropdown_bd_(campo1,campo2,campo3,campo4,campo5){ //MONTO COMBOBOX
                          funcoes.dropdown_bd($('.params').attr('combobox')+'&tipo='+campo1, campo2, campo3, campo4, campo5);  // Url, #campo, descricao, descricao_sing, seleciono_campo
                      }
                      function chang_box_dropdown_bd_(campo1,campo2,campo3,campo4,campo5,campo6,campo7,campo8){ //MONTO CHANGCOMBOBOX
                          funcoes.chang_box_dropdown_bd($('.params').attr('changcombobox')+'&tipo='+campo1, campo2, campo3, campo4, campo5, campo6, campo7,campo8); // param tipo, id do #campo1, id do #campo2, descricao, descricao_sing, descricao_sing2, valor do campo 1, valor do campo 2
                      }

                      //=================================================================
                      //FUNÇÃO  QUE MONTA OS CKEDITOR, COMBOBOX, CHANGBOX CLICK
                      $('.monta_dados_form').click(function () {
                          funcoes.limpa_form('form_add_edd');
                          funcoes.limpo_campos_hidden();
                          monto_campos_form();
                          seto_dados_form();
                          if(funcoes.areas_resp_return()=='false'){
                              $('#'+$('.params').attr('foco_campo_add_edd')).focus();
                          }
                          listagem_arquivos();
                      });

                      //=================================================================
                      //FUNÇÃO QUE LIMPA O FORM NO CLICK
                      $('.limpo_form_add_edd').click(function () {
                          funcoes.limpa_form('form_add_edd');
                          $('input[name="id"]').val('');
                          funcoes.loading_geral('hide','', ''); // oculto loading
                      });

                      //=================================================================
                      //FUNCÃO QUE SETA OS DADOS NO FORM EDITAR
                      seto_dados_form();
                      function seto_dados_form(param) {
                          var id_editar = $('input[name="id"]').val();
                          if(id_editar != ""){ //campo editar
                              $('#btn_salvar_novo_form').hide(); //oculto btn salvar e novo no editar
                          }
                          if (id_editar == null && id_editar == undefined && id_editar == "") {
                              var id_editar = param;
                          }else{
                              var id_editar = id_editar;
                          }
                          jquery_ajax_return(
                                  seto_editar, //função de retorno
                                  $('.params').attr('set_editar')+'&id='+id_editar, //url
                                  "json", //tipo
                                  "POST", //method
                                  "", //serialize
                                  "false"

                              );
                              function seto_editar(result) {
                                  monto_campos_form();
                                  $('#erros_inline_n').html('');
                                  for (var p in result) {
                                      funcoes.seto_dados_form(result[p]['tipo_set'],result[p]['campo_set_form'],result[p]['valor_campo_set']); // Seto os dados no form
                                      funcoes.loading_geral('hide','', ''); // oculto loading
                                  }
                                  if(funcoes.areas_resp_return()=='false'){
                                      $('#'+$('.params').attr('foco_campo_add_edd')).focus();
                                  }
                            }
                      }

                      //=================================================================
                       if($('.editor_html_completo').length){
                            monta_ckeditor ('completo','.editor_html_completo');
                       }
                       if($('.editor_html_basico').length){
                            monta_ckeditor ('basico','.editor_html_basico');
                       }
                       if($('.editor_html_inline').length){
                            monta_ckeditor ('inline','.editor_html_inline');
                       }
                      //FUNÇÃO QUE MONTA O CAMPO CKEDITOR
                      function monta_ckeditor (tipo,campo) {
                          require(['tinymce'], function(){

                              //COMPLETO
                              if(tipo == 'completo'){
                                  
                                  tinymce.init({
                                      selector: campo,
                                      language: 'pt_BR',
                                      height: 100,
                                      theme: 'modern',
                                      plugins: [
                                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                                        'insertdatetime media nonbreaking save table contextmenu directionality',
                                        'emoticons template paste textcolor colorpicker textpattern imagetools spellchecker autoresize placeholder',
                                      ],
                                      toolbar1: 'bold italic underline removeformat | forecolor backcolor | styleselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | image media link hr | fullscreen preview code',
                                      toolbar2: '',
                                      image_advtab: true,
                                      force_br_newlines : true,
                                      force_p_newlines : false,
                                      forced_root_block : '',
                                      contextmenu: "cut copy paste | image mdeia link hr inserttable",
                                      statusbar: false,
                                      file_browser_callback: function(field, url, type, win) {
                                           tinymce.activeEditor.windowManager.open({
                                               file: path_libs_inc_plugins+'/tinymce/kcfinder/browse.php?opener=tinymce4&lang=pt-br&field=' + field + '&type=' + type,
                                               title: 'Gerenciador de Arquivos',
                                               width: 800,
                                               height: 500,
                                               inline: true,
                                               close_previous: false
                                           }, {
                                               window: win,
                                               input: field
                                           });
                                           return false;
                                      },
                                      setup: function (editor) {
                                            editor.on('change', function () {
                                                tinymce.triggerSave();
                                            });
                                            if (funcoes.getPageSize()[0]<992){
                                                editor.on('focus', function () {
                                                    $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").show();
                                                });
                                                editor.on('blur', function () {
                                                    $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide();
                                                });
                                                editor.on("init", function() {
                                                    $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide();
                                                });
                                            }
                                      }
                                  });
                              }

                              //BÁSICO
                              if(tipo == 'basico'){
                                  tinymce.init({
                                      selector: campo,
                                      language: 'pt_BR',
                                      height: 100,
                                      theme: 'modern',
                                      menubar: false,
                                      plugins: [
                                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                                        'insertdatetime media nonbreaking save table contextmenu directionality',
                                        'emoticons template paste textcolor colorpicker textpattern imagetools spellchecker autoresize placeholder'
                                      ],
                                      toolbar1: 'bold italic underline removeformat | alignleft aligncenter alignright alignjustify | image link   ',
                                      toolbar2: '',
                                      image_advtab: true,
                                      force_br_newlines : true,
                                      force_p_newlines : false,
                                      forced_root_block : '',
                                      contextmenu: "cut copy paste | image link",
                                      statusbar: false,
                                      file_browser_callback: function(field, url, type, win) {
                                           tinymce.activeEditor.windowManager.open({
                                               file: path_libs_inc_plugins+'/tinymce/kcfinder/browse.php?opener=tinymce4&lang=pt-br&field=' + field + '&type=' + type,
                                               title: 'Gerenciador de Arquivos',
                                               width: 800,
                                               height: 500,
                                               inline: true,
                                               close_previous: false
                                           }, {
                                               window: win,
                                               input: field
                                           });
                                           return false;
                                       },
                                       setup: function (editor) {
                                            editor.on('change', function () {
                                                tinymce.triggerSave();
                                            });
                                            if (funcoes.getPageSize()[0]<992){
                                                editor.on('focus', function () {
                                                    $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").show();
                                                });
                                                editor.on('blur', function () {
                                                    $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide();
                                                });
                                                editor.on("init", function() {
                                                    $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide();
                                                });
                                            }
                                      }
                                  });
                              }

                              //INLINE
                              if(tipo == 'inline'){

                                  tinymce.init({
                                      selector: campo,
                                      language: 'pt_BR',
                                      theme: 'modern',
                                      inline: true,
                                      plugins: [
                                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                                        'insertdatetime media nonbreaking save table contextmenu directionality',
                                        'emoticons template paste textcolor colorpicker textpattern imagetools spellchecker placeholder',
                                      ],
                                      toolbar1: 'bold italic underline removeformat | forecolor backcolor | styleselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | image media link hr | fullscreen preview code',
                                      toolbar2: '',
                                      image_advtab: true,
                                      force_br_newlines : true,
                                      force_p_newlines : false,
                                      forced_root_block : '',
                                      contextmenu: "cut copy paste | image mdeia link hr inserttable",
                                      file_browser_callback: function(field, url, type, win) {
                                           tinymce.activeEditor.windowManager.open({
                                               file: path_libs_inc_plugins+'/tinymce/kcfinder/browse.php?opener=tinymce4&lang=pt-br&field=' + field + '&type=' + type,
                                               title: 'Gerenciador de Arquivos',
                                               width: 800,
                                               height: 500,
                                               inline: true,
                                               close_previous: false
                                           }, {
                                               window: win,
                                               input: field
                                           });
                                           return false;
                                      },
                                      setup: function (editor) {
                                            editor.on('change', function () {
                                                tinymce.triggerSave();
                                            });
                                      }
                                  });
                              }

                          });
                      }

                      //=================================================================
                      //FUNÇÃO QUE MOSTRA OU OCULTA OS MENUS RESPONSIVOS
                      areas_resp();
                      function areas_resp(){
                         funcoes.areas_resp();
                      }

                      //=================================================================
                      //SUBMETO FORMULÁRIO JQUERY SIMPLES ( RETORNO EM JSON )
                      submeto_formulario();
                      function submeto_formulario(){
                          submit_form_json(
                            "#form_add_edd", //id do form
                            ".btn_salvar_form", //id do botão submit
                            "<i class=\"fa fa-refresh fa-pulse\"></i> PROCESSANDO ... ", //Texto no botão submit ao carregar
                            'false', //Auto hidden sucesso (true or false)
                            'false', //Auto hidden erro (true or false)
                            '15000', //Tempo hidden
                            'false', //autoclean somente em sucesso (true or false)
                            'false', //Subir para o topo a cada submit (true or false)
                            '.btn_salvar_novo_form', //id btn salvar e novo
                            '.btn_salvar_fechar_form' //id btn salvar e fechar
                          );
                      }

                      //=================================================================
                      //SUBMETO FORMULÁRIO JQUERY SIMPLES ( RETORNO EM JSON )
                      function submit_form_json (
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

                          //SUBMIT AO CLICAR NO BTN SALVAR E NOVO
                          $(id_submit2).click(function (e) {
                              e.preventDefault();
                              $('html,body').animate({scrollTop: 0},0);
                              $('input[name="op_salvar"]').val('sucesso_limpo');

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

                          //SUBMIT AO CLICAR NO BTN SALVAR E FECHAR
                          $(id_submit3).click(function (e) {
                              e.preventDefault();
                              var fechar_area_form = 'true';
                              $('input[name="op_salvar"]').val('sucesso');

                              //VARIAVEIS
                              var value_btn = $(id_submit).html(); //Pego texto do botão submit
                              $(id_submit).attr('disabled', 'disabled'); //Desativo botão submit
                              $(id_submit2).attr('disabled', 'disabled'); //Desativo botão submit2
                              $(id_submit3).attr('disabled', 'disabled'); //Desativo botão submit3
                              $(id_submit).html(text_btn_loading); //Altero texto do botão submit
                              $(id_form + '_js_control').val('true'); //Controle js do form
                              funcoes.loading_geral2('show','', ''); // loading
                              submit_form_json(fechar_area_form,value_btn);
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
                                                  if(param == 'true'){ //fecho areas do formulário e volto para listagem
                                                      $('#area_listagem').show();
                                                      $('#area_btns_acoes').show();
                                                      $('#area_formulario').hide();
                                                      $('#area_btns_status_acoes_forms').hide();
                                                      funcoes.limpa_form(id_form,'true');
                                                  }
                                                  listagem(''); //atualizo a listagem de registros
                                                  listagem_arquivos(); //atualizo a listagem de arquivos

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
                                                  seto_dados_form();
                                              }
                                              if (p == "id") { //Seto valor no campo id
                                                  if(param == 'true'){ //fecho areas do formulário e volto para listagem
                                                      $('input[name="id"]').val(''); //limpo campo id
                                                      funcoes.loading_geral('hide','', ''); // oculto loading
                                                  }else{
                                                      $('input[name="id"]').val(conteudo); //gravo campo id
                                                      funcoes.loading_geral('hide','', ''); // oculto loading
                                                      //funcoes.limpa_form('form_add_edd');
                                                      //funcoes.limpo_campos_hidden();
                                                      monto_campos_form();
                                                      seto_dados_form();
                                                      if(funcoes.areas_resp_return()=='false'){
                                                          $('#'+$('.params').attr('foco_campo_add_edd')).focus();
                                                      }
                                                      listagem_arquivos();
                                                  }
                                                  $('#btn_salvar_novo_form').hide();
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

                                      //ERROS INLINE
                                      for (var p in obj) {
                                          for (var p in obj) {
                                             if (p == "sucesso") {
                                                 var final = '';
                                             }
                                             if (p == "erro") {
                                                 var final = '';
                                             }
                                          }
                                      }
                                      if (final !== "") {
                                         var erros_inline = ' <div class="callout callout-danger"> ';
                                         erros_inline +=  '<h4><i class="fa fa-remove"></i> Erros no cadastro verifique.</h4><p>';
                                         for (var p in obj) {
                                             if (obj.hasOwnProperty(p)) {
                                                 erros_inline += obj[p]+'<br />';
                                            }
                                        }
                                         erros_inline +=  '</div></p>';
                                      }else{
                                         var erros_inline = '';
                                      }
                                      $('#erros_inline_n').html(erros_inline);

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
                                      $(id_submit).removeAttr('disabled').html(value_btn);
                                      $(id_submit2).removeAttr('disabled');
                                      $(id_submit3).removeAttr('disabled');
                                      funcoes.loading_geral2('hide','', ''); // loading
                                      notificacao1.mostrar_error_geral(errorMessage);
                                      
                                  }
                              });
                          }
                        });
                      };

                      //=================================================================
                      //FORM DE ADICIONAR MODAL
                      $('.add_modal').click(function () {
                          var param_id = $(this).attr('data-target');
                          $(param_id+'_area').attr('modal','true');
                          if($(param_id+'_area').length && $(param_id+'_area').attr('modal') == 'true'){
                              $(param_id+'_area').html('<iframe style="border:none;overflow:auto;width:100%;"  height="400" src="'+$(param_id+'_area').attr('url')+'"></iframe>');
                          }
                      });
                      $('.close').click(function () {
                          //$('#campo_modal_1').html('');
                          monto_campos_form();
                          seto_dados_form();
                          if(funcoes.areas_resp_return()=='false'){
                              $('#'+$('.params').attr('foco_campo_add_edd')).focus();
                          }
                      });


                    //=================================================================
                    //AJAX DE LISTAGEM DE ARQUIVOS
                    listagem_arquivos();
                    function listagem_arquivos(){
                        require(['funcoes_notificacao'], function (notificacao) {
                        var notificacao1 = new notificacao();

                        //PARAMETROS
                        var text_btn_loading = "<i class=\"fa fa-refresh fa-pulse\"></i> CARREGANDO ... ";

                        //LIMPO CAMPOS
                        $('#carrego_listagem1').html(''); //Imprimo na tela
                        $('#carrego_listagem2').html(''); //Imprimo na tela
                        $('#carrego_listagem3').html(''); //Imprimo na tela
                        $('#carrego_listagem4').html(''); //Imprimo na tela

                        if($('input[name="id"]').val() != ""){ //campo editar

                            //CARREGO LISTAGEM 1
                            if($('#carrego_listagem1').length){
                                var id_listagem     = '#carrego_listagem1';
                                var action          = $(id_listagem).attr('url'); //pego url
                                var tipo            = $(id_listagem).attr('tipo'); //pego tipo
                                var titulo          = $(id_listagem).attr('titulo'); //pego titulo
                                var icone           = $(id_listagem).attr('icone'); //pego icone
                                var icone2          = $(id_listagem).attr('icone2'); //pego icone2
                                var campo           = $(id_listagem).attr('campo'); //pego campo input
                                var upload_multiplo = $(id_listagem).attr('upload_multiplo'); //pego parametro upload multiplo
                                submit_form_json_list_arquivos(action,tipo,id_listagem,titulo,icone,icone2,campo,upload_multiplo);
                            }

                            //CARREGO LISTAGEM 2
                            if($('#carrego_listagem2').length){
                                var id_listagem     = '#carrego_listagem2';
                                var action          = $(id_listagem).attr('url'); //pego url
                                var tipo            = $(id_listagem).attr('tipo'); //pego tipo
                                var titulo          = $(id_listagem).attr('titulo'); //pego titulo
                                var icone           = $(id_listagem).attr('icone'); //pego icone
                                var icone2          = $(id_listagem).attr('icone2'); //pego icone2
                                var campo           = $(id_listagem).attr('campo'); //pego campo input
                                var upload_multiplo = $(id_listagem).attr('upload_multiplo'); //pego parametro upload multiplo
                                submit_form_json_list_arquivos(action,tipo,id_listagem,titulo,icone,icone2,campo,upload_multiplo);
                            }

                            //CARREGO LISTAGEM 3
                            if($('#carrego_listagem3').length){
                                var id_listagem     = '#carrego_listagem3';
                                var action          = $(id_listagem).attr('url'); //pego url
                                var tipo            = $(id_listagem).attr('tipo'); //pego tipo
                                var titulo          = $(id_listagem).attr('titulo'); //pego titulo
                                var icone           = $(id_listagem).attr('icone'); //pego icone
                                var icone2          = $(id_listagem).attr('icone2'); //pego icone2
                                var campo           = $(id_listagem).attr('campo'); //pego campo input
                                var upload_multiplo = $(id_listagem).attr('upload_multiplo'); //pego parametro upload multiplo
                                submit_form_json_list_arquivos(action,tipo,id_listagem,titulo,icone,icone2,campo,upload_multiplo);
                            }
                            //CARREGO LISTAGEM 4
                            if($('#carrego_listagem4').length){
                                var id_listagem     = '#carrego_listagem4';
                                var action          = $(id_listagem).attr('url'); //pego url
                                var tipo            = $(id_listagem).attr('tipo'); //pego tipo
                                var titulo          = $(id_listagem).attr('titulo'); //pego titulo
                                var icone           = $(id_listagem).attr('icone'); //pego icone
                                var icone2          = $(id_listagem).attr('icone2'); //pego icone2
                                var qtd_arq         = $(id_listagem).attr('icone2'); //pego icone2
                                var campo           = $(id_listagem).attr('campo'); //pego campo input
                                var upload_multiplo = $(id_listagem).attr('upload_multiplo'); //pego parametro upload multiplo
                                submit_form_json_list_arquivos(action,tipo,id_listagem,titulo,icone,icone2,campo,upload_multiplo);
                            }
                        }

                        //FUNÇÃO AJAX
                        function submit_form_json_list_arquivos(action,tipo,id_listagem,titulo,icone,icone2,campo,upload_multiplo) {
                                //JQUERY AJAX
                                $('#'+campo).val('');
                                $(id_listagem).html(''); //Imprimo na tela
                                $.ajax({
                                    url: $('.params').attr('action_list_arquivos')+'&tipo='+action+'&id='+$('input[name="id"]').val(),
                                    dataType: "json",
                                    type: 'GET',
                                    data: '',
                                    success: function (result) {
                                        var html = '';
                                        require(['lightbox']);
                                        if(upload_multiplo == true){
                                            var add_mais_arquivo   = '<a href="javascript:void(0);" campo="'+campo+'" data-title="tooltip" data-placement="top" title="Adicionar mais arquivos" class="pull-right foco_campo_input add-img-arq"><i class="fa fa-plus"></i> Adicionar Arquivos</a>';
                                            var add_mais_imagem    = '<a href="javascript:void(0);" campo="'+campo+'" data-title="tooltip" data-placement="top" title="Adicionar mais imagens" class="pull-right foco_campo_input add-img-arq"><i class="fa fa-plus"></i> Adicionar Imagens</a>';
                                            var class_tamanho      = 'col-md-12';
                                            var class_item_arquivo = 'caixa-arquivo';
                                            var class_item_imagem  = 'caixa-galeria ';
                                            html += '<div class="col-md-12 col-sm-12 col-xs-12"><div class="box box-default">'; //area-geral-listagem
                                            if(tipo == 'arquivo'){
                                            //    html += '<div class="box-header with-border"><h3 class="box-title"> <i class="'+icone+'"></i> '+titulo+' </h3> '+add_mais_arquivo+'</div><div class="box-body">';
                                            }
                                            if(tipo == 'imagem'){
                                            //    html += '<div class="box-header with-border"><h3 class="box-title"> <i class="'+icone2+'"></i> '+titulo+' </h3> '+add_mais_imagem+'</div><div class="box-body">';
                                            }
                                        }else{
    										var add_mais_arquivo   = '<div class="box-header with-border"><h3 class="box-title"> <i class="'+icone+'"></i> '+titulo+' </h3> <a href="javascript:void(0);" campo="'+campo+'" data-title="tooltip" data-placement="top" title="Trocar arquivo" class="pull-right foco_campo_input add-img-arq"><i class="fa fa-refresh"></i> Trocar Arquivo</a> </div><div class="box-body">';
    										var add_mais_imagem    = ' <div class="box-header with-border"><h3 class="box-title"> <i class="'+icone2+'"></i> '+titulo+' </h3> <a href="javascript:void(0);" campo="'+campo+'" data-title="tooltip" data-placement="top" title="Trocar imagem" class="pull-right foco_campo_input add-img-arq"><i class="fa fa-refresh"></i> Trocar Imagem</a> </div><div class="box-body">';
                                            var class_tamanho      = 'col-md-3';
                                            var class_item_arquivo = 'caixa-arquivo';
                                            var class_item_imagem  = 'caixa-galeria-one';
                                            html += '<div class="col-md-12 col-sm-12 col-xs-12"><div class="box box-default">';
                                            if(tipo == 'arquivo'){
                                                //html += ''+add_mais_arquivo+'';
                                            }
                                            if(tipo == 'imagem'){
                                                //html += ''+add_mais_imagem+'';
                                            }
                                        }
                                        html += '<div class="box-body">';
                                        for (var i = 0; i < result.length; i++) {
                                            if(tipo == 'arquivo'){
                                              html += '<div class="'+class_item_arquivo+'"><a data-title="tooltip" data-placement="top" title="Abrir arquivo (Nova Janela)" href="'+result[i]['path_arquivo']+result[i]['url_arquivo']+'" target="_blank"><i class="'+icone+'"></i> '+result[i]['descricao']+'</a> <a href="javascript:void(0);" title="Excluir Arquivo" data-title="tooltip" data-placement="top" class="excluir_arquivo" tipo="'+action+'" url_arquivo="'+result[i]['url_arquivo']+'" param_id="'+result[i]['id_arquivo']+'"><i class="fa fa-times danger-dark"></i></a> </div>';
                                            }
                                            if(tipo == 'imagem'){
                                               html += '<div class="'+class_item_imagem+'"><a href="javascript:void(0);" data-title="tooltip" data-placement="top" title="Excluir Imagem" class="excluir_arquivo" tipo="'+action+'" url_arquivo="'+result[i]['url_arquivo']+'" param_id="'+result[i]['id_arquivo']+'"><i class="fa fa-times danger-dark"></i></a>  <a data-title="tooltip" data-lightbox="roadtrip" data-placement="top" title="Abrir imagem" href="'+result[i]['path_arquivo']+result[i]['url_arquivo']+'" target="_blank"> <img class="center-cropped" width="92" height="80" src="'+result[i]['path_arquivo']+result[i]['url_arquivo']+'" ></a> </div>';
                                            }
                                        }
                                        html += '</div></div></div>';

                                        $(id_listagem).html(html); //Imprimo na tela
                                        excluir_arquivo(); //excluo arquivo
                                        foco_campo_input(); //foco campo input

                                    }
                                });
                            }
                      });
                    }

                    //=================================================================
                    //FUNÇÃO QUE FOCA CAMPO
                    function foco_campo_input(){
                        $('.foco_campo_input').click(function () {
                            if(funcoes.areas_resp_return()=='false'){
                                $('#'+$(this).attr('campo')).focus();
                            }
                        });
                    };

                    //=================================================================
                    //FUNÇÃO DE EXCLUIR ARQUIVO
                    function excluir_arquivo(){
                        $('.excluir_arquivo').click(function () {

                            //CONFIRMAÇÃO DE EXCLUSÃO
                            var url         = $('.params').attr('action_excluir_arquivo');
                            var id          = $(this).attr('param_id');
                            var url_arquivo = $(this).attr('url_arquivo');
                            var tipo        = $(this).attr('tipo');
                            swal({
                                title: config_msg.mensagens()[5],
                                text: config_msg.mensagens()[6],
                                type: "error",
                                showCancelButton: true,
                                confirmButtonColor: "#DD6B55",
                                confirmButtonText: config_msg.mensagens()[5],
                                showConfirmButton: true,
                                closeOnConfirm: true
                            }, function(){

                                //EXECUTO URL
                                jquery_ajax_return(
                                    excluir_arquivo, //função de retorno
                                    url+'&tipo='+tipo+'&id='+id+'&url_arquivo='+url_arquivo, //url
                                    "json", //tipo
                                    "GET", //method
                                    "", //serialize
                                    "", //campo load
                                    "" //nome img
                                );
                                //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                                function excluir_arquivo(result) {
                                  for (var p in result) {
                                    if (result.hasOwnProperty(p)) {
                                        if (p == "sucesso") {
                                            swal(config_msg.mensagens()[13], result[p], "success", "true"); // Mensagem de sucesso
                                            listagem_arquivos();
                                        }
                                        if (p == "erro") {
                                            swal(config_msg.mensagens()[14], result[p], "error", "false", "1"); // Mensagem de erro
                                            //funcoes.mostrar_error(result[p]); // Mensagem de erro
                                        }
                                        funcoes.loading_geral('hide','', ''); // oculto loading
                                    }
                                  }
                                }
                            });
                        });
                    }

                    //=================================================================
                    //FUNÇÃO ABERTA COM CONFIRMAÇÃO
                    funcao_aberta();
                    function funcao_aberta(){
                        $('.funcao_aberta').click(function () {

                            //CONFIRMAÇÃO DE EXCLUSÃO
                            var url        = $('.params').attr('action_funcao_aberta');
                            var param      = $(this).attr('param');
                            var title_msg  = $(this).attr('title_msg');
                            var title_text = $(this).attr('title_text');

                            if(title_msg == undefined && title_msg == undefined){
                                action_funcao_aberta();
                                var msg_conf_status = false;
                            }else{
                                var msg_conf_status = true;
                                swal({
                                    title: title_msg,
                                    text: title_text,
                                    type: "success",
                                    showCancelButton: true,
                                    confirmButtonColor: "#24a23c",
                                    confirmButtonText: config_msg.mensagens()[10],
                                    showConfirmButton: true,
                                    closeOnConfirm: true
                                }, function(){
                                    action_funcao_aberta();
                                });
                            }
                            function action_funcao_aberta(){
                                //EXECUTO URL
                                jquery_ajax_return(
                                    funcao_aberta, //função de retorno
                                    url+param, //url
                                    "json", //tipo
                                    "GET", //method
                                    "", //serialize
                                    "", //campo load
                                    "" //nome img
                                );
                                //RESULTADO DA REQUISIÇÃO AJAX JQUERY
                                function funcao_aberta(result) {
                                  for (var p in result) {
                                    if (result.hasOwnProperty(p)) {
                                        if (p == "sucesso") {
                                            if(msg_conf_status == false){
                                                funcoes.mostrar_sucesso(result[p]); // Mensagem de sucesso
                                            }else{
                                                swal(config_msg.mensagens()[11], result[p], "success", "true"); // Mensagem de sucesso
                                            }
                                            listagem('',null,null,null,null,$('#parametros_listagem').attr('paginaativa'),null,null,null); // Chamo a listagem
                                        }
                                        if (p == "erro") {
                                            if(msg_conf_status == false){
                                                funcoes.mostrar_error(result[p]); // Mensagem de erro
                                            }else{
                                                swal(config_msg.mensagens()[14], result[p], "error", "false", "1"); // Mensagem de erro
                                                funcoes.mostrar_error(result[p]); // Mensagem de erro
                                            }
                                            oculto_btns_acoes();
                                        }
                                        funcoes.loading_geral('hide','', ''); // oculto loading
                                    }
                                  }
                                }
                            }

                        });
                    }

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
                    //SELECT 2 MULTIPLE
                    select_multiple_ord();
                    function select_multiple_ord(){
                        require(['select2'], function (select2) {
                            $(".select2").select2({
                               tags: true,
                               tokenSeparators: [',', ' ']
                            })
                        });
                    }

                    //=================================================================
                    //SELECT MULTIPLE NOVO
                    select_multiple();
                    function select_multiple(){
                        require(['chosen'], function (chosen) {
                            $(".select_multiple").chosen({no_results_text: "Nenhum resultado encontrado.",  create_option: true});
                        });
                    }

                    //=================================================================
                    //SELECT PERSONALIZADO
                    if ($('.select_personalizado').hasClass('estatico')){
                        select_personalizado();
                        function select_personalizado(){
                            require(['chosen'], function (chosen) {
                                if($(".select_personalizado").html() != ""){
                                    $(".select_personalizado").chosen({no_results_text: "Nenhum resultado encontrado!"});
                                }
                            });
                        }
                    };

                    //=================================================================
                    //FUNÇÃO AUTOHIDDEN COM SELECT CHANG
                    $('.select_chang_show_hide').change(function () {
                        var sel = $(this).get(0);
                        var selecionado = sel.options[sel.selectedIndex].value;
                        $('.select_chang_show_hide_areas').hide();
                        $('.area_sh_'+selecionado).show();
                    });
                    $('.select_chang_show_hide1').change(function () {
                        var sel = $(this).get(0);
                        var selecionado = sel.options[sel.selectedIndex].value;
                        $('.select_chang_show_hide_areas1').hide();
                        $('.area_sh_'+selecionado).show();
                    });

                    //=================================================================
                    //MODO MODAL DEIXO VISIVEL SÓ O CONTEUDO
                    var param_modal_add = $('.params').attr('param_modal_add');
                    if(param_modal_add == "param_modal_add"){
                        $('.param_modal_add').remove();
                        $('.content-wrapper').css('padding-top', '15px');
                    }

                    //=================================================================
                    //FUNÇÃO REDIRECIONA PÁGINA RESTRIÇÕES
                    $('.link_restricao').click(function () {
                        var link = $(this).attr('link');
                        var param_value = $(this).attr('param_value');
                        funcoes.redireciono(link+param_value);
                    });

                    //=================================================================
                    //FUNÇÃO GENERICA JQUER AJAX LINK
                    $('.ajax_jquery').click(function (e) {
                        e.preventDefault();
                        var id_    = $(this).attr("id");
                        var url    = $(this).attr("link");
                        var url_id = $(this).attr("link_id");
                        funcoes.ajax_jquery_rapido(id_,url,url_id);
                    });

                    //=================================================================
                    //INICIALIZO tooltip
                    tooltip();
                    function tooltip(){
                        require(['bootstrap'], function (tooltip) {
                            $('[data-title="tooltip"]').tooltip();
                        });
                        require(['bootstrap'], function (popover) {
                             $('[data-toggle="popover"]').popover({ trigger: "hover",html: true });
                        });
                    }


              });
          }//fim listagem


      }
});
