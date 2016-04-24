
    //PEGO A LINGUAGEM DO SISTEMA
    if(language === 'default'){
      var mod_language = '';
    }else{
      var mod_language = '_'+language;
    }

    //MONTO O MODO DO SISTEMA
    if(modo_sistema == undefined){
        var pasta_min = "";
        var ext_min   = "";
    }else{
        if(modo_sistema == "Modo de Produção"){
            var pasta_min = "min";
            var ext_min   = ".min";
        }else{
            var pasta_min = "";
            var ext_min   = "";
        }
    }

    //MONTO O PATH
    path_js = path+'/assets/js/';
    path_libs_inc_plugins = path_js+'libs/inc/plugins';

    //CONFIGURAÇÕES
    require.config({
      name: 'app',
      baseUrl: path_js,

      paths: {

        //FUNÇÕES DAS PÁGINAS
        'config_modulo':            'libs/config/config_modulos', //CONFIGURAÇÕES DA APLICAÇÃO
        'config_mensagens':         'libs/config/config_mensagens'+mod_language, //MENSAGENS DE INTERFACE
        'funcoes_notificacao':      'libs/geral/'+pasta_min+'/funcoes_notificacao', // notificação de mensagem
        'funcoes_form_validacao':   'libs/geral/'+pasta_min+'/funcoes_form_validacao', // form validação
        'funcoes_gerais':           'libs/geral/'+pasta_min+'/funcoes_gerais', // funcoes gerais
        'funcoes_geral':            'libs/geral/'+pasta_min+'/funcoes_geral', // funcoes geral
        'feedback':                 'libs/geral/'+pasta_min+'/feedback', // feedback

        //FUNÇÕES GERAIS
        'jquery':                   'libs/inc/vendors/bower_components/jquery/dist/jquery.min',
        'jquery_ui':                'libs/inc/plugins/jQueryUI/jquery-ui.min',
        'bootstrap':                'libs/inc/bootstrap/js/bootstrap.min',
        'moment':                   'libs/inc/plugins/moment/min/moment.min',
        'datepicker':               'libs/inc/plugins/datepicker/bootstrap-datepicker.min',
        'datetimepicker':           'libs/inc/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min',
        'slimScroll':               'libs/inc/plugins/slimScroll/jquery.slimscroll.min',
        'app':                      'libs/inc/js/app.min',
        'bootstrap-growl':          'libs/inc/vendors/bootstrap-growl/bootstrap-growl.min',
        'mask':                     'libs/inc/vendors/input-mask/input-mask.min',
        'select2':                  'libs/inc/plugins/select2/select2.full.min',
        'fakeLoader':               'libs/inc/plugins/fakeLoader/fakeLoader.min',
        'sweetalert':               'libs/inc/plugins/bootstrap-sweetalert/lib/sweet-alert.min',
        'bsmSelect-master':         'libs/inc/plugins/bsmSelect-master/js/jquery.bsmselect.min',
        'jquery-hotkeys':           'libs/inc/plugins/jquery.hotkeys-master/jquery.hotkeys.min',
        'autocomplete':             'libs/inc/plugins/jQuery-Autocomplete-master/dist/jquery.autocomplete.min',
        'tinymce':                  'libs/inc/plugins/tinymce/tinymce.min',
        'lightbox':                 'libs/inc/plugins/lightbox/dist/js/lightbox.min',
        'jQuery.printElement':      'libs/inc/plugins/jQuery.printElement/jquery.printElement.min',
        'chosen':                   'libs/inc/plugins/chosen/chosen.jquery.min',
        'chartjs':                  'libs/inc/plugins/Chart.js-master/Chart.min',
        'chart_google':             'libs/inc/plugins/chartgoogle/loader.min',
        'stacktable':               'libs/inc/plugins/stacktable.js-master/stacktable.min',
        'tablesaw-master':          'libs/inc/plugins/tablesaw-master/dist/tablesaw',
        'Lobibox':                  'libs/inc/plugins/Lobibox-master/dist/js/Lobibox.min',



        //OUTRAS FUNÇÕES NÃO USADAS
        //'fastclick':                'libs/inc/plugins/fastclick/fastclick.min', //funcoes do templete locastyle
        //'ckeditor':                 'libs/inc/plugins/ckeditor/ckeditor',
        //'ckeditor-jquery':          'libs/inc/plugins/ckeditor/adapters/jquery',
        //'icheck':                   'libs/inc/plugins/iCheck/icheck.min', //funcoes do templete locastyle
        //'morris':                   'libs/inc/plugins/morris/morris.min', //funcoes do templete locastyle
        //'sparkline':                'libs/inc/plugins/sparkline/jquery.sparkline.min', //funcoes do templete locastyle
        //'jvectormap':               'libs/inc/plugins/jvectormap/jquery-jvectormap-1.2.2.min', //funcoes do templete locastyle
        //'jvectormap-world':         'libs/inc/plugins/jvectormap/jquery-jvectormap-world-mill-en', //funcoes do templete locastyle
        //'knob':                     'libs/inc/plugins/knob/jquery.knob', //funcoes do templete locastyle
        //'bootstrap-dropdown-hover': 'libs/inc/plugins/bootstrap-dropdown-hover/js/bootstrap-dropdownhover',
        //'dashboard':                'libs/inc/js/pages/dashboard', //funcoes do templete locastyle
        //'raphael':                  'libs/inc/plugins/raphael/raphael', //funcoes do templete locastyle
        //'moment-locales':           'libs/inc/plugins/moment/min/moment-with-locales',
        //'daterangepicker':          'libs/inc/plugins/daterangepicker/daterangepicker', //funcoes do templete locastyle

      },
      shim: {
          ckeditor: { exports: 'CKEDITOR', deps: [ 'jquery' ] },
          tinymce: {
              exports: 'tinyMCE',
              init: function () {
                  this.tinyMCE.DOM.events.domLoaded = true;
                  return this.tinyMCE;
              }
          }
      },
      urlArgs: {
          'bust': Date.now()
      }
  });

  //CHAMO PÁGINA
  if(pagina != 'main'){
     require(['modulos/'+modulos+'/'+pagina+'']);
  }
