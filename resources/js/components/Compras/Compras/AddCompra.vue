<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Compras
            </h3>
            <div class="card-tools">
                <router-link to="/compras" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <b class="text-primary">Detalle Comprobante</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="proveedor" class="col-sm-12 col-form-label"
                        >Proveedor:</label
                    >
                    <div class="input-group">
                        <div class="col-sm-11">
                            <v-select
                                :options="arrayProveedor"
                                :getOptionLabel="
                                    option =>
                                        option.raz_social +
                                        ' - ' +
                                        option.num_identificacion
                                "
                                @search="selectProveedor"
                                placeholder="Buscar Proveedor..."
                                v-model="selected"
                                @input="getId"
                            >
                            </v-select>
                        </div>
                        <button
                            title="Agregar Proveedor"
                            class="btn btn-success btn-sm"
                            data-target="#modProv"
                            @click="abrirProveedor()"
                        >
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label
                        for="establecimiento"
                        class="col-sm-12 col-form-label"
                        >Establecimiento:</label
                    >
                    <select
                        v-model="establecimiento_id"
                        class="form-control"
                        @change="findProductos"
                    >
                        <option value="0">Seleccione...</option>
                        <option
                            v-for="establecimiento in arrayEstablecimientos"
                            :key="establecimiento.id"
                            :value="establecimiento.id"
                            v-text="
                                establecimiento.nom_comercial +
                                    ' - ' +
                                    establecimiento.nom_referencia
                            "
                        ></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="tip_comprobante" class="col-sm-2 col-form-label"
                    >Tipo Comprobante:</label
                >
                <div class="col-sm-4">
                    <select v-model="tip_comprobante" class="form-control">
                        <option value="0" disabled>Seleccione...</option>
                        <option value="FAC">FACTURA</option>
                        <option value="NOT">NOT. VENTA</option>
                        <option value="ORD">ORD. DESPACHO</option>
                    </select>
                </div>
                <label for="num_comprobante" class="col-sm-2 col-form-label"
                    ># Comprobante:</label
                >
                <div class="col-sm-4">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="000-000-000000000."
                        maxlength="17"
                        v-model="num_comprobante"
                    />
                </div>
            </div>
            <div class="form-group row">
                <label for="fec_emision" class="col-sm-2 col-form-label"
                    >Fecha de Emisión:</label
                >
                <div class="col-sm-4">
                    <input
                        type="date"
                        class="form-control"
                        v-model="fec_emision"
                    />
                </div>
            </div>
            <b class="text-primary">Detalle de Productos</b>
            <hr class="mt-0" />
            <div class="col-sm-12">
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
                    <div class="col-sm-1">
                        <label for="for_pago" class="col-form-label"
                            >Cant:</label
                        >
                        <input
                            type="number"
                            class="form-control form-control-sm"
                            min="1"
                            v-model="detalle.cantidad"
                        />
                    </div>
                    <div class="col-sm-2">
                        <label for="for_pago" class="col-form-label"
                            >Fec. Cad.:</label
                        >
                        <input
                            type="date"
                            class="form-control form-control-sm"
                            v-model="detalle.fec_vencimiento"
                        />
                    </div>
                    <div class="col-sm-2">
                        <label for="for_pago" class="col-form-label"
                            >Precio:</label
                        >
                        <input
                            type="number"
                            class="form-control form-control-sm"
                            min="0"
                            v-model="detalle.precio"
                        />
                    </div>
                    <div class="col-sm-2">
                        <label for="for_pago" class="col-form-label"
                            >Desc:</label
                        >
                        <input
                            type="number"
                            class="form-control form-control-sm"
                            min="0"
                            max="10"
                            v-model="detalle.descuento"
                        />
                    </div>
                    <div class="col-sm-1">
                        <button
                            type="button"
                            title="Agregar"
                            @click="agregar()"
                            class="btn btn-success mt-4"
                        >
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </div>
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
                                <td v-text="detalle.producto"></td>
                                <td
                                    align="right"
                                    v-text="detalle.cantidad"
                                ></td>
                                <td align="center">
                                    {{
                                        !detalle.fec_vencimiento
                                            ? "--"
                                            : detalle.fec_vencimiento
                                    }}
                                </td>
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
                                <td colspan="6" align="right">
                                    <strong>Subtotal 0:</strong>
                                </td>
                                <td align="right">
                                    $
                                    {{
                                        (sub_0 = calcularSubTotalCero.toFixed(
                                            2
                                        ))
                                    }}
                                </td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="6" align="right">
                                    <strong>Subtotal 12:</strong>
                                </td>
                                <td align="right">
                                    $
                                    {{
                                        (sub_12 = calcularSubTotalDoce.toFixed(
                                            2
                                        ))
                                    }}
                                </td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="6" align="right">
                                    <strong>Descuento:</strong>
                                </td>
                                <td align="right">
                                    $
                                    {{
                                        (tot_desc = calcularDescuento.toFixed(
                                            2
                                        ))
                                    }}
                                </td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="6" align="right">
                                    <strong>Iva 12%:</strong>
                                </td>
                                <td align="right">
                                    $
                                    {{ calcularImpuesto.toFixed(2) }}
                                </td>
                            </tr>
                            <tr style="background-color: #ceecf5">
                                <td colspan="6" align="right">
                                    <strong>Total Neto:</strong>
                                </td>
                                <td align="right">
                                    $ {{ (total = calcularTotal.toFixed(2)) }}
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
                <div class="col-sm-3">
                    <label for="for_pago" class="col-form-label"
                        >Forma de Pago:</label
                    >
                    <select
                        v-model="for_pago"
                        class="form-control"
                        @change="valSaldo()"
                    >
                        <option value="0" disabled>Seleccione...</option>
                        <option value="C">CRÉDITO</option>
                        <option value="E">EFECTIVO</option>
                    </select>
                </div>
                <template v-if="for_pago == 'C'">
                    <div class="col-sm-3">
                        <label for="saldo" class="col-form-label"
                            >Valor de Crédito:</label
                        >
                        <input
                            type="number"
                            class="form-control"
                            v-model="saldo"
                            disabled
                        />
                    </div>
                    <div class="col-sm-3">
                        <label for="dias_credito" class="col-form-label"
                            >Dias de Crédito:</label
                        >
                        <input
                            type="number"
                            class="form-control"
                            min="0"
                            v-model="dias_credito"
                            @change="fechaLimite"
                        />
                    </div>
                    <div class="col-sm-3">
                        <label for="fec_limite" class="col-form-label"
                            >Fecha a Pagar:</label
                        >
                        <input
                            type="date"
                            class="form-control"
                            v-model="fec_limite"
                        />
                    </div>
                    <div class="col-sm-12">
                        <label for="observaciones" class="col-form-label"
                            >Observaciones:</label
                        >
                        <textarea
                            class="form-control"
                            maxlength="150"
                            rows="3"
                            v-model="observaciones"
                        />
                    </div>
                </template>
            </div>
            <div v-if="errors.length" class="alert alert-danger">
                <div>
                    <div v-for="error in errors" :key="error">
                        {{ error }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <router-link to="/compras" class="btn btn-danger">
                Cancelar
            </router-link>
            <button type="button" class="btn btn-success" @click="guardar">
                Guardar
            </button>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            selected: "",
            proveedor_id: 0,
            establecimiento_id: 0,
            tip_comprobante: "0",
            num_comprobante: "",
            fec_emision: "",
            sub_0: 0,
            sub_12: 0,
            tot_desc: 0,
            total: 0,
            for_pago: "0",
            saldo: 0,
            dias_credito: 0,
            fec_limite: "",
            observaciones: "",
            arrayProveedor: [],
            arrayProductos: [],
            arrayPresentaciones: [],
            arrayEstablecimientos: [],
            arrayDetalle: [],
            errors: [],

            detalle: {
                producto: 0,
                presentacion: 0,
                cantidad: 0,
                fec_vencimiento: "",
                precio: 0,
                descuento: 0,
                impuesto: 0
            }
        };
    },
    computed: {
        getId() {
            if (!this.selected) {
                this.proveedor_id = 0;
            } else {
                this.proveedor_id = this.selected.id;
            }
        },
        calcularSubTotalDoce() {
            let resultado = 0.0;
            for (let i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].impuesto === 12) {
                    resultado =
                        resultado +
                        (this.arrayDetalle[i].precio *
                            this.arrayDetalle[i].cantidad) /
                            1.12;
                }
            }
            return resultado;
        },
        calcularSubTotalCero() {
            let resultado = 0.0;
            for (let i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].impuesto === 0) {
                    resultado =
                        resultado +
                        this.arrayDetalle[i].precio *
                            this.arrayDetalle[i].cantidad;
                }
            }
            return resultado;
        },
        calcularDescuento() {
            let resultado = 0.0;
            for (let i = 0; i < this.arrayDetalle.length; i++) {
                resultado =
                    resultado + parseFloat(this.arrayDetalle[i].descuento);
            }
            return resultado;
        },
        calcularImpuesto() {
            let resultado = 0.0;
            resultado = this.sub_12 * 0.12;
            return resultado;
        },
        calcularTotal() {
            let resultado = 0.0;
            resultado =
                parseFloat(this.sub_0) +
                parseFloat(this.sub_12) +
                parseFloat(this.calcularImpuesto) -
                parseFloat(this.tot_desc);
            return resultado;
        }
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        validaCampos() {
            this.errors = [];
            if (!this.proveedor_id) {
                this.errors.push("Seleccione Proveedor.");
            }
            if (!this.establecimiento_id) {
                this.errors.push("Seleccione Establecimiento.");
            }
            if (this.tip_comprobante == "0") {
                this.errors.push("Seleccione tipo de comprobante.");
            }
            if (!this.num_comprobante) {
                this.errors.push("Ingrese número de comprobante.");
            }
            if (!this.fec_emision) {
                this.errors.push("Ingrese fecha de emisión.");
            }
            if (this.arrayDetalle.length == 0) {
                this.errors.push("Ingrese productos.");
            }
            if (this.for_pago == "0") {
                this.errors.push("Seleccione Forma de Pago.");
            }
            if (this.for_pago == "C") {
                if (this.dias_credito == 0) {
                    this.errors.push("Ingrese Días de Crédito.");
                }
                if (!this.fec_limite || this.fec_limite == null) {
                    this.errors.push("Ingrese Fecha a Pagar.");
                }
            }
            return this.errors;
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
                        .post("/api/compra/guardar", {
                            establecimiento_id: this.establecimiento_id,
                            proveedor_id: this.proveedor_id,
                            tip_comprobante: this.tip_comprobante,
                            num_comprobante: this.num_comprobante,
                            fec_emision: this.fec_emision,
                            sub_0: this.sub_0,
                            sub_12: this.sub_12,
                            tot_desc: this.tot_desc,
                            total: this.total,
                            for_pago: this.for_pago,
                            saldo: this.saldo,
                            dias_credito: this.dias_credito,
                            fec_limite: this.fec_limite,
                            observaciones: this.observaciones,
                            detalles: this.arrayDetalle
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/compras");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        selectProveedor(search, loading) {
            loading(true);
            axios
                .get("/api/proveedor/buscar?proveedor=" + search)
                .then(resp => {
                    this.arrayProveedor = resp.data;
                    loading(false);
                })
                .catch(err => {
                    console.log(err);
                });
        },
        selectEstablecimientos() {
            axios.get("/api/establecimientos").then(resp => {
                this.arrayEstablecimientos = resp.data;
            });
        },
        findProductos() {
            axios.get("/api/productos/listar/comprar").then(resp => {
                this.arrayProductos = resp.data;
            });
        },
        findPresentaciones() {
            axios
                .get("/api/productos/listar/presentaciones", {
                    params: {
                        q: this.detalle.producto.id,
                        e: this.establecimiento_id
                    }
                })
                .then(resp => {
                    this.arrayPresentaciones = resp.data;
                });
        },
        agregar() {
            this.arrayDetalle.push({
                producto:
                    this.detalle.producto.nombre +
                    " " +
                    this.detalle.presentacion.presentacion +
                    " " +
                    this.detalle.presentacion.sigla,
                presentacion_id: this.detalle.presentacion.id,
                cantidad: this.detalle.cantidad,
                precio: parseFloat(this.detalle.precio).toFixed(2),
                descuento: this.detalle.descuento,
                impuesto: this.detalle.impuesto
            });
            this.cleanDetalle();
        },
        cleanDetalle() {
            (this.detalle.producto = 0),
                (this.detalle.presentacion = 0),
                (this.detalle.cantidad = 0),
                (this.detalle.fec_vencimiento = ""),
                (this.detalle.precio = 0),
                (this.detalle.descuento = 0),
                (this.detalle.impuesto = 0);
        },
        encuentra(id) {
            let sw = 0;
            for (let i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].producto_id == id) {
                    sw = true;
                }
            }
            return sw;
        },
        eliminarDetalle(index) {
            this.arrayDetalle.splice(index, 1);
        },
        fechaLimite() {
            let me = this;
            let Fecha = new Date();
            let sFecha =
                fecha ||
                Fecha.getDate() +
                    "/" +
                    (Fecha.getMonth() + 1) +
                    "/" +
                    Fecha.getFullYear();
            let sep = sFecha.indexOf("/") != -1 ? "/" : "-";
            let aFecha = sFecha.split(sep);
            let fecha = aFecha[2] + "/" + aFecha[1] + "/" + aFecha[0];
            fecha = new Date(fecha);
            fecha.setDate(fecha.getDate() + parseInt(me.dias_credito));
            let anno = fecha.getFullYear();
            let mes = fecha.getMonth() + 1;
            let dia = fecha.getDate();
            mes = mes < 10 ? "0" + mes : mes;
            dia = dia < 10 ? "0" + dia : dia;
            me.fec_limite = anno + "-" + mes + "-" + dia;
            return me.fec_limite;
        },
        valSaldo() {
            if (this.for_pago == "C") {
                this.saldo = this.total;
            }
        }
    },
    mounted() {
        this.selectEstablecimientos();
    }
};
</script>
