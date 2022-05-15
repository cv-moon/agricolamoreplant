<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Orden de Trabajo
            </h3>
            <div class="card-tools">
                <router-link to="/odt" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <b class="text-primary">Datos del Cliente</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="dat_cliente" class="col-sm-12 col-form-label"
                        >Cliente:</label
                    >
                    <div class="input-group">
                        <div class="col-sm-11">
                            <v-select
                                :options="arrayCliente"
                                :getOptionLabel="
                                    option =>
                                        option.nombre +
                                        ' - ' +
                                        option.num_identificacion
                                "
                                @search="selectCliente"
                                placeholder="Buscar Cliente..."
                                v-model="selected"
                                @input="getId"
                            >
                            </v-select>
                        </div>
                        <button
                            title="Agregar Cliente"
                            class="btn btn-success btn-sm"
                            data-target="#modCliente"
                            @click="abrirCliente()"
                        >
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="col-3">
                    <label for="telefonos" class="col-form-label"
                        >Saldo Pendiente:</label
                    >
                    <h3 class="text-red">${{ info.sal_pendiente }}</h3>
                </div>
                <div class="col-3">
                    <label for="dis_facturar" class="col-form-label"
                        >Disponible a Facturar:</label
                    >
                    <h3 class="text-success">
                        ${{ info.dis_facturar.toFixed(2) }}
                    </h3>
                </div>
            </div>
            <b class="text-primary">Datos del Comprobante</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-2">
                    <label for="fec_emision" class="col-form-label"
                        >Fecha de Emisión:</label
                    >
                    <p class="col-form-label">{{ factura.fec_emision }}</p>
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
                    <p class="col-form-label">{{ factura.num_secuencial }}</p>
                </div>
            </div>
            <b class="text-primary">Detalle de Productos</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="for_pago" class="col-form-label"
                        >Producto:</label
                    >
                    <select
                        v-model="detalle.producto"
                        class="form-control form-control-sm"
                        @change="findPresentaciones"
                    >
                        <option value="0">Seleccione...</option>
                        <option
                            v-for="producto in arrayProductos"
                            :key="producto.id"
                            :value="producto"
                            v-text="producto.nombre"
                        ></option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="for_pago" class="col-form-label"
                        >Presentacion:</label
                    >
                    <select
                        v-model="detalle.presentacion"
                        class="form-control form-control-sm"
                        @change="getInfo(detalle.presentacion)"
                    >
                        <option value="0">Seleccione...</option>
                        <option
                            v-for="presentacion in arrayPresentaciones"
                            :key="presentacion.id"
                            :value="presentacion"
                            v-text="
                                presentacion.presentacion +
                                    ' ' +
                                    presentacion.sigla
                            "
                        ></option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label for="for_pago" class="col-form-label">Stock:</label>
                    <input
                        type="number"
                        class="form-control form-control-sm bg-info"
                        v-model="detalle.dis_stock"
                        readonly
                    />
                </div>
                <div class="col-sm-2">
                    <label for="for_pago" class="col-form-label">Cant:</label>
                    <input
                        type="number"
                        class="form-control form-control-sm"
                        min="1"
                        v-model="detalle.cantidad"
                    />
                </div>
                <div class="col-sm-2">
                    <label for="for_pago" class="col-form-label">Precio:</label>
                    <input
                        type="number"
                        class="form-control form-control-sm"
                        min="0"
                        v-model="detalle.precio"
                        readonly
                    />
                </div>
                <div class="col-sm-1">
                    <label for="for_pago" class="col-form-label">Desc:</label>
                    <input
                        type="number"
                        class="form-control form-control-sm"
                        min="0"
                        max="10"
                        v-model="detalle.descuento"
                        readonly
                    />
                </div>
                <div class="col-sm-1">
                    <button
                        type="button"
                        title="Agregar"
                        class="btn btn-success mt-4"
                        :disabled="detalle.button"
                        @click="addDetalle(detalle)"
                    >
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12 table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%">
                                    Acción
                                </th>
                                <th class="text-center" style="width: 35%">
                                    Producto
                                </th>
                                <th class="text-center" style="width: 10%">
                                    Cantidad
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
                                v-for="(detalle, index) in arrayDetalle"
                                :key="detalle.id"
                            >
                                <td align="center">
                                    <button
                                        @click="eliminarDetalle(index)"
                                        title="Eliminar Producto"
                                        type="button"
                                        class="btn btn-danger btn-xs"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                                <td v-text="detalle.nombre"></td>
                                <td v-text="detalle.det_cantidad"></td>
                                <td align="right">${{ detalle.det_precio }}</td>
                                <td align="right">
                                    ${{ detalle.det_descuento.toFixed(2) }}
                                </td>
                                <td align="right">
                                    $
                                    {{ detalle.det_total.toFixed(2) }}
                                </td>
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
            <div class="form-group row">
                <div class="col-7">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h5 class="card-title">ABONO</h5>
                        </div>
                        <div class="card-body"></div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card card-success card-outline">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    Subtotal sin Impuestos:
                                </div>
                                <div
                                    class="
                    col-sm-6
                    solor-palette-set
                    bg-lightblue
                    disabled
                    color-palette
                    text-right
                  "
                                >
                                    $
                                    {{
                                        (factura.sub_sin_imp = subtotalSinImpuesto.toFixed(
                                            2
                                        ))
                                    }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">Total Descuento:</div>
                                <div class="col-sm-6 bg-lightblue text-right">
                                    $
                                    {{
                                        (factura.tot_descuento = totalDescuento.toFixed(
                                            2
                                        ))
                                    }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6">Valor Total:</div>
                                <div
                                    class="
                    col-sm-6
                    bg-lightblue
                    disabled
                    color-palette
                    text-right
                  "
                                >
                                    $
                                    {{
                                        (factura.val_total = calcularTotal.toFixed(
                                            2
                                        ))
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="factura.errors.length" class="alert alert-danger">
                <div>
                    <div v-for="error in factura.errors" :key="error">
                        {{ error }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <router-link to="/odt" class="btn btn-danger">
                Cancelar
            </router-link>
            <button
                type="button"
                class="btn btn-success"
                @click="guardar"
                :disabled="disabled"
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
            selected: null,

            factura: {
                cliente_id: 0,
                punto_id: 0,
                usuario_id: 0,
                fec_emision: "",
                num_secuencial: 0,
                sub_sin_imp: 0,
                tot_descuento: 0,
                val_total: parseFloat(0).toFixed(2),
                errors: []
            },

            datos: {
                tip_comprobante: "ORDEN DE TRABAJO",
                comprobante: ""
            },

            info: {
                sal_pendiente: 0,
                dis_facturar: 0
            },

            producto: {
                producto_id: 0,
                nombre: "",
                cantidad: 0,
                pre_venta: 0,
                por_descuento: 0,
                impuesto: "",
                valor: 0,
                codigo: 0,
                min_stock: 0,
                dis_stock: 0
            },

            detalle: {
                producto: 0,
                presentacion: 0,
                cantidad: 0,
                min_stock: 0,
                dis_stock: 0,
                precio: 0,
                descuento: 0,
                impuesto: 0,
                button: false
            },
            disabled: false,
            arrayCliente: [],
            arrayProductos: [],
            arrayPresentaciones: [],
            arrayDetalle: []
        };
    },
    computed: {
        getId() {
            if (!this.selected) {
                this.factura.cliente_id = 0;
            } else {
                this.factura.cliente_id = this.selected.id;
                axios
                    .get("/api/cliente/saldo", {
                        params: {
                            id: this.factura.cliente_id
                        }
                    })
                    .then(resp => {
                        this.info.sal_pendiente = parseFloat(
                            resp.data.saldos
                        ).toFixed(2);
                        this.info.dis_facturar =
                            parseFloat(resp.data.limite["lim_credito"]).toFixed(
                                2
                            ) - parseFloat(resp.data.saldos).toFixed(2);
                        if (this.info.dis_facturar <= 0) {
                            this.disabled = true;
                            Swal.fire({
                                title: "Alto!",
                                text:
                                    "El cliente ha excedido su límite de crédito",
                                icon: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33",
                                confirmButtonText: "OK"
                            }).then(result => {
                                if (result.isConfirmed) {
                                    this.$router.push("/odt");
                                }
                            });
                        }
                    });
            }
        },
        subtotalSinImpuesto() {
            let res = 0;
            if (this.arrayDetalle.length > 0) {
                this.arrayDetalle.forEach(e => {
                    res += parseFloat(e.det_total);
                });
            } else {
                res = parseFloat(0);
            }
            return res;
        },
        totalDescuento() {
            let res = 0;
            if (this.arrayDetalle.length > 0) {
                this.arrayDetalle.forEach(e => {
                    res += parseFloat(e.det_descuento);
                });
            } else {
                res = parseFloat(0);
            }
            return res;
        },
        calcularTotal() {
            let res = 0;
            if (this.arrayDetalle.length > 0) {
                res =
                    parseFloat(this.factura.sub_sin_imp) -
                    parseFloat(this.factura.tot_descuento);
            } else {
                res = parseFloat(0);
            }
            if (res > this.info.dis_facturar) {
                this.disabled = true;
                Swal.fire(
                    "Alto!",
                    "El cliente no puede exceder el límite de crédito",
                    "error"
                );
            }
            return res;
        }
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        getFactura() {
            axios.get("/api/odt/comprobante").then(resp => {
                this.factura.fec_emision = resp.data.fec_emision;
                this.factura.num_secuencial = resp.data.num_secuencial;
                this.factura.punto_id = resp.data.punto_id;
            });
        },
        validaCampos() {
            this.factura.errors = [];
            if (!this.factura.cliente_id || this.factura.cliente_id == 0) {
                this.factura.errors.push("Seleccione Cliente.");
            }
            if (this.arrayDetalle.length == 0) {
                this.factura.errors.push("Agregue Productos");
            }
            return this.factura.errors;
        },
        guardar() {
            const condiciones = this.validaCampos();
            if (condiciones.length) {
                return;
            }

            Swal.fire({
                title: "Espere...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();

                    axios
                        .post("/api/odt/guardar", {
                            cliente_id: this.factura.cliente_id,
                            punto_id: this.factura.punto_id,
                            num_secuencial: this.factura.num_secuencial,
                            sub_sin_imp: this.factura.sub_sin_imp,
                            tot_descuento: this.factura.tot_descuento,
                            val_total: this.factura.val_total,
                            detalles: this.arrayDetalle
                        })
                        .then(resp => {
                             Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/odt");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        selectCliente(search, loading) {
            loading(true);
            axios
                .get("/api/cliente/buscar?cli=" + search)
                .then(resp => {
                    this.arrayCliente = resp.data;
                    loading(false);
                })
                .catch(err => {
                    console.log(err);
                });
        },
        // Estructura para aggregar productos al detalle
        findProductos() {
            axios.get("/api/productos/venta").then(resp => {
                this.arrayProductos = resp.data;
            });
        },
        findPresentaciones() {
            axios
                .get("/api/productos/presentaciones", {
                    params: {
                        q: this.detalle.producto.id
                    }
                })
                .then(resp => {
                    this.arrayPresentaciones = resp.data;
                });
        },
        cleanDetalle() {
            (this.detalle.producto = 0),
                (this.detalle.presentacion = 0),
                (this.detalle.cantidad = 0),
                (this.detalle.precio = 0),
                (this.detalle.descuento = 0),
                (this.detalle.impuesto = 0);
        },
        getInfo(data = null) {
            this.detalle.min_stock = data.min_stock;
            this.detalle.dis_stock = parseInt(data.dis_stock);
            this.detalle.precio = parseFloat(data.pre_venta).toFixed(2);
            this.detalle.descuento = parseFloat(data.por_descuento).toFixed(2);
        },
        addDetalle(data = []) {
            console.log(data);
            let resp = this.arrayDetalle.find(
                e => e.presentacion_id == data.presentacion.id
            );
            if (resp) {
                Swal.fire({
                    toast: true,
                    position: "top-right",
                    showConfirmButton: false,
                    icon: "error",
                    title: "El producto ya se encuentra agregado.",
                    timer: 3000
                });
            } else {
                this.arrayDetalle.push({
                    presentacion_id: data.presentacion.id,
                    nombre: data.producto.nombre,
                    det_cantidad: data.cantidad,
                    det_precio: data.precio,
                    det_descuento:
                        data.cantidad * ((data.descuento / 100) * data.precio),
                    det_total:
                        data.precio * data.cantidad -
                        data.cantidad * ((data.descuento / 100) * data.precio)
                });
            }
        },
        eliminarDetalle(index) {
            this.arrayDetalle.splice(index, 1);
        }
    },
    mounted() {
        this.getFactura();
        this.findProductos();
    }
};
</script>
