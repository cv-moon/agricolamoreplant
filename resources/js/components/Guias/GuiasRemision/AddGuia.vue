<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Guía de Remisión
      </h3>
      <div class="card-tools">
        <router-link to="/guias" class="btn btn-secondary btn-sm">
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
          <p class="col-form-label">{{ guia.fec_emision }}</p>
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
            {{ guia.cla_acceso }}
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
      <div v-if="guia.errors.length" class="alert alert-danger">
        <div>
          <div v-for="error in guia.errors" :key="error">
            {{ error }}
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <router-link to="/guias" class="btn btn-danger"> Cancelar </router-link>
      <button type="button" class="btn btn-success" @click="guardar">
        Guardar
      </button>
    </div>
    <!-- Start Transportista Modal -->
    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Agregar Transportista</h4>
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
                    v-model="transportista.nombre"
                  />
                </div>
                <label for="placa" class="col-sm-1 col-form-label"
                  >Placa:</label
                >
                <div class="col-sm-3">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Placa."
                    maxlength="7"
                    v-model="transportista.placa"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="identificacion_id" class="col-sm-2 col-form-label"
                  >Tipo Identificación:</label
                >
                <div class="col-sm-4">
                  <select
                    v-model="transportista.identificacion_id"
                    class="form-control"
                  >
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
                  <template v-if="identificacion_id === 2">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Cédula."
                      maxlength="10"
                      v-model="transportista.num_identificacion"
                    />
                  </template>
                  <template v-else-if="identificacion_id === 1">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="RUC."
                      maxlength="13"
                      v-model="transportista.num_identificacion"
                    />
                  </template>
                  <template v-else-if="identificacion_id === 3">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Pasaporte."
                      maxlength="13"
                      v-model="transportista.num_identificacion"
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
                    v-model="transportista.direccion"
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
                    v-model="transportista.telefonos"
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
                    v-model="transportista.email"
                  />
                </div>
              </div>
              <div
                v-if="transportista.errors.length"
                class="alert alert-danger"
              >
                <div>
                  <div v-for="error in transportista.errors" :key="error">
                    {{ error }}
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" @click="cerrarModal">
              Close
            </button>
            <button
              type="button"
              class="btn btn-success"
              @click="guardarTransportista"
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

      guia: {
        factura_id: 0,
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
        motivo: "",
        ruta: "",
        observaciones: "",
        errors: [],
      },

      datos: {
        tip_comprobante: "GUÍA DE REMISIÓN",
        comprobante: "",
        firma: "",
        fir_clave: "",
      },

      producto: {
        producto_id: 0,
        nombre: "",
        cantidad: 0,
      },

      transportista: {
        identificacion_id: 0,
        nombre: "",
        num_identificacion: "",
        direccion: "",
        telefonos: "",
        email: "",
        placa: "",
        errors: [],
      },

      identificacion_id: 0,
      arrayFactura: [],
      arrayIdentificaciones: [],
      arrayDetalle: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    getGuia() {
      axios.get("/api/guia/comprobante").then((resp) => {
        this.guia.cla_acceso =
          resp.data.cla_acceso + this.modulo11(resp.data.cla_acceso);
        this.guia.fec_emision = resp.data.fec_emision;
        this.guia.num_secuencial = resp.data.num_secuencial;
        this.guia.punto_id = resp.data.punto_id;
        this.guia.tip_ambiente = resp.data.tip_ambiente;
        this.guia.tip_emision = resp.data.tip_emision;
        this.datos.comprobante = resp.data.comprobante;
        this.datos.firma = resp.data.firma;
        this.datos.fir_clave = resp.data.fir_clave;
      });
    },
    validaCampos() {
      this.guia.errors = [];
      if (!this.guia.cliente_id || this.guia.cliente_id == 0) {
        this.guia.errors.push("Seleccione Cliente.");
      }
      if (this.arrayDetalle.length == 0) {
        this.guia.errors.push("Agregue Productos");
      }
      if (this.pago.tipo == "C") {
        if (!this.pago.plazo || this.pago.plazo == 0) {
          this.guia.errors.push("Revise el plazo");
        }
      }
      return this.guia.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/guia/guardar", {
          cliente_id: this.guia.cliente_id,
          punto_id: this.guia.punto_id,
          transportista_id: this.guia.transportista_id,
          transportista_id: this.guia.transportista_id,
          detalles: this.arrayDetalle,
        })
        .then((resp) => {
          axios
            .post("/api/factura/xml_guia", {
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

    eliminarDetalle(index) {
      this.arrayDetalle.splice(index, 1);
    },

    // Estructura para Agregar  Cliente
    abrirModal() {
      $("#modal").modal("show");
      this.selectIdentificaciones();
    },
    cerrarModal() {
      $("#modal").modal("hide");
      this.limpiar();
    },
    validaCamposTransportista() {
      this.transportista.errors = [];
      if (!this.transportista.nombre) {
        this.transportista.errors.push("Ingrese nombre.");
      }
      if (this.transportista.identificacion_id == "0") {
        this.transportista.errors.push("Seleccione tipo de identificación.");
      }
      if (!this.transportista.num_identificacion) {
        this.transportista.errors.push("Ingrese número de identificación.");
      }
      if (!this.transportista.placa) {
        this.transportista.errors.push("Ingrese Placa.");
      }
      return this.transportista.errors;
    },
    guardarTransportista() {
      const condiciones = this.validaCamposTransportista();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/transportista/guardar", {
          nombre: this.transportista.nombre,
          identificacion_id: this.transportista.identificacion_id,
          num_identificacion: this.transportista.num_identificacion,
          direccion: this.transportista.direccion,
          telefonos: this.transportista.telefonos,
          email: this.transportista.email,
          placa: this.transportista.placa,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.cerrarModal();
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
      this.transportista.nombre = "";
      this.transportista.identificacion_id = "0";
      this.transportista.num_identificacion = "";
      this.transportista.direccion = "";
      this.transportista.telefonos = "";
      this.transportista.email = "";
      this.transportista.placa = "";
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
          this.$router.push("/guias");
        } else {
          Swal.fire(
            "Error!",
            "La factura no pudo ser enviada, intente mas tarde.",
            "error"
          );
          this.$router.push("/guias");
        }
      } catch (err) {
        Swal.fire("Error!", "Error en el envio al SRI" + err, "error");
        this.$router.push("/guias");
      }
    },
  },
  mounted() {
    this.getGuia();
  },
};
</script>