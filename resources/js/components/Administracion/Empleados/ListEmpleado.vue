<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Usuarios - Empleados
      </h3>
      <div class="card-tools">
        <router-link to="/empleados/agregar" class="btn btn-success">
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
            <th># Iden</th>
            <th>Salario</th>
            <th>Fec. Ingreso</th>
            <th>Fec. Salida</th>
            <th>Rol</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="usuario in arrayUsuarios" :key="usuario.id">
            <td>
              <router-link
                type="button"
                title="Editar"
                :to="'/empleados/editar/' + usuario.id"
                class="btn btn-warning btn-xs"
              >
                <i class="fas fa-pen"></i>
              </router-link>
              <template v-if="usuario.estado == 0">
                <button
                  type="button"
                  title="Habilitar"
                  class="btn btn-primary btn-xs"
                  @click="habilitar(usuario.id)"
                >
                  <i class="far fa-check-circle"></i>
                </button>
              </template>
              <template v-else-if="usuario.estado == 1">
                <button
                  type="button"
                  title="Deshabilitar"
                  class="btn btn-danger btn-xs"
                  @click="deshabilitar(usuario.id)"
                >
                  <i class="fas fa-ban"></i>
                </button>
              </template>
            </td>
            <td v-text="usuario.apellidos + ' ' + usuario.nombres"></td>
            <td
              v-text="
                usuario.tip_identificacion + '-' + usuario.num_identificacion
              "
            ></td>
            <td v-text="usuario.salario" align="right"></td>
            <td v-text="usuario.fec_ingreso"></td>
            <td v-text="usuario.fec_salida"></td>
            <td v-text="usuario.rol"></td>
            <td>
              <div v-if="usuario.estado == 1">
                <span class="badge badge-success">Activo</span>
              </div>
              <div v-else-if="usuario.estado == 0">
                <span class="badge badge-danger">Inactivo</span>
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
      arrayUsuarios: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/empleados").then((resp) => {
        this.arrayUsuarios = resp.data;
        $("#tabla").DataTable().destroy();
        this.myTable();
      });
    },
    habilitar(id) {
      Swal.fire({
        title: "Desea habilitar este registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, habilitar!",
        cancelButtonText: "No, cancelar!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .put("/api/empleado/activar", {
              id: id,
            })
            .then((resp) => {
              Swal.fire(
                "Habilitado!",
                "El registro ha sido habilitado.",
                "success"
              );
              this.listar();
            })
            .catch((err) => {
              Swal.fire(
                "Error!",
                "No se pudo habilitar el registro. " + err,
                "error"
              );
            });
        }
      });
    },
    deshabilitar(id) {
      Swal.fire({
        title: "Desea deshabilitar este registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, deshabilitar!",
        cancelButtonText: "No, cancelar!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .put("/api/empleado/desactivar", {
              id: id,
            })
            .then((resp) => {
              Swal.fire(
                "Deshabilitado!",
                "El registro ha sido deshabilitado.",
                "success"
              );
              this.listar();
            })
            .catch((err) => {
              Swal.fire(
                "Error!",
                "No se pudo dehabilitar el registro. " + err,
                "error"
              );
            });
        }
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>