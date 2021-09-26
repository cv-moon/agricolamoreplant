<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Compras
      </h3>
      <div class="card-tools">
        <router-link to="/compras/agregar" class="btn btn-success">
          <i class="fas fa-plus"></i> Nuevo
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <table
        id="tabla"
        class="table table-striped table-bordered dt-responsive nowrap"
        style="width: 100%"
      >
        <thead>
          <tr>
            <th>Acción</th>
            <th>Proveedor</th>
            <th>Establecimiento</th>
            <th># Comprobante</th>
            <th>Fec. Emisión</th>
            <th>Fec. Registro</th>
            <th>Total</th>
            <th>F. Pago</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="compra in arrayCompras" :key="compra.id">
            <td>
              <router-link
                type="button"
                title="Ver"
                :to="'/compras/detalle/' + compra.id"
                class="btn btn-info btn-xs"
              >
                <i class="fas fa-eye"></i>
              </router-link>
              <template v-if="compra.estado == 1">
                <button
                  type="button"
                  title="Anular"
                  class="btn btn-danger btn-xs"
                  @click="anular(compra.id)"
                >
                  <i class="fas fa-trash-alt"></i>
                </button>
              </template>
            </td>
            <td v-text="compra.proveedor"></td>
            <td v-text="compra.establecimiento"></td>
            <td
              v-text="compra.tip_comprobante + ' ' + compra.num_comprobante"
            ></td>
            <td v-text="compra.fec_emision"></td>
            <td
              v-text="compra.created_at"
            ></td>
            <td align="right" v-text="compra.total"></td>
            <td>
              <div v-if="compra.for_pago == 'E'">EFECTIVO</div>
              <div v-else-if="compra.for_pago == 'C'">CRÉDITO</div>
            </td>
            <td>
              <div v-if="compra.estado == 1">
                <span class="badge badge-success">Registrado</span>
              </div>
              <div v-else-if="compra.estado == 0">
                <span class="badge badge-danger">Anulado</span>
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
      arrayCompras: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/compras").then((resp) => {
        this.arrayCompras = resp.data;
        $("#tabla").DataTable().destroy();
        this.myTable();
      });
    },
    anular(id) {
      Swal.fire({
        title: "Desea anular este registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, anular!",
        cancelButtonText: "No, cancelar!",
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .put("/api/compra/anular", {
              id: id,
            })
            .then((resp) => {
              Swal.fire("Anulado!", "El registro ha sido anulado.", "success");
              this.listar();
            })
            .catch((err) => {
              Swal.fire(
                "Error!",
                "No se pudo anular el registro. " + err,
                "error"
              );
            });
        }
      });
    },
  },
  mounted() {
    this.listar();
  },
};
</script>