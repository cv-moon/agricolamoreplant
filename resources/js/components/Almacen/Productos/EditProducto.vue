<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Editar Producto: {{ nombre }}
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
                    <label for="categoria_id" class="col-form-label"
                        >Categoría:</label
                    >
                    <select
                        v-model="categoria_id"
                        class="form-control"
                        disabled
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
                        readonly
                    />
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label for="composicion" class="col-form-label"
                        >Composición:</label
                    >
                    <textarea
                        class="form-control"
                        placeholder="Composición"
                        maxlength="300"
                        cols="12"
                        rows="2"
                        v-model="composicion"
                        readonly
                    ></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label for="pre_venta" class="col-form-label"
                        >Precio Compra:</label
                    >
                    <input
                        type="number"
                        class="form-control"
                        placeholder="0.000"
                        min="0"
                        v-model="pre_compra"
                        readonly
                    />
                </div>
                <div class="col-md-3">
                    <label for="por_descuento" class="col-form-label"
                        >Descuento (%):</label
                    >
                    <input
                        type="number"
                        class="form-control"
                        placeholder="0.00"
                        min="0"
                        v-model="por_descuento"
                        readonly
                    />
                </div>
                <div class="col-md-3">
                    <label for="por_descuento" class="col-form-label"
                        >Margen Utilidad:</label
                    >
                    <input
                        type="number"
                        class="form-control"
                        placeholder="0.00"
                        min="0"
                        v-model="mar_utilidad"
                        readonly
                    />
                </div>
                <div class="col-md-3">
                    <label for="tar_agregado_id" class="col-form-label"
                        >Impuesto:</label
                    >
                    <select
                        v-model="tar_agregado_id"
                        class="form-control"
                        disabled
                    >
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
            <b class="text-primary">Presentaciones del producto</b>
            <hr class="mt-0" />
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="cod_principal" class="col-form-label"
                        >Cod. Principal:</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Código Principal."
                        maxlength="15"
                        v-model="cod_principal"
                    />
                </div>
                <div class="col-sm-3">
                    <label for="cod_auxiliar" class="col-form-label"
                        >Cod. Auxiliar:</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Código Auxiliar."
                        maxlength="15"
                        v-model="cod_auxiliar"
                    />
                </div>
                <div class="col-sm-3">
                    <label for="cod_auxiliar" class="col-form-label"
                        >Presentación:</label
                    >
                    <div class="input-group">
                        <input
                            type="number"
                            class="form-control"
                            min="1"
                            v-model="presentacion"
                            disabled
                        />
                        <div class="input-group-append">
                            <select
                                v-model="unidad_id"
                                class="form-control"
                                @change="getCodigo"
                            >
                                <option value="0" disabled>Sel.</option>
                                <option
                                    v-for="unidad in arrayUnidades"
                                    :key="unidad.id"
                                    :value="unidad.id"
                                    v-text="unidad.sigla"
                                ></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="pre_venta" class="col-form-label"
                        >P.V.P.:</label
                    >
                    <input
                        type="number"
                        class="form-control"
                        placeholder="0"
                        min="0"
                        v-model="pre_venta"
                    />
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
            presentacion_id: 0,
            categoria_id: 0,
            tar_agregado_id: 0,
            unidad_id: 0,
            nombre: "",
            composicion: "",
            pre_compra: 0,
            por_descuento: 0,
            mar_utilidad: 0,
            cod_principal: "",
            cod_auxiliar: "",
            presentacion: "",
            pre_venta: 0,
            arrayCategorias: [],
            arrayUnidades: [],
            arrayImpuestos: [],
            errors: []
        };
    },
    methods: {
        detalle() {
            axios
                .get("/api/producto/detalle", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(resp => {
                    this.presentacion_id = resp.data["id"];
                    this.categoria_id = resp.data["categoria_id"];
                    this.unidad_id = resp.data["unidad_id"];
                    this.tar_agregado_id = resp.data["tar_agregado_id"];
                    this.nombre = resp.data["nombre"];
                    this.cod_principal = resp.data["cod_principal"];
                    this.cod_auxiliar = resp.data["cod_auxiliar"];
                    this.composicion = resp.data["composicion"];
                    this.pre_venta = resp.data["pre_venta"];
                    this.pre_compra = resp.data["pre_compra"];
                    this.mar_utilidad = resp.data["mar_utilidad"];
                    this.por_descuento = resp.data["por_descuento"];
                    this.presentacion = resp.data["presentacion"];
                });
        },
        validaCampos() {
            this.errors = [];
            if (!this.nombre) {
                this.errors.push("Ingrese Nombre.");
            }
            if (this.categoria_id == 0) {
                this.errors.push("Seleccione Categoria.");
            }
            if (this.tar_agregado_id == 0) {
                this.errors.push("Seleccione Impuesto.");
            }
            if (!this.por_descuento) {
                this.errors.push("Ingrese (%) Descuento.");
            }
            return this.errors;
        },
        editar() {
            const condiciones = this.validaCampos();
            if (condiciones.length) {
                return;
            }

            Swal.fire({
                title: "Espere...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    axios
                        .put("/api/producto/editar", {
                            id: this.producto_id,
                            categoria_id: this.categoria_id,
                            tar_agregado_id: this.tar_agregado_id,
                            nombre: this.nombre,
                            composicion: this.composicion,
                            pre_compra: this.pre_compra,
                            mar_utilidad: this.mar_utilidad,
                            por_descuento: this.por_descuento
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/productos");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        selectCategoria() {
            axios.get("/api/categorias").then(resp => {
                this.arrayCategorias = resp.data;
            });
        },
        selectImpuesto() {
            axios.get("/api/tarifas-iva").then(resp => {
                this.arrayImpuestos = resp.data;
            });
        },
        selectUnidad() {
            axios.get("/api/unidades").then(resp => {
                this.arrayUnidades = resp.data;
            });
        },
        getCodigo() {
            for (let i = 0; i < this.arrayPresentaciones.length; i++) {
                let cat = this.arrayCategorias.find(
                    e => e.id === this.categoria_id
                );
                let uni = this.arrayUnidades.find(
                    e => e.id === this.arrayPresentaciones[i].unidad_id
                );
                this.arrayPresentaciones[
                    i
                ].cod_principal = this.arrayPresentaciones[i].cod_auxiliar =
                    cat.nombre.substring(0, 3) +
                    this.nombre.toUpperCase().substring(0, 3) +
                    "X" +
                    this.arrayPresentaciones[i].presentacion +
                    uni.sigla;
            }
        }
    },
    mounted() {
        this.detalle();
        this.selectCategoria();
        this.selectImpuesto();
        this.selectUnidad();
    }
};
</script>
