<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Categorías
      </h3>
      <div class="card-tools">
        <router-link to="/categorias/agregar" class="btn btn-success">
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
            <th>Descripción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="categoria in arrayCategorias" :key="categoria.id">
            <td>
              <router-link
                type="button"
                title="Editar"
                :to="'/categorias/editar/' + categoria.id"
                class="btn btn-warning btn-xs"
              >
                <i class="fas fa-pen"></i>
              </router-link>
            </td>
            <td v-text="categoria.nombre"></td>
            <td v-text="categoria.descripcion"></td>
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
      arrayCategorias: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/categorias").then((resp) => {
        this.arrayCategorias = resp.data;
        this.myTable();
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>