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
    </div>
  </div>
</template>
<script>
import script_comprobantes from "../../../factura";
export default {
  data() {
    return {
      retencion: {
        punto_id: 0,
        cla_acceso: "",
        fec_emision: "",
        num_secuencial: "",
        tip_ambiente: 0,
        tip_emision: 0,
      },
      datos: {
        comprobante: "RETENCIÓN",
        firma: "",
        fir_clave: "",
      },
    };
  },
  computed: {},
  methods: {
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

    // Métodos para retencion electronica.
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
  },
  mounted() {
    this.getRetencion();
  },
};
</script>