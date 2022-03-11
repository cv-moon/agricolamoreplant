<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Retención
            </h3>
            <div class="card-tools">
                <router-link to="/retenciones" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <b class="text-primary">Datos del Comprobante</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-2">
                    <label for="fec_emision" class="col-form-label"
                        >Fecha de Emisión:</label
                    >
                    <p class="col-form-label">{{ retencion.fec_emision }}</p>
                </div>
                <div class="col-2">
                    <label for="tip_comprobante" class="col-form-label"
                        >Comprobante:</label
                    >
                    <p class="col-form-label">{{ datos.tip_comprobante }}</p>
                </div>
                <div class="col-2">
                    <label for="comprobante" class="col-form-label"
                        ># Comprobante:</label
                    >
                    <p class="col-form-label">{{ datos.comprobante }}</p>
                </div>
                <div class="col-sm-6">
                    <label for="num_comprobante" class="col-form-label"
                        >Clave de Acceso:</label
                    >
                    <p class="col-form-label">
                        {{ retencion.cla_acceso }}
                    </p>
                </div>
            </div>
            <b class="text-primary">Datos de Retención</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-sm-12">
                    <label for="compra" class="ol-form-label"
                        >Factura Compra:</label
                    >
                    <select
                        v-model="retencion.compra_id"
                        class="form-control"
                        @change="getData(retencion.compra_id)"
                    >
                        <option value="0" disabled>Seleccione...</option>
                        <option
                            v-for="compra in arrayCompras"
                            :key="compra.id"
                            :value="compra.id"
                            v-text="
                                compra.num_comprobante +
                                    ' / ' +
                                    compra.fec_emision +
                                    ' / ' +
                                    compra.nombre
                            "
                        ></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12 table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="text-center"># Comprobante</th>
                                <th class="text-center">F. Emisión</th>
                                <th class="text-center">Eje. Fiscal</th>
                                <th class="text-center">Base Imponible</th>
                                <th class="text-center">Imp.</th>
                                <th class="text-center">Tarifa</th>
                                <th class="text-center">% Ret.</th>
                                <th class="text-center">T. Ret.</th>
                            </tr>
                        </thead>
                        <tbody v-if="arrayDetalle.length">
                            <tr
                                v-for="(detalle, index) in arrayDetalle"
                                :key="detalle.id"
                            >
                                <td
                                    v-text="
                                        detalle.comprobante +
                                            ' ' +
                                            detalle.num_comprobante
                                    "
                                ></td>
                                <td v-text="detalle.fec_emi_comprobante"></td>
                                <td
                                    align="center"
                                    v-text="detalle.eje_fiscal"
                                ></td>
                                <td
                                    align="right"
                                    v-text="detalle.bas_imponible"
                                ></td>
                                <td
                                    align="center"
                                    v-text="detalle.imp_retencion"
                                ></td>
                                <td>
                                    <select
                                        v-model="detalle.tarifa_retencion_id"
                                        class="form-control"
                                        @change="
                                            obtener(
                                                index,
                                                detalle.tarifa_retencion_id
                                            )
                                        "
                                    >
                                        <option value="0" disabled
                                            >Seleccione...</option
                                        >
                                        <option
                                            v-for="tarifa in detalle.arrayImpuesto"
                                            :key="tarifa.id"
                                            :value="tarifa.id"
                                            v-text="
                                                tarifa.codigo +
                                                    ' - ' +
                                                    tarifa.valor +
                                                    '%'
                                            "
                                        ></option>
                                    </select>
                                </td>
                                <td align="right">
                                    {{ detalle.porcentaje }}
                                </td>
                                <td align="right">
                                    {{ detalle.val_retenido }}
                                </td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="7" align="right">
                                    <strong>Total a Retener:</strong>
                                </td>
                                <td align="right">
                                    $
                                    {{
                                        (retencion.tot_retenido = calculaTotalRetencion)
                                    }}
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="8" class="text-center">
                                    NO se ha seleccionado un comprobante de
                                    compra.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <router-link to="/retenciones" class="btn btn-danger">
                Cancelar
            </router-link>
            <button
                type="button"
                class="btn btn-success"
                :disabled="retencion.compra_id == 0"
                @click="guardar"
            >
                Guardar
            </button>
        </div>
    </div>
