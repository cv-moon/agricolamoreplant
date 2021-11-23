<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Impuestos para Retención
      </h3>
      <div class="card-tools">
        <router-link to="/impuestos-retencion/agregar" class="btn btn-success">
          <i class="fas fa-plus"></i> Nuevo
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <table
        id="tabla"
        class="table table-striped table-bordered dt-responsive nowrap"
        style="width: 100%"
      >
        <thead>
          <tr>
            <th>Acción</th>
            <th>Impuesto</th>
            <th>Tarifa</th>
            <th>Valor (%)</th>
            <th>Codigo</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="tarifa in arrayTarifas" :key="tarifa.id">
            <td>
              <router-link
                type="button"
                title="Editar"
                :to="'/impuestos-retencion/editar/' + tarifa.id"
                class="btn btn-warning btn-xs"
              >
                <i class="fas fa-pen"></i>
              </router-link>
            </td>
            <td v-text="tarifa.impuesto"></td>
            <td style="width: 15px" v-text="tarifa.tarifa"></td>
            <td v-text="tarifa.valor"></td>
            <td v-text="tarifa.codigo"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
    <script>
export default {
  data() {
    return {
      arrayTarifas: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/tarifas-retencion").then((resp) => {
        this.arrayTarifas = resp.data;
        this.myTable();
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>