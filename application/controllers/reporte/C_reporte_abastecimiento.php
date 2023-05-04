<?php
/* A
------------------------------------------------------------------------------------------
Creador: Brayan Janco Cahuana Fecha:07/09/2021, Codigo: GAN-MS-A3-025,
Creacion del Controlador C_reporte_abastecimiento para conectar con abastecimiento 
y M_reporte_abastecimiento
----------------------------------------------------------------------------------------
Modificado: Jose Daniel Luna Flores  Fecha: 28/11/2022 Codigo:GAN-MS-M6-0140
Descripcion: Se modifico el controlador C_reporte_abastecimiento para añadir el nuevo campo id_lote tanto en la generacion del pdf y excel
 */
?>
<?php
if (!defined('BASEPATH'))
    exit('Not access system ...');

class C_reporte_abastecimiento extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('reporte/M_reporte_abastecimiento','reporte_abastecimiento');
        $this->load->helper('url');
        $this->load->library('Pdf');
        $this->load->library('excel');
    }

    public function index() {
        if ($this->session->userdata('login')) {
            $log['usuario'] = $this->session->userdata('usuario');
            $log['permisos'] = $this->session->userdata('permisos');
            $data['codigo_usr'] = $this->session->userdata('id_usuario');
            $data['ubicaciones'] = $this->reporte_abastecimiento->get_lst_ubicaciones();
            $data['fecha_imp'] = date('Y-m-d H:i:s');

            $data['lib'] = 0;
            $data['datos_menu'] = $log;
            $data['cantidadN'] = $this->general->count_notificaciones();
            $data['lst_noti'] = $this->general->lst_notificacion();
            $data['mostrar_chat'] = $this->general->get_ajustes("mostrar_chat");
            $data['titulo'] = $this->general->get_ajustes("titulo");
            $data['logo'] = $this->general->get_ajustes("logo");
            $data['thema'] = $this->general->get_ajustes("tema");
            $data['descripcion'] = $this->general->get_ajustes("descripcion");
            $data['contenido'] = 'reporte/abastecimiento';
            $usrid = $this->session->userdata('id_usuario');
            $data['chatUsers'] = $this->general->chat_users($usrid);
            $data['getUserDetails'] = $this->general->get_user_details($usrid);
            $this->load->view('templates/estructura',$data);
        } else {
            redirect('logout');
        }
    }
    
    public function lst_reporte_abastecimiento() {

        $array_abast = array(
            'id_ubicacion' => $this->input->post('selc_ubi'),
            'fecha_inicial' => $this->input->post('selc_frep'),
            'fecha_fin' => $this->input->post('selc_finrep'),
        );
        $json_abast=json_encode($array_abast);

        $lst_abastecimiento = $this->reporte_abastecimiento->get_rep_abastecimiento($json_abast);
        $data= array('responce'=>'success','posts'=>$lst_abastecimiento);
        echo json_encode($data);
    }
    public function eliminar_abast() {
        $id_abastecimiento = $this->input->post('dato1');
        $eliminar_abast = $this->reporte_abastecimiento->get_eliminar_abastecimiento($id_abastecimiento);
        echo json_encode($eliminar_abast);
    }
    public function generar_excel_abastecimiento(){

        $ajustes = $this->general->get_ajustes("logo");
        $ajuste = json_decode($ajustes->fn_mostrar_ajustes);
        $logo = $ajuste->logo;

        $ajustes = $this->general->get_ajustes("titulo");
        $ajuste = json_decode($ajustes->fn_mostrar_ajustes);
        $titulo = $ajuste->titulo;
        
        $empresa = 'EMPRESA '.$titulo;
        $direccion_img = 'assets/img/icoLogo/'.$logo;

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document")
                                    ->setSubject("Office 2007 XLSX Test Document")
                                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Test result file");
        
        $usuario = $this->session->userdata('usuario');
        $fec_impresion = date('Y-m-d H:i:s');

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', 'Nº')
                    ->setCellValue('B7', 'Nro Lote')
                    ->setCellValue('C7', 'Producto')
                    ->setCellValue('D7', 'Destino')
                    ->setCellValue('E7', 'Cantidad')
                    ->setCellValue('F7', 'Unidad')
                    ->setCellValue('G7', 'Precio Compra')
                    ->setCellValue('H7', 'Precio Venta')
                    ->setCellValue('I7', 'Fecha');

        $objPHPExcel->getActiveSheet()->setCellValue('D2', $empresa);
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setSize(14)->getColor()->setRGB('000000');
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getFill()->getStartColor()->setRGB('F28A8C');
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->mergeCells('D2:H2');
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);            

        $objPHPExcel->getActiveSheet()->setCellValue('D3', 'REPORTE DE ABASTECIMIENTO');
        $objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setSize(15)->getColor()->setRGB('000000');
        $objPHPExcel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->mergeCells('D3:H3');
        $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Usuario: '.$usuario);
        $objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setSize(12)->getColor()->setRGB('000000');
        $objPHPExcel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->mergeCells('D4:H4');
        $objPHPExcel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->setCellValue('D5', 'Fecha: '.$fec_impresion);
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setSize(12)->getColor()->setRGB('000000');
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getFont()->setBold(false);
        $objPHPExcel->getActiveSheet()->mergeCells('D5:H5');
        $objPHPExcel->getActiveSheet()->getStyle('D5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    

        $objPHPExcel->getActiveSheet()->getStyle("A7:H7")->getFont()->setBold(true);                    

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('5');
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('40');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('15');
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('15');

        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight('30');
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight('30');

        $objPHPExcel->getActiveSheet()->getStyle('A7:I7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A7:I7')->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => 'A1A5A8')
        ));

        $abas = array(   
            'id_ubicacion' => $this->input->post('ubi_trabajo'),
            'fecha_inicial' => $this->input->post('fecha_inicial'),
            'fecha_fin' => $this->input->post('fecha_fin'),
        );

        $json=json_encode($abas);
        

        $lst_abastecimiento = $this->reporte_abastecimiento->get_rep_abastecimiento($json);

        $excel_row=8;
        $borders = array(
            'borders' => array(
                'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => 'FF000000'),
                )
            ),
        );
        foreach($lst_abastecimiento as $row)
        {   
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $excel_row, $excel_row-7)
                                          ->setCellValue('B' . $excel_row, "$row->oidlote")
                                          ->setCellValue('C' . $excel_row, "$row->oproducto")
                                          ->setCellValue('D' . $excel_row, "$row->odestino")
                                          ->setCellValue('E' . $excel_row, "$row->ocantidad")
                                          ->setCellValue('F' . $excel_row, "$row->ounidad")
                                          ->setCellValue('G' . $excel_row, "$row->opreciocompra")
                                          ->setCellValue('H' . $excel_row, "$row->oprecioventa")
                                          ->setCellValue('I' . $excel_row, "$row->ofecha");
            $objPHPExcel->getActiveSheet()->getStyle('A'.$excel_row.':I'.($excel_row))->applyFromArray($borders);                                
            $objPHPExcel->getActiveSheet()->getStyle('A'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);          
            $objPHPExcel->getActiveSheet()->getStyle('C'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$excel_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                                                         
            $excel_row++;                                           
        }

        $objPHPExcel->getActiveSheet()->setTitle('Simple');

        $objPHPExcel->setActiveSheetIndex(0);

        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName('test_img');
        $objDrawing->setDescription('test_img');
        $objDrawing->setPath($direccion_img);
        $objDrawing->setCoordinates('B2');                      
        $objDrawing->setOffsetX(80); 
        $objDrawing->setOffsetY(5);                
        $objDrawing->setWidth(100); 
        $objDrawing->setHeight(132); 
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte Abastecimiento.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
        header ('Cache-Control: cache, must-revalidate'); 
        header ('Pragma: public'); 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function generar_pdf_abastecimiento() {

        $ajustes = $this->general->get_ajustes("logo");
        $ajuste = json_decode($ajustes->fn_mostrar_ajustes);
        $logo = $ajuste->logo;

        $ajustes = $this->general->get_ajustes("titulo");
        $ajuste = json_decode($ajustes->fn_mostrar_ajustes);
        $titulo = $ajuste->titulo;

        // configuracion de tamaños de letras y margenes
        $id_usuario = $this->session->userdata('id_usuario');
        $id_papel = $this->general->get_papel_size($id_usuario);

        $abas = array(   
            'id_ubicacion' => $this->input->post('ubi_trabajo'),
            'fecha_inicial' => $this->input->post('fecha_inicial'),
            'fecha_fin' => $this->input->post('fecha_fin'),
        );

        $json=json_encode($abas);
        

        $lst_abastecimiento = $this->reporte_abastecimiento->get_rep_abastecimiento($json);

        if ($id_papel[0]->oidpapel == 1304) {
            // tamaño carta
            $marginTitle = 77;
            $pdfMarginLeft = 15;
            $pdfMarginRight = 15;
            $pdfFontSizeData= PDF_FONT_SIZE_DATA;
            $pdfSize = PDF_PAGE_FORMAT;
            $pdfFontSizeMain= PDF_FONT_SIZE_MAIN;
            $imageSizeN= 15;
            $imageSizeM= 20;
            $imageSizeX = 45;
            $imageSizeY = 15;
            $cantidadWidth = 68;
            $ubicacionWidth = 115;
            $precioWidth = 70;
            $descripcionWidth = 76;
            $codigoWidth = 108;
            $productoWidth = 150;
            $numeroWidth = 30;
            $unidadWidth = 63; 
            $unidadWidth = 68; 
            $fechaWidth = 73;
            $footer=true;
        } else  {
            $dim=80;
            $dim=$dim+(count($lst_abastecimiento)*13.1);
            $pdfSize = array(80,$dim);
            $pdfFontSizeMain= 9;
            $pdfFontSizeData= 8;
            $pdfMarginLeft = 2;
            $pdfMarginRight = 7;
            $imageSizeM= 5;
            $imageSizeN = 5;
            $imageSizeX = 25;
            $imageSizeY = 15;
            $marginTitle = 30;
            $numeroWidth = 20;
            $productoWidth = 35 ;
            $descripcionWidth = 35;
            $cantidadWidth = 30;
            $unidadWidth = 30; 
            $precioWidth = 30; 
            $fechaWidth = 30;
            $footer=false;
        }
      
        // fin configuracion

        $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, $pdfSize, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('-');
        $pdf->SetTitle('Reporte de Abastecimiento');
        $pdf->SetSubject('Reporte de Abastecimiento');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setFooterData(array(0,75,146), array(0,75,146));
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, 'B', $pdfFontSize));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, 'B', $pdfFontSizeData));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins($pdfMarginLeft, 20, $pdfMarginRight);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(15);

        $pdf->SetAutoPageBreak($footer, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->setPrintHeader(true);
        $pdf->setPrintFooter(true);

        $pdf->SetFont('times', '', $pdfFontSizeMain, '', true);
        $pdf->AddPage('P', $pdfSize);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $image_file = 'assets/img/icoLogo/'.$logo;

        $pdf->Image($image_file, $imageSizeM, $imageSizeN, $imageSizeX, $imageSizeY, '', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->SetFont('times', 'B', $pdfFontSizeMain);

        $titulo='        EMPRESA '.$titulo;
        
        $pdf->MultiCell($marginTitle, 5, $titulo, 0, 'C', 0, 1, '', '', true);
        $pdf->SetFont('times', 'N', $pdfFontSizeData);

        $usuario = $this->session->userdata('usuario');
        $fec_impresion = date('Y-m-d H:i:s');

        $tbl = '
          <div style="text-align:rigth; font-size: 12px">
              <font><b>Usuario:</b> '.$usuario.' </font><br>
              <font><b>Fecha:</b> '.$fec_impresion.' </font>
          </div>

          <div style="text-align:center;">
              <font><b> REPORTE DE ABASTECIMIENTO </b></font><br>
          </div> ';

        $tbl1 = '
          <table cellpadding="3" border="1">
            <tr align="center" style="font-weight: bold" bgcolor="#E5E6E8">
                <th width="'.$numeroWidth.'px"> N&ordm; </th>
                <th width="'.$numeroWidth.'px"> Nro Lote </th>
                <th width="'.$productoWidth.'px"> Producto </th>
                    <th width="'.$descripcionWidth.'px"> Destino </th>
                <th width="'.$cantidadWidth.'px"> Cantidad </th>
                <th width="'.$unidadWidth.'px"> Unidad </th>
                <th width="'.$precioWidth.'px"> Precio Compra </th>
                <th width="'.$precioWidth.'px"> Precio Venta </th>
                <th width="'.$fechaWidth.'px"> Fecha </th>
            </tr> ';

            $nro=0;
            $tbl2 = '';
            foreach ($lst_abastecimiento as $abs):
              $nro++;
              $tbl2 = $tbl2.'
                <tr>
                  <td align="center">'.$nro.'</td>
                  <td>'.$abs->oidlote.'</td>
                  <td>'.$abs->oproducto.'</td>
                  <td>'.$abs->odestino.'</td>
                  <td align="rigth">'.$abs->ocantidad.'</td>
                  <td align="rigth">'.$abs->ounidad.'</td>
                  <td align="rigth">'.$abs->opreciocompra.'</td>
                  <td align="rigth">'.$abs->oprecioventa.'</td>
                  <td align="center">'.$abs->ofecha.'</td>
                </tr>';
            endforeach;
              $tbl3 = '
          </table> ';
        $pdf->writeHTML($tbl.$tbl1.$tbl2.$tbl3, true, false, false, false, '');
        ob_end_clean();
        $pdf_ruta = $pdf->Output('Reporte_Abastecimiento.pdf', 'I');
    }

}
