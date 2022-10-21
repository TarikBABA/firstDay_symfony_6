<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;


class PdfService
{
    private $dompdf;
    public function __construct()
    {
        $this->dompdf = new Dompdf();

        $pdfOption = new Options();

        $pdfOption->set('defaultFont', 'Garamond');

        $this->dompdf->setOptions($pdfOption);
    }

    public function showPdf($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->stream('details.pdf', [
            'Attachement' => false
        ]);
    }

    public function generateBinaryPdf($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->output();
    }
}