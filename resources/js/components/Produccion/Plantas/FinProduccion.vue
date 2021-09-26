<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Producción: {{ fec_ingreso }}
      </h3>
      <div class="card-tools">
        <router-link to="/producciones" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <div class="col-sm-3">
          <label for="planta" class="col-form-label col-sm-12">Planta:</label>
          <p v-text="planta"></p>
        </div>
      </div>
      <b class="text-primary">Detalle de Ingreso</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-3">
          <label for="fec_ingreso" class="col-form-label col-sm-12"
            >Fecha:</label
          >
          <p v-text="fec_ingreso"></p>
        </div>
        <div class="col-sm-3">
          <label for="cant_ingreso" class="col-form-label col-sm-12"
            >Cantidad:</label
          >
          <p v-text="cant_ingreso"></p>
        </div>
        <div class="col-sm-3">
          <label for="estado" class="col-form-label col-sm-12">Estado:</label>
          <p v-if="estado == 'EP'" class="badge badge-success">EN PRODUCCIÓN</p>
          <p v-else-if="estado == 'PF'" class="badge badge-warning">
            FINALIZADA
          </p>
        </div>
      </div>
      <b class="text-primary">Finalización</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-3">
          <label for="cant_salida" class="col-form-label">Cantidad:</label>
          <input
            type="number"
            class="form-control"
            placeholder="0"
            min="0"
            v-model="cant_salida"
            @change="calcPorcentaje"
          />
        </div>
        <div class="col-sm-3">
          <label for="por_mortalidad" class="col-form-label"
            >(%) Mortalidad:</label
          >
          <input
            type="number"
            class="form-control"
            placeholder="0"
            min="0"
            v-model="por_mortalidad"
          />
        </div>
      </div>
    </div>
    <div class="card-footer">
      <router-link to="/producciones" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-warning" @click="finalizar">
        Finalizar
      </button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      produccion_id: 0,
      planta: "",
      fec_ingreso: "",
      cant_ingreso: 0,
      por_mortalidad: 0,
      cant_salida: 0,
      estado: "",
      errors: [],
    };
  },
  computed: {
    calcPorcentaje() {
      let resp = 0;
      resp = (this.cant_salida * 100) / this.cant_ingreso;
      return (this.por_mortalidad = resp.toFixed(2));
    },
  },
  methods: {
    detalle() {
      axios
        .get("/api/produccion/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.produccion_id = resp.data["id"];
          this.planta = resp.data["planta"];
          this.fec_ingreso = resp.data["fec_ingreso"];
          this.cant_ingreso = resp.data["cant_ingreso"];
          this.estado = resp.data["estado"];
        });
    },
    validaCampos() {
      this.errors = [];
      if (!this.cant_salida || this.cant_salida == 0) {
        this.errors.push("Ingrese Cantidad de Salida.");
      }
      if (!this.por_mortalidad || this.por_mortalidad == 0) {
        this.errors.push("Ingrese Porcentaje de Mortalidad.");
      }
      return this.errors;
    },
    finalizar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .put("/api/produccion/finalizar", {
          id: this.produccion_id,
          cant_salida: this.cant_salida,
          por_mortalidad: this.por_mortalidad,
        })
        .then((resp) => {
          Swal.fire("Bien!", "Producción Finalizada.", "success");
          this.$router.push("/producciones");
        })
        .catch((err) => {
          Swal.fire("Error!", "No se pudo finalizar. " + err, "error");
        });
    },
  },
  mounted() {
    this.detalle();
  },
};
</script>