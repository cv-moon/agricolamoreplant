<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Cuenta por Pagar: {{ proveedor }}
      </h3>
      <div class="card-tools">
        <router-link to="/deudas" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <b class="text-primary">Detalle del Comprobante</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-3">
          <label for="proveedor" class="col-form-label">Proveedor:</label>
          <p v-text="proveedor"></p>
        </div>
        <div class="col-sm-3">
          <label for="comprobante" class="col-form-label">Comprobante:</label>
          <p v-text="tip_comprobante + '-' + num_comprobante"></p>
        </div>
        <div class="col-sm-3">
          <label for="fec_emision" class="col-form-label">Fecha Emisión:</label>
          <p v-text="fec_emision"></p>
        </div>
      </div>
      <b class="text-primary">Detalle del Crédito</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-3">
          <label for="saldo" class="col-form-label">Saldo:</label>
          <p v-text="saldo"></p>
        </div>
        <div class="col-sm-3">
          <label for="dias_credito" class="col-form-label"
            >Días de Crédito:</label
          >
          <p v-text="dias_credito"></p>
        </div>
        <div class="col-sm-3">
          <label for="fec_limite" class="col-form-label">Fecha Límite:</label>
          <p v-text="fec_limite"></p>
        </div>
        <div class="col-sm-3">
          <label for="fec_limite" class="col-form-label">Estado:</label>
          <p class="text-danger" v-if="estado=='A'">Anulado</p>
          <p class="text-success" v-else-if="estado=='C'">Pagado</p>
          <p class="text-warning" v-else-if="estado=='P'">Pendiente</p>
        </div>
      </div>
      <b class="text-primary">Detalle del Abonos</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-12 table-responsive">
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th class="text-center">Fecha Abono</th>
                <th class="text-center">Valor Abono</th>
                <th class="text-center">Observaciones</th>
              </tr>
            </thead>
            <tbody v-if="arrayAbonos.length">
              <tr v-for="abono in arrayAbonos" :key="abono.id">
                <td v-text="abono.fec_abono"></td>
                <td align="right" v-text="abono.val_abono"></td>
                <td v-text="abono.obs_abono"></td>
              </tr>
            </tbody>
            <tbody v-else>
              <tr>
                <td colspan="3" class="text-center">
                  NO hay artículos agregados
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <router-link to="/deudas" class="btn btn-danger"> Cancelar </router-link>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      saldo: 0,
      abonos: 0,
      dias_credito: 0,
      fec_limite: "",
      estado: "",
      proveedor: "",
      tip_comprobante: "",
      num_comprobante: "",
      fec_emision: "",
      arrayAbonos: [],
    };
  },
  methods: {
    detalle() {
      axios
        .get("/api/deuda/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.saldo = resp.data.deuda["saldo"];
          this.abonos = resp.data.deuda["abonos"];
          this.dias_credito = resp.data.deuda["dias_credito"];
          this.fec_limite = resp.data.deuda["fec_limite"];
          this.estado = resp.data.deuda["estado"];
          this.proveedor = resp.data.deuda["proveedor"];
          this.tip_comprobante = resp.data.deuda["tip_comprobante"];
          this.num_comprobante = resp.data.deuda["num_comprobante"];
          this.fec_emision = resp.data.deuda["fec_emision"];
          this.arrayAbonos = resp.data.abonos;
        });
    },
    limpiar() {
      (this.saldo = 0),
        (this.abonos = 0),
        (this.dias_credito = 0),
        (this.fec_limite = ""),
        (this.estado = ""),
        (this.proveedor = ""),
        (this.tip_comprobante = ""),
        (this.num_comprobante = ""),
        (this.fec_emision = ""),
        (this.arrayAbonos = []);
    },
  },
  mounted() {
    this.detalle();
  },
};
</script>