<?php

include_once 'lib/fpdf/fpdf.php';
include_once 'myPDF.php';
include_once getenv("FILE_CONFIG_PHP");
class generarReportes
{
    public function factura_reporte($datos, $modo, $fec_ini, $fec_fin)
    {
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            $pdf->Image($logo, 12, 12, 55, 20);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        $pdf->RoundedRect(10, 10, 275, 25, 2, '1234', 'D');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(75, 10);
        $pdf->Cell(155, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $modo == 1
        ? $pdf->Cell(155, 8, utf8_decode('Reporte de Ventas'), 0, 2, 'C', 0)
        : $pdf->Cell(155, 8, utf8_decode('Reporte de proformas'), 0, 2, 'C', 0);
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
        if ($fec_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini != null && $fec_fin != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(20, 6, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        $pdf->SetY(40);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(6, 6, 'No.', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'Fecha', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'Tipo', 1, 0, 'C', 1);
        $modo == 1
        ? $pdf->Cell(15, 6, 'No. Fac', 1, 0, 'C', 1)
        : $pdf->Cell(15, 6, 'No. Prof', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'Serie', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'RUC/Cedula', 1, 0, 'C', 1);
        $pdf->Cell(50, 6, 'NOMBRE', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'BASE 0%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'BASE 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.V.A. 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'Trans.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'Dsctos.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'IVA Ret', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.R.F.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'TOTAL', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'VEND.', 1, 0, 'C', 1);
        $pdf->ln();

        $totalf = 0;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        $contador = 1;
        $tbasec = 0;
        $tbased = 0;
        $tivad = 0;
        $tdesc = 0;
        $tivaret = 0;
        $tirf = 0;
        $ttra = 0;
        $total = 0;

        $datos_filtrado = [];

        foreach ($datos as $key => $value) {
            if (!array_key_exists($datos[$key]->id_factura, $datos_filtrado)) {
                $datos_filtrado[$datos[$key]->id_factura] = $datos[$key];
            }
        }

        foreach ($datos_filtrado as $detail) {
            $pdf->Cell(6, 6, $contador, 1, 0, 'C', 1);
            $pdf->Cell(20, 6, $detail->fecha_emision, 1, 0, 'C', 1);
            $modo == 1 ? $pdf->Cell(15, 6, 'FAC', 1, 0, 'C', 1) : $pdf->Cell(15, 6, 'PROF', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->id_factura, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, '00' . $detail->id_punto_emision . '00' . $detail->id_establecimiento, 1, 0, 'C', 1);
            $pdf->Cell(20, 6, $detail->identificacion, 1, 0, 'C', 1);
            $pdf->Cell(50, 6, $detail->nombre, 1, 0, 'L', 1);
            $pdf->Cell(15, 6, $detail->subtotal_0, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->subtotal_12, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->iva_12, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->descuento, 1, 0, 'C', 1);
            if ($detail->cantidadiva != 0 && $detail->cantidadrenta != 0) {
                $cantiva = $detail->cantidadiva;
                $cantrent = $detail->cantidadrenta;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            } else {
                $cantiva = 0.00;
                $cantrent = 0.00;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            }
            $totdesc = $detail->descuento + $detail->cantidadiva + $detail->cantidadrenta;
            $totalf = $detail->valor_total - $totdesc;
            $pdf->Cell(15, 6, $totalf, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->vendedor, 1, 0, 'C', 1);
            $pdf->Ln();
            $tbasec = $detail->subtotal_0 + $tbasec;
            $tbased = $tbased + $detail->subtotal_12;
            $tivad = $tivad + $detail->iva_12;
            $tdesc = $tdesc + $detail->descuento;
            $tivaret = $tivaret + $detail->cantidadiva;
            $tirf = $tirf + $detail->cantidadrenta;
            $total = $total + $totalf;
            $contador++;
            //$total += $detail->unit_price * $detail->quantity;
        }
        $pdf->SetFillColor(108, 154, 216);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(141, 6, 'TOTAL:', 1, 0, 'R', 0);
        $pdf->Cell(15, 6, $tbasec, 1, 0, 'C', 0);
        $pdf->Cell(15, 6, $tbased, 1, 0, 'C', 0);
        $pdf->Cell(15, 6, $tivad, 1, 0, 'C', 0);
        $pdf->Cell(15, 6, $ttra, 1, 0, 'C', 0);
        $pdf->Cell(15, 6, $tdesc, 1, 0, 'C', 0);
        $pdf->Cell(15, 6, $tivaret, 1, 0, 'C', 0);
        $pdf->Cell(15, 6, $tirf, 1, 0, 'C', 0);
        $pdf->Cell(15, 6, $total, 1, 0, 'C', 0);

        return $modo == 1 ? $pdf->Output('reporte-facturas-ventas.pdf', 'D') : $pdf->Output('reporte-proformas.pdf', 'D');
    }

    public function factura_compra_reporte($datos, $fec_ini, $fec_fin)
    {
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->RoundedRect(10, 10, 275, 25, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            $pdf->Image($logo, 12, 12, 55, 20);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(75, 10);
        $pdf->Cell(155, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de compras'), 0, 2, 'C', 0);

        $pdf->SetXY(230, 11);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(250, 11);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 6, $fecha_actual, 0, 2, 'R', 0);
        if ($fec_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini != null && $fec_fin != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(20, 6, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        $pdf->SetY(40);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(6, 6, 'No.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'FECHA', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'RUC', 1, 0, 'C', 1);
        $pdf->Cell(45, 6, 'PROVEEDOR', 1, 0, 'C', 1);
        $pdf->Cell(12, 6, 'TIPO', 1, 0, 'C', 1);
        $pdf->Cell(12, 6, 'FAC.PROV.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'BASE 0%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'BASE 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'DESCTO.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.V.A. 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'IVA Ret', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.R.F.', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'BASEIMPEXE', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'BASENOGRAVAIVA', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'OTROS', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'TOTAL', 1, 0, 'C', 1);
        $pdf->ln();

        $total = 0;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        $contador = 1;
        $tbasec = 0;
        $tbased = 0;
        $tivad = 0;
        $tdesc = 0;
        $tivaret = 0;
        $tirf = 0;
        $total = 0;
        $datos_filtrado = [];

        foreach ($datos as $key => $value) {
            if (!array_key_exists($datos[$key]->id_factcompra, $datos_filtrado)) {
                $datos_filtrado[$datos[$key]->id_factcompra] = $datos[$key];
            }
        }

        foreach ($datos_filtrado as $detail) {
            $pdf->Cell(6, 6, $contador, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->fech_emision, 1, 0, 'C', 1);
            $pdf->Cell(25, 6, $detail->identif_proveedor, 1, 0, 'C', 1);
            $pdf->Cell(45, 6, $detail->nombre_proveedor, 1, 0, 'L', 1);
            $pdf->Cell(12, 6, 'Factura', 1, 0, 'C', 1);
            $pdf->Cell(12, 6, $detail->documento_tributario, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->subtotal_0, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->subtotal_12, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->descuento, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->iva_12, 1, 0, 'C', 1);
            if ($detail->cantidadiva != 0 && $detail->cantidadrenta != 0) {
                $cantiva = $detail->cantidadiva;
                $cantrent = $detail->cantidadrenta;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            } else {
                $cantiva = 0.00;
                $cantrent = 0.00;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            }
            $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->total_factura, 1, 0, 'C', 1);
            $pdf->Ln();
            $tbasec = $tbasec + $detail->subtotal_0;
            $tbased = $tbased + $detail->subtotal_12;
            $tdesc = $tdesc + $detail->descuento;
            $tivad = $tivad + $detail->iva_12;
            $tivaret = $tivaret + $detail->cantidadiva;
            $tirf = $tirf + $detail->cantidadrenta;
            $total = $total + $detail->total_factura;
            $contador++;
            //$total += $detail->unit_price * $detail->quantity;
        }
        //footer table
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(115, 6, 'TOTAL:', 1, 0, 'R', 1);
        $pdf->Cell(15, 6, $tbasec, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tbased, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tdesc, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tivad, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tivaret, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tirf, 1, 0, 'C', 1);
        $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, '0.00', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $total, 1, 0, 'C', 1);

        $pdf->Output("facturacompras.pdf", "D");
    }
    public function cuenta_pagar_reporte($datos, $fec_ini, $fec_fin,$vencido)
    {
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->RoundedRect(10, 10, 275, 25, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            $pdf->Image($logo, 12, 12, 55, 20);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(75, 10);
        $pdf->Cell(155, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de Cuentas por Pagar'), 0, 2, 'C', 0);

        $pdf->SetXY(230, 11);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(250, 11);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 6, $fecha_actual, 0, 2, 'R', 0);
        //dd("FEcha inicial:",$fec_ini,"FEcha final:",$fec_fin);
        if ($fec_ini !== null && $fec_fin === null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini !== null && $fec_fin !== null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(20, 6, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);
        
        
        $pdf->SetXY(60,40);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(6, 6, 'No.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'FECHA', 1, 0, 'C', 1);
        $pdf->Cell(25, 6, 'RUC', 1, 0, 'C', 1);
        $pdf->Cell(45, 6, 'PROVEEDOR', 1, 0, 'C', 1);
        //$pdf->Cell(12, 6, 'TIPO', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'No CUOTA', 1, 0, 'C', 1);
        $pdf->Cell(16, 6, 'VALOR CUOTA', 1, 0, 'C', 1);
        
            $pdf->Cell(18, 6, 'VALOR PAGADO', 1, 0, 'C', 1);
            $pdf->Cell(25, 6, 'PAGADO POR', 1, 0, 'C', 1);
            $pdf->Cell(23, 6, 'FORMA PAGO', 1, 0, 'C', 1);
        
        
        /*$pdf->Cell(15, 6, 'BASE 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'DESCTO.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.V.A. 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'IVA Ret', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.R.F.', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'BASEIMPEXE', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'BASENOGRAVAIVA', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'OTROS', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'TOTAL', 1, 0, 'C', 1);*/
        $pdf->ln();

        $total = 0;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        
        $datos_filtrado = [];
        foreach ($datos as $key => $value) {
            if (!array_key_exists($datos[$key]->clave_acceso, $datos_filtrado)) {
                $datos_filtrado[$datos[$key]->clave_acceso] = $datos[$key];
            }
        }
        
        $pago_vencido=0;
        $contador = 1;
        $tbasec = 0;
        $tbased = 0;
        $tivad = 0;
        $tdesc = 0;
        $tivaret = 0;
        $tirf = 0;
        $total = 0;
        $suma_cuota=0;
        $suma_pagado=0;
        foreach($datos as $detail){
            $fecha_pago=date("d/m/Y", strtotime($detail->fecha_pago));
            if($fecha_actual>=$fecha_pago){
                $pago_vencido+=$detail->valor_cuota;
            }
            $total += $detail->valor_cuota;
            $tirf += $detail->valor_pago;
        }
        
        foreach ($datos as $detail) {
            $pdf->SetX(60);
            $fecha_pago=date("d/m/Y", strtotime($detail->fecha_pago));
            $pdf->Cell(6, 6, $contador, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $fecha_pago, 1, 0, 'C', 1);
            $pdf->Cell(25, 6, $detail->identif_proveedor, 1, 0, 'C', 1);
            $pdf->Cell(45, 6, $detail->nombre_proveedor, 1, 0, 'L', 1);
            //$pdf->Cell(12, 6, 'Factura', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->num_cuota, 1, 0, 'C', 1);
            $pdf->Cell(16, 6, $detail->valor_cuota, 1, 0, 'C', 1);
            
                $pdf->Cell(18, 6, $detail->valor_pago, 1, 0, 'C', 1);
                if($detail->valor_pago!=0){
                    $pdf->Cell(25, 6,utf8_decode($detail->pagos_por), 1, 0, 'C', 1);
                }else{
                    $pdf->Cell(25, 6, '', 1, 0, 'C', 1);
                }
                
                
                if($detail->valor_pago!=0){
                    $pdf->Cell(23, 6, utf8_decode($detail->descripcion), 1, 0, 'C', 1);
                }else{
                    $pdf->Cell(23, 6, '', 1, 0, 'C', 1);
                }
                
            
            
            /*$pdf->Cell(15, 6, $detail->subtotal_12, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->descuento, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->iva_12, 1, 0, 'C', 1);
            if ($detail->cantidadiva != 0 && $detail->cantidadrenta != 0) {
                $cantiva = $detail->cantidadiva;
                $cantrent = $detail->cantidadrenta;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            } else {
                $cantiva = 0.00;
                $cantrent = 0.00;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            }
            $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->total_factura, 1, 0, 'C', 1);
            $pdf->Ln();
            $tbasec = $tbasec + $detail->subtotal_0;
            $tbased = $tbased + $detail->subtotal_12;
            $tdesc = $tdesc + $detail->descuento;
            $tivad = $tivad + $detail->iva_12;
            $tivaret = $tivaret + $detail->cantidadiva;
            $tirf = $tirf + $detail->cantidadrenta;
            $total = $total + $detail->total_factura;*/
            $pdf->Ln();
            $contador++;

            
        }
        $total_vecido=0;
        $total_vecido=$vencido[0]->suma-$tirf;
        $suma_cuota=number_format($total,2,",",".");
        $suma_pagado=number_format($tirf,2,",",".");
        $suma_vencido=number_format($total_vecido,2,",",".");
        //footer table
        $pdf->SetX(60);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(106, 6, 'TOTAL:', 1, 0, 'R', 1);
        /*$pdf->Cell(15, 6, $tbasec, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tbased, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tdesc, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tivad, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tivaret, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tirf, 1, 0, 'C', 1);
        $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, '0.00', 1, 0, 'C', 1);*/
        $pdf->Cell(16, 6, $suma_cuota, 1, 0, 'C', 1);
        
            $pdf->Cell(18, 6, $suma_pagado, 1, 0, 'C', 1);
        
        
        if($suma_vencido!==0 && $total>$suma_pagado){
            $pdf->Cell(25, 6, 'TOTAL VENCIDO:', 1, 0, 'C', 1);
        }else{
            $pdf->Cell(25, 6, '', 1, 0, 'C', 1);
        }
        if($suma_vencido!==0 && $total>$suma_pagado){
            $pdf->SetTextColor(220,50,50);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(23, 6, $suma_vencido, 1, 0, 'C', 1);
        }else{
            $pdf->Cell(23, 6, '', 1, 0, 'C', 1);
        }
        
        //$pdf->Cell(25, 6, 'TOTAL VENCIDO:', 1, 0, 'C', 1);
        /*$pdf->SetFillColor(220,50,50);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(18, 6, $pago_vencido, 1, 0, 'C', 1);*/
        $pdf->Output("cuenta_por_pagar.pdf", "D");
    }
    public function cuenta_pagar_reporte_proveedor($datos, $fec_ini, $fec_fin,$vencido)
    {
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->RoundedRect(10, 10, 275, 25, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            $pdf->Image($logo, 12, 12, 55, 20);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(75, 10);
        $pdf->Cell(155, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de Cuentas por Pagar'), 0, 2, 'C', 0);

        $pdf->SetXY(230, 11);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(250, 11);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 6, $fecha_actual, 0, 2, 'R', 0);
        //dd("FEcha inicial:",$fec_ini,"FEcha final:",$fec_fin);
        if ($fec_ini !== null && $fec_fin === null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini !== null && $fec_fin !== null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(20, 6, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);
        
        
        $pdf->SetXY(10,45);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 8);
        //$pdf->Cell(6, 6, 'No.', 1, 0, 'C', 1);
        //$pdf->Cell(15, 6, 'FECHA', 1, 0, 'C', 1);
        $pdf->Cell(50, 6, 'IDENTIFICACION', 1, 0, 'C', 0);
        $pdf->Cell(75, 6, 'PROVEEDOR', 1, 0, 'C', 0);
        //$pdf->Cell(12, 6, 'TIPO', 1, 0, 'C', 0);
        //$pdf->Cell(15, 6, 'No CUOTA', 1, 0, 'C', 0);
        $pdf->Cell(50, 6, 'VALOR FACTURA', 1, 0, 'C', 0);
        
            $pdf->Cell(50, 6, 'ABONO', 1, 0, 'C', 0);
            $pdf->Cell(50, 6, 'SALDO', 1, 0, 'C', 0);
            //$pdf->Cell(23, 6, 'FORMA PAGO', 1, 0, 'C', 1);
        
        
        /*$pdf->Cell(15, 6, 'BASE 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'DESCTO.', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.V.A. 12%', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'IVA Ret', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'I.R.F.', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'BASEIMPEXE', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, 'BASENOGRAVAIVA', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'OTROS', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, 'TOTAL', 1, 0, 'C', 1);*/
        $pdf->ln();

        $total = 0;
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', '', 7);
        
        /*$datos_filtrado = [];
        foreach ($datos as $key => $value) {
            if (!array_key_exists($datos[$key]->clave_acceso, $datos_filtrado)) {
                $datos_filtrado[$datos[$key]->clave_acceso] = $datos[$key];
            }
        }*/
        
        $pago_vencido=0;
        $contador = 1;
        $tbasec = 0;
        $tbased = 0;
        $tivad = 0;
        $tdesc = 0;
        $tivaret = 0;
        $tirf = 0;
        $total = 0;
        $suma_cuota=0;
        $suma_pagado=0;
        $total_saldo=0;
        $suma_saldo=0;
        foreach($datos as $detail){
            $total += $detail->valor_cuota;
            $tirf += $detail->valor_pago;
            $total_saldo += $detail->descuento;
        }
        
        foreach ($datos as $detail) {
            $pdf->SetX(10);
            //$fecha_pago=date("d/m/Y", strtotime($detail->fecha_pago));
            //$pdf->Cell(6, 6, $contador, 1, 0, 'C', 1);
            //$pdf->Cell(15, 6, $fecha_pago, 1, 0, 'C', 1);
            $pdf->Cell(50, 6, $detail->identif_proveedor, 1, 0, 'C', 0);
            $pdf->Cell(75, 6, $detail->nombre_proveedor, 1, 0, 'L', 0);
            //$pdf->Cell(12, 6, 'Factura', 1, 0, 'C', 0);
            //$pdf->Cell(18, 6, $detail->num_cuota, 1, 0, 'C', 0);
            $pdf->Cell(50, 6, $detail->valor_cuota, 1, 0, 'C', 0);
            
                $pdf->Cell(50, 6, $detail->valor_pago, 1, 0, 'C', 0);
               
                $pdf->Cell(50, 6, $detail->descuento, 1, 0, 'C', 1);   
            
            
            /*$pdf->Cell(15, 6, $detail->subtotal_12, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->descuento, 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->iva_12, 1, 0, 'C', 1);
            if ($detail->cantidadiva != 0 && $detail->cantidadrenta != 0) {
                $cantiva = $detail->cantidadiva;
                $cantrent = $detail->cantidadrenta;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            } else {
                $cantiva = 0.00;
                $cantrent = 0.00;
                $pdf->Cell(15, 6, $cantiva, 1, 0, 'C', 1);
                $pdf->Cell(15, 6, $cantrent, 1, 0, 'C', 1);
            }
            $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, '0.00', 1, 0, 'C', 1);
            $pdf->Cell(15, 6, $detail->total_factura, 1, 0, 'C', 1);
            $pdf->Ln();
            $tbasec = $tbasec + $detail->subtotal_0;
            $tbased = $tbased + $detail->subtotal_12;
            $tdesc = $tdesc + $detail->descuento;
            $tivad = $tivad + $detail->iva_12;
            $tivaret = $tivaret + $detail->cantidadiva;
            $tirf = $tirf + $detail->cantidadrenta;
            $total = $total + $detail->total_factura;*/
            $pdf->Ln();
            $contador++;

            
        }
        $total_vecido=0;
        $total_vecido=$vencido[0]->suma-$tirf;
        $suma_cuota=number_format($total,2,",",".");
        $suma_pagado=number_format($tirf,2,",",".");
        $suma_vencido=number_format($total_vecido,2,",",".");
        $suma_saldo=number_format($total_saldo,2,",",".");
        //footer table
        $pdf->SetX(10);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(125, 6, 'TOTALES:', 0, 0, 'C', 0);
        /*$pdf->Cell(15, 6, $tbasec, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tbased, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tdesc, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tivad, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tivaret, 1, 0, 'C', 1);
        $pdf->Cell(15, 6, $tirf, 1, 0, 'C', 1);
        $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
        $pdf->Cell(20, 6, '0.00', 1, 0, 'C', 1);
        $pdf->Cell(15, 6, '0.00', 1, 0, 'C', 1);*/
        $pdf->Cell(50, 6, $suma_cuota, 0, 0, 'C', 0);
        
            $pdf->Cell(50, 6, $suma_pagado, 0, 0, 'C', 0);
        
            $pdf->Cell(50, 6, $suma_saldo, 0, 0, 'C', 0);
        
       
        //$pdf->Cell(25, 6, 'TOTAL VENCIDO:', 1, 0, 'C', 1);
        /*$pdf->SetFillColor(220,50,50);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(18, 6, $pago_vencido, 1, 0, 'C', 1);*/
        $pdf->Output("cuenta_por_pagar.pdf", "D");
    }
    public function cuenta_pagar_reporte_detalle_factura($datos, $fec_ini, $fec_fin,$vencido,$proveedor){
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            //$pdf->Image($logo, 12, 12, 55, 20);
            $pdf->Image($logo, 12, 11, 45, 18);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
            
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(35, 10);
        $pdf->Cell(155, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de Cuentas por Pagar'), 0, 2, 'C', 0);

        $pdf->SetXY(165, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, $fecha_actual, 0, 2, 'R', 0);
        //dd("FEcha inicial:",$fec_ini,"FEcha final:",$fec_fin);
        if ($fec_ini !== null && $fec_fin === null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini !== null && $fec_fin !== null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(15, 5, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);
        //$pdf->SetXY(80,40);
        $id_prov="";
        $serie1="";
        $serie2="";
        $documento="";
        $resta=0;
        $resta_total=0;
        $fecha_emision="";
        $fecha_vencimiento="";
        $total_factura=0;
        $total_abono=0;
        $total_saldo=0;
        $suma_factura=0;
        $suma_abono=0;
        $suma_saldo=0;
        $totales_factura=0;
        $totales_abono=0;
        $totales_saldo=0;
        $sumatot_factura=0;
        $sumatot_abono=0;
        $sumatot_saldo=0;
        $pdf->Ln();
        foreach($proveedor as $detail){
            $pdf->SetX(55);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 6, 'IDENTIFICACION', 0, 0, 'C', 0);
            $pdf->Cell(25, 6, $detail->identif_proveedor, 0, 0, 'R', 0);
            $pdf->Cell(45, 6, $detail->nombre_proveedor, 0, 0, 'R', 0);
            $id_prov=$detail->id_proveedor;
            $pdf->Ln(10);
            $pdf->SetX(10);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 8);
            
            $pdf->Cell(27, 6, 'No Doc', 1, 0, 'C', 0);
            $pdf->Cell(27, 6, 'Serie', 1, 0, 'C', 0);
            $pdf->Cell(27, 6, 'Fecha Emision', 1, 0, 'C', 0);
            $pdf->Cell(28, 6, 'Fecha Vencimiento', 1, 0, 'C', 0);
            $pdf->Cell(27, 6, 'Valor Facturado', 1, 0, 'C', 0);
            $pdf->Cell(27, 6, 'Abonos', 1, 0, 'C', 0);
            $pdf->Cell(27, 6, 'Saldos', 1, 0, 'C', 0);
            $pdf->Ln();
            $diferencia1="";
            $diferencia2="";
            $diferencia3="";
            foreach($datos as $detail){
                if($detail->id_proveedor==$id_prov){
                    if($detail->saldo!=0){
                    $pdf->SetX(10);
                    $resta=$detail->saldo;
                    $resta_total=number_format($resta,2,",",".");
                    $pdf->SetFillColor(240, 240, 240);
                    $pdf->SetFont('Arial', '', 7);
                    $serie1=substr($detail->observacion,24,3);
                    $serie2=substr($detail->observacion,27,3);
                    $documento=substr($detail->observacion,30,9);
                    $fecha_emision=date("d/m/Y", strtotime($detail->fech_emision));
                    $fecha_vencimiento=date("d/m/Y", strtotime($detail->fecha_pago));
                    $diferencia1=strtotime($fecha_vencimiento);
                    //$diferencia2=$fecha_actual->diff( $diferencia1);
                    //$diferencia3=date_diff($fecha_actual,$fecha_actual);
                    //$pdf->Cell(12, 6, 'Factura', 1, 0, 'C', 0);
                    //$pdf->Cell(18, 6, $detail->num_cuota, 1, 0, 'C', 0);
                     
                    if($diferencia1>$fecha_actual){
                        $pdf->SetTextColor(240, 0, 0);
                        $pdf->Cell(27, 6, $documento, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $serie1.'-'.$serie2, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $fecha_emision, 1, 0, 'C', 0);
                        $pdf->Cell(28, 6, $fecha_vencimiento, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $detail->valor_cuota, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $detail->valor_pagado, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $resta_total, 1, 0, 'C', 0);
                        $pdf->Ln();
                    }else{
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Cell(27, 6, $documento, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $serie1.'-'.$serie2, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $fecha_emision, 1, 0, 'C', 0);
                        $pdf->Cell(28, 6, $fecha_vencimiento, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $detail->valor_cuota, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $detail->valor_pagado, 1, 0, 'C', 0);
                        $pdf->Cell(27, 6, $resta_total, 1, 0, 'C', 0);
                        $pdf->Ln();
                    }
                    
                    
                    $total_factura+=$detail->valor_cuota;
                        $total_abono+=$detail->valor_pagado;
                       $total_saldo+=$resta;
                       $totales_factura+=$detail->valor_cuota;
                       $totales_abono+=$detail->valor_pagado;
                       $totales_saldo+=$resta;
                }
                    
                }
            }

            $pdf->SetX(10);
            $suma_factura=number_format($total_factura,2,",",".");
            $suma_abono=number_format($total_abono,2,",",".");
            $suma_saldo=number_format($total_saldo,2,",",".");
            $sumatot_factura=number_format($totales_factura,2,",",".");
            $sumatot_abono=number_format($totales_abono,2,",",".");
            $sumatot_saldo=number_format($totales_saldo,2,",",".");
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(109, 6, 'Total', 'T', 0, 'C', 0);
            $pdf->Cell(27, 6, $suma_factura, 'T', 0, 'C', 0);
            $pdf->Cell(27, 6, $suma_abono, 'T', 0, 'C', 0);
            $pdf->Cell(27, 6, $suma_saldo, 'T', 0, 'C', 0);
            $total_factura=0;
            $total_abono=0;
            $total_saldo=0;
            $suma_factura=0;
            $suma_abono=0;
            $suma_saldo=0;
            $pdf->Ln(10);

        }
            $pdf->SetX(10);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(109, 6, 'Totales', 0, 0, 'C', 0);
            $pdf->Cell(27, 6, $sumatot_factura, 0, 0, 'C', 0);
            $pdf->Cell(27, 6, $sumatot_abono, 0, 0, 'C', 0);
            $pdf->Cell(27, 6, $sumatot_saldo, 0, 0, 'C', 0);
        $pdf->Output("cuenta_por_pagar.pdf", "D");
    }
    public function cuenta_pagar_reporte_resumen_factura($datos, $fec_ini, $fec_fin,$vencido,$proveedor){
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        
        //$pdf->RoundedRect(10, 10, 275, 25, 2, '1234', 'D');
        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            //$pdf->Image($logo, 12, 12, 55, 20);
            $pdf->Image($logo, 12, 11, 45, 18);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
            
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(35, 10);
        $pdf->Cell(155, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de Cuentas por Pagar'), 0, 2, 'C', 0);

        $pdf->SetXY(165, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, $fecha_actual, 0, 2, 'R', 0);
        //dd("FEcha inicial:",$fec_ini,"FEcha final:",$fec_fin);
        if ($fec_ini !== null && $fec_fin === null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini !== null && $fec_fin !== null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(15, 5, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);
        //$pdf->SetXY(80,40);
        $id_prov="";
        $serie1="";
        $serie2="";
        $documento="";
        $resta=0;
        $resta_total=0;
        $fecha_emision="";
        $fecha_vencimiento="";
        $total_factura=0;
        $total_abono=0;
        $total_saldo=0;
        $suma_factura=0;
        $suma_abono=0;
        $suma_saldo=0;
        $id_fact="";
        $no_doc="";
        $fecha_pag="";
        $pdf->Ln();
        foreach($proveedor as $detail){
            $pdf->SetX(55);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 6, 'IDENTIFICACION', 0, 0, 'C', 0);
            $pdf->Cell(25, 6, $detail->identif_proveedor, 0, 0, 'R', 0);
            $pdf->Cell(45, 6, $detail->nombre_proveedor, 0, 0, 'R', 0);
            $id_prov=$detail->id_proveedor;
            $pdf->Ln(12);
            
            foreach($datos as $detail){
                if($detail->id_proveedor==$id_prov){
                    $pdf->SetX(10);
                    $pdf->SetFillColor(240, 240, 240);
                    $pdf->SetFont('Arial', 'B', 8);
                    
                    $pdf->Cell(33, 6, 'No. Doc', 1, 0, 'C', 0);
                    $pdf->Cell(33, 6, 'Serie', 1, 0, 'C', 0);
                    $pdf->Cell(31, 6, 'Fecha', 1, 0, 'C', 0);
                    //$pdf->Cell(25, 6, 'Fecha Vencimiento', 1, 0, 'C', 0);
                    $pdf->Cell(31, 6, 'Pago', 1, 0, 'C', 0);
                    $pdf->Cell(31, 6, 'Forma Pago', 1, 0, 'C', 0);
                    //$pdf->Cell(25, 6, 'Fecha', 1, 0, 'C', 0);
                    $pdf->Cell(31, 6, 'Valor', 1, 0, 'C', 0);
                    //$pdf->Cell(35, 6, 'Saldos', 1, 0, 'C', 1);
                    $pdf->Ln();
                    $pdf->SetX(10);
                    $resta_total=number_format($resta,2,",",".");
                    $pdf->SetFillColor(240, 240, 240);
                    $pdf->SetFont('Arial', '', 7);
                    $serie1=substr($detail->observacion,24,3);
                    $serie2=substr($detail->observacion,27,3);
                    $documento=substr($detail->observacion,30,9);
                    $fecha_emision=date("d/m/Y", strtotime($detail->fech_emision));
                    $fecha_vencimiento=date("d/m/Y", strtotime($detail->fecha_pago));
                    $pdf->Cell(33, 6, $documento, 1, 0, 'C', 0);
                    $pdf->Cell(33, 6, $serie1.'-'.$serie2, 1, 0, 'C', 0);
                    //$pdf->Cell(12, 6, 'Factura', 0, 0, 'C', 1);
                    //$pdf->Cell(18, 6, $detail->num_cuota, 0, 0, 'C', 1);
                    $pdf->Cell(31, 6, $fecha_vencimiento, 1, 0, 'C', 0);
                    //$pdf->Cell(31, 6, $fecha_vencimiento, 1, 0, 'R', 0);
                    $pdf->Cell(31, 6, '', 1, 0, 'R', 0);
                    $pdf->Cell(31, 6, '', 1, 0, 'R', 0);
                    $pdf->Cell(31, 6, $detail->valor_cuota, 1, 0, 'C', 0);
                    $id_fact=$detail->id_factura_compra;
                    $total_factura=$detail->valor_cuota;
                    $no_doc=$documento;
                    $pdf->Ln();
                    foreach($vencido as $detail){
                        if($detail->id_factura_compra==$id_fact){
                            $pdf->SetX(10);
                            $pdf->SetFillColor(240, 240, 240);
                            $pdf->SetFont('Arial', '', 7);
                            //$pdf->Cell(25, 6, $documento, 0, 0, 'C', 0);
                            $fecha_pag=date("d/m/Y", strtotime($detail->fecha_pago));
                            $pdf->Cell(33, 6, $detail->id_factura_compra, 1, 0, 'R', 0);
                            $pdf->Cell(33, 6, $serie1.'-'.$serie2, 1, 0, 'C', 0);
                            //$pdf->Cell(45, 6, $detail->numero_tarjeta, 1, 0, 'R', 0);
                            $pdf->Cell(31, 6, $fecha_pag, 1, 0, 'C', 0);
                            //$pdf->Cell(45, 6, '', 1, 0, 'R', 0);
                            $pdf->Cell(31, 6, $detail->pagos_por, 1, 0, 'C', 0);
                            $pdf->Cell(31, 6, $detail->descripcion, 1, 0, 'C', 0);
                            $pdf->Cell(31, 6, '-'.$detail->valor_pagado, 1, 0, 'C', 0);
                            $pdf->Ln();
                            $total_abono+=$detail->valor_pagado;
                        }
                            
                    
                    }
                            
                            $total_saldo+=$total_factura-$total_abono;
                            $suma_saldo=number_format($total_saldo,2,",",".");
                            $pdf->SetX(15);
                            $pdf->SetFillColor(240, 240, 240);
                            $pdf->SetFont('Arial', 'B', 8);
                            $pdf->Cell(159, 6, 'TOTAL   '.$no_doc, 'T', 0, 'C', 0);
                            //$pdf->Cell(25, 6, $no_doc, 1, 0, 'R', 0);
                            //$pdf->Cell(25, 6, '', 1, 0, 'R', 0);
                            //$pdf->Cell(44, 6, '', 'T', 0, 'R', 0);
                            //$pdf->Cell(44, 6, '', 'T', 0, 'R', 0);
                            $pdf->Cell(22, 6, $suma_saldo, 'T', 0, 'C', 0);
                            $pdf->Ln(11);
                            $total_saldo=0;
                            $total_abono=0;
                            $total_factura=0;
                            $no_doc="";
                }
            }    
        }
        /*foreach($proveedor as $detail){
            $pdf->SetX(95);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 6, 'IDENTIFICACION', 0, 0, 'C', 0);
            $pdf->Cell(25, 6, $detail->identif_proveedor, 0, 0, 'R', 0);
            $pdf->Cell(45, 6, $detail->nombre_proveedor, 0, 0, 'R', 0);
            $id_prov=$detail->id_proveedor;
            $pdf->Ln(15);
            $pdf->SetX(60);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 6);
            
            $pdf->Cell(25, 6, 'No. Doc', 'TB', 0, 'C', 0);
            $pdf->Cell(25, 6, 'Serie', 'TB', 0, 'C', 0);
            $pdf->Cell(25, 6, 'Fecha Emision', 'TB', 0, 'C', 0);
            $pdf->Cell(25, 6, 'Fecha Vencimiento', 'TB', 0, 'C', 0);
            $pdf->Cell(25, 6, 'Num.Compra', 'TB', 0, 'C', 0);
            $pdf->Cell(25, 6, 'Fecha', 'TB', 0, 'C', 0);
            $pdf->Cell(25, 6, 'Valor', 'TB', 0, 'C', 0);
            //$pdf->Cell(25, 6, 'Saldos', 1, 0, 'C', 1);
            $pdf->Ln();
            
            foreach($datos as $detail){
                if($detail->id_proveedor==$id_prov){
                    $pdf->SetX(60);
                    //$resta=$detail->valor_cuota-$detail->valor_pagado;
                    $resta_total=number_format($resta,2,",",".");
                    $pdf->SetFillColor(240, 240, 240);
                    $pdf->SetFont('Arial', '', 6);
                    $serie1=substr($detail->observacion,24,3);
                    $serie2=substr($detail->observacion,27,3);
                    $documento=substr($detail->observacion,30,9);
                    $fecha_emision=date("d/m/Y", strtotime($detail->fech_emision));
                    $fecha_vencimiento=date("d/m/Y", strtotime($detail->fecha_pago));
                    $pdf->Cell(25, 6, $documento, 0, 0, 'C', 0);
                    $pdf->Cell(25, 6, $serie1.'-'.$serie2, 0, 0, 'C', 0);
                    //$pdf->Cell(12, 6, 'Factura', 0, 0, 'C', 1);
                    //$pdf->Cell(18, 6, $detail->num_cuota, 0, 0, 'C', 1);
                    $pdf->Cell(25, 6, $fecha_emision, 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, $fecha_vencimiento, 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, '', 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, '', 0, 0, 'R', 0);
                    $pdf->Cell(25, 6, $detail->valor_cuota, 0, 0, 'R', 0);
                    $id_fact=$detail->id_factura_compra;
                    $total_factura=$detail->valor_cuota;
                    $no_doc=$documento;
                    $pdf->Ln();

                    foreach($vencido as $detail){
                        if($detail->id_factura_compra==$id_fact){
                            $pdf->SetFillColor(240, 240, 240);
                            $pdf->SetFont('Arial', '', 6);
                            $pdf->Cell(150, 6, $detail->id_factura_compra, 0, 0, 'R', 0);
                            $pdf->Cell(25, 6, $detail->numero_tarjeta, 0, 0, 'R', 0);
                            $pdf->Cell(25, 6, '', 0, 0, 'R', 0);
                            $pdf->Cell(25, 6, '-'.$detail->valor_pagado, 0, 0, 'R', 0);
                            $pdf->Ln();
                            $total_abono+=$detail->valor_pagado;
                        }
                            
                    }
                            $total_saldo+=$total_factura-$total_abono;
                            $suma_saldo=number_format($total_saldo,2,",",".");
                            $pdf->SetX(60);
                            $pdf->SetFillColor(240, 240, 240);
                            $pdf->SetFont('Arial', 'B', 6);
                            $pdf->Cell(100, 6, 'TOTAL', 'TB', 0, 'R', 0);
                            $pdf->Cell(25, 6, $no_doc, 'TB', 0, 'R', 0);
                            $pdf->Cell(25, 6, '', 'TB', 0, 'R', 0);
                            $pdf->Cell(25, 6, $suma_saldo, 'TB', 0, 'R', 0);
                            $pdf->Ln();
                            $total_saldo=0;
                            $total_abono=0;
                            $total_factura=0;
                            $no_doc="";
                        
                }
                
            }
            
            $pdf->Ln(15);

        }*/
            /*$pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(150, 6, 'Totales', 0, 0, 'R', 0);
            $pdf->Cell(25, 6, $vencido[0]->suma, 0, 0, 'R', 0);
            $pdf->Cell(25, 6, $vencido[0]->abono, 0, 0, 'R', 0);
            $pdf->Cell(25, 6, $vencido[0]->saldo, 0, 0, 'R', 0);*/
        $pdf->Output("cuenta_por_pagar.pdf", "D");
    }
    public function cuenta_pagar_reporte_dias_vencidos($datos, $fec_ini, $fec_fin,$vencido,$proveedor){
        $fecha_actual = date("d/m/Y");
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);

        $pdf->RoundedRect(10, 10, 275, 25, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            $pdf->Image($logo, 12, 12, 55, 20);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(75, 10);
        $pdf->Cell(155, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(155, 8, utf8_decode('Reporte de Cuentas por Pagar'), 0, 2, 'C', 0);

        $pdf->SetXY(230, 11);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(20, 6, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(20, 6, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(250, 11);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(20, 6, $fecha_actual, 0, 2, 'R', 0);
        //dd("FEcha inicial:",$fec_ini,"FEcha final:",$fec_fin);
        if ($fec_ini !== null && $fec_fin === null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini !== null && $fec_fin !== null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(20, 6, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(20, 6, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);
        //$pdf->SetXY(80,40);
        $id_prov="";
        $serie1="";
        $serie2="";
        $documento="";
        $resta=0;
        $resta_total=0;
        $fecha_emision="";
        $fecha_vencimiento="";
        $total_factura=0;
        $total_abono=0;
        $total_saldo=0;
        $suma_factura=0;
        $suma_abono=0;
        $suma_saldo=0;
        $totales_factura=0;
        $totales_abono=0;
        $totales_saldo=0;
        $sumatot_factura=0;
        $sumatot_abono=0;
        $sumatot_saldo=0;
        $pdf->Ln();
        $pdf->SetX(10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Arial', 'B', 8);
        //$pdf->Cell(10, 6, 'No. Factura', 1, 0, 'C', 0);
        $pdf->Cell(20, 6, 'No. Doc', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, 'Serie', 1, 0, 'C', 0);
        $pdf->Cell(45, 6, 'Proveedor', 1, 0, 'C', 0);
        $pdf->Cell(20, 6, 'Fecha', 1, 0, 'C', 0);
        $pdf->Cell(20, 6, 'Valor', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '-120', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '-90', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '-60', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '-30', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '0', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '30', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '60', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '90', 1, 0, 'C', 0);
        $pdf->Cell(15, 6, '120', 1, 0, 'C', 0);
        $pdf->Cell(20, 6, 'Total', 1, 0, 'C', 0);
        $pdf->Ln();
        $id_fact="";
        $total_0=0;
        $total_30=0;
        $total_60=0;
        $total_90=0;
        $total_120=0;
        $total_neg30=0;
        $total_neg60=0;
        $total_neg90=0;
        $total_neg120=0;
        $total_fact=0;
        $suma_total_0=0;
        $suma_total_30=0;
        $suma_total_60=0;
        $suma_total_90=0;
        $suma_total_120=0;
        $suma_total_neg30=0;
        $suma_total_neg60=0;
        $suma_total_neg90=0;
        $suma_total_neg120=0;
        foreach($datos as $detail){
            $pdf->SetX(10);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', '', 7);
            $serie1=substr($detail->observacion,24,3);
            $serie2=substr($detail->observacion,27,3);
            $documento=substr($detail->observacion,30,9);
            //$pdf->Cell(10, 6, $detail->id_factura_compra, 1, 0, 'C', 0);
            $id_fact=$detail->id_factura_compra;
            $pdf->Cell(20, 6, $documento, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $serie1.'-'. $serie2, 1, 0, 'C', 0);
            $pdf->Cell(45, 6, $detail->nombre_proveedor, 1, 0, 'L', 0);
            $pdf->Cell(20, 6, $detail->fech_emision, 1, 0, 'C', 0);
            $pdf->Cell(20, 6, $detail->valor_cuota, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->mencientoveinte, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->mennoventa, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->mensesenta, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->mentreninta, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->cero, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->treninta, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->sesenta, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->noventa, 1, 0, 'C', 0);
            $pdf->Cell(15, 6, $detail->cientoveinte, 1, 0, 'C', 0);
            $total_fact+=$detail->mencientoveinte+
                         $detail->mennoventa+
                         $detail->mensesenta+
                         $detail->mentreninta+
                         $detail->cero+
                         $detail->treninta+
                         $detail->sesenta+
                         $detail->noventa+
                         $detail->cientoveinte;
            $total_factura=number_format($total_fact,2,",",".");
            $total_saldo+=$total_fact;
            $pdf->Cell(20, 6,  $total_factura, 1, 0, 'C', 0);
            $pdf->Ln();
            $total_fact=0;
            $id_fact="";
            $total_factura=0;
            $total_abono+=$detail->valor_cuota;
            $total_0+=$detail->cero;
            $total_30+=$detail->treninta;
            $total_60+=$detail->sesenta;
            $total_90+=$detail->noventa;
            $total_120+=$detail->cientoveinte;
            $total_neg30+=$detail->mentreninta;
            $total_neg60+=$detail->mensesenta;
            $total_neg90+=$detail->mennoventa;
            $total_neg120+=$detail->mencientoveinte;
            /*$pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '0', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(15, 6, '', 1, 0, 'C', 0);
            $pdf->Cell(20, 6, '', 1, 0, 'C', 0);
            $pdf->Ln();*/
            
        }
            $sumatot_abono=number_format($total_abono,2,",",".");
            $sumatot_factura=number_format($total_saldo,2,",",".");
            $suma_total_0=number_format($total_0,2,",",".");
            $suma_total_30=number_format($total_30,2,",",".");
            $suma_total_60=number_format($total_60,2,",",".");
            $suma_total_90=number_format($total_90,2,",",".");
            $suma_total_120=number_format($total_120,2,",",".");
            $suma_total_neg30=number_format($total_neg30,2,",",".");
            $suma_total_neg60=number_format($total_neg60,2,",",".");
            $suma_total_neg90=number_format($total_neg90,2,",",".");
            $suma_total_neg120=number_format($total_neg120,2,",",".");
            $pdf->SetX(10);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 6, 'Total', 0, 0, 'C', 0);
            $pdf->Cell(20, 6, $sumatot_abono, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_neg120, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_neg90, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_neg60, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_neg30, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_0, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_30, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_60, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_90, 0, 0, 'C', 0);
            $pdf->Cell(15, 6, $suma_total_120, 0, 0, 'C', 0);
            $pdf->Cell(20, 6, $sumatot_factura, 0, 0, 'C', 0);
            $pdf->Output("cuenta_por_pagar.pdf", "D");
    }
    public function balanceComprobacion($datos, $fec_ini, $fec_fin)
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        //$pdf->Image('../server/' . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo, null, null, 65, 25);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(60, 10);
        $pdf->Cell(105, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(105, 8, utf8_decode('BALANCE DE COMPROBACIÓN'), 0, 2, 'C', 0);

        $pdf->SetXY(165, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, $fecha_actual, 0, 2, 'R', 0);
        if ($fec_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini != null && $fec_fin != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(15, 5, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        //HEADER FOR TABLE
        $pdf->SetY(30);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(15, 6, 'CODIGO', 'TB', 0, 'C', 1);
        $pdf->Cell(85, 6, 'CUENTA', 'TB', 0, 'C', 1);
        $pdf->Cell(25, 6, 'SALDO ANTERIOR', 'TB', 0, 'C', 1);
        $pdf->Cell(20, 6, 'DEBE', 'TB', 0, 'C', 1);
        $pdf->Cell(20, 6, 'HABER', 'TB', 0, 'C', 1);
        $pdf->Cell(25, 6, 'SALDO ACTUAL', 'TB', 0, 'C', 1);
        $pdf->Ln();

        //fill cells
        $tsant = 0;
        $tdebe = 0;
        $thaber = 0;
        $tsactual = 0;
        $pdf->SetFont('Arial', '', 7);

        foreach ($datos as $detail) {
            $pdf->Cell(15, 4, $detail->codcta, 0, 0, 'L', 1);
            $pdf->Cell(85, 4, $detail->nomcta, 0, 0, 'L', 1);
            $pdf->Cell(25, 4, $detail->debe, 0, 0, 'R', 1);
            $pdf->Cell(20, 4, $detail->debe, 0, 0, 'R', 1);
            $pdf->Cell(20, 4, $detail->haber, 0, 0, 'R', 1);
            $pdf->Cell(25, 4, $detail->haber, 0, 0, 'R', 1);
            $pdf->Ln();
            $tsant = $detail->debe + $tsant;
            $tdebe = $detail->debe + $tdebe;
            $thaber = $detail->haber + $thaber;
            $tsactual = $detail->haber + $tsactual;
        }

        //footer
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(100, 6, 'TOTAL:', 'T', 0, 'R', 1);
        $pdf->Cell(25, 6, $tsant, 'T', 0, 'R', 1);
        $pdf->Cell(20, 6, $tdebe, 'T', 0, 'R', 1);
        $pdf->Cell(20, 6, $thaber, 'T', 0, 'R', 1);
        $pdf->Cell(25, 6, $tsactual, 'T', 0, 'R', 1);

        $pdf->Output("ejemplo.pdf", "D");
    }

    public function proforma($datos, $id_proforma, $factura_info, $generar_en_servidor = false, $ruta = null)
    {
        $pdf = new PDF_MC_Table('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $logo = constant("DATA_EMPRESA") . "/" . $factura_info->id_empresa . '/imagen/' . $datos[0]->logo;
        $pdf->SetAutoPageBreak(1, 4);
        $width = $pdf->GetPageWidth();
        // HEADER
        if (isset($datos[0]->logo) && file_exists($logo)) {
            if($factura_info->id_empresa!=32){
               $pdf->Image($logo, 10, 10, 55, 20);
            }else{
                $pdf->Image($logo, 10, 6, 50, 35);
            }
            
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        if (preg_match('/grupo solis/i', $datos[0]->nombre_empresa)) {
            $pdf->Image(isset($datos[0]->logo) && file_exists($datos[0]->logo) ? $datos[0]->logo : $_SERVER['DOCUMENT_ROOT'] . '/images/img-solis/trivento.jpeg', 71, 13, 30, 25);
            $pdf->Image(isset($datos[0]->logo) && file_exists($datos[0]->logo) ? $datos[0]->logo : $_SERVER['DOCUMENT_ROOT'] . '/images/img-solis/robusta.jpg', 70, 5, 30, 15);
            $pdf->Image(isset($datos[0]->logo) && file_exists($datos[0]->logo) ? $datos[0]->logo : $_SERVER['DOCUMENT_ROOT'] . '/images/img-solis/safety.jpeg', 105, 9, 20, 20);
            $pdf->Image(isset($datos[0]->logo) && file_exists($datos[0]->logo) ? $datos[0]->logo : $_SERVER['DOCUMENT_ROOT'] . '/images/img-solis/cellery.jpeg', 130, 17, 30, 15);
            $pdf->Image(isset($datos[0]->logo) && file_exists($datos[0]->logo) ? $datos[0]->logo : $_SERVER['DOCUMENT_ROOT'] . '/images/img-solis/3m.png', 134, 7, 20, 10);
        }
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(($width / 2) - ($pdf->GetStringWidth("PROFORMA N°. " . $id_proforma) / 2), 33);
        $pdf->Cell($pdf->GetStringWidth("PROFORMA N°. " . $id_proforma), 8, utf8_decode("PROFORMA N°. " . $id_proforma), 0, 2, 'C', 0);
        $pdf->SetXY((($width / 4) * 3) + 10, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(($width / 4) - 10, 6, isset($datos[0]->ruc_empresa) ? utf8_decode('RUC: ' . $datos[0]->ruc_empresa) : '*************', 0, 2, 'C', 0);
        $pdf->MultiCell(($width / 4) - 10, 6, isset($datos[0]->direccion_empresa) ? utf8_decode($datos[0]->direccion_empresa) : 'Sin direccion', 0, 'C', 0);
        $pdf->Ln();

        // INFORMATION HEADER
        $margin = 5;
        $height = $pdf->GetY();
        $cli_atencion = '';
        if($factura_info->id_empresa!=32){
            $pdf->SetDrawColor(85, 175, 84);
        }else{
            $pdf->SetDrawColor(85, 175, 204);
        }
        //$pdf->SetDrawColor(85, 175, 204);
        $pdf->RoundedRect($margin, $height + 10, $width - 10, 25, 2, '1234', 'D');
        $pdf->SetXY($margin, $height + 12.5);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell($width / 4, 5, utf8_decode('RUC:'), 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, utf8_decode('CLIENTE:'), 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, utf8_decode('ATENCIÓN:'), 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, utf8_decode('E-MAIL:'), 0, 2, 'L', 0);
        $pdf->SetXY($width / 2, $height + 12.5);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell($width / 4, 5, utf8_decode('FECHA:'), 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, utf8_decode('CIUDAD:'), 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, utf8_decode('DIRECCIÓN:'), 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, utf8_decode('TELÉFONO:'), 0, 2, 'L', 0);

        $pdf->SetXY(($width / 6) - $margin, $height + 12.5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell($width / 4, 5, isset($datos[0]->identificacion) ? utf8_decode($datos[0]->identificacion) : 'identificacion', 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, isset($datos[0]->cliente) ? utf8_decode($datos[0]->cliente) : 'cliente', 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, utf8_decode($cli_atencion), 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, isset($datos[0]->email) ? utf8_decode($datos[0]->email) : 'email', 0, 2, 'L', 0);

        $pdf->SetXY(($width / 6 * 4) - $margin, $height + 12.5);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell($width / 4, 5, isset($datos[0]->fecha_emision) ? utf8_decode($datos[0]->fecha_emision) : 'fecha_emision', 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, isset($datos[0]->ciudad) ? utf8_decode($datos[0]->ciudad) : 'ciudad', 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, isset($datos[0]->direccion) ? utf8_decode($datos[0]->direccion) : 'direccion', 0, 2, 'L', 0);
        $pdf->Cell($width / 4, 5, isset($datos[0]->telefono) ? utf8_decode($datos[0]->telefono) : 'telefono', 0, 2, 'L', 0);
        $pdf->Ln();

        // MESSAGE
        $pdf->SetXY($margin, $height + 40);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(20, 5, utf8_decode('APRECIADO CLIENTE:'), 0, 2, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(20, 5, utf8_decode('De acuerdo a su  solicitud nos permitimos enviar nuestra propuesta comercial:'), 0, 2, 'L', 0);
        $pdf->SetFillColor(85, 175, 84);
        $pdf->SetTextColor(0, 0, 255);
        $pdf->SetDrawColor(0, 0, 0);
        if (preg_match('/grupo solis/i', $datos[0]->nombre_empresa)) {
            // SLOGAN
            $pdf->SetXY($margin, $height + 55);
            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->MultiCell(200, 10, utf8_decode('EN GRUPO SOLIS NOS PREOCUPAMOS POR SU SEGURIDAD.'), 1, 'C', 1);
            $height_products = $height + 65;
        } else {
            $height_products = $height + 55;
        }
        //cabecera de detalles de productos
        $pdf->SetFont('Helvetica', 'B', 6);
        if($factura_info->id_empresa!=32){
            $pdf->SetFillColor(141, 207, 140);
        }else{
            $pdf->SetFillColor(141, 207, 240);
        }
        //dd("X=".$margin."Y=".$height_products);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY($margin, $height_products);
        $pdf->MultiCell(25, 8, utf8_decode('ITEM'), 1, 'C', 1);
        $pdf->SetXY(30, $height_products);
        $pdf->MultiCell(20, 8, utf8_decode('PRODUCTO'), 1, 'C', 1);
        $pdf->SetXY(50, $height_products);
        $pdf->MultiCell(25, 8, utf8_decode('CARACTERÍSTICAS'), 1, 'C', 1);
        $pdf->SetXY(75, $height_products);
        $pdf->MultiCell(20, 8, utf8_decode('NORMATIVA'), 1, 'C', 1);
        $pdf->SetXY(95, $height_products);
        $pdf->MultiCell(16, 8, utf8_decode('USO'), 1, 'C', 1);
        $pdf->SetXY(111, $height_products);
        $pdf->MultiCell(12, 8, utf8_decode('MARCA'), 1, 'C', 1);
        $pdf->SetXY(123, $height_products);
        $pdf->MultiCell(12, 8, utf8_decode('UNIDAD'), 1, 'C', 1);
        $pdf->SetXY(135, $height_products);
        $pdf->MultiCell(12, 8, utf8_decode('TALLA'), 1, 'C', 1);
        $pdf->SetXY(147, $height_products);
        $pdf->MultiCell(14, 8, utf8_decode('CANTIDAD'), 1, 'C', 1);
        $pdf->SetXY(161, $height_products);
        $pdf->MultiCell(20, 4, utf8_decode('VALOR UNITARIO'), 1, 'C', 1);
        $pdf->SetXY(181, $height_products);
        $pdf->MultiCell(20, 8, utf8_decode('VALOR TOTAL'), 1, 'C', 1);
        //$pdf->SetXY(190, $height_products);
        //$pdf->MultiCell(15, 4, utf8_decode('TIEMPO DE ENTREGA'), 1, 'C', 1);
        //dd("X=".$margin."Y=".$height_products);
        $y = $height_products + 8;
        $pdf->SetFont('Helvetica', '', 4);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFillColor(85, 175, 84);
        $pdf->SetXY($margin, $height_products+8);
        $pdf->SetWidths(array(25, 20, 25, 20, 16, 12, 12, 12, 14, 20, 20, 15));
        //$logo = constant("DATA_EMPRESA") . "/" . $factura_info->id_empresa . '/imagen/' . $datos[0]->logo;
        $imagen=constant("DATA_EMPRESA") . $factura_info->id_empresa .'/productos/';
        //$pdf->Image($imagen.$datos[0]->imagen, $pdf->GetX(), $pdf->GetY(), 25, 25);
        $sub_total=0;
        $descuento=0;
        $iva=0;
        $total_fact=0;
        $suma_sub_total=0;
        $suma_descuento=0;
        $suma_iva=0;
        $suma_total_fact=0;
        foreach ($datos as $detail) {
            $data = array(
                utf8_decode($detail->nombre),
                utf8_decode($detail->caracteristicas),
                utf8_decode($detail->normativa),
                utf8_decode($detail->uso),
                utf8_decode($detail->marca),
                utf8_decode($detail->tipo_medida),
                utf8_decode($detail->cantidad),
                utf8_decode($detail->precio),
                utf8_decode($detail->total_pro),
                utf8_decode($detail->total_pro),
                //utf8_decode('INMEDIATA'),
                
            );
                $sub_total=$detail->subtotal;
                $descuento=$detail->descuento;
                $iva=$detail->iva;
                $total_fact=$detail->total;
            if ($detail->imagen && file_exists($imagen.$detail->imagen)) {
            //if ($detail->imagen) {
                array_unshift($data, $pdf->Image($imagen.$detail->imagen, $pdf->GetX(), $pdf->GetY(), 25, 25));
            } else {
                array_unshift($data, utf8_decode('* sin imagen'));
            }
            //dd("Imagen:".$imagen.$detail->imagen);
            $pdf->Row($data, $margin+5);
        }
        $suma_sub_total=number_format($sub_total,2,",",".");
        $suma_descuento=number_format($descuento,2,",",".");
        $suma_iva=number_format($iva,2,",",".");
        $suma_total_fact=number_format($total_fact,2,",",".");
        $pdf->SetXY(135, $pdf->GetY());
        $pdf->SetX(135);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(46, 7, utf8_decode('SUBTOTAL'), 1, 0, 'C', 0);
        $pdf->SetFont('Helvetica', '', 8);
        //$pdf->Cell(20, 7, isset($datos[0]->subtotal) ? utf8_decode('$  ' . $datos[0]->subtotal) : 'subtotal', 1, 1, 'R', 0);
        $pdf->Cell(20, 7, isset($suma_sub_total) ? utf8_decode('$  ' . $suma_sub_total) : 'subtotal', 1, 1, 'R', 0);
        $pdf->SetX(135);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(46, 7, utf8_decode('DESCUENTO'), 1, 0, 'C', 0);
        $pdf->SetFont('Helvetica', '', 8);
        //$pdf->Cell(20, 7, isset($datos[0]->descuento) ? utf8_decode('$  ' . $datos[0]->descuento) : 'descuento', 1, 1, 'R', 0);
        $pdf->Cell(20, 7, isset($suma_descuento) ? utf8_decode('$  ' . $suma_descuento) : 'descuento', 1, 1, 'R', 0);
        $pdf->SetX(135);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(46, 7, utf8_decode('IVA (12%)'), 1, 0, 'C', 0);
        $pdf->SetFont('Helvetica', '', 8);
        //$pdf->Cell(20, 7, isset($datos[0]->iva) ? utf8_decode('$  ' . $datos[0]->iva) : 'iva', 1, 1, 'R', 0);
        $pdf->Cell(20, 7, isset($suma_iva) ? utf8_decode('$  ' . $suma_iva) : 'iva', 1, 1, 'R', 0);
        $pdf->SetX(135);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(46, 7, utf8_decode('TOTAL'), 1, 0, 'C', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(20, 7, isset($suma_total_fact) ? utf8_decode('$  ' . $suma_total_fact) : 'total', 1, 1, 'R', 0);
        $pdf->Ln();
        $pdf->SetDrawColor(85, 175, 84);
        $pdf->SetX(5);
        $pdf->SetFont('Helvetica', 'BU', 9);
        $pdf->Cell(200, 6, utf8_decode('CONDICIONES COMERCIALES'), 'T', 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(5);
        $pdf->Cell(50, 6, utf8_decode('LUGAR DE ENTREGA:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(150, 6, utf8_decode($factura_info->lugar_de_entrega), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(5);
        $pdf->Cell(50, 6, utf8_decode('CONDICIOINES DE PAGO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(150, 6, utf8_decode($factura_info->condiciones_de_pago), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(5);
        $pdf->Cell(50, 6, utf8_decode('OBSERVACIONES:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(150, 6, utf8_decode($factura_info->observacion), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetX(5);
        $pdf->Cell(50, 6, utf8_decode('VALIDEZ DE LA OFERTA:'), 'B', 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(150, 6, utf8_decode($factura_info->fecha_expiracion), 'B', 1, 'L', 0);
        $pdf->Ln();

        $pdf->SetX(5);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(25, 6, utf8_decode('Elaborado por:'), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(75, 6, isset($datos[0]->vendedor) ? utf8_decode($datos[0]->vendedor) : 'vendedor', 0, 0, 'L', 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(100, 6, utf8_decode('Le invitamos a revisar nuestra página web.'), 0, 1, 'R', 0);
        $pdf->SetTextColor(0, 0, 255);
        $pdf->SetX(30);
        $pdf->SetFont('Helvetica', 'U', 8);
        $pdf->Cell(120, 6, isset($datos[0]->mailvendedor) ? utf8_decode($datos[0]->mailvendedor) : 'mailvendedor', 0, 0, 'L', 0);
        $pdf->SetTextColor(4, 255, 0);
        $pdf->SetFont('', 'U', 9);
        $pdf->Write(6, utf8_decode($factura_info->email_empresa), utf8_decode($factura_info->email_empresa));
        $current_date = date("Y-m-d");
        //dd($ruta);
        if (!$generar_en_servidor) {
            return $pdf->Output(isset($datos[0]->identificacion) ? "proforma-" . $datos[0]->identificacion . "-" . $current_date . ".pdf" : 'no-data.pdf', "D");
        } else if ($generar_en_servidor && !is_null($ruta)) {
            if (file_exists($ruta)) {
                $name = $id_proforma . '.pdf';
                $location = $ruta . "/" . $name;
                $pdf->Output("F", $location);
                return json_encode(
                    array(
                        "location" => $location,
                        "name" => $name)
                );
            } else {
                throw new Exception("No se pudo generar la proforma");
            }

        } else if ($generar_en_servidor && is_null($ruta)) {
            throw new Exception("La ruta para la proforma no es valida.");
        }
    }
    public function asiento()
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y H:i:s");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        //$pdf->Cell(20);
        $pdf->SetXY(20, 15);
        //$pdf->Image('../server/'.$id_empresa.'/imagen/'.$imagen, null, null, 80, 30);

        //variables de empresa que emite
        $emp_dir_sucursal = '';
        $emp_contri_especial = '';

        //cuadros detalle empresa que emite
        $pdf->RoundedRect(10, 40, 98, 30, 2, '1234', 'D');
        $pdf->Ln(30);
        $pdf->SetXY(10, 41);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(96, 6, utf8_decode($document->infoTributaria->razonSocial), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(22, 5, utf8_decode('Dirección Matriz: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($document->infoTributaria->dirMatriz), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(25, 5, utf8_decode('Dirección Sucursal: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(23, 5, utf8_decode($emp_dir_sucursal), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(36, 5, utf8_decode('Contribuyente Especial Nro.: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(29, 5, utf8_decode($emp_contri_especial), 0, 1, 'L', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        if ($document->infoCompRetencion->obligadoContabilidad == 'SI') {

            $contabilidad = "Obligado a llevar contabilidad : SI";
        } else {
            $contabilidad = "Obligado a llevar contabilidad : NO";
        }
        $pdf->Cell(39, 5, utf8_decode('Obligado A Llevar Contabilidad: '), 0, 0, 'L', 0);
        $pdf->SetFont('Helvetica', '', 7);
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
        $this->generarCodigoBarras($claveAcceso, $id_empresa);
        $pdf->image('../server/' . $id_empresa . '/comprobantes/retencion/codigosbarras/codigo_' . $claveAcceso . '.png', null, null, 100, 20);
        $pdf->SetXY(111, 65);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, $claveAcceso, 0, 2, 'C', 0);
        //cuadro detalle factura
        $pdf->SetXY(111, 11);
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->RoundedRect(110, 10, 90, 60, 2, '1234', 'D');
        $pdf->Cell(88, 6, utf8_decode('R.U.C: ') . $document->infoTributaria->ruc, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(88, 6, utf8_decode('COMPROBANTE DE RETENCIÓN'), 0, 2, 'C', 0);
        $pdf->Cell(88, 6, utf8_decode('No. ') . $document->infoTributaria->estab . $document->infoTributaria->ptoEmi . $document->infoTributaria->secuencial, 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(88, 5, utf8_decode('NUMERO DE AUTORIZACION: '), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->Cell(88, 5, utf8_decode($claveAcceso), 0, 2, 'C', 0);
        $pdf->SetFont('Helvetica', 'B', 7);

        //cuadro de datos del cliente
        $pdf->SetXY(10, 73);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->RoundedRect(10, 72, 190, 12, 2, '1234', 'D');
        $pdf->Cell(110, 5, utf8_decode('Razón Social / Nombres y Apellidos: ' . $document->infoCompRetencion->razonSocialSujetoRetenido), 0, 0, 'L', 0);
        $pdf->Cell(29, 5, utf8_decode('Identificación: ' . $document->infoCompRetencion->identificacionSujetoRetenido), 0, 1, 'L', 0);
        $pdf->Cell(110, 5, utf8_decode('Fecha de Emisión: ' . $document->infoCompRetencion->fechaEmision), 0, 0, 'L', 0);

        //tabla de productos
        $pdf->SetXY(10, 86);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetFont('Helvetica', 'B', 8);
        //header de tabla
        $pdf->MultiCell(25, 8, utf8_decode('Comprobante'), 1, 'C', 1);
        $pdf->SetXY(35, 86);
        $pdf->MultiCell(30, 8, utf8_decode('Número'), 1, 'C', 1);
        $pdf->SetXY(65, 86);
        $pdf->MultiCell(25, 8, utf8_decode('Fecha Emisión'), 1, 'C', 1);
        $pdf->SetXY(90, 86);
        $pdf->MultiCell(20, 4, utf8_decode('Ejercicio Fiscal'), 1, 'C', 1);
        $pdf->SetXY(110, 86);
        $pdf->MultiCell(25, 8, utf8_decode('Base Imponible'), 1, 'C', 1);
        $pdf->SetXY(135, 86);
        $pdf->MultiCell(20, 8, utf8_decode('Impuesto'), 1, 'C', 1);
        $pdf->SetXY(155, 86);
        $pdf->MultiCell(20, 4, utf8_decode('Porcentaje de Retención'), 1, 'C', 1);
        $pdf->SetXY(175, 86);
        $pdf->MultiCell(25, 8, utf8_decode('Valor Retenido'), 1, 'C', 1);
        //$pdf->Ln();

        //rellenado de campos

        $pdf->SetFont('Helvetica', '', 8);
        $total = 0;
        foreach ($document->impuestos->impuesto as $a => $b) {
            if ($b->codDocSustento = '01') {
                $pdf->Cell(20, 6, 'FACTURA', 1, 0, "L", 0);
            } else {
                $pdf->Cell(20, 6, $b->codDocSustento, 1, 0, "L", 0);
            }
            $pdf->Cell(30, 6, $b->codDocSustento, 1, 0, 'R', 0);
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
        $pdf->Ln(2);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(125, 6, utf8_decode('Información Adicional'), 1, 0, 'L', 0);
        $pdf->Cell(40, 6, 'Total', 'LTB', 0, 'R', 0);
        $pdf->Cell(25, 6, number_format(floatval($total), 2), 'TBR', 1, 'R', 0);
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(125, 6, utf8_decode('Correo: ' . $correo), 'LR', 2, 'L', 0);
        $pdf->Cell(125, 6, utf8_decode('Observaciones: ' . $infoAdicional), 'LR', 2, 'L', 0);
        $pdf->Cell(125, 6, utf8_decode('Retencion aplicada a la factura No.: ' . $b->codDocSustento), 'LBR', 2, 'L', 0);

        $pdf->Output('../server/' . $id_empresa . '/comprobantes/retencion/' . $claveAcceso . '.pdf', 'F');
        $email = new sendEmail();
        $email->enviarCorreo('Comprobante de Retencion', $document->infoCompRetencion->razonSocialSujetoRetenido, $claveAcceso, $correo, $id_empresa);
        //$pdf->Output("ejemplo.pdf", "I");
    }

    public function cuentasPorCobrar($datos, $fec_ini, $fec_fin)
    {
        // dd($datos);
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            $pdf->Image($logo, 12, 12, 55, 16);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(60, 10);
        $pdf->Cell(105, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(105, 8, utf8_decode('CUENTAS POR COBRAR'), 0, 2, 'C', 0);

        $pdf->SetXY(165, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, $fecha_actual, 0, 2, 'R', 0);
        if ($fec_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini != null && $fec_fin != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(15, 5, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        //HEADER FOR TABLE
        $pdf->SetY(32);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(15, 5, 'CODIGO', 'TB', 0, 'C', 1);
        $pdf->Cell(85, 5, 'CLIENTE', 'TB', 0, 'C', 1);
        // $pdf->Cell(25, 5, 'SALDO ANTERIOR', 'TB', 0, 'C', 1);
        $pdf->Cell(25, 5, '', 'TB', 0, 'C', 1);
        $pdf->Cell(20, 5, 'VENTAS', 'TB', 0, 'C', 1);
        $pdf->Cell(20, 5, 'ABONOS', 'TB', 0, 'C', 1);
        $pdf->Cell(25, 5, 'SALDO ACTUAL', 'TB', 0, 'C', 1);
        $pdf->Ln();

        //fill cells
        $tsant = 0;
        $tdebe = 0;
        $thaber = 0;
        $tsactual = 0;
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetY(38);
        foreach ($datos as $detail) {
            $pdf->Cell(15, 4, $detail->codigo, 0, 0, 'L', 1);
            $pdf->Cell(85, 4, utf8_decode($detail->nombre), 0, 0, 'L', 1);
            // $pdf->Cell(25, 4, $detail->saldo_anterior, 0, 0, 'R', 1);
            $pdf->Cell(25, 4, '', 0, 0, 'R', 1);
            $pdf->Cell(20, 4, $detail->saldo_anterior, 0, 0, 'R', 1);
            $pdf->Cell(20, 4, $detail->abono, 0, 0, 'R', 1);
            $pdf->Cell(25, 4, $detail->saldo_actual, 0, 0, 'R', 1);
            $pdf->Ln();
            $tsant = $detail->saldo_anterior + $tsant;
            $tdebe = $detail->saldo_anterior + $tdebe;
            $thaber = $detail->abono + $thaber;
            $tsactual = $detail->saldo_actual + $tsactual;
        }

        //footer
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(100, 6, 'TOTAL:', 'T', 0, 'R', 1);
        // $pdf->Cell(25, 6, $tsant, 'T', 0, 'R', 1);
        $pdf->Cell(25, 6, '', 'T', 0, 'R', 1);
        $pdf->Cell(20, 6, number_format(floatval($tdebe),2), 'T', 0, 'R', 1);
        $pdf->Cell(20, 6, number_format(floatval($thaber),2), 'T', 0, 'R', 1);
        $pdf->Cell(25, 6, number_format(floatval($tsactual),2), 'T', 0, 'R', 1);

        $pdf->Output("cuentas-por-cobrar.pdf", "D");
    }

    public function cuentasPorPagar($datos, $fec_ini, $fec_fin)
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();

        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        //$pdf->Image('../server/' . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo, null, null, 65, 25);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(60, 10);
        $pdf->Cell(105, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(105, 8, utf8_decode('RESUMEN CUENTAS POR PAGAR'), 0, 2, 'C', 0);

        $pdf->SetXY(165, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, $fecha_actual, 0, 2, 'R', 0);
        if ($fec_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else if ($fec_ini != null && $fec_fin != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = date("d/m/Y", strtotime($fec_fin));
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        $pdf->Cell(15, 5, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        //HEADER FOR TABLE
        $pdf->SetY(32);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(15, 5, 'CODIGO', 'TB', 0, 'C', 1);
        $pdf->Cell(85, 5, 'PROVEEDOR', 'TB', 0, 'C', 1);
        $pdf->Cell(25, 5, 'SALDO ANTERIOR', 'TB', 0, 'C', 1);
        $pdf->Cell(20, 5, 'COMPRAS', 'TB', 0, 'C', 1);
        $pdf->Cell(20, 5, 'ABONOS', 'TB', 0, 'C', 1);
        $pdf->Cell(25, 5, 'SALDO ACTUAL', 'TB', 0, 'C', 1);
        $pdf->Ln();

        //fill cells
        $tsant = 0;
        $tdebe = 0;
        $thaber = 0;
        $tsactual = 0;
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetY(38);
        foreach ($datos as $detail) {
            $pdf->Cell(15, 4, $detail->codigo, 0, 0, 'L', 1);
            $pdf->Cell(85, 4, utf8_decode($detail->nombre), 0, 0, 'L', 1);
            $pdf->Cell(25, 4, $detail->saldo_anterior, 0, 0, 'R', 1);
            $pdf->Cell(20, 4, $detail->saldo_anterior, 0, 0, 'R', 1);
            $pdf->Cell(20, 4, $detail->abono, 0, 0, 'R', 1);
            $pdf->Cell(25, 4, $detail->saldo_actual, 0, 0, 'R', 1);
            $pdf->Ln();
            $tsant = $detail->saldo_anterior + $tsant;
            $tdebe = $detail->saldo_anterior + $tdebe;
            $thaber = $detail->abono + $thaber;
            $tsactual = $detail->saldo_actual + $tsactual;
        }

        //footer
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(100, 6, 'TOTAL:', 'T', 0, 'R', 1);
        $pdf->Cell(25, 6, $tsant, 'T', 0, 'R', 1);
        $pdf->Cell(20, 6, $tdebe, 'T', 0, 'R', 1);
        $pdf->Cell(20, 6, $thaber, 'T', 0, 'R', 1);
        $pdf->Cell(25, 6, $tsactual, 'T', 0, 'R', 1);

        $pdf->Output("cuentas-por-pagar.pdf", "D");
    }
    public function empleado($datos, $fec_ini)
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $fecha_actual = date("d/m/Y");
        $pdf->AddPage();
        $pdf->AliasNbPages();
        $logo="";
        $pdf->RoundedRect(10, 10, 190, 20, 2, '1234', 'D');
        $logo = constant("DATA_EMPRESA") . "/" . $datos[0]->id_empresa . '/imagen/' . $datos[0]->logo;
        if (isset($datos[0]->logo) && file_exists($logo)) {
            $pdf->Image($logo, 12, 11, 55, 18);
        } else {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFont('Arial', 'B', 020);
            $pdf->Cell(50, 20, utf8_decode('NO TIENE LOGO'), 0, 2, 'C', 0);
        }
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(60, 10);
        $pdf->Cell(105, 10, utf8_decode($datos[0]->nombre_empresa), 0, 2, 'C', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(105, 8, utf8_decode('Empleados'), 0, 2, 'C', 0);

        $pdf->SetXY(165, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(15, 5, utf8_decode('Fecha:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Desde:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Hasta:'), 0, 2, 'L', 0);
        $pdf->Cell(15, 5, utf8_decode('Página:'), 0, 2, 'L', 0);
        $pdf->SetXY(180, 10);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(15, 5, $fecha_actual, 0, 2, 'R', 0);
        if ($fec_ini != null) {
            $fec_desde = date("d/m/Y", strtotime($fec_ini));
            $fec_hasta = $fecha_actual;
        } else {
            $fec_desde = $fecha_actual;
            $fec_hasta = $fecha_actual;
        }
        //dd($fec_ini);
        $pdf->Cell(15, 5, $fec_desde, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $fec_hasta, 0, 2, 'R', 0);
        $pdf->Cell(15, 5, $pdf->PageNo() . ' de {nb}', 0, 2, 'R', 0);

        //HEADER FOR TABLE
        $pdf->SetY(34);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(75, 5, 'NOMBRE', 'TB', 0, 'C', 1);
        $pdf->Cell(30, 5, 'No IDENTIFICACION', 'TB', 0, 'C', 1);
        $pdf->Cell(35, 5, 'DEPARTAMENTO', 'TB', 0, 'C', 1);
        $pdf->Cell(20, 5, 'CARGO', 'TB', 0, 'C', 1);
        $pdf->Cell(10, 5, 'EDAD', 'TB', 0, 'C', 1);
        $pdf->Cell(15, 5, 'SUELDO', 'TB', 0, 'C', 1);
        $pdf->Ln();

        //fill cells
        $tsant = 0;
        $tdebe = 0;
        $thaber = 0;
        $tsactual = 0;
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetY(40);
        foreach ($datos as $detail) {
            $pdf->Cell(75, 4, utf8_decode($detail->apellido_paterno).' '.utf8_decode($detail->apellido_materno).' '.utf8_decode($detail->primer_nombre).' '.$detail->segundo_nombre,0, 0, 'L', 1);
            $pdf->Cell(27, 4, $detail->dni, 0, 0, 'R', 1);
            $pdf->Cell(38, 4, utf8_decode($detail->dep_nombre), 0, 0, 'C', 1);
            $pdf->Cell(20, 4, utf8_decode($detail->car_nombre), 0, 0, 'C', 1);
            $pdf->Cell(10, 4, $detail->edad, 0, 0, 'R', 1);
            $pdf->Cell(15, 4, $detail->sueldo, 0, 0, 'R', 1);
            $pdf->Ln();
        }
        $pdf->Output("empleados.pdf", "D");

        
    }

}
