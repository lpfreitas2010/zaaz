define(["jquery"],function(r){function e(e,t){r(".form-control").focus(function(){var e=r(this).attr("id"),t=r("#msg_erro_"+e).text();r(".msg_erro_form").hide(),""!=t&&(r("#msg_erro_"+e).show(),r("#msg_erro_"+e).addClass("area-error-inputt"))}),r(".form-control").hover(function(){var e=r(this).attr("id"),t=r("#msg_erro_"+e).text();r(".msg_erro_form").hide(),""!=t&&(r("#msg_erro_"+e).show(),r("#msg_erro_"+e).addClass("area-error-inputt"))},function(){var e=r(this).attr("id");r("#msg_erro_"+e).hide(),r("#msg_erro_"+e).removeClass("area-error-inputt")}),this.submit_form_json=function(e,t,o,a,n,i,s,m,u,c,l,d,f){require(["funcoes_notificacao"],function(h){function _(){var f=r(t).html();r(t).attr("disabled","disabled"),r(t).html(o),r(a).fadeIn(500),r(a).html(n),r(e+"_js_control").val("true"),r.ajax({url:v,dataType:"json",type:"POST",data:r(e).serialize(),success:function(o){r(s).html(""),r(i).html(""),r(".msg_erro_form").html(""),r(".remove-error-input").removeClass("has-error animated pulse"),r(".oculto-icone-error").hide();var n=0;for(var h in o)if(o.hasOwnProperty(h)){n+=1,_=_;var _=[o[h]],v=_,g=v.join("<br />"),b=g.split(","),b=b.join("<br />");if(""!==b&&(1==n&&r("#"+h).focus(),r("#msg_erro_"+h).html(b),r("#icon_error_"+h).show(),r("#input_error_"+h).addClass("has-error animated pulse")),"sucesso"==h&&(p.mostrar_sucesso(_),"true"==m&&setTimeout(function(){r(i).html("")},c),"true"==l&&r(e).each(function(){this.reset()})),"tempo"==h)var y=_;"redireciono"==h&&window.setTimeout("location.href='"+_+"'",y),"erro"==h&&(p.mostrar_error(_),"true"==u&&setTimeout(function(){r(s).html("")},c))}d===!0&&r("html, body").animate({scrollTop:r(body).offset().top},1e3),r(a).fadeOut(500),r(t).removeAttr("disabled").html(f)},error:function(r,e,t){""==t&&(t="<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. "),p.mostrar_error_geral(r,e,t)}})}var p=new h,v=r(e).attr("url");saving_record=0,r(e).on("submit",function(r){r.preventDefault(),_()}),""!==f&&r(f).blur(function(r){r.preventDefault(),_()})})},this.submit_form_json_upload=function(e,t,o,a,n,i,s,m,u,c,l,d){require(["funcoes_notificacao"],function(f){var h=new f,_=r(e).attr("action");r(e).attr("action","javascript:void(0);"),r(e).submit(function(){var f=r(t).html();r(t).attr("disabled","disabled"),r(t).html(o),r(a).fadeIn(500),r(a).html(n),r(e+"_js_control").val("true");var p=new FormData(this);r.ajax({url:_,dataType:"json",type:"POST",data:p,mimeType:"multipart/form-data",contentType:!1,cache:!1,processData:!1,success:function(o,n,_){function p(t){var o="",a=0;for(var n in t)if(t.hasOwnProperty(n)){a+=1,s=s;var s=[t[n]],d=s,f=d.join("<br />"),_=f.split(","),_=_.join("<br />");if(""!==_&&(1==a&&r("#"+n).focus(),r("#msg_erro_"+n).html(_),r("#icon_error_"+n).show(),r("#input_error_"+n).addClass("input-error")),"sucesso"==n&&(h.mostrar_sucesso(s),"true"==m&&setTimeout(function(){r(i).html("")},c),"true"==l&&r(e).each(function(){this.reset()})),"tempo"==n)var p=s;"redireciono"==n&&window.setTimeout("location.href='"+s+"'",p),"erro"==n&&(h.mostrar_error(s),"true"==u&&setTimeout(function(){h.mostrar_error("")},c))}return o}r(s).html(""),r(i).html(""),r(".msg_erro_form").html(""),r(".remove-error-input").removeClass("input-error"),r(".oculto-icone-error").hide(),p(o),d===!0&&r("html, body").animate({scrollTop:r(body).offset().top},1e3),r(a).fadeOut(500),r(t).removeAttr("disabled").html(f)},error:function(r,e,t){""==t&&(t="<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. "),h.mostrar_error_geral(r,e,t)}})})})},this.submit_form_json_upload_img_crop=function(e,t,o,a,n,i,s,m,u,c,l,d,f,h,_,p){require(["funcoes_notificacao"],function(v){var g=new v,b=r(e).attr("action");r(e).attr("action","javascript:void(0);"),r(e).submit(function(){var v=r(t).html();r(t).attr("disabled","disabled"),r(t).html(o),r(a).fadeIn(500),r(a).html(n),r(e+"_js_control").val("true");var y=new FormData(this);r.ajax({url:b,dataType:"json",type:"POST",data:y,mimeType:"multipart/form-data",contentType:!1,cache:!1,processData:!1,success:function(o,n,b){function y(t){var o="";for(var a in t)if(t.hasOwnProperty(a)){n=n;var n=t[a];if("nome_arquivo"==a&&""!=n){r(e).hide(),r("#btn-fechar").hide(),r(f).hide(),r(h).html('<img id="imagem_nova" class="img_cortar" src="'+_+n+'">'),r('input[name="nome_img"]').val(n);var s=Math.round(300);Math.round(300);console.log(s),r(p).show(),require(["imgareaselect"],function(e){r("#imagem_nova").imgAreaSelect({onSelectEnd:function(e,t){r('input[name="x1"]').val(t.x1),r('input[name="y1"]').val(t.y1),r('input[name="x2"]').val(t.width),r('input[name="y2"]').val(t.height)},x1:0,y1:0,x2:s,y2:s,minWidth:300,minHeight:300,maxWidth:650,maxHeight:650,aspectRatio:"1:1",handles:!0})})}if("sucesso"==a&&("ok"==m&&setTimeout(function(){r(i).html("")},c),"true"==l&&r(e).each(function(){this.reset()})),"tempo"==a)var d=n;"redireciono"==a&&window.setTimeout("location.href='"+n+"'",d),"erro"==a&&(g.mostrar_error(n),"true"==u&&setTimeout(function(){g.mostrar_error("")},c))}return o}r(s).html(""),r(i).html(""),r(".msg_erro_form").html(""),r(".remove-error-input").removeClass("input-error"),y(o),d===!0&&r("html, body").animate({scrollTop:r(body).offset().top},1e3),r(a).fadeOut(500),r(t).removeAttr("disabled").html(v)},error:function(r,e,t){""==t&&(t="<b>Erro de carregamento de script!</b><br /> Recarregue a página e tente novamente. "),g.mostrar_error_geral(r,e,t)}})})})}}return e});