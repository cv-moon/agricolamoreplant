<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Kardex
      </h3>
      <div class="card-tools">
        <router-link to="/inventarios" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <b class="text-primary">Datos Generales</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-6">
          <label for="producto" class="col-form-label">Producto:</label>
          <p v-text="producto"></p>
        </div>
        <div class="col-sm-6">
          <label for="establecimiento" class="col-form-label"
            >Establecimiento:</label
          >
          <p v-text="establecimiento"></p>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-3">
          <label for="pre_venta" class="col-form-label">Precio de Venta:</label>
          <p v-text="pre_venta"></p>
        </div>
        <div class="col-sm-3">
          <label for="min_stock" class="col-form-label">Stock Mínimo:</label>
          <p v-text="min_stock"></p>
        </div>
        <div class="col-sm-3">
          <label for="dis_stock" class="col-form-label"
            >Stock Disponible:</label
          >
          <p v-text="dis_stock"></p>
        </div>
        <div class="col-sm-3">
          <label for="estado" class="col-form-label">Estado:</label>
          <p v-if="estado == 1">ACTIVO</p>
          <p v-else-if="estado == 0">DESACTIVADO</p>
        </div>
      </div>
      <b class="text-primary">Detalle de Movimientos</b>
      <hr class="mt-0" />
      <table
        id="tabla"
        class="table table-bordered dt-responsive nowrap table-sm"
        style="width: 100%"
      >
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Concepto</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="inventario in arrayKardex"
            :key="inventario.id"
            :class="getClass(inventario.concepto)"
          >
            <td v-text="inventario.created_at"></td>
            <td v-text="inventario.concepto"></td>
            <td align="right" v-text="inventario.cantidad"></td>
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
      establecimiento: "",
      producto: "",
      min_stock: 0,
      dis_stock: 0,
      pre_venta: 0,
      estado: null,
      arrayKardex: [],
      arrayEstablecimientos: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    detalle() {
      axios
        .get("/api/kardex/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.producto = `${resp.data.datos["cod_principal"]} - ${resp.data.datos["nombre"]}`;
          this.establecimiento = `${resp.data.datos["nom_comercial"]} - ${resp.data.datos["nom_referencia"]}`;
          this.pre_venta = resp.data.datos["pre_venta"];
          this.min_stock = resp.data.datos["min_stock"];
          this.dis_stock = resp.data.datos["dis_stock"];
          this.estado = resp.data.datos["estado"];
          this.arrayKardex = resp.data.kardex;
          $("#tabla").DataTable().destroy();
          this.myTable();
        });
    },
    getClass(concepto = "") {
      if (concepto.includes("INGRESO")) return "table-info";
      if (concepto.includes("COMPRA")) return "table-danger";
      if (concepto.includes("VENTA")) return "table-success";
      if (concepto.includes("TRASLADO")) return "table-warning";
    },
  },
  mounted() {
    this.detalle();
  },
};
</script>