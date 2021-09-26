<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Gestión de Productos por Establecimiento
      </h3>
      <div class="card-tools">
        <router-link to="/inventarios" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="establecimiento" class="col-sm-2 col-form-label"
          >Establecimiento:</label
        >
        <div class="col-sm-6">
          <select v-model="establecimiento_id" class="form-control">
            <option value="0">Seleccione...</option>
            <option
              v-for="establecimiento in arrayEstablecimientos"
              :key="establecimiento.id"
              :value="establecimiento.id"
              v-text="
                establecimiento.nom_comercial +
                ' - ' +
                establecimiento.nom_referencia
              "
            ></option>
          </select>
        </div>
        <button
          type="button"
          title="Buscar"
          class="btn btn-success"
          @click="listar(establecimiento_id)"
        >
          <i class="fas fa-search"></i>
        </button>
      </div>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th class="text-center">
                <template v-if="arrayProductos.length">
                  <input
                    type="checkbox"
                    v-model="checkAll"
                    @change="selectAll"
                  />
                </template>
              </th>
              <th>Producto</th>
              <th>Stock Inicial</th>
              <th>Stock Mínimo</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="arrayProductos.length">
              <tr v-for="producto in arrayProductos" :key="producto.id">
                <td class="text-center">
                  <input type="checkbox" v-model="producto.check" />
                </td>
                <td v-text="producto.nombre"></td>
                <td>
                  <input
                    type="number"
                    placeholder="0"
                    min="0"
                    v-model="producto.dis_stock"
                  />
                </td>
                <td>
                  <input
                    type="number"
                    placeholder="0"
                    min="0"
                    v-model="producto.min_stock"
                  />
                </td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td align="center" colspan="4">No data available in table</td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <router-link to="/inventarios" class="btn btn-danger">
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
      establecimiento_id: 0,
      checkAll: null,
      arrayProductos: [],
      arrayEstablecimientos: [],
    };
  },
  methods: {
    guardar() {
      axios
        .post("/api/inventario/guardar", {
          establecimiento_id: this.establecimiento_id,
          productos: this.arrayProductos,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/inventarios");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    listar(id = 0) {
      axios
        .get("/api/productos/sin_registro?establecimiento=" + id)
        .then((resp) => {
          this.arrayProductos = resp.data;
        });
    },
    selectEstablecimientos() {
      axios.get("/api/establecimientos").then((resp) => {
        this.arrayEstablecimientos = resp.data;
      });
    },
    selectAll() {
      this.arrayProductos.forEach((e) => (e.check = this.checkAll));
    },
  },
  mounted() {
    this.listar();
    this.selectEstablecimientos();
  },
};
</script>