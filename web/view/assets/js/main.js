
    //PEGO A LINGUAGEM DO SISTEMA
    if(language === 'default'){
      var mod_language = '';
    }else{
      var mod_language = '_'+language;
    }

    //MONTO O PATH
    path_js = path+'/assets/js/';

    //CONFIGURAÇÕES
    require.config({
      name   : 'app',
      baseUrl: path_js,
      paths: {

        'config_modulo':          'libs/config/config_modulos', //CONFIGURAÇÕES DA APLICAÇÃO
        'config_mensagens':       'libs/config/config_mensagens'+mod_language, //MENSAGENS DE INTERFACE
        'funcoes_notificacao':    'libs/geral/funcoes_notificacao', //FUNÇÕES DE NOTIFICAÇÃO
        'funcoes_form_validacao': 'libs/geral/funcoes_form_validacao', //FUNÇÕES DE VALIDAÇÃO DE FORMULÁRIO
        'funcoes_gerais':         'libs/geral/funcoes_gerais', //FUNÇÕES GERAIS
        'gerais':                 'libs/geral/functions', //FUNÇÕES GERAIS DO FRAMEWORK DA GOOGLE
        'jquery':                 'libs/inc/vendors/bower_components/jquery/dist/jquery', //JQUERY
        'bootstrap':              'libs/inc/vendors/bower_components/bootstrap/dist/js/bootstrap.min',
        'mask':                   'libs/inc/vendors/input-mask/input-mask.min',
        'bootstrap-growl':        'libs/inc/vendors/bootstrap-growl/bootstrap-growl.min',
        'lightgallery':           'libs/inc/vendors/bower_components/lightgallery/light-gallery/js/lightGallery.min',
        'autosize':               'libs/inc/vendors/bower_components/autosize/dist/autosize.min',
        'moment':                 'libs/inc/vendors/bower_components/moment/min/moment.min',
        'moment-locales':         'libs/inc/vendors/bower_components/moment/min/moment-with-locales',
        'datetimepicker':         'libs/inc/vendors/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min',
        'bootstrap-select':       'libs/inc/vendors/bower_components/bootstrap-select/dist/js/bootstrap-select',
        'nouislider':             'libs/inc/vendors/bower_components/nouislider/distribute/jquery.nouislider.all.min',
        'summernote':             'libs/inc/vendors/bower_components/summernote/dist/summernote.min',
        'chosen':                 'libs/inc/vendors/chosen_v1.4.2/chosen.jquery.min',
        'fileinput':              'libs/inc/vendors/fileinput/fileinput',
        'waves':                  'libs/inc/vendors/Waves/dist/waves',
        'sweetalert':             'libs/inc/vendors/bower_components/bootstrap-sweetalert/lib/sweet-alert',
        'farbtastic':             'libs/inc/vendors/farbtastic/farbtastic.min',
        'imgareaselect':          'libs/inc/imgareaselect/scripts/jquery.imgareaselect.pack'

      },
      urlArgs: { 'bust': Date.now() }
    });

    //CHAMO A PÁGINA
    if(pagina != 'main'){
      require(['modulos/'+modulos+'/'+pagina+'']);
    }

    //FUNÇÃO QUE CARREGA IMAGEM PADRÃO NO CASO DE ERRO
    /*require(['jquery'], function ($) {
       $('img').error(function(){
         var imagem = $(this).attr("src");
         $(this).attr("src",path+"/assets/error/img/thumbs-up.png");
       });
    });*/