</template>
<script>
import script_comprobantes from "../../../factura";
export default {
    data() {
        return {
            retencion: {
                compra_id: 0,
                punto_id: 0,
                proveedor_id: 0,
                cla_acceso: "",
                fec_emision: "",
                num_secuencial: "",
                tip_ambiente: 0,
                tip_emision: 0,
                eje_fiscal: "",
                tot_retenido: 0
            },
            datos: {
                comprobante: "",
                firma: "",
                fir_clave: "",
                tip_comprobante: "RETENCIÓN"
            },
            arrayCompras: [],
            arrayDetalle: [],
            arrayTarifas: []
        };
    },
    computed: {
        calculaTotalRetencion() {
            let res = 0;
            for (let i = 0; i < this.arrayDetalle.length; i++) {
                res = res + parseFloat(this.arrayDetalle[i].val_retenido);
            }
            return res.toFixed(2);
        }
    },
    methods: {
        obtener(index = 0, id = 0) {
            let res = 0;
            let data = [];
            data = this.arrayTarifas.find(e => e.id == id);
            res = this.arrayDetalle[index].bas_imponible * (data.valor / 100);
            return (
                (this.arrayDetalle[index].val_retenido = res.toFixed(2)),
                (this.arrayDetalle[index].porcentaje = data.valor)
            );
        },
        selectCompra() {
            axios.get("/api/compra/buscar").then(resp => {
                this.arrayCompras = resp.data;
            });
        },
        getCompra() {
            this.$route.params.fact
                ? (this.retencion.compra_id = this.$route.params.fact)
                : (this.retencion.compra_id = 0);
        },
        getData(id = 0) {
            this.arrayDetalle = [];
            let data = null;
            data = this.arrayCompras.find(e => e.id == id);
            this.retencion.eje_fiscal =
                new Date().getMonth() + 1 + "/" + new Date().getFullYear();
            this.retencion.proveedor_id = data.proveedor_id;
            if (data.sub_0 > 0) {
                let arrayImpuesto = this.arrayTarifas.filter(
                    e => e.impuesto_id == 1
                );
                this.arrayDetalle.push({
                    compra_id: data.id,
                    comprobante_id: 1,
                    tarifa_retencion_id: 0,
                    comprobante: data.tip_comprobante,
                    num_comprobante: data.num_comprobante,
                    fec_emi_comprobante: data.fec_emision,
                    eje_fiscal: this.retencion.eje_fiscal,
                    bas_imponible: data.sub_0,
                    imp_retencion: "RENTA",
                    arrayImpuesto,
                    porcentaje: 0,
                    val_retenido: 0
                });
            }
            if (data.sub_12 > 0) {
                let arrayImpuesto = this.arrayTarifas.filter(
                    e => e.impuesto_id == 2
                );
                this.arrayDetalle.push({
                    compra_id: data.id,
                    comprobante_id: 1,
                    tarifa_retencion_id: 0,
                    comprobante: data.tip_comprobante,
                    num_comprobante: data.num_comprobante,
                    fec_emi_comprobante: data.fec_emision,
                    eje_fiscal: this.retencion.eje_fiscal,
                    bas_imponible: data.sub_12,
                    imp_retencion: "IVA",
                    arrayImpuesto,
                    porcentaje: 0,
                    val_retenido: 0
                });
                console.log(this.arrayImpuesto);
            }
        },
        getTarifas() {
            axios.get("/api/tarifa-retencion/impuesto").then(resp => {
                this.arrayTarifas = resp.data;
            });
        },
        getRetencion() {
            axios.get("/api/retencion/comprobante").then(resp => {
                this.retencion.cla_acceso =
                    resp.data.cla_acceso + this.modulo11(resp.data.cla_acceso);
                this.retencion.fec_emision = resp.data.fec_emision;
                this.retencion.num_secuencial = resp.data.num_secuencial;
                this.retencion.punto_id = resp.data.punto_id;
                this.retencion.tip_ambiente = resp.data.tip_ambiente;
                this.retencion.tip_emision = resp.data.tip_emision;
                this.datos.comprobante = resp.data.comprobante;
                this.datos.firma = resp.data.firma;
                this.datos.fir_clave = resp.data.fir_clave;
            });
        },

        guardar() {
            // const condiciones = this.validaCampos();
            // if (condiciones.length) {
            //   return;
            // }
            Swal.fire({
                title: "Espere...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();

                    axios
                        .post("/api/retencion/guardar", {
                            punto_id: this.retencion.punto_id,
                            proveedor_id: this.retencion.proveedor_id,
                            fec_emision: this.retencion.fec_emision,
                            num_secuencial: this.retencion.num_secuencial,
                            tip_ambiente: this.retencion.tip_ambiente,
                            tip_emision: this.retencion.tip_emision,
                            cla_acceso: this.retencion.cla_acceso,
                            eje_fiscal: this.retencion.eje_fiscal,
                            tot_retenido: this.retencion.tot_retenido,
                            detalles: this.arrayDetalle
                        })
                        .then(resp => {
                            axios
                                .post("/api/xml_retencion", {
                                    retencion: resp.data.retencion,
                                    detalles: resp.data.detalles
                                })
                                .then(res => {
                                    this.crearfacturacion(
                                        "/" + res.data.firma,
                                        res.data.clave,
                                        res.data.archivo,
                                        res.data.tipo,
                                        res.data.id,
                                        res.data.carpeta
                                    );
                                });
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },

        // Métodos para retencion electronica.
        modulo11(numero) {
            let digito_calculado = -1;

            if (typeof numero == "string" && /^\d+$/.test(numero)) {
                let digitos = numero.split("").map(Number); //arreglo con los dígitos del número

                digito_calculado =
                    11 -
                    (digitos.reduce(function(valorPrevio, valorActual, indice) {
                        return valorPrevio + valorActual * (7 - (indice % 6));
                    }, 0) %
                        11);

                digito_calculado =
                    digito_calculado == 11 ? 0 : digito_calculado; //según ficha técnica
                digito_calculado =
                    digito_calculado == 10 ? 1 : digito_calculado; //según ficha técnica
            }
            return digito_calculado;
        },

        async crearfacturacion(firma, password, factura, tipo, id, carpeta) {
            try {
                let {
                    data: comprobante
                } = await script_comprobantes.obtener_comprobante_firmado.getAll(
                    {
                        factura: factura,
                        id_factura: id,
                        tipo: tipo
                    }
                );
                let {
                    resultado: contenido
                } = await script_comprobantes.lectura_firma.getAll({
                    firma: firma,
                    id_factura: id,
                    tipo: tipo
                });
                let {
                    data: certificado
                } = await script_comprobantes.firmar_comprobante.getAll({
                    contenido: contenido[0],
                    password: password,
                    comprobante: comprobante,
                    id_factura: id,
                    tipo: tipo
                });
                let {
                    data: quefirma
                } = await script_comprobantes.verificar_firma.getAll({
                    mensaje: certificado,
                    tipo: tipo,
                    id_factura: id,
                    carpeta: carpeta
                });
                let {
                    data: validado
                } = await script_comprobantes.validar_comprobante.getAll({
                    comprobante: comprobante,
                    tipo: tipo,
                    id_factura: id,
                    carpeta: carpeta
                });
                let {
                    data: recibida
                } = await script_comprobantes.autorizar_comprobante.getAll({
                    comprobante: comprobante,
                    validado: validado,
                    tipo: tipo,
                    id_factura: id,
                    carpeta: carpeta
                });
                let {
                    data: registrado
                } = await script_comprobantes.autorizado_comprobante.getAll({
                    recibida: recibida,
                    tipo: tipo,
                    id_factura: id
                });
                if (registrado == "enviado") {
                    Swal.fire(
                        "Bien!",
                        "La factura se envió exitosamente.",
                        "success"
                    );
                    this.$router.push("/retenciones");
                } else {
                    Swal.fire(
                        "Error!",
                        "La factura no pudo ser enviada, intente mas tarde.",
                        "error"
                    );
                    this.$router.push("/retenciones");
                }
            } catch (err) {
                Swal.fire("Error!", "Error en el envio al SRI" + err, "error");
                this.$router.push("/retenciones");
            }
        }
    },
    mounted() {
        this.getRetencion();
        this.getCompra();
        this.getTarifas();
    },
    created() {
        this.selectCompra();
    }
};
</script>
