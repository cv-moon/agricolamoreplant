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
        $xml->openUri("archivos/comprobantes/retenciones/" . $request->retencion['cla_acceso'] . ".xml");
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

            $xml->startElement('codigo');
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

            $xml->startElement('fechaEmisionDocSustento');
            $xml->text(date('d/m/Y', strtotime($request->retencion['fec_emision'])));
            $xml->endElement();

            $xml->endElement();
        }
        $xml->endElement();
        $xml->startElement('infoAdicional');

        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Email");
        $xml->text($request->retencion['email']);
        $xml->endElement();

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

    public function eGuia(Request $request)
    {
        if (!file_exists('archivos/comprobantes/guias')) {
            mkdir('archivos/comprobantes/guias', 0777, true);
        }

        file_put_contents("archivos/comprobantes/guias/" . $request->guia['cla_acceso'] . ".xml", "");

        $xml = new XMLWriter();
        $xml->openUri("archivos/comprobantes/guias/" . $request->guia['cla_acceso'] . ".xml");
        $xml->setIndent(true);
        $xml->setIndentString("\t");
        $xml->startDocument('1.0', 'utf-8');

        $xml->startElement("guiaRemision");
        $xml->writeAttribute("id", "comprobante");
        $xml->writeAttribute("version", "1.0.0");
        //infoTributaria

        $xml->startElement("infoTributaria");
        if ($request->guia['tip_ambiente'] == 0) {
            $xml->startElement("ambiente");
            $xml->text(1);
            $xml->endElement();
        } else if ($request->guia['tip_ambiente'] == 1) {
            $xml->startElement("ambiente");
            $xml->text(2);
            $xml->endElement();
        }

        $xml->startElement("tipoEmision");
        $xml->text($request->guia['tip_emision']);
        $xml->endElement();

        $xml->startElement("razonSocial");
        $xml->text($request->guia['raz_social']);
        $xml->endElement();

        $xml->startElement("nombreComercial");
        $xml->text($request->guia['nom_comercial']);
        $xml->endElement();

        $xml->startElement("ruc");
        $xml->text($request->guia['ruc']);
        $xml->endElement();

        $xml->startElement("claveAcceso");
        $xml->text($request->guia['cla_acceso']);
        $xml->endElement();

        $xml->startElement("codDoc");
        $xml->text('06');
        $xml->endElement();

        $xml->startElement("estab");
        $xml->text(str_pad($request->guia['numero'], 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("ptoEmi");
        $xml->text(str_pad($request->guia['codigo'], 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        $xml->startElement("secuencial");
        $xml->text(substr($request->guia['cla_acceso'], -19, -10));
        $xml->endElement();

        $xml->startElement("dirMatriz");
        $xml->text($request->guia['dir_matriz']);
        $xml->endElement();

        // if ($request->guia['reg_microempresa'] == 1) {
        //     $xml->startElement("regimenMicroempresas");
        //     $xml->text('CONTRIBUYENTE REGIMEN MICROEMPRESAS');
        //     $xml->endElement();
        // }

        $xml->endElement();

        $xml->startElement("infoGuiaRemision");

        $xml->startElement("dirEstablecimiento");
        $xml->text($request->guia['dir_establecimiento']);
        $xml->endElement();

        $xml->startElement("dirPartida");
        $xml->text($request->guia['dir_establecimiento']);
        $xml->endElement();

        $xml->startElement("razonSocialTransportista");
        $xml->text($request->guia['transportista']);
        $xml->endElement();

        $xml->startElement("tipoIdentificacionTransportista");
        $xml->text($request->guia['tip_transportista']);
        $xml->endElement();

        $xml->startElement("rucTransportista");
        $xml->text($request->guia['num_identificacion']);
        $xml->endElement();

        $xml->startElement("obligadoContabilidad");
        if ($request->guia['obli_contabilidad'] == 1) {
            $xml->text('SI');
        } elseif ($request->guia['obli_contabilidad'] == 0) {
            $xml->text('NO');
        }
        $xml->endElement();

        $xml->startElement("fechaIniTransporte");
        $xml->text(date('d/m/Y', strtotime($request->guia['fec_inicio'])));
        $xml->endElement();

        $xml->startElement("fechaFinTransporte");
        $xml->text(date('d/m/Y', strtotime($request->guia['fec_fin'])));
        $xml->endElement();

        $xml->startElement("placa");
        $xml->text($request->guia['placa']);
        $xml->endElement();

        $xml->endElement();

        $xml->startElement("destinatarios");
        $xml->startElement("destinatario");

        $xml->startElement("identificacionDestinatario");
        $xml->text($request->guia['des_identificacion']);
        $xml->endElement();

        $xml->startElement("razonSocialDestinatario");
        $xml->text($request->guia['des_nombre']);
        $xml->endElement();

        $xml->startElement("dirDestinatario");
        $xml->text($request->guia['des_direccion']);
        $xml->endElement();

        $xml->startElement("motivoTraslado");
        $xml->text($request->guia['motivo']);
        $xml->endElement();

        // $xml->startElement("docAduaneroUnico");
        // $xml->text($request->doc_aduanero_tr);
        // $xml->endElement();

        $xml->startElement("codEstabDestino");
        $xml->text(str_pad($request->guia['numero'], 3, "0", STR_PAD_LEFT));
        $xml->endElement();

        if ($request->guia['ruta']) {
            $xml->startElement("ruta");
            $xml->text($request->guia['ruta']);
            $xml->endElement();
        }

        $xml->startElement("codDocSustento");
        $xml->text('01');
        $xml->endElement();


        $xml->startElement("numDocSustento");
        $xml->text(substr($request->guia['factura'], 24, 3) . '-' . substr($request->guia['factura'], 27, 3) . '-' . substr($request->guia['factura'], 30, 9));
        $xml->endElement();

        if ($request->num_aut_sustento_tr) {
            $xml->startElement("numAutDocSustento");
            $xml->text($request->guia['factura']);
            $xml->endElement();
        }

        $xml->startElement("fechaEmisionDocSustento");
        $xml->text(date('d/m/Y', strtotime($request->guia['fec_emision_comprobante'])));
        $xml->endElement();

        $xml->startElement("detalles");

        foreach ($request->detalles as $key => $det) {
            $xml->startElement("detalle");

            $xml->startElement("codigoInterno");
            $xml->text($det["cod_principal"]);
            $xml->endElement();

            $xml->startElement("descripcion");
            $xml->text($det["nombre"]);
            $xml->endElement();

            $xml->startElement("cantidad");
            $xml->text($det["det_cantidad"]);
            $xml->endElement();

            $xml->endElement();
        }

        $xml->endElement();

        $xml->endElement();
        $xml->endElement();
        $xml->startElement('infoAdicional');
        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Dirección");
        $xml->text($request->guia['tra_direccion']);
        $xml->endElement();

        if ($request->guia['tra_telefonos']) {

            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text($request->guia['tra_telefonos']);
            $xml->endElement();
        } else {
            $xml->startElement('campoAdicional');
            $xml->writeAttribute("nombre", "Teléfono");
            $xml->text('sin número');
            $xml->endElement();
        }
        $xml->startElement('campoAdicional');
        $xml->writeAttribute("nombre", "Email");
        $xml->text($request->guia['tra_email']);
        $xml->endElement();
        $xml->endElement();
        $xml->endDocument();

        return [
            'firma' => $request->guia['firma'],
            'clave' => $request->guia['fir_clave'],
            'tipo' => 'guia',
            'archivo' => 'archivos/comprobantes/guias/' . $request->guia['cla_acceso'] . ".xml",
            'carpeta' => 'archivos/comprobantes/guias/',
            'id' => $request->guia['id']
        ];
    }
}
