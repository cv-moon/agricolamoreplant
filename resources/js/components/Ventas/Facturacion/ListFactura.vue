<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Facturación
      </h3>
      <div class="card-tools">
        <router-link to="/facturacion/agregar" class="btn btn-success">
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
            <th>F. Emi.</th>
            <th># Fact.</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Usuario</th>
            <th>F. Pago</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="factura in arrayFacturas" :key="factura.id">
            <td v-text="factura.fec_emision"></td>
            <td
              v-text="
                `
                ${factura.establecimiento
                  .toString()
                  .padStart(3, 0)}-${factura.punto
                  .toString()
                  .padStart(3, 0)}-${factura.num_secuencial
                  .toString()
                  .padStart(9, 0)}`
              "
            ></td>
            <td v-text="factura.cliente"></td>
            <td align="right" v-text="factura.val_total"></td>
            <td v-text="factura.usuario"></td>
            <td>
              <div v-if="factura.for_pago == 'E'">EFECTIVO</div>
              <div v-else-if="factura.for_pago == 'C'">CRÉDITO</div>
            </td>
            <td v-text="factura.respuesta"></td>
            <td>
              <router-link
                type="button"
                title="Detalle"
                :to="'/factura/detalle/' + factura.id"
                class="btn btn-warning btn-xs"
              >
                <i class="fas fa-pen"></i>
              </router-link>
              <button
                type="button"
                title="Reenviar"
                @click="reenvio(factura.id)"
                class="btn btn-info btn-xs"
              >
                <i class="fas fa-paper-plane"></i>
              </button>
              <button
                type="button"
                title="PDF"
                @click="desPdf(factura.cla_acceso)"
                class="btn btn-danger btn-xs"
              >
                <i class="fas fa-file-pdf"></i>
              </button>
              <button
                type="button"
                title="XML"
                @click="desXml(factura.cla_acceso)"
                class="btn btn-secondary btn-xs"
              >
                <i class="fas fa-file-code"></i>
              </button>
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
      arrayFacturas: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/facturas").then((resp) => {
        this.arrayFacturas = resp.data;
        this.myTable();
      });
    },
    reenvio(id) {
      axios
        .post("/api/factura/reenvio", {
          id: id,
        })
        .then((resp) => {
          Swal.fire(
            "Bien!",
            "El comprobante se reenvió exitosamente.",
            "success"
          );
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo reenviar el comprobante. " + err,
            "error"
          );
        });
    },
    desPdf(clave) {
      window.open("/api/factura/pdf/" + clave, "_blank");
    },
    desXml(clave) {
      window.open("/api/factura/xml/" + clave, "_blank");
    },
  },
  mounted() {
    this.listar();
  },
};
</script>