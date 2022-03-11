<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Compra: {{ proveedor }}
            </h3>
            <div class="card-tools">
                <router-link to="/compras" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <b class="text-primary">Detalle del Comprobante</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="proveedor" class="col-form-label"
                        >Proveedor:</label
                    >
                    <p v-text="proveedor"></p>
                </div>
                <div class="col-sm-3">
                    <label for="comprobante" class="col-form-label"
                        >Comprobante:</label
                    >
                    <p v-text="tip_comprobante + '-' + num_comprobante"></p>
                </div>
                <div class="col-sm-3">
                    <label for="fec_emision" class="col-form-label"
                        >Fecha Emisión:</label
                    >
                    <p v-text="fec_emision"></p>
                </div>
                <div class="col-sm-3">
                    <label for="for_pago" class="col-form-label"
                        >Forma de Pago:</label
                    >
                    <p v-if="for_pago == 'E'">EFECTIVO</p>
                    <p v-else-if="for_pago == 'C'">CRÉDITO</p>
                </div>
            </div>
            <b class="text-primary">Detalle de Productos</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-sm-12 table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 35%">
                                    Producto
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Cantidad
                                </th>
                                <th class="text-center" style="width: 15%">
                                    Fec. Cad.
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Precio
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Desc. %
                                </th>
                                <th class="text-center" style="width: 15%">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="arrayDetalle.length">
                            <tr
                                v-for="detalle in arrayDetalle"
                                :key="detalle.id"
                            >
                                <td v-text="detalle.producto"></td>
                                <td
                                    align="right"
                                    v-text="detalle.cantidad"
                                ></td>
                                <td v-text="detalle.fec_vencimiento"></td>
                                <td align="right" v-text="detalle.precio"></td>
                                <td
                                    align="right"
                                    v-text="detalle.descuento"
                                ></td>
                                <td align="right">
                                    $
                                    {{
                                        (
                                            detalle.precio * detalle.cantidad -
                                            detalle.descuento
                                        ).toFixed(2)
                                    }}
                                </td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="5" align="right">
                                    <strong>Subtotal 0:</strong>
                                </td>
                                <td align="right">$ {{ sub_0 }}</td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="5" align="right">
                                    <strong>Descuento:</strong>
                                </td>
                                <td align="right">$ {{ tot_desc }}</td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="5" align="right">
                                    <strong>Subtotal 12:</strong>
                                </td>
                                <td align="right">$ {{ sub_12 }}</td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="5" align="right">
                                    <strong>Iva 12%:</strong>
                                </td>
                                <td align="right">
                                    $
                                    {{ calcularImpuesto.toFixed(2) }}
                                </td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="5" align="right">
                                    <strong>Total Neto:</strong>
                                </td>
                                <td align="right">$ {{ total }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="7" class="text-center">
                                    NO hay artículos agregados
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <router-link to="/compras" class="btn btn-danger">
                Cancelar
            </router-link>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            proveedor: "",
            tip_comprobante: "",
            num_comprobante: "",
            fec_emision: "",
            for_pago: "",
            sub_0: 0,
            sub_12: 0,
            tot_desc: 0,
            total: 0,
            arrayDetalle: []
        };
    },
    computed: {
        calcularImpuesto() {
            var resultado = 0.0;
            resultado = this.sub_12 * 0.12;
            return resultado;
        }
    },
    methods: {
        detalle() {
            axios
                .get("/api/compra/detalle", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(resp => {
                    this.proveedor = resp.data.comprobante["proveedor"];
                    this.tip_comprobante =
                        resp.data.comprobante["tip_comprobante"];
                    this.num_comprobante =
                        resp.data.comprobante["num_comprobante"];
                    this.fec_emision = resp.data.comprobante["fec_emision"];
                    this.for_pago = resp.data.comprobante["for_pago"];
                    this.sub_0 = resp.data.comprobante["sub_0"];
                    this.sub_12 = resp.data.comprobante["sub_12"];
                    this.tot_desc = resp.data.comprobante["tot_desc"];
                    this.total = resp.data.comprobante["total"];
                    this.arrayDetalle = resp.data.detalles;
                });
        },
        limpiar() {
            this.proveedor = "";
            this.tip_comprobante = "";
            this.num_comprobante = "";
            this.fec_emision = "";
            this.for_pago = "";
            this.arrayDetalle = [];
            this.sub_0 = 0;
            this.sub_12 = 0;
            this.tot_desc = 0;
            this.total = 0;
        }
    },
    mounted() {
        this.detalle();
    }
};
</script>
