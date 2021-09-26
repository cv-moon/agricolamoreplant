<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Transportistas
      </h3>
      <div class="card-tools">
        <router-link to="/transportistas/agregar" class="btn btn-success">
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
            <th>T. Iden</th>
            <th># Iden</th>
            <th>Placa</th>
            <th>Teléfonos</th>
            <th>Dirección</th>
            <th>E-mail</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="transportista in arrayTransportistas"
            :key="transportista.id"
          >
            <td>
              <router-link
                type="button"
                title="Editar"
                :to="'/transportistas/editar/' + transportista.id"
                class="btn btn-warning btn-xs"
              >
                <i class="fas fa-pen"></i>
              </router-link>
            </td>
            <td v-text="transportista.nombre"></td>
            <td v-text="transportista.tip_identificacion"></td>
            <td v-text="transportista.num_identificacion"></td>
            <td v-text="transportista.placa"></td>
            <td v-text="transportista.telefonos"></td>
            <td v-text="transportista.direccion"></td>
            <td v-text="transportista.email"></td>
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
      arrayTransportistas: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/transportistas").then((resp) => {
        this.arrayTransportistas = resp.data;
        this.myTable();
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>