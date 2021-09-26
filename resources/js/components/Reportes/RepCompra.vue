<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Reporte de Compras
      </h3>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="estado" class="col-sm-1 col-form-label">Estado:</label>
        <div class="col-sm-3">
          <select v-model="estado" class="form-control">
            <option value="">Seleccione...</option>
            <option value="0">ANULADO</option>
            <option value="1">REGISTRADO</option>
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
        <label for="proveedor" class="col-sm-2 col-form-label"
          >Proveedor:</label
        >
        <div class="col-sm-4">
          <select v-model="proveedor" class="form-control">
            <option value="">Seleccione...</option>
            <option
              v-for="proveedor in arrayProveedores"
              :key="proveedor.id"
              :value="proveedor.id"
              v-text="proveedor.nombre"
            ></option>
          </select>
        </div>
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
      proveedor: "",
      estado: "",
      establecimiento: "",
      arrayEstablecimientos: [],
      arrayProveedores: [],
    };
  },
  methods: {
    imprimir() {
      window.open(
        "/api/reporte/compras?desde=" +
          this.fec_inicio +
          "&hasta=" +
          this.fec_fin +
          "&proveedor=" +
          this.proveedor +
          "&establecimiento=" +
          this.establecimiento +
          "&estado=" +
          this.estado,
        "_blank"
      );
    },
    selectProveedores() {
      axios.get("/api/proveedores").then((resp) => {
        this.arrayProveedores = resp.data;
      });
    },
    selectEstablecimiento() {
      axios.get("/api/establecimientos").then((resp) => {
        this.arrayEstablecimientos = resp.data;
      });
    },
  },
  mounted() {
    this.selectProveedores();
    this.selectEstablecimiento();
  },
};
</script>