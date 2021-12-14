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
          <label for="comprobante" class="col-form-label"># Comprobante:</label>
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
        <div class="col-sm-6">
          <label for="fac_compra" class="col-sm-12 col-form-label"
            >Factura Compra:</label
          >
          <!-- <v-select
            :options="arrayCompras"
            :getOptionLabel="
              (option) =>
                option.num_comprobante +
                ' / ' +
                option.fec_emision +
                ' / ' +
                option.nombre
            "
            @search="selectCompra"
            placeholder="Buscar Compra..."
            v-model="selected"
            @input="addDetalle(selected)"
          >
          </v-select> -->
          <select
            v-model="selected"
            class="form-control"
            @change="addDetalle(selected)"
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
      <b class="text-primary">Impuestos</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-4">
          <button
            type="button"
            class="btn btn-success"
            data-target="#modal"
            @click="abrirModal()"
          >
            <i class="fas fa-plus"> Agregar Impuestos </i>
          </button>
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
                <th class="text-center">% Ret.</th>
                <th class="text-center">T. Ret.</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                <td
                  v-text="detalle.comprobante + ' ' + detalle.num_comprobante"
                ></td>
                <td v-text="detalle.fec_emi_comprobante"></td>
                <td align="center" v-text="detalle.eje_fiscal"></td>
                <td align="right" v-text="detalle.bas_imponible"></td>
                <td align="center" v-text="detalle.imp_retencion"></td>
                <td></td>
                <td align="right">
                  {{ (detalle.val_Retenido = calculaRetencionInd) }}
                </td>
              </tr>
              <tr>
                <td colspan="8" align="center" v-if="!selected">
                  Seleccione un comprobante a retener.
                </td>
              </tr>
              <tr>
                <td colspan="7">Total a Retener</td>
                <td>
                  {{ (tot_retener = calculaRetencion) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div v-if="retencion.errors.length" class="alert alert-danger">
        <div>
          <div v-for="error in retencion.errors" :key="error">
            {{ error }}
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <router-link to="/retenciones" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-success" @click="guardar">
        Guardar
      </button>
    </div>
    <!-- Start Modal Impuesto a retener -->
    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Agregar Impuestos a Retener</h4>
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
            <div class="form-group row">
              <select
                v-model="selected"
                class="form-control"
                @change="addDetalle(selected)"
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
              <select
                v-model="selected"
                class="form-control"
                @change="addDetalle(selected)"
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
  </div>
</template>
<script>
import script_comprobantes from "../../../factura";
export default {
  data() {
    return {
      selected: null,

      retencion: {
        compra_id: 0,
        punto_id: 0,
        usuario_id: 0,
        transportista_id: 0,
        tip_emision: null,
        tip_ambiente: null,
        fec_emision: "",
        fec_autorizacion: "",
        num_secuencial: 0,
        cla_acceso: "",
        num_autorizacion: "",
        fec_inicio: "",
        fec_fin: "",
        des_nombre: "",
        des_identificacion: "",
        des_direccion: "",
        motivo: "VENTA DE PRODUCTOS",
        ruta: "",
        observaciones: "",
        errors: [],
      },

      datos: {
        tip_comprobante: "RETENCIÓN",
        comprobante: "",
        firma: "",
        fir_clave: "",
      },

      producto: {
        producto_id: 0,
        nombre: "",
        cantidad: 0,
      },

      arrayCompras: [],
      arrayDetalle: [],
      arrayTarifas: [],
    };
  },
  computed: {
    calculaRetencionInd() {
      let res = 0;
      res = this.arrayDetalle.forEach(
        (e) => e.bas_imponible * this.arrayTarifas.find((e) => e.tarifa / 100)
      );
      return res;
    },
    calculaRetencion() {},
  },
  methods: {
    selectCompra() {
      // loading(true);
      // axios
      //   .get("/api/compra/buscar?q=" + search)
      //   .then((resp) => {
      //     this.arrayCompras = resp.data;
      //     loading(false);
      //   })
      //   .catch((err) => {
      //     console.log(err);
      //   });
      axios.get("/api/compra/buscar").then((resp) => {
        this.arrayCompras = resp.data;
      });
    },
    getRetencion() {
      axios.get("/api/retencion/comprobante").then((resp) => {
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
    validaCampos() {
      this.retencion.errors = [];

      return this.retencion.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/retencion/guardar", {
          factura_id: this.retencion.factura_id,
          punto_id: this.retencion.punto_id,
          transportista_id: this.retencion.transportista_id,
          tip_ambiente: this.retencion.tip_ambiente,
          tip_emision: this.retencion.tip_emision,
          num_secuencial: this.retencion.num_secuencial,
          cla_acceso: this.retencion.cla_acceso,
          detalles: this.arrayDetalle,
        })
        .then((resp) => {
          console.log(resp.data);
          // axios
          //   .post("/api/factura/xml_guia", {
          //     guia: resp.data.guia,
          //     detalles: resp.data.detalles,
          //   })
          //   .then((res) => {
          //     this.crearfacturacion(
          //       "/" + res.data.firma,
          //       res.data.clave,
          //       res.data.archivo,
          //       res.data.tipo,
          //       res.data.id,
          //       res.data.carpeta
          //     );
          //   });
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    selectComprobantes() {
      axios.get("/api/retenciones/compras").then((resp) => {
        this.arrayFacturas = resp.data;
      });
    },

    // Modal detalles impuestos
    // Estructura para aggregar productos al detalle
    abrirModal() {
      $("#modal").modal("show");
    },
    cerrarModal() {
      $("#modal").modal("hide");
    },
    addDetalle(id) {
      this.arrayDetalle = [];
      let data = this.arrayCompras.find((e) => id == e.id);
      if (data) {
        let eje_fiscal =
          new Date().getMonth() + 1 + "/" + new Date().getFullYear();
        this.selectTarifa();
        if (data.sub_0 > 0) {
          this.arrayDetalle.push({
            tarifa_retencion_id: 0,
            comprobante: data.tip_comprobante,
            num_comprobante: data.num_comprobante,
            fec_emi_comprobante: data.fec_emision,
            eje_fiscal,
            bas_imponible: data.sub_0,
            imp_retencion: "RENTA",
            val_retenido: 0,
          });
        }
        if (data.sub_12 > 0) {
          this.arrayDetalle.push({
            tarifa_retencion_id: 0,
            comprobante: data.tip_comprobante,
            num_comprobante: data.num_comprobante,
            fec_emi_comprobante: data.fec_emision,
            eje_fiscal,
            bas_imponible: data.sub_12,
            imp_retencion: "IVA",
            val_retenido: 0,
          });
        }
      }
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

    selectTarifa() {
      axios.get("/api/tarifas-retencion").then((resp) => {
        this.arrayTarifas = resp.data;
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
    },
  },
  mounted() {
    this.getRetencion();
    this.selectComprobantes();
    this.selectCompra();
  },
};
</script>