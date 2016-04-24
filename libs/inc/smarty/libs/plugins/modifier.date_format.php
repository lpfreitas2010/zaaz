<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty date_format modifier plugin
 * Type:     modifier<br>
 * Name:     date_format<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *          - string: input date string
 *          - format: strftime format for output
 *          - default_date: default date if $string is empty
 *
 * @link   http://www.smarty.net/manual/en/language.modifier.date.format.php date_format (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 *
 * @param string $string       input date string
 * @param string $format       strftime format for output
 * @param string $default_date default date if $string is empty
 * @param string $formatter    either 'strftime' or 'auto'
 *
 * @return string |void
 * @uses   smarty_make_timestamp()
 */
function smarty_modifier_date_format($string, $format = null, $default_date = '', $formatter = 'auto')
{
    if ($format === null) {
        $format = Smarty::$_DATE_FORMAT;
    }
    if($format == 'dia_semana'){
        $diasemana = array('Domingo','Segunda-feira','Terça-feira','Quarta-feira', 'Quinta-feira','Sexta-feira','Sábado');
        $data = date('w', strtotime($string));
        return $diasemana[$data];
        exit;
    }
    if($format == 'dia_semana_hoje'){
        $diasemana  = array('Domingo','Segunda-feira','Terça-feira','Quarta-feira', 'Quinta-feira','Sexta-feira','Sábado');
        $data       = date('w', strtotime($string));
        $data_bd    = date('Y-m-d', strtotime($string));
        $data_atual = date('Y-m-d');
        if($data_bd == $data_atual){
            return 'Hoje';
        }else{
            return $diasemana[$data];
        }
        exit;
    }
    if($format == 'mes'){
        $meses = array(1 => 'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
    	$intDayOfMonth = date('n',strtotime($string));
    	return $meses[$intDayOfMonth];
        exit;
    }
    if($format == 'data_extenso'){
        $diasemana = array('Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado');
    	$meses = array(1 => 'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
        $intDayOfWeek = date('w',strtotime($string));
    	$intDayOfMonth = date('d',strtotime($string));
    	$intMonthOfYear = date('n',strtotime($string));
    	$intYear = date('Y',strtotime($string));
    	return $diasemana[$intDayOfWeek] . ', ' . $intDayOfMonth . ' de ' . $meses[$intMonthOfYear] . ' de ' . $intYear;
        exit;
    }
    if($format == 'idade'){
        // Separa em dia, mês e ano
        $data_banco = date('d-m-Y', strtotime($string));
        list($dia, $mes, $ano) = explode('-', $data_banco);

         // Descobre que dia é hoje e retorna a unix timestamp
         $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
         // Descobre a unix timestamp da data de nascimento do fulano
         $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

         // Depois apenas fazemos o cálculo já citado :)
         $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
         return $idade;
    }
    if($format == 'tempo'){
        $data_atual = mktime(); // data atual em segundos
        //separamos as partes da data
        list($ano,$mes,$dia)  = explode("-",$string);
        list($dia,$hora)      = explode(" ",$dia);
        list($hora,$min,$seg) = explode(":",$hora);

        //transformamos a data do banco em segundos usando a função mktime()
        $data_banco = mktime($hora,$min,$seg,$mes,$dia,$ano);
        $diferenca  = $data_atual - $data_banco; // subtraímos a data atual menos a data do banco em segundos
        $minutos    = $diferenca/60; // dividimos os segundos por 60 para transformá-los em minutos
        $horas      = $diferenca/3600; // dividimos os segundos por 3600 para transformá-los em horas
        $dias       = $diferenca/86400; // dividimos os segundos por 86400 para transformá-los em dias

        //abaixo fazemos verificações para definir a mensagem a ser mostrada.
        if($minutos < 1){ // se a tiver passado de 0 a 60 segundos
           $diferenca = "Há alguns segundos";
        } elseif($minutos > 1 && $horas < 1) { // se tiver passado de 1 a 60 minutos
        if(floor($minutos) == 1 or floor($horas) == 1){ $s = ''; } else { $s = 's'; } // plural ou singular de minuto
           $diferenca = "Há ".floor($minutos)." minuto".$s;
        } elseif($horas <= 24) { // se tiver passado de 1 a 24 horas
        if(floor($horas) == 1){ $s = ''; } else { $s = 's'; } // plural ou singular de hora
           $diferenca = "Há ".floor($horas)." hora".$s;
        } elseif($dias <= 2){ // se tiver passado um dia
           $diferenca = "Ontem";
        } elseif($dias <= 7){ // se tiver passado 6 dias
           $diferenca = "Há ".floor($dias)." dias";
        } elseif($dias <= 8){ // se tiver passado uma semana (7 dias)
           $diferenca = "Há uma semana";
        } else {
           //$diferenca = "Há ".floor($dias)." dias";//
           $diferenca = date("d/m/Y", $data_banco);
        }
        return $diferenca;
    }
    /**
     * require_once the {@link shared.make_timestamp.php} plugin
     */
    require_once(SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
    if ($string != '' && $string != '0000-00-00' && $string != '0000-00-00 00:00:00') {
        $timestamp = smarty_make_timestamp($string);
    } elseif ($default_date != '') {
        $timestamp = smarty_make_timestamp($default_date);
    } else {
        return;
    }
    if ($formatter == 'strftime' || ($formatter == 'auto' && strpos($format, '%') !== false)) {
        if (DS == '\\') {
            $_win_from = array('%D', '%h', '%n', '%r', '%R', '%t', '%T');
            $_win_to = array('%m/%d/%y', '%b', "\n", '%I:%M:%S %p', '%H:%M', "\t", '%H:%M:%S');
            if (strpos($format, '%e') !== false) {
                $_win_from[] = '%e';
                $_win_to[] = sprintf('%\' 2d', date('j', $timestamp));
            }
            if (strpos($format, '%l') !== false) {
                $_win_from[] = '%l';
                $_win_to[] = sprintf('%\' 2d', date('h', $timestamp));
            }
            $format = str_replace($_win_from, $_win_to, $format);
        }

        return strftime($format, $timestamp);
    } else {
        return date($format, $timestamp);
    }
}
