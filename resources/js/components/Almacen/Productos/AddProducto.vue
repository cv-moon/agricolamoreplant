<template>
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h3 class="card-title mt-2">
        <i class="fas fa-align-justify"></i>
        Agregar Producto
      </h3>
      <div class="card-tools">
        <router-link to="/productos" class="btn btn-secondary btn-sm">
          <i class="fas fa-arrow-left"></i> Regresar
        </router-link>
      </div>
    </div>
    <div class="card-body">
      <b class="text-primary">Datos Generales</b>
      <hr class="mt-0" />
      <div class="form-group row">
        <div class="col-sm-4">
          <label for="categoria_id" class="col-form-label">Categoría:</label>
          <select
            v-model="categoria_id"
            class="form-control"
            @change="getCodigo"
          >
            <option value="0" disabled>Seleccione...</option>
            <option
              v-for="categoria in arrayCategorias"
              :key="categoria.id"
              :value="categoria.id"
              v-text="categoria.nombre"
            ></option>
          </select>
        </div>
        <div class="col-sm-8">
          <label for="nombre" class="col-form-label">Nombre:</label>
          <input
            type="text"
            class="form-control col-sm-9"
            placeholder="Nombre."
            maxlength="300"
            v-model="nombre"
          />
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <label for="composicion" class="col-form-label">Composición:</label>
          <input
            type="text"
            class="form-control"
            placeholder="Composición."
            maxlength="300"
            v-model="composicion"
          />
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-4">
          <label for="cod_principal" class="col-form-label"
            >Cod. Principal:</label
          >
          <input
            type="text"
            class="form-control"
            placeholder="Código Principal."
            maxlength="100"
            v-model="cod_principal"
          />
        </div>
        <div class="col-sm-4">
          <label for="cod_auxiliar" class="col-form-label"
            >Cod. Auxiliar:</label
          >
          <input
            type="text"
            class="form-control"
            placeholder="Código Auxiliar."
            maxlength="100"
            v-model="cod_auxiliar"
          />
        </div>
        <div class="col-sm-4">
          <label for="categoria_id" class="col-form-label"
            >U. Presentación:</label
          >
          <select v-model="unidad_id" class="form-control">
            <option value="0" disabled>Seleccione...</option>
            <option
              v-for="unidad in arrayUnidades"
              :key="unidad.id"
              :value="unidad.id"
              v-text="unidad.nombre"
            ></option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <div class="col-md-4">
          <label for="pre_venta" class="col-form-label">P.V.P:</label>
          <input
            type="number"
            class="form-control"
            placeholder="0.000"
            min="0"
            v-model="pre_venta"
          />
        </div>
        <div class="col-md-4">
          <label for="por_descuento" class="col-form-label"
            >Descuento (%):</label
          >
          <input
            type="number"
            class="form-control"
            placeholder="0.00"
            min="0"
            v-model="por_descuento"
          />
        </div>
        <div class="col-md-4">
          <label for="tarifa_id" class="col-form-label">Impuesto:</label>
          <select v-model="tarifa_id" class="form-control">
            <option value="0" disabled>Seleccione...</option>
            <option
              v-for="impuesto in arrayImpuestos"
              :key="impuesto.id"
              :value="impuesto.id"
              v-text="impuesto.tarifa"
            ></option>
          </select>
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
      <router-link to="/productos" class="btn btn-danger">
        Cancelar
      </router-link>
      <button type="button" class="btn btn-success" @click="guardar">
        Guardar
      </button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      categoria_id: 0,
      unidad_id: 0,
      tarifa_id: 0,
      nombre: "",
      cod_principal: "",
      cod_auxiliar: "",
      composicion: "",
      pre_venta: 0,
      por_descuento: 0,
      arrayCategorias: [],
      arrayUnidades: [],
      arrayImpuestos: [],
      errors: [],
    };
  },
  methods: {
    validaCampos() {
      this.errors = [];
      if (!this.nombre) {
        this.errors.push("Ingrese Nombre.");
      } else {
        axios
          .get("/api/producto/valida/nombre", {
            params: {
              q: this.nombre,
            },
          })
          .then((resp) => {
            if (resp.data.producto == "Existe") {
              this.errors.push(
                "Ya existe un registro con el nombre ingresado."
              );
            }
          });
      }
      if (this.unidad_id == 0) {
        this.errors.push("Seleccione Unidad de Presentación");
      }
      if (this.categoria_id == 0) {
        this.errors.push("Seleccione Categoria.");
      }
      if (!this.cod_principal) {
        this.errors.push("Ingrese Código Principal.");
      }
      if (this.tarifa_id == 0) {
        this.errors.push("Seleccione Impuesto.");
      }
      if (!this.pre_venta) {
        this.errors.push("Ingrese P.V.P.");
      }
      if (!this.por_descuento) {
        this.errors.push("Ingrese (%) Descuento.");
      }
      return this.errors;
    },
    guardar() {
      const condiciones = this.validaCampos();
      if (condiciones.length) {
        return;
      }
      axios
        .post("/api/producto/guardar", {
          categoria_id: this.categoria_id,
          unidad_id: this.unidad_id,
          tarifa_id: this.tarifa_id,
          nombre: this.nombre,
          composicion: this.composicion,
          cod_principal: this.cod_principal,
          cod_auxiliar: this.cod_auxiliar,
          pre_venta: this.pre_venta,
          por_descuento: this.por_descuento,
        })
        .then((resp) => {
          Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
          this.$router.push("/productos");
        })
        .catch((err) => {
          Swal.fire(
            "Error!",
            "No se pudo realizar el registro. " + err,
            "error"
          );
        });
    },
    selectCategoria() {
      axios.get("/api/categorias").then((resp) => {
        this.arrayCategorias = resp.data;
      });
    },
    selectImpuesto() {
      axios.get("/api/tarifas-iva").then((resp) => {
        this.arrayImpuestos = resp.data;
      });
    },
    selectUnidad() {
      axios.get("/api/unidades").then((resp) => {
        this.arrayUnidades = resp.data;
      });
    },
    getCodigo() {
      let contador = "";
      if (this.categoria_id !== 0) {
        axios
          .get("/api/productos/conteo", {
            params: {
              q: this.categoria_id,
            },
          })
          .then((resp) => {
            let obtener = this.arrayCategorias.filter(
              (e) => e.id == this.categoria_id
            );
            this.cod_principal = this.cod_auxiliar = `${obtener[0].nombre
              .substring(0, 3)
              .toUpperCase()}${resp.data}`;
          });
      }
    },
  },
  mounted() {
    this.selectCategoria();
    this.selectImpuesto();
    this.selectUnidad();
  },
};
</script>