<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Abrir Caja
      </h3>
    </div>
    <template v-if="vista">
      <div class="card-body">
        <div class="text-center">
          <h1>NO HAY CAJAS PARA ABRIR</h1>
          <span style="font-size: 5rem">
            <span style="color: Green">
              <i class="fas fa-lock-open"></i>
            </span>
          </span>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="card-body">
        <form>
          <b class="text-primary">Datos Generales</b>
          <hr class="mt-0" />
          <div class="form-group row">
            <label for="establecimiento" class="col-sm-2 col-form-label"
              >Establecimiento:</label
            >
            <div class="col-sm-4">
              <input
                type="text"
                class="form-control"
                maxlength="100"
                v-model="establecimiento"
                readonly
              />
            </div>
            <label for="punto" class="col-sm-2 col-form-label">Punto:</label>
            <div class="col-sm-4">
              <input
                type="text"
                class="form-control"
                maxlength="100"
                v-model="punto"
                readonly
              />
            </div>
          </div>
          <div class="form-group row">
            <label for="usuario" class="col-sm-2 col-form-label"
              >Usuario:</label
            >
            <div class="col-sm-4">
              <input
                type="text"
                class="form-control"
                maxlength="100"
                v-model="usuario"
                readonly
              />
            </div>
            <label for="punto" class="col-sm-2 col-form-label"
              >$ Apertura:</label
            >
            <div class="col-sm-4">
              <input
                type="number"
                class="form-control"
                min="0"
                v-model="mon_apertura"
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
        <router-link to="/dashboard" class="btn btn-danger">
          Cancelar
        </router-link>
        <button type="button" class="btn btn-success" @click="guardar">
          Guardar
        </button>
      </div>
    </template>
  </div>
</template>
<script>
export default {
  data() {
    return {
      punto_id: 0,
      user_id: 0,
      establecimiento: "",
      punto: "",
      usuario: "",
      mon_apertura: 0,
      errors: [],
      vista: null,
    };
  },
  methods: {
    getAbrir() {
      axios.get("/api/arqueo/validar").then((resp) => {
        if (resp.data == "open") {
          this.vista = true;
        } else {
          this.vista = false;
          this.establecimiento = resp.data.establecimiento;
          this.punto = resp.data.punto;
          this.usuario = resp.data.usuario;
          this.user_id = resp.data.user_id;
          this.punto_id = resp.data.punto_id;
        }
      });
    },
    validaCampos() {
      this.errors = [];
      if (!this.mon_apertura || this.mon_apertura == 0) {
        this.errors.push("Revise monto de apertura.");
      }
      return this.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/arqueo/guardar", {
          punto_id: this.punto_id,
          user_id: this.user_id,
          mon_apertura: this.mon_apertura,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/arqueos");
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
  created() {
    this.getAbrir();
  },
};
</script>