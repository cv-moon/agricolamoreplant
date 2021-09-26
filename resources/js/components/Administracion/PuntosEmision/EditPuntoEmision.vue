<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Editar Punto de Emisión: {{ nombre }}
      </h3>
      <div class="card-tools">
        <router-link to="/puntos-emision" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <form>
        <b class="text-primary">Establecimiento</b>
        <hr class="mt-0" />
        <div class="form-group row">
          <div class="col-sm-6">
            <select v-model="establecimiento_id" class="form-control" disabled>
              <option value="0" disabled>Seleccione...</option>
              <option
                v-for="establecimiento in arrayEstablecimientos"
                :key="establecimiento.id"
                :value="establecimiento.id"
                v-text="
                  establecimiento.numero +
                  '-' +
                  establecimiento.nom_comercial +
                  '-' +
                  establecimiento.nom_referencia
                "
              ></option>
            </select>
          </div>
        </div>
        <b class="text-primary">Datos Generales</b>
        <hr class="mt-0" />
        <div class="form-group row">
          <label for="codigo" class="col-sm-1 col-form-label">No.:</label>
          <div class="col-sm-2">
            <input
              type="number"
              class="form-control"
              placeholder="0"
              min="0"
              max="999"
              disabled
              v-model="codigo"
            />
          </div>
          <label for="nombre" class="col-sm-1 col-form-label">Nombre:</label>
          <div class="col-sm-3">
            <input
              type="text"
              class="form-control"
              placeholder="Nombre."
              maxlength="300"
              v-model="nombre"
            />
          </div>
          <label for="user_id" class="col-sm-2 col-form-label"
            >Responsable:</label
          >
          <div class="col-sm-3">
            <select v-model="user_id" class="form-control">
              <option value="0" disabled>Seleccione...</option>
              <option
                v-for="user in arrayUsers"
                :key="user.id"
                :value="user.id"
                v-text="user.usuario"
              ></option>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Documentos</th>
                  <th>Establecimiento</th>
                  <th>Pto. Emisión</th>
                  <th>Secuencial</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Factura</td>
                  <td>{{ numEstablecimiento }}</td>
                  <td>{{ numPunto }}</td>
                  <td>
                    {{ sec_factura }}
                  </td>
                </tr>
                <tr>
                  <td>Liquidación de Compras</td>
                  <td>{{ numEstablecimiento }}</td>
                  <td>{{ numPunto }}</td>
                  <td>
                    {{ sec_liq_compras }}
                  </td>
                </tr>
                <tr>
                  <td>Nota de Crédito</td>
                  <td>{{ numEstablecimiento }}</td>
                  <td>{{ numPunto }}</td>
                  <td>
                    {{ sec_not_credito }}
                  </td>
                </tr>
                <tr>
                  <td>Nota de Débito</td>
                  <td>{{ numEstablecimiento }}</td>
                  <td>{{ numPunto }}</td>
                  <td>
                    {{ sec_not_debito }}
                  </td>
                </tr>
                <tr>
                  <td>Guía de Remisión</td>
                  <td>{{ numEstablecimiento }}</td>
                  <td>{{ numPunto }}</td>
                  <td>
                    {{ sec_gui_remision }}
                  </td>
                </tr>
                <tr>
                  <td>Retención</td>
                  <td>{{ numEstablecimiento }}</td>
                  <td>{{ numPunto }}</td>
                  <td>
                    {{ sec_retencion }}
                  </td>
                </tr>
                <tr>
                  <td>Recibo de Cobro</td>
                  <td>{{ numEstablecimiento }}</td>
                  <td>{{ numPunto }}</td>
                  <td>
                    {{ sec_recibo }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="errors.length" class="alert alert-danger">
          <div>
            <div v-for="error in errors" :key="error">
              {{ error }}
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="card-footer">
      <router-link to="/puntos-emision" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-primary" @click="editar">
        Actualizar
      </button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      punto_id: 0,
      establecimiento_id: 0,
      user_id: 0,
      codigo: 0,
      nombre: "",
      sec_factura: 0,
      sec_liq_compras: 0,
      sec_not_credito: 0,
      sec_not_debito: 0,
      sec_gui_remision: 0,
      sec_retencion: 0,
      sec_recibo: 0,
      numero: 0,
      arrayEstablecimientos: [],
      arrayUsers: [],
      errors: [],
    };
  },
  computed: {
    numEstablecimiento() {
      let est = this.numero.toString();
      return est.padStart(3, "0");
    },
    numPunto() {
      let num = this.codigo.toString();
      return num.padStart(3, "0");
    },
  },
  methods: {
    detalle() {
      axios
        .get("/api/punto/detalle", {
          params: {
            id: this.$route.params.id,
          },
        })
        .then((resp) => {
          this.punto_id = resp.data["id"];
          this.establecimiento_id = resp.data["establecimiento_id"];
          this.user_id = resp.data["user_id"];
          this.nombre = resp.data["nombre"];
          this.codigo = resp.data["codigo"];
          this.sec_factura = resp.data["sec_factura"];
          this.sec_liq_compras = resp.data["sec_liq_compras"];
          this.sec_not_credito = resp.data["sec_not_credito"];
          this.sec_not_debito = resp.data["sec_not_debito"];
          this.sec_gui_remision = resp.data["sec_gui_remision"];
          this.sec_retencion = resp.data["sec_retencion"];
          this.numero = resp.data["numero"];
        });
    },
    validaCampos() {
      this.errors = [];
      if (this.establecimiento_id == "0") {
        this.errors.push("Seleccione Establecimiento.");
      }
      if (this.user_id == "0") {
        this.errors.push("Seleccione Responsable.");
      }
      if (!this.codigo) {
        this.errors.push("Ingrese Número");
      } else {
        if (this.codigo > 999) {
          this.errors.push("El número no puede ser mayor a 999");
        }
      }
      if (!this.nombre) {
        this.errors.push("Ingrese Nombre");
      }
      return this.errors;
    },
    editar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .put("/api/punto/editar", {
          id: this.punto_id,
          establecimiento_id: this.establecimiento_id,
          user_id: this.user_id,
          codigo: this.codigo,
          nombre: this.nombre,
          sec_factura: this.sec_factura,
          sec_liq_compras: this.sec_liq_compras,
          sec_not_credito: this.sec_not_credito,
          sec_not_debito: this.sec_not_debito,
          sec_gui_remision: this.sec_gui_remision,
          sec_retencion: this.sec_retencion,
          sec_recibo: this.sec_recibo,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/puntos-emision");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    selectEstablecimientos() {
      axios.get("/api/punto/establecimientos").then((resp) => {
        this.arrayEstablecimientos = resp.data;
      });
    },
    selectResponsable() {
      axios.get("/api/users").then((resp) => {
        this.arrayUsers = resp.data;
      });
    },
  },
  mounted() {
    this.detalle();
    this.selectResponsable();
    this.selectEstablecimientos();
  },
};
</script>