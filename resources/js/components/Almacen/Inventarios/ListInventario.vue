<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Inventarios
      </h3>
      <div class="card-tools">
        <!-- Example single danger button -->
        <div class="btn-group">
          <button
            type="button"
            class="btn btn-primary dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            Acciones
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <router-link class="dropdown-item" to="/inventarios/gestionar"
              >Gestionar</router-link
            >
            <div class="dropdown-divider"></div>
            <router-link class="dropdown-item" to="/inventarios/traslados"
              >Traslados</router-link
            >
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="establecimiento" class="col-sm-2 col-form-label"
          >Establecimiento:</label
        >
        <div class="col-sm-6">
          <select v-model="establecimiento" class="form-control">
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
          @click="listar(establecimiento)"
        >
          <i class="fas fa-search"></i>
        </button>
      </div>
      <table
        id="tabla"
        class="table table-striped table-bordered dt-responsive nowrap table-sm"
        style="width: 100%"
      >
        <thead>
          <tr>
            <th>Acción</th>
            <th>Estab.</th>
            <th>Cod. Prin.</th>
            <th>Producto</th>
            <th>Presentación</th>
            <th>Stock</th>
            <th>Mínimo</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="inventario in arrayInventarios"
            :key="inventario.id"
            :class="
              inventario.dis_stock <= inventario.min_stock
                ? 'table-danger'
                : inventario.dis_stock <= inventario.min_stock * 2 &&
                  inventario.dis_stock > inventario.min_stock
                ? 'table-warning'
                : ''
            "
          >
            <td>
              <router-link
                type="button"
                title="Kardex"
                :to="'/inventarios/kardex/' + inventario.id"
                class="btn btn-info btn-xs"
              >
                <i class="fas fa-clipboard-list"></i>
              </router-link>
            </td>
            <td v-text="inventario.nom_referencia"></td>
            <td v-text="inventario.cod_principal"></td>
            <td v-text="inventario.nombre"></td>
            <td v-text="inventario.unidad"></td>
            <td align="right" v-text="inventario.dis_stock"></td>
            <td align="right" v-text="inventario.min_stock"></td>
            <td>
              <div v-if="inventario.estado">
                <span class="badge badge-success">Activo</span>
              </div>
              <div v-else>
                <span class="badge badge-danger">Inactivo</span>
              </div>
            </td>
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
      establecimiento: 0,
      arrayInventarios: [],
      arrayEstablecimientos: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar(establecimiento) {
      axios
        .get("/api/inventarios?establecimiento=" + establecimiento)
        .then((resp) => {
          this.arrayInventarios = resp.data;
          $("#tabla").DataTable().destroy();
          this.myTable();
          this.establecimiento = 0;
        });
    },
    selectProveedores() {
      axios.get("/api/establecimientos").then((resp) => {
        this.arrayEstablecimientos = resp.data;
      });
    },
  },
  mounted() {
    this.listar(this.proveedor);
    this.selectProveedores();
  },
};
</script>