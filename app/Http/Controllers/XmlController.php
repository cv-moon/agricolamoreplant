<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use XMLWriter;

class XmlController extends Controller
{
    public function efactura(Request $request)
    {
        if (!file_exists('archivos/comprobantes/facturas')) {
            mkdir('archivos/comprobantes/facturas', 0777, true);
        }

        file_put_contents("archivos/comprobantes/facturas/" . $request->factura['cla_acceso'] . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri('archivos/comprobantes/facturas/' . $request->factura['cla_acceso'] . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement('factura');
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.1.0");


        $xml->startElement("infoTributaria");

        if ($request->factura['tip_ambiente'] == 0) {
            $xml->startElement("ambiente");
            $xml->text(1);
            $xml->endElement();
        } else if ($request->factura['tip_ambiente'] == 1) {
            $xml->startElement("ambiente");
            $xml->text(2);
            $xml->endElement();
        }

        $xml->startElement("tipoEmision");
        $xml->text($request->factura['tip_emision']);
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text($request->factura['raz_social']);
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text($request->factura['nom_comercial']);
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text($request->factura['ruc']);
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text($request->factura['cla_acceso']);
        $xml->endElement();

        $xml->startElement("codDoc");
        $xml->text('01');
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text(str_pad($request->factura['numero'], 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text(str_pad($request->factura['codigo'], 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text(substr($request->factura['cla_acceso'], -19, -10));
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text($request->factura['dir_matriz']);
        $xml->endElement();

        // if ($request->factura['reg_microempresa'] == 1) {
        //     $xml->startElement("regimenMicroempresas");
        //     $xml->text('CONTRIBUYENTE REGIMEN MICROEMPRESAS');
        //     $xml->endElement();
        // }

        $xml->endElement();

        $xml->startElement('infoFactura');

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($request->factura['fec_emision'])));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text($request->factura['dir_establecimiento']);
        $xml->endElement();

        $xml->startElement("obligadoContabilidad");
        if ($request->factura['obli_contabilidad'] == 1) {
            $xml->text('SI');
        } elseif ($request->factura['obli_contabilidad'] == 0) {
            $xml->text('NO');
        }
        $xml->endElement();

        $xml->startElement("tipoIdentificacionComprador");
        $xml->text($request->factura['tip_cliente']);
        $xml->endElement();

        $xml->startElement("razonSocialComprador");
        $xml->text($request->factura['nombre']);
        $xml->endElement();

        $xml->startElement("identificacionComprador");
        $xml->text($request->factura['num_identificacion']);
        $xml->endElement();

        $xml->startElement("direccionComprador");
        $xml->text($request->factura['dir_cliente']);
        $xml->endElement();

        $xml->startElement("totalSinImpuestos");
        $xml->text($request->factura['sub_sin_imp']);
        $xml->endElement();

        $xml->startElement("totalDescuento");
        $xml->text($request->factura['tot_descuento']);
        $xml->endElement();

        $xml->startElement("totalConImpuestos");

        if ($request->factura['sub_0'] > 0) {
            $xml->startElement("totalImpuesto");

            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text('0');
            $xml->endElement();

            $xml->startElement("baseImponible");
            $xml->text($request->factura['sub_0']);
            $xml->endElement();

            $xml->startElement("valor");
            $xml->text('0.00');
            $xml->endElement();

            $xml->endElement();
        }
        if ($request->factura['sub_iva'] > 0) {
            $xml->startElement("totalImpuesto");

            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text('2');
            $xml->endElement();

            $xml->startElement("baseImponible");
            $xml->text($request->factura['sub_iva']);
            $xml->endElement();

            $xml->startElement("valor");
            $xml->text(number_format($request->factura['val_iva'], 2));
            $xml->endElement();

            $xml->endElement();
        }
        $xml->endElement();

        if ($request->factura['val_propina'] > 0) {
            $xml->startElement("propina");
            $xml->text($request->factura['val_propina']);
            $xml->endElement();
        } else {
            $xml->startElement("propina");
            $xml->text("0.00");
            $xml->endElement();
        }

        $xml->startElement("importeTotal");
        $xml->text($request->factura['val_total']);
        $xml->endElement();

        $xml->startElement("moneda");
        $xml->text(strtoupper('DOLAR'));
        $xml->endElement();

        $xml->startElement("pagos");

        $xml->startElement("pago");

        $xml->startElement("formaPago");
        $xml->text($request->factura['forma']);
        $xml->endElement();

        $xml->startElement("total");
        $xml->text($request->factura['val_total']);
        $xml->endElement();

        if ($request->factura['for_pago'] == 'C') {
            $xml->startElement("plazo");
            $xml->text($request->credito['dias_credito']);
            $xml->endElement();
        } else {
            $xml->startElement("plazo");
            $xml->text(0);
            $xml->endElement();
        }

        $xml->startElement("unidadTiempo");
        $xml->text('dias');
        $xml->endElement();

        $xml->endElement();

        $xml->endElement();
        $xml->endElement();

        $xml->startElement('detalles');
        foreach ($request->detalles as $key => $det) {
            $xml->startElement('detalle');

            $xml->startElement('codigoPrincipal');
            $xml->text($det["cod_principal"]);
            $xml->endElement();

            $xml->startElement('descripcion');
            $xml->text($det["nombre"]);
            $xml->endElement();

            $xml->startElement('cantidad');
            $xml->text($det["det_cantidad"]);
            $xml->endElement();

            $xml->startElement('precioUnitario');
            $xml->text($det["pre_venta"]);
            $xml->endElement();

            if ($det["det_descuento"]) {
                $xml->startElement('descuento');
                $xml->text($det["det_descuento"]);
                $xml->endElement();
            } else {
                $xml->startElement('descuento');
                $xml->text('0.00');
                $xml->endElement();
            }

            $xml->startElement('precioTotalSinImpuesto');
            $xml->text($det["det_total"]);
            $xml->endElement();

            // if ($det["descripcion"]) {
            //     $xml->startElement('detallesAdicionales');
            //     $xml->startElement('detAdicional');
            //     $xml->writeAttribute("nombre", "descripcion");
            //     $xml->writeAttribute("valor", $det["descripcion"]);
            //     $xml->endElement();
            //     $xml->endElement();
            // }

            $xml->startElement('impuestos');
            $xml->startElement('impuesto');

            $xml->startElement('codigo');
            $xml->text(2);
            $xml->endElement();

            $xml->startElement("codigoPorcentaje");
            $xml->text($det["codigo"]);
            $xml->endElement();

            if ($det["codigo"] == 2) {
                $xml->startElement("tarifa");
                $xml->text('12.00');
                $xml->endElement();
            } else {
                $xml->startElement("tarifa");
                $xml->text('0.00');
                $xml->endElement();
            }

            $xml->startElement('baseImponible');
            $xml->text($det['det_total']);
            $xml->endElement();

            if ($det["codigo"] == 2) {
                $xml->startElement("valor");
                $xml->text(number_format($det['det_total'] * 0.12, 2));
                $xml->endElement();
            } else {
                $xml->startElement("valor");
                $xml->text('0.00');
                $xml->endElement();
            }
            $xml->endElement();
            $xml->endElement();
            $xml->endElement();
        }
        $xml->endElement();

        $xml->startElement('infoAdicional');
        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Dirección");
        $xml->text($request->factura['dir_cliente']);
        $xml->endElement();

        if ($request->factura['telefonos']) {

            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text($request->factura['telefonos']);
            $xml->endElement();
        } else {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text('sin número');
            $xml->endElement();
        }
        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Email");
        $xml->text($request->factura['email']);
        $xml->endElement();

        if ($request->factura['for_pago'] == 'E') {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "F. Pago");
            $xml->text('EFECTIVO');
            $xml->endElement();
        }

        if ($request->factura['for_pago'] == 'C') {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "F. Pago");
            $xml->text('CRÉDITO');
            $xml->endElement();
        }
        if ($request->factura['for_pago'] == 'C') {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "D. Crédito");
            $xml->text($request->credito['dias_credito']);
            $xml->endElement();
        }

        $xml->endElement();
        $xml->endElement();

        return [
            'firma' => $request->factura['firma'],
            'clave' => $request->factura['fir_clave'],
            'tipo' => 'factura_venta',
            'archivo' => 'archivos/comprobantes/facturas/' . $request->factura['cla_acceso'] . ".xml",
            'carpeta' => 'archivos/comprobantes/facturas/',
            'id' => $request->factura['id']
        ];
    }

    public function eRetencion(Request $request)
    {
        if (!file_exists('archivos/comprobantes/retenciones')) {
            mkdir('archivos/comprobantes/retenciones', 0777, true);
        }

        file_put_contents("archivos/comprobantes/retenciones/" . $request->retencion['cla_acceso'] . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri('archivos/comprobantes/retenciones/' . $request->retencion['cla_acceso'] . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement("comprobanteRetencion");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");

        $xml->startElement("infoTributaria");

        if ($request->retencion['tip_ambiente'] == 0) {
            $xml->startElement("ambiente");
            $xml->text(1);
            $xml->endElement();
        } else if ($request->retencion['tip_ambiente'] == 1) {
            $xml->startElement("ambiente");
            $xml->text(2);
            $xml->endElement();
        }

        $xml->startElement("tipoEmision");
        $xml->text($request->retencion['tip_emision']);
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text($request->retencion['raz_social']);
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text($request->retencion['nom_comercial']);
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text($request->retencion['ruc']);
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text($request->retencion['cla_acceso']);
        $xml->endElement();

        $xml->startElement("codDoc");
        $xml->text('07');
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text(str_pad($request->retencion['numero'], 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text(str_pad($request->retencion['codigo'], 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text(substr($request->retencion['cla_acceso'], -19, -10));
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text($request->retencion['dir_matriz']);
        $xml->endElement();

        // if ($request->retencion['reg_microempresa'] == 1) {
        //     $xml->startElement("regimenMicroempresas");
        //     $xml->text('CONTRIBUYENTE REGIMEN MICROEMPRESAS');
        //     $xml->endElement();
        // }

        $xml->endElement();

        $xml->startElement('infoCompRetencion');

        $xml->startElement("fechaEmision");
        $xml->text(date('d/m/Y', strtotime($request->retencion['fec_emision'])));
        $xml->endElement();

        $xml->startElement("dirEstablecimiento");
        $xml->text($request->retencion['dir_establecimiento']);
        $xml->endElement();

        $xml->startElement("obligadoContabilidad");
        if ($request->retencion['obli_contabilidad'] == 1) {
            $xml->text('SI');
        } elseif ($request->retencion['obli_contabilidad'] == 0) {
            $xml->text('NO');
        }
        $xml->endElement();

        $xml->startElement("tipoIdentificacionSujetoRetenido");
        $xml->text('04');
        $xml->endElement();

        $xml->startElement("razonSocialSujetoRetenido");
        $xml->text($request->retencion['nombre']);
        $xml->endElement();

        $xml->startElement("identificacionSujetoRetenido");
        $xml->text($request->retencion['num_identificacion']);
        $xml->endElement();

        $xml->startElement("periodoFiscal");
        $xml->text(date('m/Y', strtotime($request->retencion['fec_emision'])));
        $xml->endElement();
        
        
        $xml->endElement();

        $xml->startElement('impuestos');
        foreach ($request->detalles as $key => $det) {
            $xml->startElement('impuesto');

            $xml->startElement('código');
            $xml->text($det["cod_impuesto"]);
            $xml->endElement();

            $xml->startElement('codigoRetencion');
            $xml->text($det["cod_tarifa"]);
            $xml->endElement();

            $xml->startElement('baseImponible');
            $xml->text($det["bas_imponible"]);
            $xml->endElement();

            $xml->startElement('porcentajeRetener');
            $xml->text($det["por_retener"]);
            $xml->endElement();

            $xml->startElement('valorRetenido');
            $xml->text($det["val_retenido"]);
            $xml->endElement();

            $xml->startElement('codDocSustento');
            $xml->text($det["cod_doc_sustento"]);
            $xml->endElement();

            $xml->startElement('numDocSustento');
            $xml->text($det["num_comprobante"]);
            $xml->endElement();

            $xml->startElement('fechaEmisionDocSustent');
            $xml->text(date('d/m/Y', strtotime($request->retencion['fec_emision'])));
            $xml->endElement();

            $xml->endElement();
        }
        $xml->endElement();

        $xml->endElement();

        return
            [
                'firma' => $request->retencion['firma'],
                'clave' => $request->retencion['fir_clave'],
                'tipo' => 'retencion',
                'archivo' => 'archivos/comprobantes/retenciones/' . $request->retencion['cla_acceso'] . ".xml",
                'carpeta' => 'archivos/comprobantes/retenciones/',
                'id' => $request->retencion['id']
            ];
    }

    public function e_guia(Request $request)
    {
        $xml = new XMLWriter();
        $xml->openUri(constant("DATA_EMPRESA") . $request->id_empresa . '/comprobantes/guia/' . $request->clave_acceso . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement("guiaRemision");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        //infoTributaria

        $xml->startElement("infoTributaria");

        $xml->startElement("ambiente");
        $xml->text($request->ambiente);
        $xml->endElement();

        $xml->startElement("tipoEmision");
        $xml->text($request->tipo_emision);
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text($request->razon_social);
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text($request->nombre_empresa);
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text($request->ruc_empresa);
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text($request->clave_acceso);
        $xml->endElement();
        $xml->startElement("codDoc");
        $xml->text('01');
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text(str_pad($request->codigoes, 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text(str_pad($request->codigope, 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text(substr($request->clave_acceso, -19, -10));
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text($request->direccion_empresa);
        $xml->endElement();

        $xml->endElement();

        $xml->startElement("infoGuiaRemision");

        $xml->startElement("dirEstablecimiento");
        $xml->text($request->direccion_empresa);
        $xml->endElement();

        $xml->startElement("dirPartida");
        $xml->text($request->direccion);
        $xml->endElement();

        $xml->startElement("razonSocialTransportista");
        $xml->text($request->razon_social_tr);
        $xml->endElement();

        if ($request->tipo_identificacion_tr == "Cédula de Identidad") {
            $xml->startElement("tipoIdentificacionTransportista");
            $xml->text('05');
            $xml->endElement();
        } else if ($request->tipo_identificacion_tr == "Ruc") {
            $xml->startElement("tipoIdentificacionTransportista");
            $xml->text('04');
            $xml->endElement();
        } else if ($request->tipo_identificacion_tr == "Pasaporte") {
            $xml->startElement("tipoIdentificacionTransportista");
            $xml->text('06');
            $xml->endElement();
        } else if ($request->tipo_identificacion_tr == "Consumidor Final") {
            $xml->startElement("tipoIdentificacionTransportista");
            $xml->text('07');
            $xml->endElement();
        }

        $xml->startElement("rucTransportista");
        $xml->text($request->identificacion_tr);
        $xml->endElement();

        if ($request->obligado_contabilidad == 0) {
            $obligado = "NO";
        } else {
            $obligado = "SI";
        }
        $xml->startElement("obligadoContabilidad");
        $xml->text($obligado);
        $xml->endElement();

        $xml->startElement("fechaIniTransporte");
        $xml->text(date('d/m/Y', strtotime($request->fecha_inicio_tr)));
        $xml->endElement();

        $xml->startElement("fechaFinTransporte");
        $xml->text(date('d/m/Y', strtotime($request->fecha_fin_tr)));
        $xml->endElement();

        $xml->startElement("placa");
        $xml->text($request->placa_tr);
        $xml->endElement();

        $xml->endElement();

        $xml->startElement("destinatarios");
        $xml->startElement("destinatario");

        $xml->startElement("identificacionDestinatario");
        $xml->text($request->identificacion);
        $xml->endElement();

        $xml->startElement("razonSocialDestinatario");
        $xml->text($request->nombre);
        $xml->endElement();

        $xml->startElement("dirDestinatario");
        $xml->text($request->direccion);
        $xml->endElement();

        $xml->startElement("motivoTraslado");
        $xml->text($request->motivo_translado_tr);
        $xml->endElement();

        $xml->startElement("docAduaneroUnico");
        $xml->text($request->doc_aduanero_tr);
        $xml->endElement();
        if ($request->cod_establecimiento_tr) {
            $xml->startElement("codEstabDestino");
            $xml->text($request->cod_establecimiento_tr);
            $xml->endElement();
        }
        if ($request->ruta_tr) {
            $xml->startElement("ruta");
            $xml->text($request->ruta_tr);
            $xml->endElement();
        }
        if ($request->cod_sustento_tr) {
            $xml->startElement("codDocSustento");
            $xml->text(str_pad($request->cod_sustento_tr, 2, "0", STR_PAD_LEFT));
            $xml->endElement();
        }

        $xml->startElement("numDocSustento");
        $xml->text(str_pad($request->codigoes, 3, "0", STR_PAD_LEFT) . '-' . str_pad($request->codigope, 3, "0", STR_PAD_LEFT) . "-" . "000000001");
        $xml->endElement();
        $rand = rand(000000001, 9999999999);
        if ($request->num_aut_sustento_tr) {
            $xml->startElement("numAutDocSustento");
            $xml->text($rand);
            $xml->endElement();
        }

        $xml->startElement("fechaEmisionDocSustento");
        $xml->text(date('d/m/Y', strtotime($request->fcrea)));
        $xml->endElement();

        $xml->startElement("detalles");

        // $det = DetalleGuiaRemision::select("*")->where("id_guia_remision", "=", $request->id_guia)->get();
        // for ($i = 0; $i < count($det); $i++) {
        //     $xml->startElement("detalle");

        //     $xml->startElement("codigoInterno");
        //     $xml->text($det[$i]["codigo_interno"]);
        //     $xml->endElement();

        //     $xml->startElement("descripcion");
        //     $xml->text($det[$i]["descripcion"]);
        //     $xml->endElement();

        //     $xml->startElement("cantidad");
        //     $xml->text($det[$i]["cantidad"]);
        //     $xml->endElement();

        //     $xml->endElement();
        // }

        $xml->endElement();

        $xml->endElement();
        $xml->endElement();
        $xml->startElement("infoAdicional");
        $xml->startElement("campoAdicional");
        $xml->writeAttribute("nombre", "email");
        $xml->text($request->email);
        $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();
    }
}
