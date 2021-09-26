<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Generar Rol de Pago
      </h3>
      <div class="card-tools">
        <router-link to="/roles-pago" class="btn btn-secondary btn-xs">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <div class="col-sm-12">
            <label for="mes" class="col-sm-12 col-form-label">Mes:</label>
            <select class="form-control" v-model="mes_id" disabled>
              <option value="0" disabled>Seleccione</option>
              <option
                v-for="mes in arrayMeses"
                :key="mes.id"
                :value="mes.id"
                v-text="mes.nombre"
              ></option>
            </select>
          </div>
          <div class="col-sm-12">
            <label for="empleado" class="col-sm-12 col-form-label"
              >Empleado:</label
            >
            <div class="input-group">
              <select class="form-control" v-model="user_id" disabled>
                <option value="0" disabled>Seleccione</option>
                <option
                  v-for="beneficiario in arrayEmpleados"
                  :key="beneficiario.id"
                  :value="beneficiario.id"
                  v-text="beneficiario.apellidos + ' ' + beneficiario.nombres"
                ></option>
              </select>
              <button
                title="Actualizar"
                class="btn btn-primary btn-sm"
                @click="refresh"
              >
                <i class="fas fa-sync-alt"></i>
              </button>
            </div>
          </div>
          <div class="col-sm-12">
            <label for="por_venta" class="col-sm-12 col-form-label"
              >Porcentaje Utilidad:</label
            >
            <select v-model="por_venta" class="form-control">
              <option value="">Seleccione...</option>
              <option value="1">1 %</option>
              <option value="2">2 %</option>
              <option value="3">3 %</option>
              <option value="4">4 %</option>
              <option value="5">5 %</option>
              <option value="6">6 %</option>
              <option value="7">7 %</option>
              <option value="8">8 %</option>
              <option value="9">9 %</option>
              <option value="10">10 %</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group row">
            <div class="col-sm-6 text-right">
              <h4><b>Salario</b></h4>
            </div>
            <div class="col-sm-6 text-right">
              <h4>$ {{ salario }}</h4>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6 text-right">
              <h4><b>Venta Mensual</b></h4>
            </div>
            <div class="col-sm-6 text-right">
              <h4>$ {{ ven_mensual.toFixed(2) }}</h4>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6 text-right">
              <h4><b>Utilidad/Venta</b></h4>
            </div>
            <div class="col-sm-6 text-right">
              <h4>$ {{ calcularPorcentaje.toFixed(2) }}</h4>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-6 text-right">
              <h4><b>Total a Recibir</b></h4>
            </div>
            <div class="col-sm-6 text-right text-danger">
              <h4>$ {{ (tot_recibir = calcularPago.toFixed(2)) }}</h4>
            </div>
          </div>
        </div>
      </div>
      <div v-if="errors.length" class="alert alert-danger">
        <div>
          <div v-for="error in errors" :key="error">
            {{ error }}
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <router-link to="/roles-pago" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-warning" @click="editar">
        Editar
      </button>
      <button type="button" class="btn btn-success" @click="abrirModal">
        Generar y Pagar
      </button>
    </div>
    <!-- Start Pago Modal -->
    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Pagar Rol</h4>
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
            <div class="form-group row">
              <label for="forma" class="col-sm-12 col-form-label"
                >Forma de Pago:</label
              >
              <select v-model="pago.for_pago" class="form-control">
                <option value="0">Seleccione...</option>
                <option value="E">EFECTIVO</option>
                <option value="T">TRANSFERENCIA</option>
                <option value="C">CHEQUE</option>
              </select>
            </div>
            <div class="form-group row">
              <label for="valor" class="col-sm-12 col-form-label"
                >Valor A Pagar:</label
              >
              <input
                type="number"
                class="form-control"
                v-model="pago.val_pago"
                min="0"
              />
            </div>
            <div class="form-group row">
              <label for="nombre" class="col-sm-12 col-form-label"
                >Observaciones:</label
              >
              <textarea
                class="form-control"
                rows="3"
                maxlength="300"
                v-model="pago.observaciones"
              ></textarea>
            </div>

            <div v-if="pago.errors.length" class="alert alert-danger">
              <div>
                <div v-for="error in pago.errors" :key="error">
                  {{ error }}
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" @click="cerrarModal">
              Close
            </button>
            <button type="button" class="btn btn-success" @click="generar">
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
      rol_id: 0,
      mes_id: 0,
      user_id: 0,
      ven_mensual: 0,
      por_venta: "",
      tot_recibir: 0,
      salario: parseFloat(0).toFixed(2),
      pago: {
        for_pago: 0,
        val_pago: 0,
        observaciones: "",
        errors: [],
      },
      arrayMeses: [],
      arrayEmpleados: [],
      errors: [],
    };
  },
  computed: {
    calcularPago() {
      var resultado = 0.0;
      resultado =
        parseFloat(this.salario) + parseFloat(this.calcularPorcentaje);
      return resultado;
    },
    calcularPorcentaje() {
      var resultado = 0.0;
      resultado =
        parseFloat(this.por_venta / 100) * parseFloat(this.ven_mensual);
      return resultado;
    },
  },
  methods: {
    detalle() {
      axios
        .get("/api/roles_pago/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.rol_id = resp.data.id;
          this.mes_id = resp.data.mes_id;
          this.user_id = resp.data.user_id;
          this.por_venta = resp.data.por_venta;
          this.ven_mensual = parseFloat(resp.data.ven_mensual);
          this.salario = parseFloat(resp.data.salario).toFixed(2);
        });
    },
    validaCampos() {
      this.errors = [];
      if (this.user_id == 0) {
        this.errors.push("Seleccione Empleado.");
      }
      if (this.mes_id == 0) {
        this.errors.push("Seleccione Mes.");
      }
      if (this.por_venta == "") {
        this.errors.push("Seleccione Porcentaje.");
      }

      return this.errors;
    },
    editar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/roles_pago/editar", {
          id: this.rol_id,
          mes_id: this.mes_id,
          user_id: this.user_id,
          ven_mensual: this.ven_mensual,
          por_venta: this.por_venta,
          tot_recibir: this.tot_recibir,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/roles-pago");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },

    // Métodos para el pago
    validaCamposPago() {
      this.pago.errors = [];
      if (this.pago.for_pago == 0) {
        this.pago.errors.push("Seleccione Forma de Pago.");
      }
      if (this.pago.val_pago == 0 || !this.pago.val_pago) {
        this.pago.errors.push("Revise Valor a Pagar.");
      }
      if (!this.pago.observaciones) {
        this.pago.errors.push("Ingrese Observaciones.");
      }

      return this.pago.errors;
    },
    generar() {
      const condiciones = this.validaCamposPago();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/roles_pago/pagar", {
          id: this.rol_id,
          mes_id: this.mes_id,
          user_id: this.user_id,
          ven_mensual: this.ven_mensual,
          por_venta: this.por_venta,
          tot_recibir: this.tot_recibir,
          for_pago: this.pago.for_pago,
          val_pago: this.pago.val_pago,
          observaciones: this.pago.observaciones,
        })
        .then((resp) => {
          this.cerrarModal();
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/roles-pago");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    abrirModal() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      $("#modal").modal("show");
      this.pago.val_pago = this.tot_recibir;
    },
    cerrarModal() {
      $("#modal").modal("hide");
    },
    selectMes() {
      axios.get("/api/meses").then((resp) => {
        this.arrayMeses = resp.data;
      });
    },
    selectEmpleados() {
      axios.get("/api/empleados").then((resp) => {
        this.arrayEmpleados = resp.data;
      });
    },
    refresh() {
      axios
        .get("/api/roles_pago/datos", {
          params: { mes: this.mes_id, usuario: this.user_id },
        })
        .then((resp) => {
          if (resp.data != "") {
            this.salario = resp.data.empleado.salario;
            this.ven_mensual = resp.data.ventas;
          }
        });
    },
  },
  created() {
    this.detalle();
  },
  mounted() {
    this.selectMes();
    this.selectEmpleados();
  },
};
</script>