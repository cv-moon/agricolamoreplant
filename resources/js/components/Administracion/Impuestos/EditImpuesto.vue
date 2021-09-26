<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Editar Impuesto: {{ nombre }}
      </h3>
      <div class="card-tools">
        <router-link to="/impuestos" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <form>
        <b class="text-primary">Datos Generales</b>
        <hr class="mt-0" />
        <div class="form-group row">
          <div class="col-sm-3">
            <label for="tip_impuesto" class="col-form-label"
              >Tipo de Impuesto:</label
            >
            <select v-model="impuesto_id" class="form-control">
              <option value="0" disabled>Seleccione...</option>
              <option
                v-for="impuesto in arrayImpuestos"
                :key="impuesto.id"
                :value="impuesto.id"
                v-text="impuesto.nombre"
              ></option>
            </select>
          </div>
          <div class="col-sm-3">
            <label for="nombre" class="col-form-label">Nombre:</label>
            <input
              type="text"
              class="form-control"
              placeholder="Nombre."
              maxlength="100"
              v-model="nombre"
            />
          </div>
          <div class="col-sm-3">
            <label for="codigo" class="col-form-label">Código:</label>
            <input
              type="number"
              class="form-control"
              placeholder="0"
              min="0"
              max="255"
              v-model="codigo"
            />
          </div>
          <div class="col-sm-3">
            <label for="valor" class="col-form-label">Valor (%):</label>
            <input
              type="number"
              class="form-control"
              placeholder="0"
              min="0"
              max="255"
              v-model="valor"
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
      <router-link to="/impuestos" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-primary" @click="editar">
        Actualizar
      </button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      tarifa_id: 0,
      impuesto_id: 0,
      nombre: "",
      codigo: 0,
      valor: 0,
      arrayImpuestos: [],
      errors: [],
    };
  },
  methods: {
    detalle() {
      axios
        .get("/api/tarifa/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.tarifa_id = resp.data["id"];
          this.impuesto_id = resp.data["impuesto_id"];
          this.nombre = resp.data["nombre"];
          this.codigo = resp.data["codigo"];
          this.valor = resp.data["valor"];
        });
    },
    validaCampos() {
      this.errors = [];
      if (this.impuesto_id == 0) {
        this.errors.push("Seleccione Tipo de Impuesto.");
      }
      if (!this.nombre) {
        this.errors.push("Ingrese Nombre.");
      }
      if (!this.codigo) {
        this.errors.push("Ingrese Código.");
      }
      if (!this.valor) {
        this.errors.push("Ingrese Valor.");
      }
      return this.errors;
    },
    editar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .put("/api/tarifa/editar", {
          id: this.tarifa_id,
          impuesto_id: this.impuesto_id,
          nombre: this.nombre,
          codigo: this.codigo,
          valor: this.valor,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/impuestos");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    selectImpuestos() {
      axios.get("/api/impuestos").then((resp) => {
        this.arrayImpuestos = resp.data;
      });
    },
  },
  mounted() {
    this.detalle();
    this.selectImpuestos();
  },
};
</script>