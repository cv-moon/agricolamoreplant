<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Agregar Unidad de Medida
      </h3>
      <div class="card-tools">
        <router-link to="/unidades" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <form>
        <b class="text-primary">Datos Generales</b>
        <hr class="mt-0" />
        <div class="form-group row">
          <div class="col-sm-6">
            <label for="nombre" class="col-form-label">Nombre:</label>
            <input
              type="text"
              class="form-control"
              placeholder="Nombre."
              maxlength="100"
              v-model="nombre"
            />
          </div>
          <div class="col-sm-6">
            <label for="sigla" class="col-form-label">Sigla:</label>
            <div class="form-group row">
              <input
                type="text"
                class="form-control"
                placeholder="Sigla."
                maxlength="100"
                v-model="sigla"
              />
            </div>
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
      <router-link to="/unidades" class="btn btn-danger">
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
      nombre: "",
      sigla: "",
      errors: [],
    };
  },
  methods: {
    validaCampos() {
      this.errors = [];
      if (!this.nombre) {
        this.errors.push("Ingrese Nombre.");
      } else {
        axios
          .get("/api/unidad/valida/nombre", {
            params: {
              nombre: this.nombre,
            },
          })
          .then((resp) => {
            if (resp.data) {
              this.errors.push(
                "Ya existe un registro con el nombre ingresado."
              );
            }
          });
      }
      if (!this.sigla) {
        this.errors.push("Ingrese Sigla.");
      }
      return this.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/unidad/guardar", {
          nombre: this.nombre,
          sigla: this.sigla,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/unidades");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
  },
};
</script>