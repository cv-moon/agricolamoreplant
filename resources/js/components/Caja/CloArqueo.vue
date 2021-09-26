<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Cerrar Caja
      </h3>
    </div>
    <template v-if="vista">
      <div class="card-body">
        <div class="text-center">
          <h1>NO HAY CAJAS ABIERTAS</h1>
          <span style="font-size: 5rem">
            <span style="color: Red">
              <i class="fas fa-lock"></i>
            </span>
          </span>
        </div>
      </div>
    </template>
    <template v-else>
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
                    <p
                      class="form-control text-right"
                      v-text="tot_efectivo"
                    ></p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="tot_credito" class="col-sm-4 col-form-label"
                    >V. Crédito:</label
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
                      {{ tot_efectivo + tot_credito }}
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
                <template v-if="arqueo_id != '0'">
                  <div class="card-tools">
                    <button
                      class="btn btn-xs btn-success"
                      data-target="#gasto"
                      @click="abrirGasto()"
                    >
                      <i class="fas fa-plus"></i> Agregar
                    </button>
                  </div>
                </template>
              </div>
              <div class="card-body">
                <label for="gastos" class="col-sm-12 col-form-label text-center"
                  >Gastos Diarios</label
                >
                <div class="form-group row table-responsive">
                  <table class="table table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Valor</th>
                        <th>Beneficiario</th>
                      </tr>
                    </thead>
                    <tbody v-if="arrayGastos.length">
                      <tr v-for="gasto in arrayGastos" :key="gasto.id">
                        <td align="center">
                          <button
                            title="Eliminar"
                            type="button"
                            class="btn btn-danger btn-xs"
                            @click="eliminar(gasto.id)"
                          >
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                        <td v-text="gasto.descripcion"></td>
                        <td align="right" v-text="gasto.valor"></td>
                        <td v-text="gasto.beneficiario"></td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr>
                        <td colspan="4" class="text-center">
                          No hay registros
                        </td>
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
          <!-- Start Modal -->
          <div class="modal fade" id="gasto">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Agregar Gasto</h4>
                  <button
                    type="button"
                    class="close"
                    aria-label="Close"
                    @click="cerrarGasto"
                  >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal">
                    <div class="card-body">
                      <div class="form-group row">
                        <label for="descripcion" class="col-sm-4 col-form-label"
                          >Descripción:</label
                        >
                        <div class="col-sm-8">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Descripción."
                            maxlength="100"
                            v-model="descripcion"
                          />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="valor" class="col-sm-4 col-form-label"
                          >Valor $:</label
                        >
                        <div class="col-sm-8">
                          <input
                            type="number"
                            class="form-control"
                            min="0"
                            v-model="valor"
                          />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label
                          for="beneficiario"
                          class="col-sm-4 col-form-label"
                          >Beneficiario:</label
                        >
                        <div class="col-sm-8">
                          <select v-model="user_id" class="form-control">
                            <option value="0">Seleccione...</option>
                            <option
                              v-for="beneficiario in arrayBeneficiarios"
                              :key="beneficiario.id"
                              :value="beneficiario.id"
                              v-text="beneficiario.usuario"
                            ></option>
                          </select>
                        </div>
                      </div>
                      <!-- errors -->
                      <div v-if="errors.length" class="alert alert-danger">
                        <div>
                          <div v-for="error in errors" :key="error">
                            {{ error }}
                          </div>
                        </div>
                      </div>
                      <!-- /. errors -->
                    </div>
                    <!-- /.card-body -->
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button
                    type="button"
                    class="btn btn-default"
                    @click="cerrarGasto"
                  >
                    Close
                  </button>
                  <button
                    type="button"
                    class="btn btn-success"
                    @click="guardar"
                  >
                    Guardar
                  </button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
        </div>
        <div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-6">
            <label
              for="mon_cierre"
              class="col-sm-12 col-form-label text-center"
              >Total a Entregar</label
            >
            <h1 class="clon-sm-12 text-center text-success">
              <b>{{ (mon_cierre = calculaTotal.toFixed(2)) }}</b>
            </h1>
          </div>
          <div class="col-sm-3"></div>
        </div>
        <template v-if="arqueo_id != '0'">
          <div class="form-group row">
            <label for="tot_efectivo" class="col-sm-4 col-form-label"
              >Observaciones:</label
            >
            <textarea
              class="form-control"
              rows="3"
              maxlength="150"
              v-model="observaciones"
            ></textarea>
          </div>
        </template>
        <!-- errors -->
        <div v-if="errorsd.length" class="alert alert-danger">
          <div>
            <div v-for="err in errorsd" :key="err">
              {{ err }}
            </div>
          </div>
        </div>
        <!-- /. errors -->
      </div>
      <div class="card-footer">
        <router-link to="/arqueos" class="btn btn-danger">
          Cancelar
        </router-link>
        <button type="button" class="btn btn-success" @click="cerrar">
          Cerrar
        </button>
      </div>
    </template>
  </div>
