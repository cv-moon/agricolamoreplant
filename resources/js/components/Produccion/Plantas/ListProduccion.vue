<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Categorías
      </h3>
      <div class="card-tools">
        <router-link to="/producciones/agregar" class="btn btn-success">
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
            <th>Planta</th>
            <th>Fec. Ingreso</th>
            <th>Cant. Ingreso</th>
            <th>(%) Mortalidad</th>
            <th>Prod. Neta</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="produccion in arrayProducciones" :key="produccion.id">
            <td>
              <template v-if="produccion.estado == 'EP'">
                <router-link
                  type="button"
                  title="Editar"
                  :to="'/producciones/editar/' + produccion.id"
                  class="btn btn-warning btn-xs"
                >
                  <i class="fas fa-pen"></i>
                </router-link>
                <router-link
                  type="button"
                  title="Finalizar"
                  :to="'/producciones/finalizar/' + produccion.id"
                  class="btn btn-success btn-xs"
                >
                  <i class="fas fa-check-double"></i>
                </router-link>
              </template>
            </td>
            <td v-text="produccion.nombre"></td>
            <td v-text="produccion.fec_ingreso"></td>
            <td align="right" v-text="produccion.cant_ingreso"></td>
            <td align="right" v-text="produccion.por_mortalidad"></td>
            <td align="right" v-text="produccion.cant_salida"></td>
            <td>
              <div v-if="produccion.estado == 'EP'">
                <span class="badge badge-success">En producción</span>
              </div>
              <div v-else-if="produccion.estado == 'PF'">
                <span class="badge badge-danger">Finalizado</span>
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
      arrayProducciones: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/producciones").then((resp) => {
        this.arrayProducciones = resp.data;
        this.myTable();
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>