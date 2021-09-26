<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Cuentas por Pagar
      </h3>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="proveedor" class="col-sm-2 col-form-label"
          >Proveedor:</label
        >
        <div class="col-sm-4">
          <select v-model="proveedor" class="form-control">
            <option value="0">Seleccione...</option>
            <option
              v-for="proveedor in arrayProveedores"
              :key="proveedor.id"
              :value="proveedor.id"
              v-text="proveedor.nombre"
            ></option>
          </select>
        </div>
        <label for="estado" class="col-sm-1 col-form-label">Estado:</label>
        <div class="col-sm-2">
          <select v-model="estado" class="form-control">
            <option value="0">Seleccione...</option>
            <option value="A">ANULADO</option>
            <option value="C">PAGADO</option>
            <option value="P">PENDIENTE</option>
          </select>
        </div>
        <button
          type="button"
          title="Buscar"
          class="btn btn-success"
          @click="listar(proveedor, estado)"
        >
          <i class="fas fa-search"></i>
        </button>
      </div>
      <table
        id="tabla"
        class="table table-bordered table-striped dt-responsive nowrap"
      >
        <thead>
          <tr>
            <th>Acción</th>
            <th>Proveedor</th>
            <th>Saldo</th>
            <th>Abonos</th>
            <th>Días</th>
            <th>Fec. Límite</th>
            <th>Estado</th>
            <th>Observaciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="deuda in arrayDeudas"
            :key="deuda.id"
            :class="
              deuda.estado == 'P' && fec_actual >= deuda.fec_limite
                ? 'table-danger'
                : ''
            "
          >
            <td>
              <template v-if="deuda.estado != 'A'">
                <router-link
                  type="button"
                  title="Detalle"
                  :to="'/deudas/detalle/' + deuda.id"
                  class="btn btn-info btn-xs"
                >
                  <i class="fas fa-clipboard-list"></i>
                </router-link>
                <template v-if="deuda.estado == 'P'">
                  <button
                    type="button"
                    title="Abonos"
                    @click="abrirModal(deuda)"
                    class="btn btn-success btn-xs"
                  >
                    <i class="fas fa-donate"></i>
                  </button>
                </template>
              </template>
            </td>
            <td v-text="deuda.proveedor"></td>
            <td align="right" v-text="deuda.saldo"></td>
            <td align="right" v-text="deuda.abonos"></td>
            <td align="right" v-text="deuda.dias_credito"></td>
            <td v-text="deuda.fec_limite"></td>
            <td>
              <div v-if="deuda.estado == 'A'">
                <span class="badge badge-danger">Anulado</span>
              </div>
              <div v-else-if="deuda.estado == 'P'">
                <span class="badge badge-warning">Pendiente</span>
              </div>
              <div v-else-if="deuda.estado == 'C'">
                <span class="badge badge-success">Pagado</span>
              </div>
            </td>
            <td v-text="deuda.observaciones"></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Start Modal -->
    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Agregar Abono</h4>
            <button
              type="button"
              class="close"
              aria-label="Close"
              @click="cerrarModal"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="form-horizontal">
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-sm-6">
                    <label for="val_abono" class="col-form-label">Abono:</label>
                    <input
                      type="number"
                      class="form-control"
                      min="0"
                      v-model="val_abono"
                    />
                    <label for="fec_abono" class="col-form-label">Fecha:</label>
                    <input
                      type="date"
                      class="form-control"
                      v-model="fec_abono"
                    />
                    <label for="observaciones" class="col-form-label"
                      >Observaciones:</label
                    >
                    <textarea
                      class="form-control"
                      placeholder="Observaciones"
                      maxlength="150"
                      rows="3"
                      v-model="obs_abono"
                    ></textarea>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group row">
                      <h4 class="col-sm-12 text-center">Cálculo</h4>
                    </div>
                    <div class="form-group row">
                      <label for="saldo" class="col-sm-6 col-form-label"
                        >Saldo Anterior:</label
                      >
                      <h5 class="col-sm-6 text-right text-orange">
                        {{ saldo.toFixed(2) }}
                      </h5>
                    </div>
                    <div class="form-group row">
                      <label for="abono" class="col-sm-6 col-form-label"
                        >Abono</label
                      >
                      <h5 class="col-sm-6 text-right text-green">
                        {{ val_abono }}
                      </h5>
                    </div>
                    <div class="form-group row">
                      <label for="saldo_pendiente" class="col-sm-6 col-form-label"
                        >Saldo Pendiente:</label
                      >
                      <h5 class="col-sm-6 text-right text-red">
                        {{ saldoPendiente.toFixed(2) }}
                      </h5>
                    </div>
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
            <button type="button" class="btn btn-default" @click="cerrarModal">
              Close
            </button>
            <button type="button" class="btn btn-success" @click="guardar">
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
</template>
<script>
export default {
  data() {
    return {
      proveedor: 0,
      estado: "0",
      saldo: 0,
      fec_actual: "",
      deuda_id: 0,
      val_abono: 0,
      fec_abono: "",
      obs_abono: "",
      arrayDeudas: [],
      arrayProveedores: [],
      errors: [],
    };
  },
  computed: {
    saldoPendiente() {
      let resultado = 0.0;
      resultado = parseFloat(this.saldo) - parseFloat(this.val_abono);
      return resultado;
    },
  },
  methods: {
    myTable() {
      this.$nextTick(() => {
        $("#tabla").DataTable();
      });
    },
    listar(proveedor, estado) {
      axios
        .get("/api/deudas?proveedor=" + proveedor + "&estado=" + estado)
        .then((resp) => {
          this.arrayDeudas = resp.data;
          $("#tabla").DataTable().destroy();
          this.myTable();
        });
    },
    selectProveedores() {
      axios.get("/api/proveedores").then((resp) => {
        this.arrayProveedores = resp.data;
      });
    },
    hoy() {
      const f = new Date();
      let day = f.getDate();
      let month = f.getMonth() + 1;
      let year = f.getFullYear();
      if (month < 10) month = "0" + month;
      if (day < 10) day = "0" + day;
      this.fec_actual = year + "-" + month + "-" + day;
    },
    abrirModal(data = []) {
      $("#modal").modal("show");
      this.saldo = data.saldo - data.abonos;
      this.deuda_id = data.id;
    },
    cerrarModal() {
      $("#modal").modal("hide");
      this.deuda_id = 0;
      this.val_abono = 0;
      this.fec_abono = "";
      this.obs_abono = "";
    },
    validaCampos() {
      this.errors = [];
      if (!this.val_abono) {
        this.errors.push("Ingrese valor de abono.");
      }
      if (this.val_abono > this.saldo) {
        this.errors.push(
          "El valor del abono no puede ser mayor al saldo actual."
        );
      }
      if (!this.fec_abono) {
        this.errors.push("Ingrese fecha de abono.");
      }
      if (!this.obs_abono) {
        this.errors.push("Ingrese observaciones.");
      }
      return this.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/deuda/abonar", {
          deuda_id: this.deuda_id,
          val_abono: this.val_abono,
          fec_abono: this.fec_abono,
          obs_abono: this.obs_abono,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.cerrarModal();
          this.listar(this.proveedor, this.estado);
        })
        .catch((err) => {
          Swal.fire("err!", "No se pudo realizar el registro. " + err, "error");
        });
    },
  },
  mounted() {
    this.listar(this.proveedor, this.estado);
    this.selectProveedores();
    this.hoy();
  },
};
</script>