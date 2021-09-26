<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Unidades
      </h3>
      <div class="card-tools">
        <router-link to="/unidades/agregar" class="btn btn-success">
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
            <th>Nombre</th>
            <th>Sigla</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="unidad in arrayUnidades" :key="unidad.id">
            <td>
              <router-link
                type="button"
                title="Editar"
                :to="'/unidades/editar/' + unidad.id"
                class="btn btn-warning btn-xs"
              >
                <i class="fas fa-pen"></i>
              </router-link>
            </td>
            <td v-text="unidad.nombre"></td>
            <td v-text="unidad.sigla"></td>
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
      arrayUnidades: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/unidades").then((resp) => {
        this.arrayUnidades = resp.data;
        this.myTable();
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>