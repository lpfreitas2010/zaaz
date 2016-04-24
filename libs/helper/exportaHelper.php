<?php

    /**
    * Classe que realiza a exportação de dados para o Excel e Word
    * 
    * @author Fernando 
    * @version 1.0
    **/

    //=================================================================

    //Classe
    class exporta {

        private $colspan_excel;
        private $titulo_excel;
        private $filtro_excel;
        private $total_registros_excel;
        private $head_excel;
        private $conteudo_excel;
        private $footer_excel;
        private $nome_arquivo_excel;
        private $formato_arquivo_excel;
        private $borda_excel;
        private $head_footer_status_excel;
        private $head_montado_excel;
        private $conteudo_montado_excel;
        private $footer_montado_excel;
        private $order_by;

        public function getColspan_excel()
        {
            return $this->colspan_excel;
        }
        public function setColspan_excel($colspan_excel)
        {
            $this->colspan_excel = $colspan_excel;
            return $this;
        }

        public function getTitulo_excel()
        {
            return $this->titulo_excel;
        }
        public function setTitulo_excel($titulo_excel)
        {
            $this->titulo_excel = $titulo_excel;
            return $this;
        }

        public function getTotal_registros_excel()
        {
            return $this->total_registros_excel;
        }
        public function setTotal_registros_excel($total_registros_excel)
        {
            $this->total_registros_excel = $total_registros_excel;
            return $this;
        }
       
        public function getCor_header_excel()
        {
            return $this->cor_header_excel;
        }
        public function setCor_header_excel($cor_header_excel)
        {
            $this->cor_header_excel = $cor_header_excel;
            return $this;
        }

        public function getCor_footer_excel()
        {
            return $this->cor_footer_excel;
        }
        public function setCor_footer_excel($cor_footer_excel)
        {
            $this->cor_footer_excel = $cor_footer_excel;
            return $this;
        }

        public function getHead_excel()
        {
            return $this->head_excel;
        }
        public function setHead_excel($head_excel)
        {
            $this->head_excel = $head_excel;
            return $this;
        }
      
        public function getConteudo_excel()
        {
            return $this->conteudo_excel;
        }
        public function setConteudo_excel($conteudo_excel)
        {
            $this->conteudo_excel = $conteudo_excel;
            return $this;
        }

        public function getFooter_excel()
        {
            return $this->footer_excel;
        }
        public function setFooter_excel($footer_excel)
        {
            $this->footer_excel = $footer_excel;
            return $this;
        }

        public function getFiltro_excel()
        {
            return $this->filtro_excel;
        }
        public function setFiltro_excel($filtro_excel)
        {
            $this->filtro_excel = $filtro_excel;
            return $this;
        }

        public function getNome_arquivo_excel()
        {
            return $this->nome_arquivo_excel;
        }
        public function setNome_arquivo_excel($nome_arquivo_excel)
        {
            $this->nome_arquivo_excel = $nome_arquivo_excel;
            return $this;
        }

        public function getFormato_arquivo_excel()
        {
            return $this->formato_arquivo_excel;
        }
        public function setFormato_arquivo_excel($formato_arquivo_excel)
        {
            $this->formato_arquivo_excel = $formato_arquivo_excel;
            return $this;
        }

        public function getBorda_excel()
        {
            return $this->borda_excel;
        }
        public function setBorda_excel($borda_excel)
        {
            $this->borda_excel = $borda_excel;
            return $this;
        }

        public function getHead_montado_excel()
        {
            return $this->head_montado_excel;
        }
        public function setHead_montado_excel($head_montado_excel)
        {
            $this->head_montado_excel = $head_montado_excel;
            return $this;
        }

        public function getConteudo_montado_excel()
        {
            return $this->conteudo_montado_excel;
        }
        public function setConteudo_montado_excel($conteudo_montado_excel)
        {
            $this->conteudo_montado_excel = $conteudo_montado_excel;
            return $this;
        }

        public function getFooter_montado_excel()
        {
            return $this->footer_montado_excel;
        }
        public function setFooter_montado_excel($footer_montado_excel)
        {
            $this->footer_montado_excel = $footer_montado_excel;
            return $this;
        }

        public function getHead_footer_status_excel()
        {
            return $this->head_footer_status_excel;
        }

        public function setHead_footer_status_excel($head_footer_status_excel)
        {
            $this->head_footer_status_excel = $head_footer_status_excel;
            return $this;
        }

        public function getOrder_by()
        {
            return $this->order_by;
        }
        public function setOrder_by($order_by)
        {
            $this->order_by = $order_by;
            return $this;
        }

        //=================================================================
        //MONTO HEADER
        function monto_header_excel(){

            //RECEBO OS DADOS DE CONFIGURAÇÃO
            $borda     = $this->getBorda_excel(); //Borda
            $status    = $this->getHead_footer_status_excel(); //Status Header e Footer
            $header    = $this->getHead_excel(); //Cabeçalho da Tabela
            $titulo    = $this->getTitulo_excel(); //Titulo descrição
            $pesqui    = $this->getFiltro_excel(); //Se tiver filtro ativo
            $this->setColspan_excel(count($header)); //Total de Colunas
            $colspan   = $this->getColspan_excel(); //Colspan
            $order_by  = $this->getOrder_by(); //Order By
            $total_reg = $this->getTotal_registros_excel(); //Total de registros

            //INICIO MONTAGEM
            $string .= '<table style="font-family:Trebuchet MS;" border="'.$borda.'">';

            //SE HEAD
            if($status == true){

                //Linha
                $string .= '<tr>';
                $string .= '<td colspan="'.$colspan.'" style="font-size:1.3em;font-weight:bold;background:#161C27;color:#FFF;text-align:center;">'.$titulo.' </td>';
                $string .= '</tr>';

                //Linha
                $string .= '<tr>';
                if(!empty($pesqui)){
                    $string .= '<td style="text-align:left;background:#2F3A55;color:#FFF;" colspan="'.$colspan.'"> <b>Exibindo '.$total_reg.' registro(s) da pesquisa: '.$pesqui.' - Ordenado por: '.$order_by.'</b> </td>';
                }else{
                    $string .= '<td style="text-align:left;background:#2F3A55;color:#FFF;" colspan="'.$colspan.'"> <b>Exibindo todos os '.$total_reg.' registro(s) - Ordenado por: '.$order_by.'</b> </td>';
                }
                $string .= '</tr>';

                //Linha
                $string .= '<tr>';
                for ($i=0; $i< count($header); $i++){ //Monto Cabeçalho
                    $string .= '<td style="background:#E4E4E4;text-align:center;color:#515151">'.$header[$i].'</td>';
                }
                $string .= '</tr>';
            }
        
            //RETORNO STRING
            $this->setHead_montado_excel($string);
        }

        //=================================================================
        //MONTO CONTEUDO
        function monto_conteudo_excel(){

            //RETORNO STRING
            $string .= $this->getConteudo_excel();
            $this->setConteudo_montado_excel($string);
        }

        //=================================================================
        //MONTO FOOTER
        function monto_footer_excel(){

            //RECEBO DADOS DE CONFIGURAÇÃO
            $colspan   = $this->getColspan_excel(); //Colspan

            //MONTO
            $string .= '<tr><td style="background:#2F3A55;text-align:right;font-weight:bold;color:#FFF;" colspan="'.$colspan.'"> Gerado em '.date("d/m/Y").' - '.date("H:i:s").'</td></tr>';
            $string .= '</table>';
            
            //RETORNO STRING
            $this->setFooter_montado_excel($string);
        }

        //=================================================================
        //EXPORTO
        function exporto_arquivo_excel(){

            //RECEBO DADOS DE CONFIGURAÇÃO
            $formato      = $this->getFormato_arquivo_excel();
            $nome_arquivo = $this->getNome_arquivo_excel();

            //PEGO DADOS
            if($formato=="excel" || $formato=="word"){
                $monto_tudo .= $this->getHead_montado_excel();
                $monto_tudo .= $this->getConteudo_montado_excel();
                $monto_tudo .= $this->getFooter_montado_excel();
            }else{
                $monto_tudo .= $this->getConteudo_montado_excel();
            }

            //VERIFICO 
            if($formato=="word"){ //Exporto para Word
                $arquivoDestino = $nome_arquivo."__".date("d-m-Y__H-i-s").".doc";
            }
            if($formato=="excel"){ //Exporto para Excel
                $arquivoDestino = $nome_arquivo."__".date("d-m-Y__H-i-s").".xls";
            }
            if($formato=="csv"){ //Exporto para CSV
                $arquivoDestino = $nome_arquivo."__".date("d-m-Y__H-i-s").".csv";
            }
            ob_end_clean();
            ini_set('zlib.output_compression','Off');
            header('Pragma: public');
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");                 
            header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');     
            header('Cache-Control: pre-check=0, post-check=0, max-age=0');   
            header ("Pragma: no-cache");
            header("Expires: 0");
            if($formato=="word"){  //Exporto para Word
                header("Content-type: application/vnd.ms-word");
            }
            if($formato=="excel"){ //Exporto para Excel
                header('Content-Type: application/vnd.ms-excel;');                 
                header("Content-type: application/x-msexcel");   
            }
            if($formato=="csv"){ //Exporto para CSV
                header("Content-type: application/csv");
            }
            header('Content-Transfer-Encoding: none');                
            header('Content-Disposition: attachment; filename="'.basename($arquivoDestino).'"');

            //EXPORTO
            echo utf8_decode($monto_tudo);
        }


    }
