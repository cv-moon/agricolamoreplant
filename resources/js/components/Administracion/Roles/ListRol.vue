<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Roles de Usuario
      </h3>
    </div>
    <div class="card-body">
      <table
        id="tabla"
        class="table table-striped table-bordered dt-responsive nowrap"
        style="width: 100%"
      >
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="rol in arrayRoles" :key="rol.id">
            <td v-text="rol.nombre"></td>
            <td v-text="rol.descripcion"></td>
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
      arrayRoles: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/roles").then((resp) => {
        this.arrayRoles = resp.data;
        this.myTable();
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>