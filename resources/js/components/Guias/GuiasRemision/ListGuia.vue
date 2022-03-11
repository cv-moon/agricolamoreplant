<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Guías de Remisión
      </h3>
      <div class="card-tools">
        <router-link to="/guias/agregar" class="btn btn-success">
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
            <th># Guía.</th>
            <th>Transportista</th>
            <th>Motivo</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="guia in arrayGuias" :key="guia.id">
            <td v-text="guia.fec_emision"></td>
            <td
              v-text="
                `
                ${guia.establecimiento.toString().padStart(3, 0)}-${guia.punto
                  .toString()
                  .padStart(3, 0)}-${guia.num_secuencial
                  .toString()
                  .padStart(9, 0)}`
              "
            ></td>
            <td v-text="guia.transportista"></td>
            <td v-text="guia.motivo"></td>
            <td v-text="guia.usuario"></td>
            <td v-text="guia.respuesta"></td>
            <td>
              <button
                type="button"
                title="Reenviar"
                @click="reenvio(guia.id)"
                class="btn btn-info btn-xs"
              >
                <i class="fas fa-paper-plane"></i>
              </button>
              <button
                type="button"
                title="PDF"
                @click="desPdf(guia.cla_acceso)"
                class="btn btn-danger btn-xs"
              >
                <i class="fas fa-file-pdf"></i>
              </button>
              <button
                type="button"
                title="XML"
                @click="desXml(guia.cla_acceso)"
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
      arrayGuias: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/guias").then((resp) => {
        this.arrayGuias = resp.data;
        this.myTable();
      });
    },
    reenvio(id) {
      axios
        .post("/api/guia/reenvio", {
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
      window.open("/api/guia/pdf/" + clave, "_blank");
    },
    desXml(clave) {
      window.open("/api/guia/xml/" + clave, "_blank");
    },
  },
  mounted() {
    this.listar();
  },
};
</script>