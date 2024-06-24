<?php
require_once  'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
class PDF{

    public function create($html)
    {
        // Instanciar Dompdf
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf->setOptions($options);
        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);

        // Renderizar PDF
        $dompdf->render();

        // Establecer el nombre del archivo PDF
        $filename = 'document.pdf';

        // Enviar el PDF al navegador para su descarga
        $dompdf->stream($filename, ['Attachment' => 0]);
    }



}