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
                    <label for="categoria_id" class="col-form-label"
                        >Categoría:</label
                    >
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
                    />
                </div>
                <div class="col-md-3">
                    <label for="tar_agregado_id" class="col-form-label"
                        >Impuesto:</label
                    >
                    <select v-model="tar_agregado_id" class="form-control">
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
            <div class="form-group row table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Acción</th>
                            <th>Cod. Principal</th>
                            <th>Cod. Auxiliar</th>
                            <th>Presentación</th>
                            <th>P.V.P.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="presentacion in arrayPresentaciones"
                            :key="presentacion.id"
                        >
                            <td align="center">
                                <button
                                    @click="
                                        eliminarPresentacion(presentacion.id)
                                    "
                                    title="Eliminar Presentación"
                                    type="button"
                                    class="btn btn-danger btn-xs"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                            <td>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Cod. Principal"
                                    maxlength="15"
                                    v-model="presentacion.cod_principal"
                                />
                            </td>
                            <td>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Cod. Principal"
                                    maxlength="15"
                                    v-model="presentacion.cod_auxiliar"
                                />
                            </td>
                            <td>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        min="1"
                                        v-model="presentacion.presentacion"
                                    />
                                    <div class="input-group-append">
                                        <select
                                            v-model="presentacion.unidad_id"
                                            class="form-control"
                                        >
                                            <option value="0" disabled
                                                >Sel.</option
                                            >
                                            <option
                                                v-for="unidad in arrayUnidades"
                                                :key="unidad.id"
                                                :value="unidad.id"
                                                v-text="unidad.sigla"
                                            ></option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="0.00"
                                    min="0"
                                    v-model="presentacion.pre_venta"
                                />
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="5">
                                <button class="btn btn-success btn-xs">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            tar_agregado_id: 0,
            nombre: "",
            composicion: "",
            pre_compra: 0,
            por_descuento: 0,
            mar_utilidad: 0,
            arrayPresentaciones: [],
            arrayCategorias: [],
            arrayUnidades: [],
            arrayImpuestos: [],
            errors: []
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
                            q: this.nombre
                        }
                    })
                    .then(resp => {
                        if (resp.data.producto == "Existe") {
                            this.errors.push(
                                "Ya existe un registro con el nombre ingresado."
                            );
                        }
                    });
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
        guardar() {
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
                        .post("/api/producto/guardar", {
                            categoria_id: this.categoria_id,
                            tar_agregado_id: this.tar_agregado_id,
                            nombre: this.nombre,
                            composicion: this.composicion,
                            pre_compra: this.pre_compra,
                            mar_utilidad: this.mar_utilidad,
                            por_descuento: this.por_descuento,
                            presentaciones: this.arrayPresentaciones
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
            let contador = "";
            if (this.categoria_id !== 0) {
                axios
                    .get("/api/productos/conteo", {
                        params: {
                            q: this.categoria_id
                        }
                    })
                    .then(resp => {
                        let obtener = this.arrayCategorias.filter(
                            e => e.id == this.categoria_id
                        );
                        this.cod_principal = this.cod_auxiliar = `${obtener[0].nombre
                            .substring(0, 3)
                            .toUpperCase()}${resp.data}`;
                    });
            }
        }
    },
    mounted() {
        this.selectCategoria();
        this.selectImpuesto();
        this.selectUnidad();
    }
};
</script>
