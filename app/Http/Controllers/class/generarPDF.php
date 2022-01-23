<?php

use Codedge\Fpdf\Fpdf\Fpdf;

include 'lib/barcode-php1/class/BCGcode128.barcode.php';
include 'lib/barcode-php1/class/BCGDrawing.php';
include 'sendEmail.php';
require('roundedRect.php');

class generarPDF
{
    public function facturaPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas)
    {
        ob_end_clean();
        $pdf = new OwnPdf('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        if (file_exists($imagen)) {
            $pdf->Image($imagen, 30, 10, 60, 50);
        }
        $pdf->RoundedRect(110, 10, 90, 71, 2, '1234', 'D');
        $pdf->RoundedRect(10, 50, 97, 31, 2, '1234', 'D');
        //cuadros detalle empresa que emite
        $pdf->Ln(30);
        $pdf->SetXY(10, 50);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(100, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Matriz:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 'L');
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Sucursal:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($document->infoFactura->dirEstablecimiento), 0, 'L');
        ($document->infoFactura->obligadoContabilidad == 'SI') ? $contabilidad = 'SI' : $contabilidad = 'NO';
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('OBLIGADO A LLEVAR CONTABILIDAD:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('CONTRIBUYENTE RÉGIMEN MICROEMPRESAS'), 0, 0, 'L', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->Cell(88, 5, utf8_decode('FACTURA '), 0, 2, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('No. ') . $document->infoTributaria->estab . '-' . $document->infoTributaria->ptoEmi . '-' . $document->infoTributaria->secuencial, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION:'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($fecha_actual), 0, 2, 'L', 0);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('AMBIENTE:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->SetX(111);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('EMISIÓN:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(111);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $this->generarCodigoBarras($claveAcceso, 'facturas');
        $pdf->image('archivos/comprobantes/facturas/codigosbarras/codigo' . $claveAcceso . '.png', 115, null, 80);

        $cli_guias = '';

        //cuadro de datos del cliente
        $pdf->RoundedRect(10, 84, 190, 17, 2, '1234', 'D');
        $pdf->SetXY(10, 85);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, utf8_decode('Razón Social / Nombres y Apellidos:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoFactura->razonSocialComprador), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('RUC / CI.:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($document->infoFactura->identificacionComprador), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Dirección:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(115, 5, utf8_decode($document->infoFactura->direccionComprador), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Fecha de Emisión:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($document->infoFactura->fechaEmision), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(25, 5, utf8_decode('Guías Remisión:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($cli_guias), 0, 0, 'L', 0);


        //tabla de productos
        $pdf->SetXY(10, 104);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->Cell(25, 6, utf8_decode('Cod. Principal'), 0, 0, 'C', 1);
        $pdf->Cell(15, 6, utf8_decode('Cant.'), 0, 0, 'C', 1);
        $pdf->Cell(75, 6, utf8_decode('Descripción'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Precio Unitario'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Descuento'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Precio Total'), 0, 0, 'C', 1);
        $pdf->Ln();

        //rellenado de campos
        $pdf->SetFont('Helvetica', '', 8);
        foreach ($document->detalles->detalle  as $a => $b) {
            $pdf->Cell(25, 5, $b->codigoPrincipal, 0, 0, 'L', 0);
            $pdf->Cell(15, 5, $b->cantidad, 0, 0, 'R', 0);
            $pdf->Cell(75, 5, $b->descripcion, 0, 0, 'L', 0);
            $pdf->Cell(25, 5, $b->precioUnitario, 0, 0, 'R', 0);
            $pdf->Cell(25, 5, $b->descuento, 0, 0, 'R', 0);
            $pdf->Cell(25, 5, $b->precioTotalSinImpuesto, 0, 1, 'R', 0);
        }

        // Cuadro Información Adicional
        $posY = $pdf->GetY();
        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(110, 5, utf8_decode('Información Adicional'), 0, 1, 'C', 1);

        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                $pdf->SetFillColor(200, 200, 200);
                $pdf->SetFont('Helvetica', 'B', 8);
                $pdf->Cell(15, 5, utf8_decode($b) . ':', 0, 0, 'L', 1);
                $pdf->SetFont('Helvetica', '', 8);
                $pdf->Cell(95, 5, utf8_decode($a), 0, 1, 'L', 1);
                if ($b == 'Email' || $b == 'email' || $b == 'correo' || $b == 'Correo') {
                    $correo = $a;
                }
            }
        }
        // $pdf->Cell(15, 5, utf8_decode('Correo:'), 'L', 0, 'L', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(95, 5, utf8_decode($factura->email), 'R', 1, 'L', 0);
        // $pdf->SetFont('Helvetica', 'B', 8);
        // $pdf->Cell(15, 5, utf8_decode('Vendedor:'), 'LB', 0, 'L', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(95, 5, utf8_decode($factura->vendedor), 'RB', 1, 'L', 0);


        //foreach ($document->infoFactura->pagos->pago as $e => $f) {
        if ($document->infoFactura->pagos->pago->formaPago == '01') {
            $formaPago = 'Sin utilizacion del sistema financiero';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '15') {
            $formaPago = 'Compensacion de deudas';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '16') {
            $formaPago = 'Tarjeta debito';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '17') {
            $formaPago = 'Dinero Electronico';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '18') {
            $formaPago = 'Tarjeta Prepago';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '19') {
            $formaPago = 'Tarjeta de credito';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '20') {
            $formaPago = 'Otros con utilizacion del sistema financiero';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '21') {
            $formaPago = 'Endoso de titulos';
        }
        //}
        // Cuadro de Forma de Pago
        $pdf->Ln();
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->Cell(110, 5, utf8_decode('Forma de Pago'), 0, 1, 'C', 1);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(15, 5, utf8_decode('Forma:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($formaPago), 0, 1, 'L', 1);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Valor:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($document->infoFactura->pagos->pago->total), 0, 1, 'L', 1);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Plazo:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($document->infoFactura->pagos->pago->plazo), 0, 1, 'L', 1);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Tiempo:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($document->infoFactura->pagos->pago->unidadTiempo), 0, 1, 'L', 1);

        $iva = 0;
        $ice = 0;
        $irbpnr = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_objeto = 0;
        $subtotal_exento = 0;
        foreach ($document->infoFactura->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = $b->baseImponible;
                    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_objeto = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_exento = $b->baseImponible;
                }
            }
            if ($b->codigo == 3) {
                $ice = $b->valor;
            }
            if ($b->codigo == 5) {
                $irbpnr = $b->valor;
            }
        }

        // Cuadro de Subtotales y Totales
        $pdf->SetXY(125, $posY);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL 12%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal12, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL 0%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal0, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL NO OBJETO DE IVA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal_no_objeto, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL EXENTO DE IVA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal_exento, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL SIN IMPUESTOS', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->totalSinImpuestos, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'DESCUENTO', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->totalDescuento, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'ICE', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $ice, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, '12%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $iva, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'IRBPNR', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $irbpnr, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'PROPINA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->propina, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(125);
        $pdf->Cell(50, 5, 'VALOR TOTAL', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->importeTotal, 0, 1, 'R', 1);


        if (!file_exists('archivos/comprobantes/facturas/pdf/')) {
            mkdir('archivos/comprobantes/facturas/pdf', 0777, true);
        }
        $pdf->Output('archivos/comprobantes/facturas/pdf/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $valor = $email->enviarCorreo('Factura', $document->infoFactura->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas);
    }

    public function guiaRemisionPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas)
    {
        ob_end_clean();
        $pdf = new OwnPdf('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        if (file_exists($imagen)) {
            $pdf->Image($imagen, 30, 10, 60, 50);
        }
        $pdf->RoundedRect(110, 10, 90, 71, 2, '1234', 'D');
        $pdf->RoundedRect(10, 50, 97, 31, 2, '1234', 'D');
        //cuadros detalle empresa que emite
        $pdf->Ln(30);
        $pdf->SetXY(10, 50);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(100, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Matriz:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 'L');
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Sucursal:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($document->infoFactura->dirEstablecimiento), 0, 'L');
        ($document->infoFactura->obligadoContabilidad == 'SI') ? $contabilidad = 'SI' : $contabilidad = 'NO';
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('OBLIGADO A LLEVAR CONTABILIDAD:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('CONTRIBUYENTE RÉGIMEN MICROEMPRESAS'), 0, 0, 'L', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->Cell(88, 5, utf8_decode('GUÍA DE REMISIÓN'), 0, 2, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('No. ') . $document->infoTributaria->estab . '-' . $document->infoTributaria->ptoEmi . '-' . $document->infoTributaria->secuencial, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION:'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($fecha_actual), 0, 2, 'L', 0);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('AMBIENTE:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->SetX(111);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('EMISIÓN:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(111);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $this->generarCodigoBarras($claveAcceso, 'facturas');
        $pdf->image('archivos/comprobantes/facturas/codigosbarras/codigo' . $claveAcceso . '.png', 115, null, 80);

        $cli_guias = '';

        //cuadro de datos del cliente
        $pdf->RoundedRect(10, 84, 190, 17, 2, '1234', 'D');
        $pdf->SetXY(10, 85);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, utf8_decode('Razón Social / Nombres y Apellidos:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoFactura->razonSocialComprador), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('RUC / CI.:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($document->infoFactura->identificacionComprador), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Dirección:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(115, 5, utf8_decode($document->infoFactura->direccionComprador), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Fecha de Emisión:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($document->infoFactura->fechaEmision), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(25, 5, utf8_decode('Guías Remisión:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($cli_guias), 0, 0, 'L', 0);


        //tabla de productos
        $pdf->SetXY(10, 104);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->Cell(25, 6, utf8_decode('Cod. Principal'), 0, 0, 'C', 1);
        $pdf->Cell(15, 6, utf8_decode('Cant.'), 0, 0, 'C', 1);
        $pdf->Cell(75, 6, utf8_decode('Descripción'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Precio Unitario'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Descuento'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Precio Total'), 0, 0, 'C', 1);
        $pdf->Ln();

        //rellenado de campos
        $pdf->SetFont('Helvetica', '', 8);
        foreach ($document->detalles->detalle  as $a => $b) {
            $pdf->Cell(25, 5, $b->codigoPrincipal, 0, 0, 'L', 0);
            $pdf->Cell(15, 5, $b->cantidad, 0, 0, 'R', 0);
            $pdf->Cell(75, 5, $b->descripcion, 0, 0, 'L', 0);
            $pdf->Cell(25, 5, $b->precioUnitario, 0, 0, 'R', 0);
            $pdf->Cell(25, 5, $b->descuento, 0, 0, 'R', 0);
            $pdf->Cell(25, 5, $b->precioTotalSinImpuesto, 0, 1, 'R', 0);
        }

        // Cuadro Información Adicional
        $posY = $pdf->GetY();
        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(110, 5, utf8_decode('Información Adicional'), 0, 1, 'C', 1);

        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                $pdf->SetFillColor(200, 200, 200);
                $pdf->SetFont('Helvetica', 'B', 8);
                $pdf->Cell(15, 5, utf8_decode($b) . ':', 0, 0, 'L', 1);
                $pdf->SetFont('Helvetica', '', 8);
                $pdf->Cell(95, 5, utf8_decode($a), 0, 1, 'L', 1);
                if ($b == 'Email' || $b == 'email' || $b == 'correo' || $b == 'Correo') {
                    $correo = $a;
                }
            }
        }
        // $pdf->Cell(15, 5, utf8_decode('Correo:'), 'L', 0, 'L', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(95, 5, utf8_decode($factura->email), 'R', 1, 'L', 0);
        // $pdf->SetFont('Helvetica', 'B', 8);
        // $pdf->Cell(15, 5, utf8_decode('Vendedor:'), 'LB', 0, 'L', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(95, 5, utf8_decode($factura->vendedor), 'RB', 1, 'L', 0);


        //foreach ($document->infoFactura->pagos->pago as $e => $f) {
        if ($document->infoFactura->pagos->pago->formaPago == '01') {
            $formaPago = 'Sin utilizacion del sistema financiero';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '15') {
            $formaPago = 'Compensacion de deudas';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '16') {
            $formaPago = 'Tarjeta debito';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '17') {
            $formaPago = 'Dinero Electronico';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '18') {
            $formaPago = 'Tarjeta Prepago';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '19') {
            $formaPago = 'Tarjeta de credito';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '20') {
            $formaPago = 'Otros con utilizacion del sistema financiero';
        }
        if ($document->infoFactura->pagos->pago->formaPago == '21') {
            $formaPago = 'Endoso de titulos';
        }
        //}
        // Cuadro de Forma de Pago
        $pdf->Ln();
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->Cell(110, 5, utf8_decode('Forma de Pago'), 0, 1, 'C', 1);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(15, 5, utf8_decode('Forma:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($formaPago), 0, 1, 'L', 1);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Valor:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($document->infoFactura->pagos->pago->total), 0, 1, 'L', 1);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Plazo:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($document->infoFactura->pagos->pago->plazo), 0, 1, 'L', 1);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Tiempo:'), 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($document->infoFactura->pagos->pago->unidadTiempo), 0, 1, 'L', 1);

        $iva = 0;
        $ice = 0;
        $irbpnr = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_objeto = 0;
        $subtotal_exento = 0;
        foreach ($document->infoFactura->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = $b->baseImponible;
                    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_objeto = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_exento = $b->baseImponible;
                }
            }
            if ($b->codigo == 3) {
                $ice = $b->valor;
            }
            if ($b->codigo == 5) {
                $irbpnr = $b->valor;
            }
        }

        // Cuadro de Subtotales y Totales
        $pdf->SetXY(125, $posY);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL 12%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal12, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL 0%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal0, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL NO OBJETO DE IVA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal_no_objeto, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL EXENTO DE IVA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $subtotal_exento, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL SIN IMPUESTOS', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->totalSinImpuestos, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'DESCUENTO', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->totalDescuento, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'ICE', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $ice, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, '12%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $iva, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'IRBPNR', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $irbpnr, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'PROPINA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->propina, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(125);
        $pdf->Cell(50, 5, 'VALOR TOTAL', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $document->infoFactura->importeTotal, 0, 1, 'R', 1);


        if (!file_exists('archivos/comprobantes/facturas/pdf/')) {
            mkdir('archivos/comprobantes/facturas/pdf', 0777, true);
        }
        $pdf->Output('archivos/comprobantes/facturas/pdf/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $valor = $email->enviarCorreo('Factura', $document->infoFactura->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas);
    }

    public function notaCreditoPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas)
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $imagen)) {
            $pdf->Image(constant("DATA_EMPRESA") . $id_empresa . '/imagen/' . $imagen, 20, 10, 80, 30);
        }
        //cuadros detalle empresa que emite
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'L', 0);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        if ($document->infoNotaCredito->obligadoContabilidad == 'SI') {

            $contabilidad = "SI";
        } else {
            $contabilidad = "NO";
        }
        $pdf->Cell(29, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        $pdf->SetXY(111, 38);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(45, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(30, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->SetXY(111, 42);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->Cell(16, 5, utf8_decode('AMBIENTE: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(58, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        $pdf->SetXY(111, 46);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->Cell(13, 5, utf8_decode('EMISIÓN: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetXY(111, 50);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $pdf->SetXY(111, 55);
        $pdf->SetFont('Helvetica', '', 7);
        $this->generarCodigoBarras($claveAcceso, $id_empresa);
        $pdf->image(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/factura/codigosbarras/codigo_' . $claveAcceso . '.png', null, null, 88, 7);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('NOTA DE CRÉDITO'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        $cli_correo = '';
        $cli_telefono = '';
        $infoAdicional = "";
        $correo = "";
        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                if ($b == 'Email' || $b == 'email' || $b == '=correo' || $b == 'Correo') {
                    $correo = $a;
                    $infoAdicional .= $b . ': ' . $a . "\n";
                } else {
                    $infoAdicional .= $b . ': ' . $a . "\n";
                }
            }
        }
        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(51, 5, utf8_decode('Razón Social / Nombres y Apellidos: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoNotaCredito->razonSocialComprador), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(21, 5, utf8_decode('Identificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(110, 5, utf8_decode($document->infoNotaCredito->identificacionComprador), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(11, 5, utf8_decode('Fecha: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(29, 5, utf8_decode($document->infoNotaCredito->fechaEmision), 0, 1, 'L', 0);
        //línea
        $pdf->Line(20, 85, 190, 85);
        //datos del comprobante devuelto
        $tipo_comprobante = 'FACTURA';
        $pdf->SetXY(10, 86);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Comprobante que se modifica: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(40, 5, utf8_decode($tipo_comprobante), 0, 0, 'L', 0);
        $pdf->Cell(30, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Fecha de Emisión (Comprobante a modificar): '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode(''), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(100, 5, utf8_decode('Razón de Modificación: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode(''), 0, 1, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 103);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(15, 8, utf8_decode('Código'), 1, 'C', 1);
        $pdf->SetXY(25, 103);
        $pdf->MultiCell(15, 4, utf8_decode('Código Auxiliar'), 1, 'C', 1);
        $pdf->SetXY(40, 103);
        $pdf->MultiCell(15, 8, utf8_decode('Cantidad'), 1, 'C', 1);
        $pdf->SetXY(55, 103);
        $pdf->MultiCell(35, 8, utf8_decode('Descripción'), 1, 'C', 1);
        $pdf->SetXY(90, 103);
        $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(107, 103);
        $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(124, 103);
        $pdf->MultiCell(17, 4, utf8_decode('Detalle Adicional'), 1, 'C', 1);
        $pdf->SetXY(141, 103);
        $pdf->MultiCell(19, 4, utf8_decode('Precio Unitario'), 1, 'C', 1);
        $pdf->SetXY(160, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Descuento'), 1, 'C', 1);
        $pdf->SetXY(180, 103);
        $pdf->MultiCell(20, 8, utf8_decode('Precio Total'), 1, 'C', 1);

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $subt = 0.0;
        foreach ($document->detalles->detalle as $a => $b) {
            $pdf->Cell(15, 6, $b->codigoInterno, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $b->codigoAdicional, 1, 0, 'R', 0);
            $pdf->Cell(15, 6, $b->cantidad, 1, 0, 'L', 0);
            $pdf->Cell(35, 6, $b->descripcion, 1, 0, 'R', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'R', 0);
            $pdf->Cell(17, 6, '', 1, 0, 'L', 0);
            $pdf->Cell(19, 6, number_format(floatval($b->precioUnitario), 2), 1, 0, 'R', 0);
            $pdf->Cell(20, 6, $b->descuento, 1, 0, 'R', 0);
            $pdf->Cell(20, 6, $b->precioTotalSinImpuesto, 1, 0, 'R', 0);
            $pdf->Ln();
        }
        $iva = 0;
        $ice = 0;
        $IRBPNR = 0;
        $subtotal12 = 0;
        $subtotal0 = 0;
        $subtotal_no_impuesto = 0;
        $subtotal_no_iva = 0;
        $propina = 0;
        $pdf->Ln();
        $fac_forma = '';
        foreach ($document->infoFactura->totalConImpuestos->totalImpuesto as $a => $b) {
            if ($b->codigo == 2) {
                $iva = $b->valor;
                if ($b->codigoPorcentaje == 0) {
                    $subtotal0 = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 2) {
                    $subtotal12 = $b->baseImponible;
                    //    $iva = $b->valor;
                }
                if ($b->codigoPorcentaje == 6) {
                    $subtotal_no_impuesto = $b->baseImponible;
                }
                if ($b->codigoPorcentaje == 7) {
                    $subtotal_no_iva = $b->baseImponible;
                }
            }
            if ($b->codigo == 3) {
                $ice = $b->valor;
            }
            if ($b->codigo == 5) {
                $IRBPNR = $b->valor;
            }
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(115, 6, utf8_decode('Información Adicional:'), 'LTR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 6, 'SUBTOTAL 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal12, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Dirección: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($document->infoNotaCredito->direccionComprador), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL IVA 0%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal0, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Teléfono: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($cli_telefono), 'R', 0, 'L', 0);
        $pdf->Cell(50, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(25, 6, $subtotal_no_impuesto, 1, 1, 'R', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL NO OBJETO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subtotal_no_iva, 1, 1, 'R', 0);
        $pdf->Cell(20, 6, utf8_decode('Email: '), 'L', 0, 'L', 0);
        $pdf->Cell(95, 6, utf8_decode($infoAdicional), 'R', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL EXENTO DE IVA', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $subt, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->infoNotaCredito->totalDescuento, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'ICE', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $ice, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 'LBR', 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IVA 12%', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $iva, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'IRBPNR', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $IRBPNR, 1, 1, 'R', 0);
        $pdf->Cell(115, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(10, 6, '', 0, 0, 'L', 0);
        $pdf->Cell(45, 6, 'VALOR TOTAL', 1, 0, 'L', 0);
        $pdf->Cell(20, 6, $document->infoNotaCredito->importeTotal, 1, 1, 'R', 0);


        $pdf->Output(constant("DATA_EMPRESA") . $id_empresa . '/comprobantes/notacredito/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $email->enviarCorreo('Notacredito', $document->infoNotaCredito->razonSocialComprador, $claveAcceso, $correo, $id_empresa, $empresas);
        return "bien";
    }
    public function comprobanteRetencionPDF($document, $claveAcceso, $id_empresa, $imagen, $empresas)
    {
        ob_end_clean();
        $pdf = new OwnPdf('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        if (file_exists($imagen)) {
            $pdf->Image($imagen, 30, 10, 60, 50);
        }
        $pdf->RoundedRect(110, 10, 90, 71, 2, '1234', 'D');
        $pdf->RoundedRect(10, 50, 97, 31, 2, '1234', 'D');
        //cuadros detalle empresa que emite
        $pdf->Ln(30);
        $pdf->SetXY(10, 50);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(100, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Matriz:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 'L');
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Sucursal:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($document->infoCompRetencion->dirEstablecimiento), 0, 'L');
        ($document->infoCompRetencion->obligadoContabilidad == 'SI') ? $contabilidad = 'SI' : $contabilidad = 'NO';
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('OBLIGADO A LLEVAR CONTABILIDAD:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('CONTRIBUYENTE RÉGIMEN MICROEMPRESAS'), 0, 0, 'L', 0);
        // $pdf->SetFont('Helvetica', '', 8);
        // $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->Cell(88, 5, utf8_decode('COMPROBANTE DE RETENCIÓN'), 0, 2, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('No. ') . $document->infoTributaria->estab . '-' . $document->infoTributaria->ptoEmi . '-' . $document->infoTributaria->secuencial, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION:'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($fecha_actual), 0, 2, 'L', 0);
        if ($document->infoTributaria->ambiente == 2) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('AMBIENTE:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        if ($document->infoTributaria->tipoEmision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->SetX(111);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('EMISIÓN:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(111);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'L', 0);
        $this->generarCodigoBarras($claveAcceso, 'retenciones');
        $pdf->image('archivos/comprobantes/retenciones/codigosbarras/codigo' . $claveAcceso . '.png', 115, null, 80);

        //cuadro de datos del cliente
        $pdf->RoundedRect(10, 84, 190, 12, 2, '1234', 'D');
        $pdf->SetXY(10, 85);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, utf8_decode('Razón Social / Nombres y Apellidos:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($document->infoCompRetencion->razonSocialSujetoRetenido), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('RUC / CI.:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($document->infoCompRetencion->identificacionSujetoRetenido), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Fecha de Emisión:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($document->infoCompRetencion->fechaEmision), 0, 1, 'L', 0);
        $pdf->Ln();

        //tabla de productos
        $posY = $pdf->GetY();
        $pdf->SetXY(10, $posY);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(25, 8, utf8_decode('Comprobante'), 0, 'C', 1);
        $pdf->SetXY(35, $posY);
        $pdf->MultiCell(30, 8, utf8_decode('Número'), 0, 'C', 1);
        $pdf->SetXY(65, $posY);
        $pdf->MultiCell(25, 8, utf8_decode('Fecha Emisión'), 0, 'C', 1);
        $pdf->SetXY(90, $posY);
        $pdf->MultiCell(20, 4, utf8_decode('Ejercicio Fiscal'), 0, 'C', 1);
        $pdf->SetXY(110, $posY);
        $pdf->MultiCell(25, 8, utf8_decode('Base Imponible'), 0, 'C', 1);
        $pdf->SetXY(135, $posY);
        $pdf->MultiCell(20, 8, utf8_decode('Impuesto'), 0, 'C', 1);
        $pdf->SetXY(155, $posY);
        $pdf->MultiCell(20, 4, utf8_decode('Porcentaje de Retención'), 0, 'C', 1);
        $pdf->SetXY(175, $posY);
        $pdf->MultiCell(25, 8, utf8_decode('Valor Retenido'), 0, 'C', 1);

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $total = 0;
        foreach ($document->impuestos->impuesto as $a => $b) {
            if ($b->codDocSustento = '01') {
                $pdf->Cell(25, 6, 'FACTURA', 1, 0, "L", 0);
            } else {
                $pdf->Cell(25, 6, $b->codDocSustento, 1, 0, "L", 0);
            }
            $pdf->Cell(30, 6, $b->numDocSustento, 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $b->fechaEmisionDocSustento, 1, 0, 'C', 0);
            $pdf->Cell(20, 6, date("Y"), 1, 0, 'C', 0);
            $pdf->Cell(25, 6, $b->baseImponible, 1, 0, 'R', 0);
            if ($b->codigo == 1) {
                $pdf->Cell(20, 6, 'IVA', 1, 0, 'L', 0);
            } else if ($b->codigo == 2) {
                $pdf->Cell(20, 6, 'RENTA', 1, 0, 'L', 0);
            } else {
                $pdf->Cell(20, 6, $b->codigo, 1, 0, "L", 0);
            }
            $pdf->Cell(20, 6, $b->porcentajeRetener . "%", 1, 0, 'R', 0);
            $pdf->Cell(25, 6, $b->valorRetenido, 1, 0, 'R', 0);
            $pdf->Ln();
            $total += floatval($b->valorRetenido);
        }
        $pdf->Ln();

        $y = $pdf->GetY();
        $pdf->SetX(10);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->Cell(110, 5, utf8_decode('Información Adicional'), 0, 1, 'C', 1);

        foreach ($document->infoAdicional->campoAdicional as $a) {
            foreach ($a->attributes() as $b) {
                $pdf->SetFillColor(220, 220, 220);
                $pdf->SetFont('Helvetica', 'B', 8);
                $pdf->Cell(15, 5, utf8_decode($b) . ':', 0, 0, 'L', 1);
                $pdf->SetFont('Helvetica', '', 8);
                $pdf->Cell(95, 5, utf8_decode($a), 0, 1, 'L', 1);
                if ($b == 'Email' || $b == 'email' || $b == 'correo' || $b == 'Correo') {
                    $correo = $a;
                }
            }
        }
        $pdf->SetXY(135, $y);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(40, 6, 'Total a retener', 0, 0, 'R', 1);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell(25, 6, number_format(floatval($total), 2), 0, 1, 'R', 1);


        if (!file_exists('archivos/comprobantes/retenciones/pdf/')) {
            mkdir('archivos/comprobantes/retenciones/pdf', 0777, true);
        }
        $pdf->Output('archivos/comprobantes/retenciones/pdf/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $valor = $email->enviarCorreo('Retención', $document->infoCompRetencion->razonSocialSujetoRetenido, $claveAcceso, $correo, $id_empresa, $empresas);
    }

    public function cierreCaja($arqueo, $efectivo, $credito, $egresos)
    {
        ob_end_clean();
        $pdf = new OwnPdf('P', 'mm', 'A4');
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->AddPage();
        $fecha_actual = date("d/m/Y");

        if (file_exists($arqueo->logo)) {
            $pdf->Image($arqueo->logo, 10, 7, 40, 30);
        }
        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetXY(60, 12);
        $pdf->Cell(105, 8, utf8_decode(mb_strtoupper($arqueo->empresa)), 0, 2, 'C', 0);
        $pdf->Cell(105, 8, utf8_decode('REPORTE CIERRE DE CAJA'), 0, 2, 'C', 0);

        $pdf->SetXY(10, 40);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(35, 5, utf8_decode('Fecha:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(35, 5, $fecha_actual, 0, 1, 'L', 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 5, utf8_decode('Datos del Arqueo:'), 0, 2, 'L', 0);
        $pdf->RoundedRect(10, 50, 190, 20, 2, '1234', 'D');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode('Sucursal:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 5, utf8_decode($arqueo->empresa . ' - ' . $arqueo->establecimiento), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode('Caja:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 5, utf8_decode($arqueo->punto), 0, 1, 'L', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode('Fecha Inicio:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 5, $arqueo->created_at, 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode('Fecha Cierre:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 5, $arqueo->fec_cierre, 0, 1, 'L', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode('Monto Aper.:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 5, $arqueo->mon_apertura, 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 5, utf8_decode('Monto Cierre:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 5, $arqueo->mon_cierre, 0, 1, 'L', 0);

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 5, utf8_decode('DETALLES'), 0, 2, 'L', 0);
        //ventas en efectivo
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Ln();
        $pdf->Cell(60, 5, utf8_decode('Ventas en Efectivo:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, 'No.', 0, 0, 'C', 1);
        $pdf->Cell(45, 5, utf8_decode('F. H. Registro'), 0, 0, 'C', 1);
        $pdf->Cell(70, 5, utf8_decode('Comprobante'), 0, 0, 'C', 1);
        $pdf->Cell(25, 5, utf8_decode('Valor'), 0, 0, 'C', 1);
        $pdf->Ln();
        $enum_ve = 1;
        $sum_ve = 0;
        $pdf->SetFont('Arial', '', 9);
        if (count($efectivo) <= 0) {
            $pdf->Cell(155, 5, utf8_decode('No hay registros'), 0, 2, 'C', 0);
        } else {
            foreach ($efectivo as $efec) {
                $pdf->Cell(15, 5, $enum_ve, 0, 0, 'R', 0);
                $pdf->Cell(45, 5, utf8_decode($efec->created_at), 0, 0, 'L', 0);
                $pdf->Cell(70, 5, utf8_decode('FACTURA ' . $efec->num_secuencial), 0, 0, 'L', 0);
                $pdf->Cell(25, 5, utf8_decode($efec->val_total), 0, 0, 'R', 0);
                $pdf->Ln();
                $enum_ve++;
                $sum_ve = $sum_ve + $efec->val_total;
            }
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(130, 5, utf8_decode('TOTAL V. EFECTIVO:'), 'T', 0, 'R', 0);
        $pdf->Cell(25, 5, number_format($sum_ve, 2), 'T', 2, 'R', 0);

        //ventas crédito

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 5, utf8_decode('Ventas a Crédito:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, 'No.', 0, 0, 'C', 1);
        $pdf->Cell(45, 5, utf8_decode('F. H. Registro'), 0, 0, 'C', 1);
        $pdf->Cell(70, 5, utf8_decode('Comprobante'), 0, 0, 'C', 1);
        $pdf->Cell(25, 5, utf8_decode('Valor'), 0, 0, 'C', 1);
        $pdf->Ln();
        $enum_vc = 1;
        $sum_vc = 0;
        $pdf->SetFont('Arial', '', 9);
        if (count($credito) <= 0) {
            $pdf->Cell(155, 5, utf8_decode('No hay registros'), 0, 2, 'C', 0);
        } else {
            foreach ($credito as $cred) {
                $pdf->Cell(15, 5, $enum_vc, 0, 0, 'R', 0);
                $pdf->Cell(45, 5, utf8_decode($cred->created_at), 0, 0, 'L', 0);
                $pdf->Cell(70, 5, utf8_decode('FACTURA ' . $cred->num_secuencial), 0, 0, 'L', 0);
                $pdf->Cell(25, 5, utf8_decode($cred->val_total), 0, 0, 'R', 0);
                $pdf->Ln();
                $enum_vc++;
                $sum_vc = $sum_vc + $cred->val_total;
            }
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(130, 5, utf8_decode('TOTAL V. CRÉDITO:'), 'T', 0, 'R', 0);
        $pdf->Cell(25, 5, number_format($sum_vc, 2), 'T', 2, 'R', 0);

        //egresos
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 5, utf8_decode('Egresos Diarios:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, 'No.', 0, 0, 'C', 1);
        $pdf->Cell(30, 5, utf8_decode('Beneficiario'), 0, 0, 'C', 1);
        $pdf->Cell(85, 5, utf8_decode('Descripción'), 0, 0, 'C', 1);
        $pdf->Cell(25, 5, utf8_decode('Valor'), 0, 0, 'C', 1);
        $pdf->Cell(35, 5, utf8_decode('Fec. Registro'), 0, 0, 'C', 1);
        $pdf->Ln();
        $enum = 1;
        $sume = 0;
        $pdf->SetFont('Arial', '', 9);
        if (count($egresos) <= 0) {
            $pdf->Cell(190, 5, utf8_decode('No hay registros'), 0, 2, 'C', 0);
        } else {
            foreach ($egresos as $egreso) {
                $pdf->Cell(15, 5, $enum, 0, 0, 'R', 0);
                $pdf->Cell(30, 5, utf8_decode($egreso->beneficiario), 0, 0, 'L', 0);
                $pdf->Cell(85, 5, utf8_decode($egreso->descripcion), 0, 0, 'L', 0);
                $pdf->Cell(25, 5, utf8_decode($egreso->valor), 0, 0, 'R', 0);
                $pdf->Cell(35, 5, utf8_decode($egreso->created_at), 0, 0, 'L', 0);
                $pdf->Ln();
                $enum++;
                $sume = $sume + $egreso->valor;
            }
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(130, 5, 'TOTAL EGRESO:', 'T', 0, 'R', 0);
        $pdf->Cell(25, 5, number_format($sume, 2), 'T', 1, 'R', 0);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'L', 0);
        $pdf->Cell(50, 5, utf8_decode('Responsable de Caja'), 'T', 0, 'C', 0);
        $pdf->Cell(70, 5, utf8_decode(''), 0, 0, 'L', 0);
        $pdf->Cell(50, 5, utf8_decode('Firma Supervisor'), 'T', 1, 'C', 0);
        $pdf->Cell(10, 5, utf8_decode(''), 0, 0, 'L', 0);
        $pdf->Cell(50, 5, utf8_decode($arqueo->responsable), 0, 0, 'L', 0);


        $pdf->Output('Arqueo ' . $arqueo->created_at . ".pdf", "I");
    }

    public function rolPago($datos)
    {
        ob_end_clean();

        $pdf = new OwnPdf('P', 'mm', 'A4');
        $fecha_actual = date("Y-m-d");
        $fecha = strtotime($datos->created_at);
        $anio = date("Y", $fecha);
        $pdf->AddPage();

        //header
        if (file_exists($datos->logo)) {
            $pdf->Image($datos->logo, 10, 7, 40, 30);
        }
        $pdf->RoundedRect(10, 10, 190, 20, 1, '1234', 'D');
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 8, utf8_decode($datos->nom_comercial), 0, 1, 'C', 0);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(190, 6, 'ROL DE PAGOS', 0, 1, 'C', 0);
        $pdf->Cell(190, 6, 'CORRESPONDIENTE A ' . $datos->mes . ' DEL ' . $anio, 0, 2, 'C', 0);
        $pdf->Ln();

        //HEADER FOR TABLE
        $pdf->RoundedRect(10, 35, 190, 10, 2, '1234', 'D');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, 'Fecha Registro:', 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(110, 4, utf8_decode($datos->created_at), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 4, 'Fecha:', 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode($fecha_actual), 0, 1, 'L', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, 'Beneficiario:', 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(110, 4, utf8_decode($datos->apellidos . ' ' . $datos->nombres), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 4, 'C.I.:', 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 4, utf8_decode($datos->num_identificacion), 0, 0, 'L', 0);

        $pdf->RoundedRect(10, 50, 85, 45, 2, '1234', 'D');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->RoundedRect(115, 50, 85, 45, 2, '1234', 'D');

        //CUADRO INGRESOS
        $pdf->SetXY(11, 51);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(83, 4, 'INGRESOS', 'B', 1, 'C', 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 4, 'Salario:', 0, 0, 'L', 0);
        $pdf->Cell(32, 4, number_format($datos->salario, 2), 0, 1, 'R', 0);
        $pdf->Cell(50, 4, 'Porcentaje sobre venta mensual:', 0, 0, 'L', 0);
        $pdf->Cell(32, 4, number_format(($datos->por_venta / 100) * $datos->ven_mensual, 2), 0, 1, 'R', 0);
        $pdf->SetXY(11, 90);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(55, 4, 'TOTAL INGRESOS:', 0, 0, 'L', 0);
        $pdf->Cell(25, 4, '$ ' . number_format($datos->tot_recibir, 2), 'T', 0, 'R', 0);

        // //CUADRO EGRESOS
        $pdf->SetXY(116, 51);
        $pdf->Cell(83, 4, 'EGRESOS', 'B', 1, 'C', 1);
        $pdf->SetXY(116, 90);
        $pdf->Cell(55, 4, 'TOTAL EGRESOS:', 0, 0, 'L', 0);
        $pdf->Cell(25, 4, '$ 0.00', 'T', 0, 'R', 0);

        //CUADRO DE NETO
        $pdf->SetXY(70, 103);
        $pdf->SetFont('Arial', 'B', 10);
        // $pdf->SetFillColor(200, 200, 200);
        $pdf->RoundedRect(70, 100, 60, 10, 2, '1234', 'DF');
        $pdf->Cell(30, 4, 'NETO A RECIBIR:', 0, 0, 'L', 0);
        $pdf->Cell(25, 4, '$ ' . number_format($datos->tot_recibir, 2), 0, 0, 'R', 0);

        //FIRMAS
        $pdf->SetXY(46, 125);
        $pdf->Cell(35, 4, 'APROBADO:', 'T', 0, 'C', 0);
        $pdf->Cell(45, 4, '', 0, 0, 'R', 0);
        $pdf->Cell(35, 4, 'RECIBI CONFORME', 'T', 0, 'C', 0);

        $pdf->Output("rolpago" . $datos->nombres . $datos->apellidos . $datos->mes . $anio . ".pdf", "I");
    }

    public function reporteVentas($empresa, $data, $desde, $hasta)
    {
        ob_end_clean();
        $fecha_actual = date("Y-m-d");
        $pdf = new OwnPdf('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        if (file_exists($empresa->logo)) {
            $pdf->Image($empresa->logo, 10, 7, 45, 35);
        }
        $pdf->RoundedRect(10, 10, 275, 25, 1, '1234', 'D');
        $pdf->SetXY(75, 10);
        $pdf->Cell(155, 6, utf8_decode($data[0]->nom_comercial . ' - ' . $data[0]->nom_referencia), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de Ventas'), 0, 2, 'C', 0);
        //prueba
        $pdf->SetXY(230, 11);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(250, 11);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 6, $fecha_actual, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $desde, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $hasta, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        //HEADER FOR TABLE
        $pdf->Ln();
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(10, 5, 'No.', 1, 0, 'C', 1);
        $pdf->Cell(80, 5, 'Empresa/Sucursal', 1, 0, 'C', 1);
        $pdf->Cell(65, 5, 'Cliente', 1, 0, 'C', 1);
        $pdf->Cell(35, 5, 'Comprobante', 1, 0, 'C', 1);
        $pdf->Cell(30, 5, 'Fec. Realizada', 1, 0, 'C', 1);
        $pdf->Cell(25, 5, 'Forma Pago', 1, 0, 'C', 1);
        $pdf->Cell(25, 5, 'Total', 1, 0, 'C', 1);
        $pdf->Ln();

        //fill cells
        $ttot = 0;
        $cont = 1;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        if (count($data) <= 0) {
            $pdf->Cell(275, 5, utf8_decode('No hay registros'), 1, 2, 'C', 0);
        } else {
            foreach ($data as $detail) {
                $pdf->Cell(10, 4, $cont, 1, 0, 'R', 1);
                $pdf->Cell(80, 4, utf8_decode($detail->nom_comercial . ' - ' . $detail->nom_referencia), 1, 0, 'L', 1);
                $pdf->Cell(65, 4, utf8_decode($detail->cliente), 1, 0, 'L', 1);
                $pdf->Cell(35, 4, 'FAC - ' . $detail->num_secuencial, 1, 0, 'L', 1);
                $pdf->Cell(30, 4, $detail->created_at, 1, 0, 'L', 1);
                if ($detail->for_pago == 'E') {
                    $pdf->Cell(25, 4, utf8_decode('Efectivo'), 1, 0, 'L', 1);
                } else if ($detail->for_pago == 'C') {
                    $pdf->Cell(25, 4, utf8_decode('Crédito'), 1, 0, 'L', 1);
                }
                $pdf->Cell(25, 4, $detail->val_total, 1, 0, 'R', 1);
                $pdf->Ln();
                $ttot = $detail->val_total + $ttot;
                $cont++;
            }
        }
        //footer
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(245, 6, 'TOTAL:', 1, 0, 'R', 1);
        $pdf->Cell(25, 6, number_format($ttot, 2), 1, 0, 'R', 1);

        $pdf->Output("reporte-ventas", "I");
    }

    public function reporteCompras($empresa, $data, $desde, $hasta)
    {
        ob_end_clean();
        $fecha_actual = date("Y-m-d");
        $pdf = new OwnPdf('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        if (file_exists($empresa->logo)) {
            $pdf->Image($empresa->logo, 10, 7, 45, 35);
        }
        $pdf->RoundedRect(10, 10, 275, 25, 1, '1234', 'D');
        $pdf->SetXY(75, 10);
        $pdf->Cell(155, 6, utf8_decode($data[0]->nom_comercial), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de Compras'), 0, 2, 'C', 0);
        //prueba
        $pdf->SetXY(230, 11);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(250, 11);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 6, $fecha_actual, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $desde, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $hasta, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        //HEADER FOR TABLE
        $pdf->Ln();
        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(10, 5, 'No.', 1, 0, 'C', 1);
        $pdf->Cell(80, 5, 'Empresa/Sucursal', 1, 0, 'C', 1);
        $pdf->Cell(55, 5, 'Proveedor', 1, 0, 'C', 1);
        $pdf->Cell(35, 5, 'Comprobante', 1, 0, 'C', 1);
        $pdf->Cell(30, 5, 'Fec. Ingreso', 1, 0, 'C', 1);
        $pdf->Cell(25, 5, 'Forma Pago', 1, 0, 'C', 1);
        $pdf->Cell(25, 5, 'Total', 1, 0, 'C', 1);
        $pdf->Cell(15, 5, 'Estado', 1, 0, 'C', 1);
        $pdf->Ln();

        //fill cells
        $ttot = 0;
        $cont = 1;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        if (count($data) <= 0) {
            $pdf->Cell(275, 5, utf8_decode('No hay registros'), 1, 2, 'C', 0);
        } else {
            foreach ($data as $detail) {
                $pdf->Cell(10, 4, $cont, 1, 0, 'R', 1);
                $pdf->Cell(80, 4, utf8_decode($detail->nom_comercial . ' - ' . $detail->nom_referencia), 1, 0, 'L', 1);
                $pdf->Cell(55, 4, utf8_decode($detail->proveedor), 1, 0, 'L', 1);
                $pdf->Cell(35, 4, $detail->tip_comprobante . ' - ' . $detail->num_comprobante, 1, 0, 'L', 1);
                $pdf->Cell(30, 4, $detail->created_at, 1, 0, 'L', 1);
                if ($detail->for_pago == 'E') {
                    $pdf->Cell(25, 4, utf8_decode('Efectivo'), 1, 0, 'L', 1);
                } else if ($detail->for_pago == 'C') {
                    $pdf->Cell(25, 4, utf8_decode('Crédito'), 1, 0, 'L', 1);
                }
                $pdf->Cell(25, 4, $detail->total, 1, 0, 'R', 1);
                if ($detail->estado == 1) {
                    $pdf->Cell(15, 4, utf8_decode('Registrado'), 1, 0, 'L', 1);
                } else if ($detail->estado == 0) {
                    $pdf->Cell(15, 4, utf8_decode('Anulado'), 1, 0, 'L', 1);
                }
                $pdf->Ln();
                $ttot = $detail->total + $ttot;
                $cont++;
            }
        }
        //footer
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(235, 6, 'TOTAL:', 1, 0, 'R', 1);
        $pdf->Cell(25, 6, number_format($ttot, 2), 1, 0, 'R', 1);

        $pdf->Output("reporte-compras", "I");
    }

    // PDF Factura Individual
    public function factura($factura, $detalles)
    {
        ob_end_clean();
        $pdf = new OwnPdf('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->AliasNbPages();
        //$pdf->Cell(20);
        if (file_exists($factura->logo)) {
            $pdf->Image($factura->logo, 30, 10, 60, 50);
        }
        $pdf->RoundedRect(110, 10, 90, 71, 2, '1234', 'D');
        $pdf->RoundedRect(10, 50, 97, 31, 2, '1234', 'D');
        //cuadros detalle empresa que emite
        $pdf->Ln(30);
        $pdf->SetXY(10, 50);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(100, 6, utf8_decode($factura->raz_social), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Matriz:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($factura->dir_matriz), 0, 'L');
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Dirección Sucursal:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->MultiCell(70, 5, utf8_decode($factura->dir_establecimiento), 0, 'L');

        ($factura->obli_contabilidad == 1) ? $contabilidad = 'SI' : $contabilidad = 'NO';
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('OBLIGADO A LLEVAR CONTABILIDAD:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(55, 5, utf8_decode('RÉGIMEN MICROEMPRESA:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(45, 5, utf8_decode($contabilidad), 0, 2, 'L', 0);

        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode('R.U.C: ') . $factura->ruc, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetFillColor(125, 234, 134);
        $pdf->Cell(88, 5, utf8_decode('FACTURA '), 0, 2, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $n_est = str_pad($factura->numero, 3, "0", STR_PAD_LEFT);
        $n_pto = str_pad($factura->codigo, 3, "0", STR_PAD_LEFT);
        $n_sec = str_pad($factura->num_secuencial, 9, "0", STR_PAD_LEFT);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('No. ') . $n_est . '-' . $n_pto . '-' . $n_sec, 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($factura->cla_acceso), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(88, 5, utf8_decode('FECHA Y HORA DE AUTORIZACION:'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, utf8_decode($factura->created_at), 0, 2, 'L', 0);
        if ($factura->tip_ambiente == 1) {
            $ambiente = 'PRODUCCION';
        } else {
            $ambiente = 'PRUEBAS';
        }
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('AMBIENTE:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($ambiente), 0, 1, 'L', 0);
        if ($factura->tip_emision == 1) {
            $emision = 'NORMAL';
        } else {
            $emision = 'NORMAL';
        }
        $pdf->SetX(111);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('EMISIÓN:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(68, 5, utf8_decode($emision), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(111);
        $pdf->Cell(88, 5, utf8_decode('CLAVE DE ACCESO'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(88, 5, $factura->cla_acceso, 0, 2, 'L', 0);
        $this->generarCodigoBarras($factura->cla_acceso);
        $pdf->image('archivos/comprobantes/facturas/codigosbarras/codigo' . $factura->cla_acceso . '.png', 115, null, 80);


        $cli_guias = '';

        $pdf->RoundedRect(10, 84, 190, 17, 2, '1234', 'D');
        //cuadro de datos del cliente
        $pdf->SetXY(10, 85);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, utf8_decode('Razón Social / Nombres y Apellidos:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(80, 5, utf8_decode($factura->nombre), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('RUC / CI.:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($factura->num_identificacion), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Dirección:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(115, 5, utf8_decode($factura->dir_cliente), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(30, 5, utf8_decode('Fecha de Emisión:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($factura->fec_emision), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(25, 5, utf8_decode('Guías Remisión:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(30, 5, utf8_decode($cli_guias), 0, 0, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 104);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->Cell(25, 6, utf8_decode('Cod. Principal'), 0, 0, 'C', 1);
        $pdf->Cell(15, 6, utf8_decode('Cant.'), 0, 0, 'C', 1);
        $pdf->Cell(75, 6, utf8_decode('Descripción'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Precio Unitario'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Descuento'), 0, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode('Precio Total'), 0, 0, 'C', 1);
        $pdf->Ln();

        //rellenado de campos
        $pdf->SetFont('Helvetica', '', 8);
        foreach ($detalles as $a => $b) {
            $pdf->Cell(25, 5, $b->cod_principal, 0, 0, 'L', 0);
            $pdf->Cell(15, 5, $b->det_cantidad, 0, 0, 'R', 0);
            $pdf->Cell(75, 5, $b->nombre, 0, 0, 'L', 0);
            $pdf->Cell(25, 5, number_format($b->pre_venta, 3), 0, 0, 'R', 0);
            $pdf->Cell(25, 5, number_format($b->det_descuento, 3), 0, 0, 'R', 0);
            $pdf->Cell(25, 5, number_format($b->det_total, 2), 0, 1, 'R', 0);
        }
        $posY = $pdf->GetY();
        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(110, 5, utf8_decode('Información Adicional'), 'TLR', 1, 'C', 1);
        $pdf->Cell(15, 5, utf8_decode('Correo:'), 'L', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($factura->email), 'R', 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Vendedor:'), 'LB', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($factura->vendedor), 'RB', 1, 'L', 0);

        //foreach ($factura->forma as $e => $f) {
        if ($factura->forma == '01') {
            $formaPago = 'Sin utilizacion del sistema financiero';
        }
        if ($factura->forma == '15') {
            $formaPago = 'Compensacion de deudas';
        }
        if ($factura->forma == '16') {
            $formaPago = 'Tarjeta debito';
        }
        if ($factura->forma == '17') {
            $formaPago = 'Dinero Electronico';
        }
        if ($factura->forma == '18') {
            $formaPago = 'Tarjeta Prepago';
        }
        if ($factura->forma == '19') {
            $formaPago = 'Tarjeta de credito';
        }
        if ($factura->forma == '20') {
            $formaPago = 'Otros con utilizacion del sistema financiero';
        }
        if ($factura->forma == '21') {
            $formaPago = 'Endoso de titulos';
        }
        //}
        $pdf->Ln();
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(110, 5, utf8_decode('Forma de Pago'), 'LTR', 1, 'C', 1);
        $pdf->Cell(15, 5, utf8_decode('Forma:'), 'L', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($formaPago), 'R', 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Valor:'), 'L', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($factura->val_total), 'R', 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Plazo:'), 'L', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($formaPago), 'R', 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Tiempo:'), 'LB', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(95, 5, utf8_decode($formaPago), 'BR', 1, 'L', 0);

        $pdf->SetFillColor(200, 200, 200);
        $pdf->SetXY(125, $posY);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL 12%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->sub_iva, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL 0%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->sub_0, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL NO OBJETO DE IVA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->sub_no_iva, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL EXENTO DE IVA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->sub_exento, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'SUBTOTAL SIN IMPUESTOS', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->sub_sin_imp, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'DESCUENTO', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->tot_descuento, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'ICE', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->val_ice, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, '12%', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->val_iva, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'IRBPNR', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->val_irbpnr, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(50, 5, 'PROPINA', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->val_propina, 0, 1, 'R', 1);
        $pdf->SetX(125);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(125);
        $pdf->Cell(50, 5, 'VALOR TOTAL', 0, 0, 'L', 1);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(25, 5, $factura->val_total, 0, 1, 'R', 1);
        $pdf->Output('FACT-' . $factura->cla_acceso . '.pdf', 'I');
    }

    public function generarCodigoBarras($claveAcceso, $tipo)
    {
        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);
        $code = new BCGcode128();
        $code->setScale(2);
        $code->setThickness(30);
        $code->setForegroundColor($colorFront);
        $code->setBackgroundColor($colorBack);
        $code->parse($claveAcceso);
        if (!file_exists('archivos/comprobantes/' . $tipo . '/codigosbarras/')) {
            mkdir('archivos/comprobantes/' . $tipo . '/codigosbarras/', 0777, true);
        }
        $drawing = new BCGDrawing('archivos/comprobantes/' . $tipo . '/codigosbarras/codigo' . $claveAcceso . '.png', $colorBack);
        $drawing->setBarcode($code);
        $drawing->draw();
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
        //$this->redim('archivos/comprobantes/facturas/codigosbarras/codigo' . $claveAcceso . '.png', 'archivos/comprobantes/facturas/codigosbarras/codigo_' . $claveAcceso . '.png', 1000, 200);
    }
    public function redim($ruta1, $ruta2, $ancho, $alto)
    {
        # se obtene la dimension y tipo de imagen 
        $datos = getimagesize($ruta1);

        $ancho_orig = $datos[0]; # Anchura de la imagen original 
        $alto_orig = $datos[1];    # Altura de la imagen original 
        $tipo = $datos[2];

        if ($tipo == 1) { # GIF 
            if (function_exists("imagecreatefromgif"))
                $img = imagecreatefromgif($ruta1);
            else
                return false;
        } else if ($tipo == 2) { # JPG 
            if (function_exists("imagecreatefromjpeg"))
                $img = imagecreatefromjpeg($ruta1);
            else
                return false;
        } else if ($tipo == 3) { # PNG 
            if (function_exists("imagecreatefrompng"))
                $img = imagecreatefrompng($ruta1);
            else
                return false;
        }

        # Se calculan las nuevas dimensiones de la imagen 
        if ($ancho_orig > $alto_orig) {
            $ancho_dest = $ancho;
            $alto_dest = ($ancho_dest / $ancho_orig) * $alto_orig;
        } else {
            $alto_dest = $alto;
            $ancho_dest = ($alto_dest / $alto_orig) * $ancho_orig;
        }

        // imagecreatetruecolor, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        $img2 = @imagecreatetruecolor($ancho_dest, $alto_dest) or $img2 = imagecreate($ancho_dest, $alto_dest);

        // Redimensionar 
        // imagecopyresampled, solo estan en G.D. 2.0.1 con PHP 4.0.6+ 
        @imagecopyresampled($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig) or imagecopyresized($img2, $img, 0, 0, 0, 0, $ancho_dest, $alto_dest, $ancho_orig, $alto_orig);

        // Crear fichero nuevo, según extensión. 
        if ($tipo == 1) // GIF 
            if (function_exists("imagegif"))
                imagegif($img2, $ruta2);
            else
                return false;

        if ($tipo == 2) // JPG 
            if (function_exists("imagejpeg"))
                imagejpeg($img2, $ruta2);
            else
                return false;

        if ($tipo == 3)  // PNG 
            if (function_exists("imagepng"))
                imagepng($img2, $ruta2);
            else
                return false;

        return true;
    }
}
