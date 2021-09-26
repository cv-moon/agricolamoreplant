<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Cuenta por Cobrar: {{ cliente }}
      </h3>
      <div class="card-tools">
        <router-link to="/creditos" class="btn btn-secondary btn-xs">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <b class="text-primary">Detalle del Comprobante</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-3">
          <label for="cliente" class="col-form-label">Cliente:</label>
          <p v-text="cliente"></p>
        </div>
        <div class="col-3">
          <label for="comprobante" class="col-form-label">Comprobante:</label>
          <p v-text="'FACT - ' + num_secuencial"></p>
        </div>
        <div class="col-3">
          <label for="created_at" class="col-form-label">Fecha Emisión:</label>
          <p v-text="created_at"></p>
        </div>
      </div>
      <b class="text-primary">Detalle del Crédito</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-3">
          <label for="saldo" class="col-form-label">Saldo:</label>
          <p v-text="saldo"></p>
        </div>
        <div class="col-3">
          <label for="dias_credito" class="col-form-label"
            >Días de Crédito:</label
          >
          <p v-text="dias_credito"></p>
        </div>
        <div class="col-3">
          <label for="fec_limite" class="col-form-label">Fecha Límite:</label>
          <p v-text="fec_limite"></p>
        </div>
        <div class="col-3">
          <label for="fec_limite" class="col-form-label">Estado:</label>
          <p class="text-danger" v-if="estado=='A'">Anulado</p>
          <p class="text-success" v-else-if="estado=='C'">Pagado</p>
          <p class="text-warning" v-else-if="estado=='P'">Pendiente</p>
        </div>
      </div>
      <b class="text-primary">Detalle del Abonos</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-12">
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
                <td v-text="abono.observaciones"></td>
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
      <router-link to="/creditos" class="btn btn-danger"> Cancelar </router-link>
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
      cliente: "",
      num_secuencial: 0,
      created_at: "",
      arrayAbonos: [],
    };
  },
  methods: {
    detalle() {
      axios
        .get("/api/credito/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.saldo = resp.data.credito["saldo"];
          this.abonos = resp.data.credito["abonos"];
          this.dias_credito = resp.data.credito["dias_credito"];
          this.fec_limite = resp.data.credito["fec_limite"];
          this.estado = resp.data.credito["estado"];
          this.cliente = resp.data.credito["cliente"];
          this.num_secuencial = resp.data.credito["num_secuencial"];
          this.created_at = resp.data.credito["created_at"];
          this.arrayAbonos = resp.data.abonos;
        });
    },
    limpiar() {
      (this.saldo = 0),
        (this.abonos = 0),
        (this.dias_credito = 0),
        (this.fec_limite = ""),
        (this.estado = ""),
        (this.cliente = ""),
        (this.tip_comprobante = ""),
        (this.num_secuencial = ""),
        (this.created_at = ""),
        (this.arrayAbonos = []);
    },
  },
  mounted() {
    this.detalle();
  },
};
</script>