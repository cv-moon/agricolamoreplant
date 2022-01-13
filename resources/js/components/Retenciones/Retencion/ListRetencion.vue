<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Retenciones
      </h3>
      <div class="card-tools">
        <router-link to="/retenciones/agregar" class="btn btn-success">
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
            <th># Ret.</th>
            <th>Suj. Retenido</th>
            <th>T. Retenido</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="retencion in arrayRetenciones" :key="retencion.id">
            <td v-text="retencion.fec_emision"></td>
            <td
              v-text="
                `
                ${retencion.establecimiento
                  .toString()
                  .padStart(3, 0)}-${retencion.punto
                  .toString()
                  .padStart(3, 0)}-${retencion.num_secuencial
                  .toString()
                  .padStart(9, 0)}`
              "
            ></td>
            <td v-text="retencion.nombre"></td>
            <td align="right" v-text="retencion.tot_retenido"></td>
            <td v-text="retencion.usuario"></td>
            <td v-text="retencion.respuesta"></td>
            <td>
              <router-link
                type="button"
                title="Detalle"
                :to="'/retenciones/detalle/' + retencion.id"
                class="btn btn-warning btn-xs"
              >
                <i class="fas fa-pen"></i>
              </router-link>
              <button
                type="button"
                title="Reenviar"
                @click="reenvio(retencion.id)"
                class="btn btn-info btn-xs"
              >
                <i class="fas fa-paper-plane"></i>
              </button>
              <button
                type="button"
                title="PDF"
                @click="desPdf(retencion.cla_acceso)"
                class="btn btn-danger btn-xs"
              >
                <i class="fas fa-file-pdf"></i>
              </button>
              <button
                type="button"
                title="XML"
                @click="desXml(retencion.cla_acceso)"
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
      arrayRetenciones: [],
    };
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar() {
      axios.get("/api/retenciones").then((resp) => {
        this.arrayRetenciones = resp.data;
        this.myTable();
      });
    },
    reenvio(id) {
      axios
        .post("/api/retencion/reenvio", {
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
      window.open("/api/retencion/pdf/" + clave, "_blank");
    },
    desXml(clave) {
      window.open("/api/retencion/xml/" + clave, "_blank");
    },
  },
  mounted() {
    this.listar();
  },
};
</script>