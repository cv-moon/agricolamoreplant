<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Cuentas por Cobrar
      </h3>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="cliente" class="col-2 col-form-label">Cliente:</label>
        <div class="col-4">
          <select v-model="cliente" class="form-control">
            <option value="0">Seleccione...</option>
            <option
              v-for="cliente in arrayClientes"
              :key="cliente.id"
              :value="cliente.id"
              v-text="cliente.nombre"
            ></option>
          </select>
        </div>
        <label for="estado" class="col-1 col-form-label">Estado:</label>
        <div class="col-2">
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
          @click="listar(cliente, estado)"
        >
          <i class="fas fa-search"></i>
        </button>
      </div>
      <table
        id="creditos"
        class="table table-bordered table-striped dt-responsive nowrap"
      >
        <thead>
          <tr>
            <th>Acción</th>
            <th>Cliente</th>
            <th>Sucursal</th>
            <th>Comprobante</th>
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
            v-for="credito in arrayCreditos"
            :key="credito.id"
            :class="
              credito.estado == 'P' && fec_actual >= credito.fec_limite
                ? 'table-danger'
                : ''
            "
          >
            <td>
              <template v-if="credito.estado != 'A'">
                <router-link
                  type="button"
                  title="Detalle"
                  :to="'/creditos/detalle/' + credito.id"
                  class="btn btn-info btn-xs"
                >
                  <i class="fas fa-clipboard-list"></i>
                </router-link>
                <template v-if="credito.estado == 'P'">
                  <button
                    type="button"
                    title="Abonos"
                    @click="abrirModal(credito)"
                    class="btn btn-success btn-xs"
                  >
                    <i class="fas fa-donate"></i>
                  </button>
                </template>
              </template>
            </td>
            <td v-text="credito.cliente"></td>
            <td v-text="credito.nom_referencia"></td>
            <td
              v-text="
                `${credito.numero.toString().padStart(3, 0)}-${credito.codigo
                  .toString()
                  .padStart(3, 0)}-${credito.num_secuencial
                  .toString()
                  .padStart(9, 0)}`
              "
            ></td>
            <td align="right" v-text="credito.saldo"></td>
            <td align="right" v-text="credito.abonos"></td>
            <td align="right" v-text="credito.dias_credito"></td>
            <td v-text="credito.fec_limite"></td>
            <td>
              <div v-if="credito.estado == 'A'">
                <span class="badge badge-danger">Anulado</span>
              </div>
              <div v-else-if="credito.estado == 'P'">
                <span class="badge badge-warning">Pendiente</span>
              </div>
              <div v-else-if="credito.estado == 'C'">
                <span class="badge badge-success">Pagado</span>
              </div>
            </td>
            <td v-text="credito.observaciones"></td>
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
                  <div class="col-6">
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
                      v-model="observaciones"
                    ></textarea>
                  </div>
                  <div class="col-6">
                    <div class="form-group row">
                      <h4 class="col-12 text-center">Cálculo</h4>
                    </div>
                    <div class="form-group row">
                      <label for="observaciones" class="col-6 col-form-label"
                        >Saldo Anterior:</label
                      >
                      <h5 class="col-6 text-right text-orange">
                        {{ saldo.toFixed(2) }}
                      </h5>
                    </div>
                    <div class="form-group row">
                      <label for="observaciones" class="col-6 col-form-label"
                        >Abono</label
                      >
                      <h5 class="col-6 text-right text-green">
                        {{ val_abono }}
                      </h5>
                    </div>
                    <div class="form-group row">
                      <label for="observaciones" class="col-6 col-form-label"
                        >Saldo Pendiente:</label
                      >
                      <h5 class="col-6 text-right text-red">
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
      cliente: 0,
      estado: "0",
      saldo: 0,
      fec_actual: "",
      credito_id: 0,
      val_abono: 0,
      fec_abono: "",
      observaciones: "",
      arrayCreditos: [],
      arrayClientes: [],
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
        $("#creditos").DataTable();
      });
    },
    listar(cliente, estado) {
      axios
        .get("/api/creditos?cliente=" + cliente + "&estado=" + estado)
        .then((resp) => {
          this.arrayCreditos = resp.data;
          $("#creditos").DataTable().destroy();
          this.myTable();
        });
    },
    selectClientes() {
      axios.get("/api/clientes/listar").then((resp) => {
        this.arrayClientes = resp.data;
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
      this.credito_id = data.id;
    },
    cerrarModal() {
      $("#modal").modal("hide");
      this.credito_id = 0;
      this.val_abono = 0;
      this.fec_abono = "";
      this.observaciones = "";
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
      if (!this.observaciones) {
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
        .post("/api/credito/abonar", {
          credito_id: this.credito_id,
          val_abono: this.val_abono,
          fec_abono: this.fec_abono,
          observaciones: this.observaciones,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.cerrarModal();
          this.listar(this.cliente, this.estado);
        })
        .catch((err) => {
          Swal.fire("err!", "No se pudo realizar el registro. " + err, "error");
        });
    },
  },
  mounted() {
    this.listar(this.cliente, this.estado);
    this.selectClientes();
    this.hoy();
  },
};
</script>