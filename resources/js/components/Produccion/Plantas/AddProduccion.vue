<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Agregar Categoria
      </h3>
      <div class="card-tools">
        <router-link to="/producciones" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <form>
        <b class="text-primary">Datos Generales</b>
        <hr class="mt-0" />
        <div class="form-group row">
          <div class="col-sm-4">
            <label for="planta" class="col-sm-12 col-form-label">Planta:</label>
            <select v-model="producto_id" class="form-control">
              <option value="0">Seleccione...</option>
              <option
                v-for="producto in arrayPlantas"
                :key="producto.id"
                :value="producto.id"
                v-text="producto.nombre"
              ></option>
            </select>
          </div>
          <div class="col-sm-4">
            <label for="fec_ingreso" class="col-sm-12 col-form-label"
              >Fec. Ingreso:</label
            >
            <input type="date" class="form-control" v-model="fec_ingreso" />
          </div>
          <div class="col-sm-4">
            <label for="cant_ingreso" class="col-sm-12 col-form-label"
              >Cant. Ingreso:</label
            >
            <input
              type="number"
              placeholder="0"
              class="form-control"
              min="0"
              v-model="cant_ingreso"
            />
          </div>
        </div>
        <div v-if="errors.length" class="alert alert-danger">
          <div>
            <div v-for="error in errors" :key="error">
              {{ error }}
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="card-footer">
      <router-link to="/producciones" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-success" @click="guardar">
        Guardar
      </button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      producto_id: 0,
      fec_ingreso: "",
      cant_ingreso: 0,
      arrayPlantas: [],
      errors: [],
    };
  },
  methods: {
    validaCampos() {
      this.errors = [];
      if (this.producto_id == "0") {
        this.errors.push("Selecciones Planta.");
      }
      if (!this.fec_ingreso) {
        this.errors.push("Ingrese Fecha de Inicio.");
      }
      if (!this.cant_ingreso || this.cant_ingreso == 0) {
        this.errors.push("Ingrese Cantidad Inicial.");
      }
      return this.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/produccion/guardar", {
          producto_id: this.producto_id,
          fec_ingreso: this.fec_ingreso,
          cant_ingreso: this.cant_ingreso,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/producciones");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    selectPlantas() {
      axios.get("/api/productos/plantas").then((resp) => {
        this.arrayPlantas = resp.data;
      });
    },
  },
  mounted() {
    this.selectPlantas();
  },
};
</script>