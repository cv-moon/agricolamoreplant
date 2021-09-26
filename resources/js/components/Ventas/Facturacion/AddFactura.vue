<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Facturar
      </h3>
      <div class="card-tools">
        <router-link to="/facturacion" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <b class="text-primary">Datos del Cliente</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-6">
          <label for="dat_cliente" class="col-sm-12 col-form-label"
            >Cliente:</label
          >
          <div class="input-group">
            <div class="col-sm-11">
              <v-select
                :options="arrayCliente"
                :getOptionLabel="
                  (option) => option.nombre + ' - ' + option.num_identificacion
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
          <label for="telefonos" class="col-form-label">Saldo Pendiente:</label>
          <h3 class="text-red">${{ info.sal_pendiente }}</h3>
        </div>
        <div class="col-3">
          <label for="dis_facturar" class="col-form-label"
            >Disponible a Facturar:</label
          >
          <h3 class="text-success">${{ info.dis_facturar }}</h3>
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
          <label for="comprobante" class="col-form-label"># Comprobante:</label>
          <p class="col-form-label">{{ datos.comprobante }}</p>
        </div>
        <div class="col-6">
          <label for="num_comprobante" class="col-form-label"
            >Clave de Acceso:</label
          >
          <p class="col-form-label">
            {{ factura.cla_acceso }}
          </p>
        </div>
      </div>
      <b class="text-primary">Detalle de Productos</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-4">
          <button
            type="button"
            class="btn btn-success"
            data-target="#modal"
            @click="abrirModal()"
          >
            <i class="fas fa-plus"> Agregar Productos </i>
          </button>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12 table-responsive">
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th class="text-center" style="width: 5%">Acción</th>
                <th class="text-center" style="width: 35%">Producto</th>
                <th class="text-center" style="width: 10%">Cantidad</th>
                <th class="text-center" style="width: 15%">Camp. Adicional</th>
                <th class="text-center" style="width: 10%">Precio</th>
                <th class="text-center" style="width: 10%">Desc. %</th>
                <th class="text-center" style="width: 15%">Subtotal</th>
              </tr>
            </thead>
            <tbody v-if="arrayDetalle.length">
              <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
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
                <td>
                  <input
                    type="number"
                    class="form-control"
                    min="1"
                    v-model="detalle.cantidad"
                  />
                </td>
                <td>
                  <input
                    type="date"
                    class="form-control"
                    v-model="detalle.fec_vencimiento"
                  />
                </td>
                <td align="right">${{ detalle.pre_venta }}</td>
                <td align="right">
                  ${{
                    (detalle.des_individual = (
                      ((detalle.pre_venta * detalle.por_descuento) / 100) *
                      detalle.cantidad
                    ).toFixed(2))
                  }}
                </td>
                <td align="right">
                  $
                  {{
                    (detalle.sub_individual = (
                      detalle.pre_venta * detalle.cantidad -
                      detalle.des_individual
                    ).toFixed(2))
                  }}
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
              <h5 class="card-title">FORMA DE PAGO</h5>
            </div>
            <div class="card-body">
              <div class="form-group row">
                <label for="forma_pago" class="col-2 col-form-label"
                  >Forma:</label
                >
                <div class="col-10">
                  <select v-model="pago.forma_id" class="form-control">
                    <option value="0" disabled>Seleccione...</option>
                    <option
                      v-for="forma in arrayForma"
                      :key="forma.id"
                      :value="forma.id"
                      v-text="forma.codigo + '-' + forma.nombre"
                    ></option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="forma_pago" class="col-2 col-form-label"
                  >Tipo:</label
                >
                <div class="col-10">
                  <select v-model="pago.tipo" class="form-control">
                    <option value="E">EFECTIVO</option>
                    <option value="C">CRÉDITO</option>
                  </select>
                </div>
              </div>
              <div class="form-group row" v-if="pago.tipo == 'C'">
                <label for="forma_pago" class="col-3 col-form-label"
                  >Plazo:</label
                >
                <div class="col-3">
                  <input
                    type="number"
                    placeholder="0"
                    min="0"
                    max="30"
                    class="form-control"
                    v-model="pago.plazo"
                  />
                </div>
                <label for="forma_pago" class="col-3 col-form-label"
                  >Unidad:</label
                >
                <div class="col-3">
                  <select v-model="pago.tiempo" class="form-control">
                    <option value="" disabled>Seleccione...</option>
                    <option value="Dias">Días</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-5">
          <div class="card card-success card-outline">
            <div class="card-body">
              <div class="form-group row">
                <div class="col-6">Subtotal Iva 12%:</div>
                <div class="col-6 bg-info text-right">
                  $ {{ (factura.sub_iva = subtotalIva.toFixed(2)) }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Subtotal 0%:</div>
                <div class="col-6 bg-gray-dark text-right">
                  $ {{ (factura.sub_0 = subtotalCero.toFixed(2)) }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Subtotal no objeto de iva:</div>
                <div class="col-6 bg-indigo text-right">
                  $ {{ (factura.sub_no_iva = subtotalNoObjeto.toFixed(2)) }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Subtotal exento de iva:</div>
                <div class="col-6 bg-lightblue text-right">
                  $ {{ (factura.sub_exento = subtotalExento.toFixed(2)) }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Subtotal sin Impuestos:</div>
                <div
                  class="
                    col-6
                    solor-palette-set
                    bg-info
                    disabled
                    color-palette
                    text-right
                  "
                >
                  $ {{ (factura.sub_sin_imp = subtotalSinImpuesto.toFixed(2)) }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Total Descuento:</div>
                <div class="col-6 bg-success text-right">
                  $ {{ (factura.tot_descuento = totalDescuento.toFixed(2)) }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Valor ICE:</div>
                <div class="col-6 bg-secondary text-right">
                  $ {{ factura.val_ice }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Valor Irbpnr:</div>
                <div class="col-6 bg-gray text-right">
                  $ {{ factura.val_irbpnr }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Iva 12%:</div>
                <div class="col-6 bg-gray text-right">
                  $ {{ (factura.val_iva = calcularIva.toFixed(2)) }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Propina 10%:</div>
                <div class="col-6 bg-gray text-right">
                  $ {{ factura.val_propina }}
                </div>
              </div>
              <div class="form-group row">
                <div class="col-6">Valor Total:</div>
                <div class="col-6 bg-gray disabled color-palette text-right">
                  $ {{ (factura.val_total = calcularTotal.toFixed(2)) }}
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
      <router-link to="/facturacion" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-success" @click="guardar">
        Guardar
      </button>
    </div>
    <!-- Start Modal Productos -->
    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Agregar Productos</h4>
            <button
              type="button"
              class="close"
              aria-label="Close"
              @click="cerrarModal"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table
              id="tabla"
              class="table table-striped table-bordered dt-responsive nowrap"
              style="width: 100%"
            >
              <thead>
                <tr>
                  <th class="text-center">Acción</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Composición</th>
                  <th class="text-center">P.V.P.</th>
                  <th class="text-center">Imp.</th>
                  <th class="text-center">% Desc</th>
                  <th class="text-center">Stock</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="producto in arrayProductos" :key="producto.id">
                  <td align="center">
                    <button
                      type="button"
                      title="Agregar"
                      @click="addDetalle(producto)"
                      class="btn btn-success btn-xs"
                    >
                      <i class="fas fa-shopping-cart"></i>
                    </button>
                  </td>
                  <td>
                    <input
                      type="number"
                      placeholder="0"
                      min="0"
                      v-model="producto.cantidad"
                      class="form-control"
                      :max="producto.dis_stock"
                    />
                  </td>
                  <td v-text="producto.nombre"></td>
                  <td v-text="producto.composicion"></td>
                  <td align="right" v-text="producto.pre_venta"></td>
                  <td align="right" v-text="producto.valor + ' %'"></td>
                  <td align="right" v-text="producto.por_descuento + ' %'"></td>
                  <td
                    align="right"
                    v-text="producto.dis_stock"
                    :class="
                      producto.dis_stock > 0 &&
                      producto.dis_stock <= producto.min_stock
                        ? 'table-danger'
                        : producto.dis_stock > producto.min_stock &&
                          producto.dis_stock <= producto.min_stock * 2
                        ? 'table-warning'
                        : 'table-success'
                    "
                  ></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" @click="cerrarModal">
              Close
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <!-- Start Cliente Modal -->
    <div class="modal fade" id="modCliente">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Agregar Cliente</h4>
            <button
              type="button"
              class="close"
              aria-label="Close"
              @click="cerrarCliente"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <b class="text-primary">Datos Generales</b>
              <hr class="mt-0" />
              <div class="form-group row">
                <label for="nombre" class="col-sm-2 col-form-label"
                  >Nombre:</label
                >
                <div class="col-sm-6">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Nombre."
                    maxlength="100"
                    v-model="cliente.nombre"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="tip_identificacion" class="col-sm-2 col-form-label"
                  >Tipo Identificación:</label
                >
                <div class="col-sm-4">
                  <select v-model="cliente.identificacion_id" class="form-control">
                    <option value="0" disabled>Seleccione...</option>
                    <option
                      v-for="identificacion in arrayIdentificaciones"
                      :key="identificacion.id"
                      :value="identificacion.id"
                      v-text="identificacion.nombre"
                    ></option>
                  </select>
                </div>
                <label for="num_identificacion" class="col-sm-2 col-form-label"
                  ># Identificación:</label
                >
                <div class="col-sm-4">
                  <template v-if="cliente.identificacion_id === 2">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Cédula."
                      maxlength="10"
                      v-model="cliente.num_identificacion"
                    />
                  </template>
                  <template v-else-if="identificacion_id === 1">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="RUC."
                      maxlength="13"
                      v-model="cliente.num_identificacion"
                    />
                  </template>
                  <template v-else-if="identificacion_id === 3">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Pasaporte."
                      maxlength="13"
                      v-model="cliente.num_identificacion"
                    />
                  </template>
                </div>
              </div>
              <div class="form-group row">
                <label for="direccion" class="col-sm-2 col-form-label"
                  >Dirección:</label
                >
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Dirección."
                    maxlength="200"
                    v-model="cliente.direccion"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="telefonos" class="col-sm-2 col-form-label"
                  >Teléfonos:</label
                >
                <div class="col-sm-4">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Teléfonos."
                    maxlength="50"
                    v-model="cliente.telefonos"
                  />
                </div>
                <label for="email" class="col-sm-2 col-form-label"
                  >Site/e-mail:</label
                >
                <div class="col-sm-4">
                  <input
                    type="email"
                    class="form-control"
                    placeholder="E-mail."
                    maxlength="100"
                    v-model="cliente.email"
                  />
                </div>
              </div>
              <b class="text-primary">Datos Adicionales</b>
              <hr class="mt-0" />
              <div class="form-group row">
                <label for="lim_credito" class="col-sm-2 col-form-label"
                  >Lim. Crédito ($):</label
                >
                <div class="col-sm-4">
                  <input
                    type="number"
                    class="form-control"
                    placeholder="0"
                    min="0"
                    v-model="cliente.lim_credito"
                  />
                </div>
                <label for="descuento" class="col-sm-2 col-form-label"
                  >Descuento (%):</label
                >
                <div class="col-sm-4">
                  <input
                    type="number"
                    class="form-control"
                    placeholder="0"
                    min="0"
                    v-model="cliente.descuento"
                  />
                </div>
              </div>
              <div v-if="cliente.errors.length" class="alert alert-danger">
                <div>
                  <div v-for="error in cliente.errors" :key="error">
                    {{ error }}
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button
              type="button"
              class="btn btn-default"
              @click="cerrarCliente"
            >
              Close
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="guardarCliente"
            >
              Guardar
            </button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </div>
</template>
<script>
import script_comprobantes from "../../../factura";
export default {
  data() {
    return {
      selected: null,

      pago: {
        forma_id: 1,
        codigo: 0,
        plazo: 0,
        tiempo: "Dias",
        tipo: "E",
      },

      factura: {
        cliente_id: 0,
        punto_id: 0,
        usuario_id: 0,
        tip_emision: 1,
        tip_ambiente: 0,
        for_pago_id: 0,
        fec_emision: "",
        fec_autorizacion: "",
        num_secuencial: 0,
        cla_acceso: "",
        num_autorizacion: "",
        sub_sin_imp: 0,
        sub_iva: 0,
        sub_0: 0,
        sub_no_iva: 0,
        sub_exento: 0,
        tot_descuento: 0,
        val_ice: parseFloat(0).toFixed(2),
        val_irbpnr: parseFloat(0).toFixed(2),
        val_iva: parseFloat(0).toFixed(2),
        val_propina: parseFloat(0).toFixed(2),
        val_total: parseFloat(0).toFixed(2),
        for_pago: "",
        errors: [],
      },

      datos: {
        tip_comprobante: "FACTURA",
        comprobante: "",
        firma: "",
        fir_clave: "",
      },

      info: {
        sal_pendiente: 0,
        dis_facturar: 0,
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
        dis_stock: 0,
      },

      cliente: {
        identificacion_id: 0,
        nombre: "",
        num_identificacion: "",
        direccion: "",
        telefonos: "",
        email: "",
        lim_credito: 0,
        descuento: 0,
        errors: [],
      },
identificacion_id:0,
      arrayCliente: [],
      arrayIdentificaciones: [],
      arrayProductos: [],
      arrayDetalle: [],
      arrayForma: [],
    };
  },
  computed: {
    getId() {
      if (!this.selected) {
        this.factura.cliente_id = 0;
      } else {
        this.factura.cliente_id = this.selected.id;
      }
    },
    totalDescuento() {
      let res = 0;
      if (this.arrayDetalle.length > 0) {
        this.arrayDetalle.forEach((e) => {
          res += parseFloat(e.des_individual);
        });
      } else {
        res = parseFloat(0);
      }
      return res;
    },
    subtotalSinImpuesto() {
      let res = 0;
      if (this.arrayDetalle.length > 0) {
        this.arrayDetalle.forEach((e) => {
          res += parseFloat(e.sub_individual);
        });
      } else {
        res = parseFloat(0);
      }
      return res;
    },
    subtotalCero() {
      let res = 0;
      if (this.arrayDetalle.length > 0) {
        this.arrayDetalle.forEach((e) => {
          if (e.codigo == 0) {
            res += parseFloat(e.sub_individual);
          }
        });
      } else {
        res = parseFloat(0);
      }
      return res;
    },
    subtotalIva() {
      let res = 0;
      if (this.arrayDetalle.length > 0) {
        this.arrayDetalle.forEach((e) => {
          if (e.codigo == 2) {
            res += parseFloat(e.sub_individual);
          }
        });
      } else {
        res = parseFloat(0);
      }
      return res;
    },
    subtotalNoObjeto() {
      let res = 0;
      if (this.arrayDetalle.length > 0) {
        this.arrayDetalle.forEach((e) => {
          if (e.codigo == 6) {
            res += parseFloat(e.sub_individual);
          }
        });
      } else {
        res = parseFloat(0);
      }
      return res;
    },
    subtotalExento() {
      let res = 0;
      if (this.arrayDetalle.length > 0) {
        this.arrayDetalle.forEach((e) => {
          if (e.codigo == 7) {
            res += parseFloat(e.sub_individual);
          }
        });
      } else {
        res = parseFloat(0);
      }
      return res;
    },
    calcularIva() {
      let res = 0;

      if (this.arrayDetalle.length > 0) {
        res = parseFloat(this.factura.sub_iva * 0.12);
      } else {
        res = parseFloat(0);
      }
      return res;
    },
    calcularTotal() {
      let res = 0;
      if (this.arrayDetalle.length > 0) {
        res =
          parseFloat(this.factura.sub_iva) +
          parseFloat(this.factura.val_ice) +
          parseFloat(this.factura.val_irbpnr) +
          parseFloat(this.factura.sub_0) +
          parseFloat(this.factura.sub_no_iva) +
          parseFloat(this.factura.sub_exento) +
          parseFloat(this.factura.val_iva) +
          parseFloat(this.factura.val_propina);
      } else {
        res = parseFloat(0);
      }
      return res;
    },
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    getFactura() {
      axios.get("/api/factura/comprobante").then((resp) => {
        this.factura.cla_acceso =
          resp.data.cla_acceso + this.modulo11(resp.data.cla_acceso);
        this.factura.fec_emision = resp.data.fec_emision;
        this.factura.num_secuencial = resp.data.num_secuencial;
        this.factura.punto_id = resp.data.punto_id;
        this.factura.tip_ambiente = resp.data.tip_ambiente;
        this.datos.comprobante = resp.data.comprobante;
        this.datos.firma = resp.data.firma;
        this.datos.fir_clave = resp.data.fir_clave;
      });
    },
    selectFormaPagos() {
      axios.get("/api/formas_pago").then((resp) => {
        this.arrayForma = resp.data;
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
      if (this.pago.tipo == "C") {
        if (!this.pago.plazo || this.pago.plazo == 0) {
          this.factura.errors.push("Revise el plazo");
        }
      }
      return this.factura.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/factura/guardar", {
          cliente_id: this.factura.cliente_id,
          punto_id: this.factura.punto_id,
          for_pago_id: this.pago.forma_id,
          tip_ambiente: this.factura.tip_ambiente,
          num_secuencial: this.factura.num_secuencial,
          fec_emision: this.factura.fec_emision,
          cla_acceso: this.factura.cla_acceso,
          sub_sin_imp: this.factura.sub_sin_imp,
          sub_iva: this.factura.sub_iva,
          sub_0: this.factura.sub_0,
          sub_no_iva: this.factura.sub_no_iva,
          sub_exento: this.factura.sub_exento,
          tot_descuento: this.factura.tot_descuento,
          val_ice: this.factura.val_ice,
          val_irbpnr: this.factura.val_irbpnr,
          val_iva: this.factura.val_iva,
          val_propina: this.factura.val_propina,
          val_total: this.factura.val_total,
          for_pago: this.pago.tipo,
          dias_credito: this.pago.plazo,
          detalles: this.arrayDetalle,
        })
        .then((resp) => {
          axios
            .post("/api/factura/xml_factura", {
              factura: resp.data.factura,
              detalles: resp.data.detalles,
              credito: resp.data.credito,
            })
            .then((res) => {
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
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    selectCliente(search, loading) {
      loading(true);
      axios
        .get("/api/cliente/buscar?cli=" + search)
        .then((resp) => {
          this.arrayCliente = resp.data;
          loading(false);
        })
        .catch((err) => {
          console.log(err);
        });
    },

    // Estructura para aggregar productos al detalle
    abrirModal() {
      $("#modal").modal("show");
      this.getProductos();
    },
    cerrarModal() {
      $("#modal").modal("hide");
    },
    getProductos() {
      axios.get("/api/productos/venta").then((resp) => {
        this.arrayProductos = resp.data;
        $("#productos").DataTable().destroy();
        this.myTable();
      });
    },
    addDetalle(data = []) {
      let resp = this.arrayDetalle.find((e) => e.producto_id == data.id);
      if (resp) {
        Swal.fire({
          toast: true,
          position: "top-right",
          showConfirmButton: false,
          icon: "error",
          title: "El producto ya se encuentra agregado.",
          timer: 3000,
        });
      } else {
        this.arrayDetalle.push({
          producto_id: data.id,
          nombre: data.nombre,
          cantidad: data.cantidad ? data.cantidad : 1,
          fec_vencimiento: "",
          pre_venta:
            data.codigo == 2
              ? parseFloat(data.pre_venta / 1.12).toFixed(3)
              : parseFloat(data.pre_venta).toFixed(3),
          por_descuento: data.por_descuento,
          codigo: data.codigo,
          valor: data.valor,
          des_individual: parseFloat(0).toFixed(2),
          sub_individual: parseFloat(0).toFixed(2),
        });
      }
    },
    eliminarDetalle(index) {
      this.arrayDetalle.splice(index, 1);
    },

    // Estructura para Agregar  Cliente
    abrirCliente() {
      $("#modCliente").modal("show");
      this.selectIdentificaciones();
    },
    cerrarCliente() {
      $("#modCliente").modal("hide");
      this.limpiar();
    },
    validaCamposCliente() {
      this.cliente.errors = [];
      if (!this.cliente.nombre) {
        this.cliente.errors.push("Ingrese nombre.");
      }
      if (this.cliente.identificacion_id == "0") {
        this.cliente.errors.push("Seleccione tipo de identificación.");
      }
      if (!this.cliente.num_identificacion) {
        this.cliente.errors.push("Ingrese número de identificación.");
      }
      return this.cliente.errors;
    },
    guardarCliente() {
      const condiciones = this.validaCamposCliente();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/cliente/guardar", {
          nombre: this.cliente.nombre,
          identificacion_id: this.cliente.identificacion_id,
          num_identificacion: this.cliente.num_identificacion,
          direccion: this.cliente.direccion,
          telefonos: this.cliente.telefonos,
          email: this.cliente.email,
          lim_credito: this.cliente.lim_credito,
          descuento: this.cliente.descuento,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.cerrarCliente();
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    limpiar() {
      this.cliente.nombre = "";
      this.cliente.identificacion_id = "0";
      this.cliente.num_identificacion = "";
      this.cliente.direccion = "";
      this.cliente.telefonos = "";
      this.cliente.email = "";
      this.cliente.lim_credito = 0;
      this.cliente.descuento = 0;
    },

    selectIdentificaciones() {
      axios.get("/api/identificaciones").then((resp) => {
        this.arrayIdentificaciones = resp.data;
      });
    },

    // Métodos para la facturación

    zeroFill(number, width) {
      width -= number.toString().length;
      if (width > 0) {
        return (
          new Array(width + (/\./.test(number) ? 2 : 1)).join("0") + number
        );
      }
      return number + "";
    },
    modulo11(numero) {
      let digito_calculado = -1;

      if (typeof numero == "string" && /^\d+$/.test(numero)) {
        let digitos = numero.split("").map(Number); //arreglo con los dígitos del número

        digito_calculado =
          11 -
          (digitos.reduce(function (valorPrevio, valorActual, indice) {
            return valorPrevio + valorActual * (7 - (indice % 6));
          }, 0) %
            11);

        digito_calculado = digito_calculado == 11 ? 0 : digito_calculado; //según ficha técnica
        digito_calculado = digito_calculado == 10 ? 1 : digito_calculado; //según ficha técnica
      }
      return digito_calculado;
    },

    async crearfacturacion(firma, password, factura, tipo, id, carpeta) {
      try {
        let { data: comprobante } =
          await script_comprobantes.obtener_comprobante_firmado.getAll({
            factura: factura,
            id_factura: id,
            tipo: tipo,
          });
        let { resultado: contenido } =
          await script_comprobantes.lectura_firma.getAll({
            firma: firma,
            id_factura: id,
            tipo: tipo,
          });
        let { data: certificado } =
          await script_comprobantes.firmar_comprobante.getAll({
            contenido: contenido[0],
            password: password,
            comprobante: comprobante,
            id_factura: id,
            tipo: tipo,
          });
        let { data: quefirma } =
          await script_comprobantes.verificar_firma.getAll({
            mensaje: certificado,
            tipo: tipo,
            id_factura: id,
            carpeta: carpeta,
          });
        let { data: validado } =
          await script_comprobantes.validar_comprobante.getAll({
            comprobante: comprobante,
            tipo: tipo,
            id_factura: id,
            carpeta: carpeta,
          });
        let { data: recibida } =
          await script_comprobantes.autorizar_comprobante.getAll({
            comprobante: comprobante,
            validado: validado,
            tipo: tipo,
            id_factura: id,
            carpeta: carpeta,
          });
        let { data: registrado } =
          await script_comprobantes.autorizado_comprobante.getAll({
            recibida: recibida,
            tipo: tipo,
            id_factura: id,
          });
        if (registrado == "enviado") {
          Swal.fire("Bien!", "La factura se envió exitosamente.", "success");
          this.$router.push("/facturacion");
        } else {
          Swal.fire(
            "Error!",
            "La factura no pudo ser enviada, intente mas tarde.",
            "error"
          );
          this.$router.push("/facturacion");
        }
      } catch (err) {
        Swal.fire("Error!", "Error en el envio al SRI" + err, "error");
        this.$router.push("/facturacion");
      }
    },
  },
  mounted() {
    this.getFactura();
    this.selectFormaPagos();
  },
};
</script>