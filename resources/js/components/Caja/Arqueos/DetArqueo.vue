<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Arqueo:
        <span class="badge badge-success" v-if="estado == 1">Abierto</span>
        <span class="badge badge-danger" v-else-if="estado == 0">Cerrado</span>
      </h3>
      <div class="card-tools">
        <router-link to="/arqueos" class="btn btn-secondary btn-xs">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <b class="text-primary">Datos Generales</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <label for="establecimiento" class="col-sm-2 col-form-label"
          >Establecimiento:</label
        >
        <div class="col-sm-4">
          <input
            type="text"
            class="form-control"
            maxlength="100"
            v-model="establecimiento"
            readonly
          />
        </div>
        <label for="punto" class="col-sm-2 col-form-label">Punto:</label>
        <div class="col-sm-4">
          <input
            type="text"
            class="form-control"
            maxlength="100"
            v-model="punto"
            readonly
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="usuario" class="col-sm-2 col-form-label">Usuario:</label>
        <div class="col-sm-4">
          <input
            type="text"
            class="form-control"
            maxlength="100"
            v-model="usuario"
            readonly
          />
        </div>
        <label for="punto" class="col-sm-2 col-form-label">$ Apertura:</label>
        <div class="col-sm-4">
          <input
            type="number"
            class="form-control"
            min="0"
            v-model="mon_apertura"
            readonly
          />
        </div>
      </div>
      <b class="text-primary">Datos del Cierre</b>
      <hr class="mt-0" />
      <div class="row">
        <div class="col-sm-6">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h5 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Ventas
              </h5>
            </div>
            <div class="card-body">
              <div class="form-group row">
                <label for="tot_efectivo" class="col-sm-4 col-form-label"
                  >V. Efectivo:</label
                >
                <div class="col-sm-8">
                  <p class="form-control text-right" v-text="tot_efectivo"></p>
                </div>
              </div>
              <div class="form-group row">
                <label for="tot_credito" class="col-sm-4 col-form-label">
                  V. Crédito:</label
                >
                <div class="col-sm-8">
                  <p class="form-control text-right" v-text="tot_credito"></p>
                </div>
              </div>
              <div class="form-group row">
                <label for="tot_credito" class="col-sm-4 col-form-label"
                  >Total Ventas:</label
                >
                <div class="col-sm-8">
                  <p class="form-control bg-success text-right">
                    {{ calculaTotal.toFixed(2) }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card card-danger card-outline">
            <div class="card-header">
              <h5 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Gastos Diarios
              </h5>
            </div>
            <div class="card-body">
              <label for="gastos" class="col-sm-12 col-form-label text-center"
                >Gastos Diarios</label
              >
              <div class="form-group row table-responsive">
                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th>Descripción</th>
                      <th>Valor</th>
                      <th>Beneficiario</th>
                    </tr>
                  </thead>
                  <tbody v-if="arrayGastos.length">
                    <tr v-for="gasto in arrayGastos" :key="gasto.id">
                      <td v-text="gasto.descripcion"></td>
                      <td align="right" v-text="gasto.valor"></td>
                      <td v-text="gasto.beneficiario"></td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="4" class="text-center">No hay registros</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="form-group row">
                <label for="tot_credito" class="col-sm-4 ol-form-label"
                  >Total Gastos:</label
                >
                <div class="col-sm-8">
                  <p
                    class="form-control bg-danger text-right"
                    v-text="tot_egreso"
                  ></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
          <label for="tot_entrega" class="col-sm-12 col-form-label text-center"
            >Total a Entregar</label
          >
          <h1 class="clon-sm-12 text-center text-success">
            <b>{{ (mon_cierre = calculaTotal.toFixed(2)) }}</b>
          </h1>
        </div>
        <div class="col-sm-3"></div>
      </div>
      <div class="form-group row">
        <label for="tot_efectivo" class="col-sm-4 col-form-label"
          >Observaciones:</label
        >
        <textarea
          class="form-control"
          rows="3"
          maxlength="150"
          v-model="observaciones"
          readonly
        ></textarea>
      </div>
    </div>
    <div class="card-footer">
      <router-link to="/arqueos" class="btn btn-danger"> Cancelar </router-link>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      punto: "",
      establecimiento: "",
      mon_apertura: 0,
      usuario: "",
      mon_cierre: 0,
      tot_credito: 0,
      tot_egreso: 0,
      tot_efectivo: 0,
      tot_cobros: 0,
      observaciones: "",
      estado: 0,
      arrayGastos: [],
      arrayResponsables: [],
    };
  },
  computed: {
    calculaTotal() {
      let total =
        parseFloat(this.tot_efectivo) -
        parseFloat(this.tot_credito) -
        parseFloat(this.tot_egreso);
      return total;
    },
  },
  methods: {
    detalle() {
      axios
        .get("/api/arqueo/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.tot_efectivo = resp.data.efectivo;
          this.tot_credito = resp.data.credito;
          this.arqueo_id = resp.data.arqueo.id;
          this.observaciones = resp.data.arqueo.observaciones;
          this.estado = resp.data.arqueo.estado;
          this.establecimiento = resp.data.arqueo.establecimiento;
          this.punto = resp.data.arqueo.punto;
          this.usuario = resp.data.arqueo.usuario;
          this.mon_apertura = resp.data.arqueo.mon_apertura;
          this.arrayGastos = resp.data.gastos;
          this.tot_egreso = resp.data.suma;
        });
    },
  },
  mounted() {
    this.detalle();
  },
};
</script>