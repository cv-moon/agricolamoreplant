<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Reporte de Ventas
      </h3>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="cliente" class="col-sm-1 col-form-label">Cliente:</label>
        <div class="col-sm-3">
          <select v-model="cliente" class="form-control">
            <option value="">Seleccione...</option>
            <option
              v-for="cliente in arrayClientes"
              :key="cliente.id"
              :value="cliente.id"
              v-text="cliente.nombre"
            ></option>
          </select>
        </div>
        <label for="desde" class="col-sm-1 col-form-label">Desde:</label>
        <div class="col-sm-3">
          <input type="date" class="form-control" v-model="fec_inicio" />
        </div>
        <label for="hasta" class="col-sm-1 col-form-label">Hasta:</label>
        <div class="col-sm-3">
          <input type="date" class="form-control" v-model="fec_fin" />
        </div>
      </div>
      <div class="form-group row">
        <label for="establecimiento" class="col-sm-2 col-form-label"
          >Establecimiento:</label
        >
        <div class="col-sm-3">
          <select v-model="establecimiento" class="form-control">
            <option value="">Seleccione...</option>
            <option
              v-for="establecimiento in arrayEstablecimientos"
              :key="establecimiento.id"
              :value="establecimiento.id"
              v-text="establecimiento.nom_referencia"
            ></option>
          </select>
        </div>
        <button
          type="button"
          title="Generar"
          class="btn btn-success"
          @click="imprimir"
        >
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      fec_inicio: "",
      fec_fin: "",
      cliente: "",
      establecimiento: "",
      arrayClientes: [],
      arrayEstablecimientos: [],
    };
  },
  methods: {
    imprimir() {
      window.open(
        "/api/reporte/ventas?desde=" +
          this.fec_inicio +
          "&hasta=" +
          this.fec_fin +
          "&establecimiento=" +
          this.establecimiento +
          "&cliente=" +
          this.cliente,
        "_blank"
      );
    },
    selectClientes() {
      axios.get("/api/clientes").then((resp) => {
        this.arrayClientes = resp.data;
      });
    },
    selectEstablecimiento() {
      axios.get("/api/establecimientos").then((resp) => {
        this.arrayEstablecimientos = resp.data;
      });
    },
  },
  mounted() {
    this.selectClientes();
    this.selectEstablecimiento();
  },
};
</script>