<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Roles de Pago - Remuneraciones
      </h3>
      <div class="card-tools">
        <router-link to="/roles-pago/generar" class="btn btn-success">
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
            <th>Empleado</th>
            <th>Mes</th>
            <th>$ Venta</th>
            <th>% Com.</th>
            <th>Tot. Recibir</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="salario in arraySalarios" :key="salario.id">
            <td>
              <!-- <router-link
                type="button"
                title="Detalle"
                :to="'/roles-pago/detalle/' + salario.id"
                class="btn btn-info btn-xs"
              >
                <i class="fas fa-eye"></i>
              </router-link> -->
              <template v-if="salario.estado == 'R'">
                <router-link
                  type="button"
                  title="Detalle"
                  :to="'/roles-pago/editar/' + salario.id"
                  class="btn btn-warning btn-xs"
                >
                  <i class="fas fa-pen"></i>
                </router-link>
              </template>
              <template v-else-if="salario.estado == 'G'">
                <button
                  type="button"
                  title="Imprimir"
                  class="btn btn-success btn-xs"
                  @click="imprimir(salario.id)"
                >
                  <i class="fas fa-print"></i>
                </button>
              </template>
            </td>
            <td v-text="salario.apellidos + ' ' + salario.nombres"></td>
            <td v-text="salario.mes"></td>
            <td align="right" v-text="salario.ven_mensual"></td>
            <td align="right" v-text="salario.por_venta + ' %'"></td>
            <td align="right" v-text="salario.tot_recibir"></td>
            <td>
              <div v-if="salario.estado == 'R'">
                <span class="badge badge-success">Registrado</span>
              </div>
              <div v-else-if="salario.estado == 'G'">
                <span class="badge badge-danger">Generado</span>
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
      arraySalarios: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/roles_pago").then((resp) => {
        this.arraySalarios = resp.data;
        $("#tabla").DataTable().destroy();
        this.myTable();
      });
    },
    imprimir(id) {
      window.open("/api/roles_pago/imprimir/" + id, "_blank");
    },
  },
  mounted() {
    this.listar();
  },
};
</script>