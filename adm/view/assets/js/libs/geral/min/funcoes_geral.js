define(function(){return{listagem:function(a){require(["funcoes_gerais","config_modulo","config_mensagens"],function(e,t,r){function i(a,t,r,i,o,n,l,s){e.jquery_ajax_return(a,path_raiz+t,r,i,o,n,l,s)}function o(a,t,r,o,s,c,u,_,d){function m(t){$("#"+a+"listagem").html(t),e.loading_geral("hide","",""),n(a),e.mostra_area(),e.tabela_zebrada(),e.seleciona_linhas_tabela(),$("#titulo-caminho-listagem").show()}l();var p=1;a=null!==a&&void 0!==a?a:"",t=null!==t&&void 0!==t?"&ord="+t:"",r=null!==r&&void 0!==r?"&ord2="+r:"",o=null!==o&&void 0!==o?"&qtd_reg="+o:"",null!==s&&void 0!==s&&(p=s),null!==c&&void 0!==c&&(p=c),u=null!==u&&void 0!==u?"&pesq="+u:"",_=null!==_&&void 0!==_?"&limp_pesq="+_:"",d=null!==d&&void 0!==d?d:"";var h=$("#"+a+"listagem").attr("url");null!==h&&void 0!==h?i(m,$("#"+a+"listagem").attr("url")+"&page="+p+t+r+o+u+_,"json","POST",d):n(a)}function n(a){a=null!==a&&void 0!==a?a:"",1==$("#"+a+"parametros_listagem").attr("paginaativa")&&"false"==e.areas_resp_return()&&$("#"+a+"campo_pesquisa").focus(),$("#"+a+"ir_pagina,#"+a+"ir_pagina2").click(function(){$(this).focus(),$(this).select()}),require(["bsmSelect-master"],function(e){$("#"+a+"option_ordenar").bsmSelect({showEffect:function(a){a.fadeIn()},hideEffect:function(a){a.fadeOut(function(){$(this).remove()})},plugins:[$.bsmSelect.plugins.sortable()],title:"",highlight:"highlight",addItemTarget:"original",removeLabel:"<strong>X</strong>",containerClass:"bsmContainer",listClass:"bsmList-custom",listItemClass:"bsmListItem-custom",listItemLabelClass:"bsmListItemLabel-custom",removeClass:"bsmListItemRemove-custom"})}),$("#"+a+"option_ordenar_form").click(function(){var e=new Array;$("#"+a+"option_ordenar :selected").each(function(a,t){e.push($(this).val())}),o(a,e,null,null,null,null,null,null,null)}),$("#"+a+"order_select").click(function(){$("#"+a+"order_select").hide(),$("#"+a+"area-ordenacao").show(),$("#bsmSelectbsmContainer0").focus()}),require(["select2"],function(a){$(".select_multi").select2({tags:!0,tokenSeparators:[","," "]})}),A(),$("#"+a+"option_ordenar2").change(function(){var e=new Array;$("#"+a+"option_ordenar2 :selected").each(function(a,t){e.push($(this).val())}),o(a,null,e,null,null,null,null,null,null)}),$("#"+a+"total_registros_pp").change(function(){o(a,null,null,$("#"+a+"total_registros_pp option:selected").val(),null,null,null,null,null)}),$("#"+a+"total_registros_pp2").change(function(){o(a,null,null,$("#"+a+"total_registros_pp2 option:selected").val(),null,null,null,null,null)}),$("#"+a+"ir_pagina,#"+a+"ir_pagina2").change(function(){o(a,null,null,null,$(this).val(),null,null,null,null)}),$("#"+a+"btn_anterior_pag,#"+a+"btn_anterior_pag2").click(function(){qtd_paginas=$("#"+a+"parametros_listagem").attr("valorqtdpaginas"),pagina_ativa=$("#"+a+"parametros_listagem").attr("paginaativa"),pagina_ativa>1&&(pagina_ativa-=1),o(a,null,null,null,null,pagina_ativa,null,null,null)}),$("#"+a+"btn_proxima_pag,#"+a+"btn_proxima_pag2").click(function(){qtd_paginas=$("#"+a+"parametros_listagem").attr("valorqtdpaginas"),pagina_ativa=$("#"+a+"parametros_listagem").attr("paginaativa"),pagina_ativa=parseInt(pagina_ativa)+1,o(a,null,null,null,null,pagina_ativa,null,null,null)}),$("#"+a+"campo_pesquisa").change(function(){pesquisa=$("#"+a+"campo_pesquisa").val(),o(a,null,null,null,null,1,pesquisa,null,null)}),$("#"+a+"btn_pesquisar").click(function(){pesquisa=$("#"+a+"campo_pesquisa").val(),o(a,null,null,null,null,1,pesquisa,null,null)}),$("#"+a+"btn_limpar_filtros,#"+a+"btn_limpar_filtros2,#"+a+"btn_limpar_filtros3").click(function(){o(a,null,null,null,null,1,null,!0,null),o(a,null,null,null,null,1,null,!0,null)}),$("#"+a+"btn_pesquisa_avancada").click(function(){o(a,null,null,null,null,1,"Pesquisa Avançada",null,a+"form-pesq_avancada")}),$(".btn_param_pesquisa").click(function(){param_pesquisa=$(this).attr("param_pesquisa"),o(a,null,null,null,null,1,param_pesquisa,null,null)}),$(".btn_param_pesquisa_focus").click(function(){param_pesquisa=$(this).attr("param_pesquisa"),$("#campo_pesquisa").val(param_pesquisa),$("#campo_pesquisa").focus()}),$(".btn_editar").click(function(){var a=$(this).attr("param_url"),e=$(this).attr("param_id");s(a,e)}),$(".btn_excluir").click(function(){var a=$(this).attr("param_url"),e=$(this).attr("param_id");c(a,e)}),$("#btn_excluir_check,#btn_excluir_check_resp").click(function(a){a.preventDefault();var e=new Array;$(".checkbox1:checked").each(function(){e.push($(this).val())});var t=$(this).attr("param_url");c(t,e)}),$(".btn_detalhamento").click(function(){var a=$(this).attr("param_id");u(a)}),$(".btn_ativar").click(function(){var a=$(this).attr("param_url"),e=$(this).attr("param_id");_(a,e)}),saving_record=0,$("#btn_ativar_check,#btn_ativar_check_resp,#btn_ativar_check2,#btn_ativar_check2_resp").click(function(a){if(0===saving_record){a.preventDefault(),saving_record=1;var e=new Array;$(".checkbox1:checked").each(function(){e.push($(this).val())});var t=$(this).attr("param_url");_(t,e)}}),$(".checkbox1").click(function(){var a=$(".checkbox1").is(":checked");if(1==a)if($(".list_op2").show(),1==$(".checkbox1:checked").length){$(".list_op1").show();var e=$(".checkbox1:checked").val();$("#btn_editar").attr("param_id",e),$("#btn_detalhamento").attr("param_id",e)}else $(".list_op1").hide();else $(".list_op1").hide(),$(".list_op2").hide()}),$("#check_all").click(function(){$(".list_op1").hide(),$(".list_op2").hide();var a=$("#check_all").is(":checked");1==a?$(".list_op2").show():($(".list_op1").hide(),$(".list_op2").hide())}),$("#btn_excluir_tudo,#btn_excluir_tudo2").click(function(){var a=$(this).attr("param_url");swal({title:r.mensagens()[0],text:r.mensagens()[4],type:"error",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:r.mensagens()[2],showConfirmButton:!0,closeOnConfirm:!0},function(){function t(a){for(var t in a)a.hasOwnProperty(t)&&("sucesso"==t&&(swal(r.mensagens()[13],a[t],"success","true"),o("",null,null,null,null,$("#parametros_listagem").attr("paginaativa"),null,null,null)),"erro"==t&&(swal(r.mensagens()[14],a[t],"error","false","1"),e.mostrar_error(a[t]),l()),e.loading_geral("hide","",""))}i(t,a,"json","GET","","","")})}),$(".fechar_msg_info").click(function(){$("#"+a+"btn_area_msg_informativa").show(),$("#"+a+"area_msg_informativa").hide()}),$(".abrir_msg_info").click(function(){e.gerarCookie(a+"area_msg_informativa_"+paginajs,""),$("#"+a+"btn_area_msg_informativa").hide(),$("#"+a+"area_msg_informativa").show()}),e.tabela_responsiva(),$(".hover_acoes_td").hover(function(){$(".hover_acoes2").show()},function(){$(".hover_acoes2").hide()}),$(".link").click(function(a){window.location=$(this).attr("link")}),j(),require(["jquery-hotkeys"],function(){$(document).bind("keydown","alt+s",function(a){return o(""),a.stopPropagation(),a.preventDefault(),!1})}),require(["mask"],function(a){var e=function(a){return 11===a.replace(/\D/g,"").length?"(00) 00000-0000":"(00) 0000-00009"},t={onKeyPress:function(a,t,r,i){r.mask(e.apply({},arguments),i)}};$(".telefone").mask(e,t),$(".money").mask("000.000.000.000.000,00",{reverse:!0}),$(".money2").mask("#.##0,00",{reverse:!0})}),e.numeros_input(),e.loading_elements(),require(["moment"],function(a){a.locale("pt-br"),require(["datetimepicker"],function(a){$(".date-picker")[0]&&$(".date-picker").datetimepicker({format:"DD/MM/YYYY"}),$(".time-picker")[0]&&$(".time-picker").datetimepicker({format:"LT"})})}),require(["lightbox"]),e.mostra_area(),b(),e.link_redirect(),C(),d(),B()}function l(){$(".list_op1").hide(),$(".list_op2").hide()}function s(a,t){$("#area_listagem").hide(),$("#area_btns_acoes").hide(),$("#area-adicionar").hide(),e.subir_topo(),$("#area-editar").show(),$("#area-editar1").hide(),$("#area_btns_status_acoes_forms").show(),$("#btn_salvar_novo_form").hide(),$('input[name="id"]').val(t),g(t),x()}function c(a,t){swal({title:r.mensagens()[0],text:r.mensagens()[1],type:"error",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:r.mensagens()[2],showConfirmButton:!0,closeOnConfirm:!0},function(){function n(a){for(var t in a)a.hasOwnProperty(t)&&("sucesso"==t&&(swal(r.mensagens()[13],a[t],"success","true"),o("",null,null,null,null,$("#parametros_listagem").attr("paginaativa"),null,null,null)),"erro"==t&&(swal(r.mensagens()[14],a[t],"error","false","1"),e.mostrar_error(a[t]),l()),e.loading_geral("hide","",""))}i(n,a,"json","POST","","","","id="+t)})}function u(a){function t(a){$("#detalho_conteudo").html(a),e.mostra_area(),e.link_redirect(),b(),require(["jQuery.printElement"],function(){$("#imprimir_area").click(function(){$("#area-imprimir").printElement({printMode:"inframe"})})}),e.loading_geral("hide","","")}e.subir_topo(),$("#area_detalho").show(),1==$(".params").attr("status_detalho")&&i(t,$("#detalho_conteudo").attr("url")+"&id="+a,"json","POST","")}function _(a,t){function r(a){for(var t in a)a.hasOwnProperty(t)&&("sucesso"==t&&(e.mostrar_sucesso(a[t]),o("",null,null,null,null,$("#parametros_listagem").attr("paginaativa"),null,null,null)),"erro"==t&&(e.mostrar_error(a[t]),l()),e.loading_geral("hide","",""))}i(r,a,"json","POST","","","","id="+t)}function d(){function a(a){for(var t in a){if("ckeditor"==a[t].tipo&&$("#"+a[t].id_ckeditor).length&&m(a[t].tipo_ckeditor,a[t].id_ckeditor),"autocomplete"==a[t].tipo&&$("#"+a[t].id_autocomplete).length&&p(a[t].tipo_autocomplete,"#"+a[t].id_autocomplete),"combobox"==a[t].tipo){if($("#"+a[t].id_combobox).length)var r=$("#"+a[t].id_combobox+"_id").val();null==r&&void 0==r&&""==r&&(r=""),h(a[t].tipo_combobox,"#"+a[t].id_combobox,a[t].txt_plural_combobox,a[t].txt_sing_combobox,r)}if("changbox"==a[t].tipo){var r=$("#"+a[t].id_combobox+"_id").val(),i=$("#"+a[t].id_changbox+"_id").val();$(".limpo_id_editar").click(function(){$("#"+a[t].id_combobox+"_id").val(""),$("#"+a[t].id_changbox+"_id").val("")}),null==r&&void 0==r&&""==r&&(r=""),null==i&&void 0==i&&""==i&&(i=""),f(a[t].tipo_changbox,"#"+a[t].id_combobox,"#"+a[t].id_changbox,a[t].txt_plural_changbox,a[t].txt_sing_changbox,a[t].txt_sing_combobox,r,i)}}""!=$('input[name="id"]').val()&&$("#area_formulario").show(),e.limpo_mensagens_erros(),e.loading_geral("hide","","")}i(a,$(".params").attr("monto_campos_form"),"json","POST","")}function m(a,e){v(a,e)}function p(a,t){e.autocomplete($(".params").attr("autocomplete")+"&tipo="+a,t)}function h(a,t,r,i,o){e.dropdown_bd($(".params").attr("combobox")+"&tipo="+a,t,r,i,o)}function f(a,t,r,i,o,n,l,s){e.chang_box_dropdown_bd($(".params").attr("changcombobox")+"&tipo="+a,t,r,i,o,n,l,s)}function g(a){function t(a){d(),$("#erros_inline_n").html("");for(var t in a)e.seto_dados_form(a[t].tipo_set,a[t].campo_set_form,a[t].valor_campo_set),e.loading_geral("hide","","");"false"==e.areas_resp_return()&&$("#"+$(".params").attr("foco_campo_add_edd")).focus()}var r=$('input[name="id"]').val();if(""!=r&&$("#btn_salvar_novo_form").hide(),null==r&&void 0==r&&""==r)var r=a;else var r=r;i(t,$(".params").attr("set_editar")+"&id="+r,"json","POST","","false")}function v(a,t){require(["tinymce"],function(){"completo"==a&&tinymce.init({selector:t,language:"pt_BR",height:100,theme:"modern",plugins:["advlist autolink lists link image charmap print preview hr anchor pagebreak","searchreplace wordcount visualblocks visualchars code fullscreen","insertdatetime media nonbreaking save table contextmenu directionality","emoticons template paste textcolor colorpicker textpattern imagetools spellchecker autoresize placeholder"],toolbar1:"bold italic underline removeformat | forecolor backcolor | styleselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | image media link hr | fullscreen preview code",toolbar2:"",image_advtab:!0,force_br_newlines:!0,force_p_newlines:!1,forced_root_block:"",contextmenu:"cut copy paste | image mdeia link hr inserttable",statusbar:!1,file_browser_callback:function(a,e,t,r){return tinymce.activeEditor.windowManager.open({file:path_libs_inc_plugins+"/tinymce/kcfinder/browse.php?opener=tinymce4&lang=pt-br&field="+a+"&type="+t,title:"Gerenciador de Arquivos",width:800,height:500,inline:!0,close_previous:!1},{window:r,input:a}),!1},setup:function(a){a.on("change",function(){tinymce.triggerSave()}),e.getPageSize()[0]<992&&(a.on("focus",function(){$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").show()}),a.on("blur",function(){$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide()}),a.on("init",function(){$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide()}))}}),"basico"==a&&tinymce.init({selector:t,language:"pt_BR",height:100,theme:"modern",menubar:!1,plugins:["advlist autolink lists link image charmap print preview hr anchor pagebreak","searchreplace wordcount visualblocks visualchars code fullscreen","insertdatetime media nonbreaking save table contextmenu directionality","emoticons template paste textcolor colorpicker textpattern imagetools spellchecker autoresize placeholder"],toolbar1:"bold italic underline removeformat | alignleft aligncenter alignright alignjustify | image link   ",toolbar2:"",image_advtab:!0,force_br_newlines:!0,force_p_newlines:!1,forced_root_block:"",contextmenu:"cut copy paste | image link",statusbar:!1,file_browser_callback:function(a,e,t,r){return tinymce.activeEditor.windowManager.open({file:path_libs_inc_plugins+"/tinymce/kcfinder/browse.php?opener=tinymce4&lang=pt-br&field="+a+"&type="+t,title:"Gerenciador de Arquivos",width:800,height:500,inline:!0,close_previous:!1},{window:r,input:a}),!1},setup:function(a){a.on("change",function(){tinymce.triggerSave()}),e.getPageSize()[0]<992&&(a.on("focus",function(){$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").show()}),a.on("blur",function(){$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide()}),a.on("init",function(){$(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide()}))}}),"inline"==a&&tinymce.init({selector:t,language:"pt_BR",theme:"modern",inline:!0,plugins:["advlist autolink lists link image charmap print preview hr anchor pagebreak","searchreplace wordcount visualblocks visualchars code fullscreen","insertdatetime media nonbreaking save table contextmenu directionality","emoticons template paste textcolor colorpicker textpattern imagetools spellchecker placeholder"],toolbar1:"bold italic underline removeformat | forecolor backcolor | styleselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | image media link hr | fullscreen preview code",toolbar2:"",image_advtab:!0,force_br_newlines:!0,force_p_newlines:!1,forced_root_block:"",contextmenu:"cut copy paste | image mdeia link hr inserttable",file_browser_callback:function(a,e,t,r){return tinymce.activeEditor.windowManager.open({file:path_libs_inc_plugins+"/tinymce/kcfinder/browse.php?opener=tinymce4&lang=pt-br&field="+a+"&type="+t,title:"Gerenciador de Arquivos",width:800,height:500,inline:!0,close_previous:!1},{window:r,input:a}),!1},setup:function(a){a.on("change",function(){tinymce.triggerSave()})}})})}function b(){e.areas_resp()}function k(){w("#form_add_edd",".btn_salvar_form",'<i class="fa fa-refresh fa-pulse"></i> PROCESSANDO ... ',"false","false","15000","false","false",".btn_salvar_novo_form",".btn_salvar_fechar_form")}function w(a,t,r,i,n,l,s,c,u,_){require(["funcoes_notificacao"],function(m){function p(r,m){require(["tinymce"],function(){tinymce.triggerSave()});var p=$(a),v=!1;window.FormData&&(v=new FormData(p[0])),$.ajax({url:f,dataType:"json",type:"POST",data:v?v:$(a).serialize(),mimeType:"multipart/form-data",contentType:!1,cache:!1,processData:!1,success:function(p){$(".msg_erro_form").html(""),$(".remove-error-input").removeClass("has-error "),$(".oculto-icone-error").hide();var f=0;for(var v in p)if(p.hasOwnProperty(v)){f+=1,b=b;var b=[p[v]],k=b,w=k.join("<br />"),q=w.split(","),q=q.join("<br />");if(""!==q&&(1==f&&"false"==e.areas_resp_return()&&$("#"+v).focus(),$("#msg_erro_"+v).html(q),$("#icon_error_"+v).show(),$("#input_error_"+v).addClass("has-error ")),"sucesso"==v&&(h.mostrar_sucesso(b),"true"==i&&setTimeout(function(){$(class_resultado_sucesso).html("")},l),"true"==s&&e.limpa_form(a,"true"),"true"==r&&($("#area_listagem").show(),$("#area_btns_acoes").show(),$("#area_formulario").hide(),$("#area_btns_status_acoes_forms").hide(),e.limpa_form(a,"true")),o(""),x(),$(t).removeAttr("disabled").html(m),$(u).removeAttr("disabled"),$(_).removeAttr("disabled"),e.loading_geral2("hide","","")),"tempo"==v)var y=b;"redireciono"==v&&window.setTimeout("location.href='"+b+"'",y),"limpo_campo"==v&&(e.limpa_form(a,"true"),g()),"id"==v&&("true"==r?($('input[name="id"]').val(""),e.loading_geral("hide","","")):($('input[name="id"]').val(b),e.loading_geral("hide","",""),d(),g(),"false"==e.areas_resp_return()&&$("#"+$(".params").attr("foco_campo_add_edd")).focus(),x()),$("#btn_salvar_novo_form").hide()),"erro"==v&&(h.mostrar_error(b),"true"==n&&setTimeout(function(){$(class_resultado_erro).html("")},l),$(t).removeAttr("disabled").html(m),$(u).removeAttr("disabled"),$(_).removeAttr("disabled"),e.loading_geral2("hide","",""))}for(var v in p)for(var v in p){if("sucesso"==v)var q="";if("erro"==v)var q=""}if(""!==q){var C=' <div class="callout callout-danger"> ';C+='<h4><i class="fa fa-remove"></i> Erros no cadastro verifique.</h4><p>';for(var v in p)p.hasOwnProperty(v)&&(C+=p[v]+"<br />");C+="</div></p>"}else var C="";$("#erros_inline_n").html(C),c===!0&&e.subir_topo(),$(t).removeAttr("disabled").html(m),$(u).removeAttr("disabled"),$(_).removeAttr("disabled"),e.loading_geral2("hide","","")},error:function(a,r,i){""==i&&(i="<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. "),$(t).removeAttr("disabled").html(m),$(u).removeAttr("disabled"),$(_).removeAttr("disabled"),e.loading_geral2("hide","",""),h.mostrar_error_geral(i)}})}var h=new m,f=$(a).attr("url");saving_record=0,$(a).submit(function(a){a.preventDefault(),$("html,body").animate({scrollTop:0},0),$('input[name="op_salvar"]').val("sucesso_limpo");var e=$(t).html();p(null,e)}),$(t).click(function(i){i.preventDefault(),$("html,body").animate({scrollTop:0},0),$('input[name="op_salvar"]').val("sucesso");var o=$(t).html();$(t).attr("disabled","disabled"),$(u).attr("disabled","disabled"),$(_).attr("disabled","disabled"),$(t).html(r),$(a+"_js_control").val("true"),e.loading_geral2("show","",""),p(null,o)}),$(u).click(function(i){i.preventDefault(),$("html,body").animate({scrollTop:0},0),$('input[name="op_salvar"]').val("sucesso_limpo");var o=$(t).html();$(t).attr("disabled","disabled"),$(u).attr("disabled","disabled"),$(_).attr("disabled","disabled"),$(t).html(r),$(a+"_js_control").val("true"),e.loading_geral2("show","",""),p(null,o)}),$(_).click(function(i){i.preventDefault();var o="true";$('input[name="op_salvar"]').val("sucesso");var n=$(t).html();$(t).attr("disabled","disabled"),$(u).attr("disabled","disabled"),$(_).attr("disabled","disabled"),$(t).html(r),$(a+"_js_control").val("true"),e.loading_geral2("show","",""),p(o,n)})})}function x(){require(["funcoes_notificacao"],function(a){function e(a,e,t,r,i,o,n,l){$("#"+n).val(""),$(t).html(""),$.ajax({url:$(".params").attr("action_list_arquivos")+"&tipo="+a+"&id="+$('input[name="id"]').val(),dataType:"json",type:"GET",data:"",success:function(r){var o="";if(require(["lightbox"]),1==l){var n="caixa-arquivo",s="caixa-galeria ";o+='<div class="col-md-12 col-sm-12 col-xs-12"><div class="box box-default">'}else{var n="caixa-arquivo",s="caixa-galeria-one";o+='<div class="col-md-12 col-sm-12 col-xs-12"><div class="box box-default">'}o+='<div class="box-body">';for(var c=0;c<r.length;c++)"arquivo"==e&&(o+='<div class="'+n+'"><a data-title="tooltip" data-placement="top" title="Abrir arquivo (Nova Janela)" href="'+r[c].path_arquivo+r[c].url_arquivo+'" target="_blank"><i class="'+i+'"></i> '+r[c].descricao+'</a> <a href="javascript:void(0);" title="Excluir Arquivo" data-title="tooltip" data-placement="top" class="excluir_arquivo" tipo="'+a+'" url_arquivo="'+r[c].url_arquivo+'" param_id="'+r[c].id_arquivo+'"><i class="fa fa-times danger-dark"></i></a> </div>'),"imagem"==e&&(o+='<div class="'+s+'"><a href="javascript:void(0);" data-title="tooltip" data-placement="top" title="Excluir Imagem" class="excluir_arquivo" tipo="'+a+'" url_arquivo="'+r[c].url_arquivo+'" param_id="'+r[c].id_arquivo+'"><i class="fa fa-times danger-dark"></i></a>  <a data-title="tooltip" data-lightbox="roadtrip" data-placement="top" title="Abrir imagem" href="'+r[c].path_arquivo+r[c].url_arquivo+'" target="_blank"> <img class="center-cropped" width="92" height="80" src="'+r[c].path_arquivo+r[c].url_arquivo+'" ></a> </div>');o+="</div></div></div>",$(t).html(o),y(),q()}})}new a;if($("#carrego_listagem1").html(""),$("#carrego_listagem2").html(""),$("#carrego_listagem3").html(""),$("#carrego_listagem4").html(""),""!=$('input[name="id"]').val()){if($("#carrego_listagem1").length){var t="#carrego_listagem1",r=$(t).attr("url"),i=$(t).attr("tipo"),o=$(t).attr("titulo"),n=$(t).attr("icone"),l=$(t).attr("icone2"),s=$(t).attr("campo"),c=$(t).attr("upload_multiplo");e(r,i,t,o,n,l,s,c)}if($("#carrego_listagem2").length){var t="#carrego_listagem2",r=$(t).attr("url"),i=$(t).attr("tipo"),o=$(t).attr("titulo"),n=$(t).attr("icone"),l=$(t).attr("icone2"),s=$(t).attr("campo"),c=$(t).attr("upload_multiplo");e(r,i,t,o,n,l,s,c)}if($("#carrego_listagem3").length){var t="#carrego_listagem3",r=$(t).attr("url"),i=$(t).attr("tipo"),o=$(t).attr("titulo"),n=$(t).attr("icone"),l=$(t).attr("icone2"),s=$(t).attr("campo"),c=$(t).attr("upload_multiplo");e(r,i,t,o,n,l,s,c)}if($("#carrego_listagem4").length){var t="#carrego_listagem4",r=$(t).attr("url"),i=$(t).attr("tipo"),o=$(t).attr("titulo"),n=$(t).attr("icone"),l=$(t).attr("icone2"),s=($(t).attr("icone2"),$(t).attr("campo")),c=$(t).attr("upload_multiplo");e(r,i,t,o,n,l,s,c)}}})}function q(){$(".foco_campo_input").click(function(){"false"==e.areas_resp_return()&&$("#"+$(this).attr("campo")).focus()})}function y(){$(".excluir_arquivo").click(function(){var a=$(".params").attr("action_excluir_arquivo"),t=$(this).attr("param_id"),o=$(this).attr("url_arquivo"),n=$(this).attr("tipo");swal({title:r.mensagens()[5],text:r.mensagens()[6],type:"error",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:r.mensagens()[5],showConfirmButton:!0,closeOnConfirm:!0},function(){function l(a){for(var t in a)a.hasOwnProperty(t)&&("sucesso"==t&&(swal(r.mensagens()[13],a[t],"success","true"),x()),"erro"==t&&swal(r.mensagens()[14],a[t],"error","false","1"),e.loading_geral("hide","",""))}i(l,a+"&tipo="+n+"&id="+t+"&url_arquivo="+o,"json","GET","","","")})})}function C(){$(".funcao_aberta").click(function(){function a(){function a(a){for(var t in a)a.hasOwnProperty(t)&&("sucesso"==t&&(0==u?e.mostrar_sucesso(a[t]):swal(r.mensagens()[11],a[t],"success","true"),o("",null,null,null,null,$("#parametros_listagem").attr("paginaativa"),null,null,null)),"erro"==t&&(0==u?e.mostrar_error(a[t]):(swal(r.mensagens()[14],a[t],"error","false","1"),e.mostrar_error(a[t])),l()),e.loading_geral("hide","",""))}i(a,t+n,"json","GET","","","")}var t=$(".params").attr("action_funcao_aberta"),n=$(this).attr("param"),s=$(this).attr("title_msg"),c=$(this).attr("title_text");if(void 0==s&&void 0==s){a();var u=!1}else{var u=!0;swal({title:s,text:c,type:"success",showCancelButton:!0,confirmButtonColor:"#24a23c",confirmButtonText:r.mensagens()[10],showConfirmButton:!0,closeOnConfirm:!0},function(){a()})}})}function j(){require(["select2"],function(a){$(".select2").select2({tags:!0,tokenSeparators:[","," "]})})}function A(){require(["chosen"],function(a){$(".select_multiple").chosen({no_results_text:"Nenhum resultado encontrado.",create_option:!0})})}function T(){require(["chosen"],function(a){""!=$(".select_personalizado").html()&&$(".select_personalizado").chosen({no_results_text:"Nenhum resultado encontrado!"})})}function B(){require(["bootstrap"],function(a){$('[data-title="tooltip"]').tooltip()}),require(["bootstrap"],function(a){$('[data-toggle="popover"]').popover({trigger:"hover",html:!0})})}var e=new e(path_raiz,path),t=new t(path_raiz,path),r=new r(path_raiz,path);o(a),id_detalho=$("#detalho_conteudo").attr("param_id"),""!=id_detalho&&u(id_detalho),d(),$(".monta_dados_form").click(function(){e.limpa_form("form_add_edd"),e.limpo_campos_hidden(),d(),g(),"false"==e.areas_resp_return()&&$("#"+$(".params").attr("foco_campo_add_edd")).focus(),x()}),$(".limpo_form_add_edd").click(function(){e.limpa_form("form_add_edd"),$('input[name="id"]').val(""),e.loading_geral("hide","","")}),g(),$(".editor_html_completo").length&&v("completo",".editor_html_completo"),$(".editor_html_basico").length&&v("basico",".editor_html_basico"),$(".editor_html_inline").length&&v("inline",".editor_html_inline"),b(),k(),$(".add_modal").click(function(){var a=$(this).attr("data-target");$(a+"_area").attr("modal","true"),$(a+"_area").length&&"true"==$(a+"_area").attr("modal")&&$(a+"_area").html('<iframe style="border:none;overflow:auto;width:100%;"  height="400" src="'+$(a+"_area").attr("url")+'"></iframe>')}),$(".close").click(function(){d(),g(),"false"==e.areas_resp_return()&&$("#"+$(".params").attr("foco_campo_add_edd")).focus()}),x(),C(),$(".form-control").focus(function(){var a=$(this).attr("id"),e=$("#msg_erro_"+a).text();$(".msg_erro_form").hide(),""!=e&&($("#msg_erro_"+a).show(),$("#msg_erro_"+a).addClass("area-error-inputt"))}),$(".form-control").hover(function(){var a=$(this).attr("id"),e=$("#msg_erro_"+a).text();$(".msg_erro_form").hide(),""!=e&&($("#msg_erro_"+a).show(),$("#msg_erro_"+a).addClass("area-error-inputt"))},function(){var a=$(this).attr("id");$("#msg_erro_"+a).hide(),$("#msg_erro_"+a).removeClass("area-error-inputt")}),j(),A(),$(".select_personalizado").hasClass("estatico")&&T(),$(".select_chang_show_hide").change(function(){var a=$(this).get(0),e=a.options[a.selectedIndex].value;$(".select_chang_show_hide_areas").hide(),$(".area_sh_"+e).show()}),$(".select_chang_show_hide1").change(function(){var a=$(this).get(0),e=a.options[a.selectedIndex].value;$(".select_chang_show_hide_areas1").hide(),$(".area_sh_"+e).show()});var E=$(".params").attr("param_modal_add");"param_modal_add"==E&&($(".param_modal_add").remove(),$(".content-wrapper").css("padding-top","15px")),$(".link_restricao").click(function(){var a=$(this).attr("link"),t=$(this).attr("param_value");e.redireciono(a+t)}),$(".ajax_jquery").click(function(a){a.preventDefault();var t=$(this).attr("id"),r=$(this).attr("link"),i=$(this).attr("link_id");e.ajax_jquery_rapido(t,r,i)}),B()})}}});
