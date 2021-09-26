<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Agregar Proveedor
      </h3>
      <div class="card-tools">
        <router-link to="/proveedores" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <form>
        <b class="text-primary">Datos Generales</b>
        <hr class="mt-0" />
        <div class="form-group row">
          <label for="nombre" class="col-sm-2 col-form-label">Nombre:</label>
          <div class="col-sm-6">
            <input
              type="text"
              class="form-control"
              placeholder="Nombre."
              maxlength="100"
              v-model="nombre"
            />
          </div>
        </div>
        <div class="form-group row">
          <label for="tip_identificacion" class="col-sm-2 col-form-label"
            >Tipo Identificación:</label
          >
          <div class="col-sm-4">
            <select v-model="tip_identificacion" class="form-control">
              <option value="0" disabled>Seleccione...</option>
              <option value="CED">CÉDULA</option>
              <option value="RUC">RUC</option>
              <option value="S/N">S/N</option>
            </select>
          </div>
          <label for="num_identificacion" class="col-sm-2 col-form-label"
            ># Identificación:</label
          >
          <div class="col-sm-4">
            <template v-if="tip_identificacion === 'CED'">
              <input
                type="text"
                class="form-control"
                placeholder="Cédula."
                maxlength="10"
                v-model="num_identificacion"
              />
            </template>
            <template v-else-if="tip_identificacion === 'RUC'">
              <input
                type="text"
                class="form-control"
                placeholder="RUC."
                maxlength="13"
                v-model="num_identificacion"
              />
            </template>
            <template
              v-else-if="
                tip_identificacion === 'SELECCIONE' ||
                tip_identificacion === 'S/N'
              "
            >
              <input
                type="text"
                class="form-control"
                placeholder="Sin numeración."
                maxlength="13"
                disabled
                v-model="num_identificacion"
              />
            </template>
          </div>
        </div>
        <div class="form-group row">
          <label for="direccion" class="col-sm-2 col-form-label"
            >Dirección:</label
          >
          <div class="col-sm-10">
            <input
              type="text"
              class="form-control"
              placeholder="Dirección."
              maxlength="200"
              v-model="direccion"
            />
          </div>
        </div>
        <div class="form-group row">
          <label for="telefonos" class="col-sm-2 col-form-label"
            >Teléfonos:</label
          >
          <div class="col-sm-4">
            <input
              type="text"
              class="form-control"
              placeholder="Teléfonos."
              maxlength="50"
              v-model="telefonos"
            />
          </div>
          <label for="email" class="col-sm-2 col-form-label"
            >Site/e-mail:</label
          >
          <div class="col-sm-4">
            <input
              type="email"
              class="form-control"
              placeholder="E-mail."
              maxlength="100"
              v-model="email"
            />
          </div>
        </div>
        <b class="text-primary">Información del Contacto</b>
        <hr class="mt-0" />
        <div class="form-group row">
          <label for="nom_contacto" class="col-sm-2 col-form-label"
            >Nombre:</label
          >
          <div class="col-sm-4">
            <input
              type="text"
              class="form-control"
              placeholder="Nombre."
              maxlength="100"
              v-model="nom_contacto"
            />
          </div>
          <label for="tel_contacto" class="col-sm-2 col-form-label"
            >Teléfonos:</label
          >
          <div class="col-sm-4">
            <input
              type="text"
              class="form-control"
              placeholder="Teléfonos."
              maxlength="50"
              v-model="tel_contacto"
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
      <router-link to="/proveedores" class="btn btn-danger">
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
      tip_identificacion: "0",
      num_identificacion: "",
      direccion: "",
      telefonos: "",
      email: "",
      nom_contacto: "",
      tel_contacto: "",
      errors: [],
    };
  },
  methods: {
    validaCampos() {
      this.errors = [];
      if (!this.nombre) {
        this.errors.push("Ingrese nombre.");
      }
      if (this.tip_identificacion == "0") {
        this.errors.push("Seleccione tipo de identificación.");
      }
      if (!this.num_identificacion) {
        this.errors.push("Ingrese número de identificación.");
      }
      return this.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/proveedor/guardar", {
          nombre: this.nombre,
          tip_identificacion: this.tip_identificacion,
          num_identificacion: this.num_identificacion,
          direccion: this.direccion,
          telefonos: this.telefonos,
          email: this.email,
          nom_contacto: this.nom_contacto,
          tel_contacto: this.tel_contacto,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/proveedores");
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