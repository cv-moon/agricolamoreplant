<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Arqueos
      </h3>
    </div>
    <div class="card-body">
      <table
        id="arqueos"
        class="table table-bordered table-striped dt-responsive nowrap"
      >
        <thead>
          <tr>
            <th>Acción</th>
            <th>Responsable</th>
            <th>F. Apertura</th>
            <th>$ Apertura</th>
            <th>F. Cierre</th>
            <th>$ Cierre</th>
            <th>T. Efectivo</th>
            <th>T. Crédito</th>
            <th>T. Gastos</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="arqueo in arrayArqueos"
            :key="arqueo.id"
            :class="arqueo.estado == 1 ? 'table-success' : 'table-danger'"
          >
            <td>
              <router-link
                type="button"
                title="Detalles"
                :to="'/arqueos/detalle/' + arqueo.id"
                class="btn btn-info btn-xs"
              >
                <i class="fas fa-eye"></i>
              </router-link>
              <template v-if="arqueo.estado == 0">
                <button
                  type="button"
                  title="Imprimir"
                  class="btn btn-success btn-xs"
                  @click="imprimir(arqueo.id)"
                >
                  <i class="fas fa-print"></i>
                </button>
              </template>
            </td>
            <td v-text="arqueo.responsable"></td>
            <td v-text="arqueo.created_at"></td>
            <td align="right" v-text="arqueo.mon_apertura"></td>
            <td v-text="arqueo.fec_cierre"></td>
            <td align="right" v-text="arqueo.mon_cierre"></td>
            <td align="right" v-text="arqueo.tot_efectivo"></td>
            <td align="right" v-text="arqueo.tot_credito"></td>
            <td align="right" v-text="arqueo.tot_egreso"></td>
            <td>
              <div v-if="arqueo.estado == 1">
                <span class="badge badge-success">Abierto</span>
              </div>
              <div v-else-if="arqueo.estado == 0">
                <span class="badge badge-danger">Cerrado</span>
              </div>
            </td>
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
      arrayArqueos: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#arqueos").DataTable();
      });
    },
    listar() {
      axios.get("/api/arqueos").then((resp) => {
        this.arrayArqueos = resp.data;
        $("#arqueos").DataTable().destroy();
        this.myTable();
      });
    },
    imprimir(id) {
      window.open("/api/arqueo/reporte/" + id, "_blank");
    },
  },
  mounted() {
    this.listar();
  },
};
</script>