</template>
<script>
export default {
  data() {
    return {
      arqueo_id: 0,
      punto: "",
      establecimiento: "",
      mon_apertura: 0,
      usuario: "",
      mon_cierre: 0,
      tot_credito: 0,
      tot_egreso: 0,
      tot_efectivo: 0,
      observaciones: "",
      user_id: 0,
      descripcion: "",
      valor: 0,
      arrayGastos: [],
      arrayBeneficiarios: [],
      errors: [],
      errorsd: [],
      vista: null,
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
    getDatos() {
      axios.get("/api/arqueo/datos").then((resp) => {
        if (resp.data == "nothing") {
          this.vista = true;
        } else {
          this.tot_efectivo = resp.data.efectivo;
          this.tot_credito = resp.data.credito;
          this.arqueo_id = resp.data.arqueo.id;
          this.establecimiento = resp.data.arqueo.establecimiento;
          this.punto = resp.data.arqueo.punto;
          this.usuario = resp.data.arqueo.usuario;
          this.mon_apertura = resp.data.arqueo.mon_apertura;
          this.arrayGastos = resp.data.gastos;
          this.tot_egreso = resp.data.suma;
        }
      });
    },
    validaCierre() {
      this.errorsd = [];
      if (!this.observaciones) {
        this.errorsd.push("Ingrese Observaciones.");
      }
      return this.errorsd;
    },
    cerrar() {
      const cond = this.validaCierre();
      if (cond.length) {
        return;
      }
      axios
        .put("/api/arqueo/cerrar", {
          id: this.arqueo_id,
          mon_cierre: this.mon_cierre,
          tot_credito: this.tot_credito,
          tot_egreso: this.tot_egreso,
          tot_efectivo: this.tot_efectivo,
          observaciones: this.observaciones,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El arqueo se cerró con éxito.", "success");
          this.$router.push("/arqueos");
        })
        .catch((err) => {
          Swal.fire("Error!", "No se pudo cerrar el arqueo. " + err, "error");
        });
    },
    listarGastos(id) {
      axios.get("/api/egresos?arqueo=" + id).then((resp) => {
        this.arrayGastos = resp.data.egresos;
        this.tot_egreso = resp.data.suma;
      });
    },
    validaGasto() {
      this.errors = [];
      if (!this.descripcion) {
        this.errors.push("Ingrese descripción.");
      }
      if (!this.valor) {
        this.errors.push("Ingrese valor.");
      }
      if (this.user_id == "0") {
        this.errors.push("Seleccione Beneficiario.");
      }
      return this.errors;
    },
    guardar() {
      const condiciones = this.validaGasto();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/egreso/guardar", {
          arqueo_id: this.arqueo_id,
          user_id: this.user_id,
          descripcion: this.descripcion,
          valor: this.valor,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.cerrarGasto();
          this.listarGastos(this.arqueo_id);
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    eliminar(cod) {
      axios
        .get("/api/egreso/eliminar/" + cod)
        .then((resp) => {
          Swal.fire("Bien!", "El registro se eliminó con éxito.", "success");
          this.listarGastos(this.arqueo_id);
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo eliminar el registro. " + err,
            "error"
          );
        });
    },
    abrirGasto() {
      $("#gasto").modal("show");
      this.selectBeneficiario();
    },
    cerrarGasto() {
      $("#gasto").modal("hide");
      this.descripcion = "";
      this.valor = 0;
      this.user_id = 0;
    },
    selectBeneficiario() {
      axios.get("/api/users").then((resp) => {
        this.arrayBeneficiarios = resp.data;
      });
    },
  },
  created() {
    this.getDatos();
  },
};
</script>