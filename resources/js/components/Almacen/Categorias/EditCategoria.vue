<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Editar Categoria: {{ nombre }}
            </h3>
            <div class="card-tools">
                <router-link to="/categorias" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <form>
                <b class="text-primary">Datos Generales</b>
                <hr class="mt-0" />
                <label for="nombre" class="col-sm-12 col-form-label"
                    >Nombre:</label
                >
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="nombre" class="col-form-label"
                            >Nombre:</label
                        >
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nombre."
                            maxlength="100"
                            v-model="nombre"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <label for="descripcion" class="col-form-label"
                            >Descripción:</label
                        >
                        <textarea
                            cols="1"
                            rows="3"
                            maxlength="200"
                            class="form-control"
                            placeholder="Ingrese Descripción"
                            v-model="descripcion"
                        ></textarea>
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
            <router-link to="/categorias" class="btn btn-danger">
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
            cat_id: 0,
            nombre: "",
            descripcion: "",
            errors: []
        };
    },
    methods: {
        detalle() {
            axios
                .get("/api/categoria/detalle", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(resp => {
                    this.cat_id = resp.data["id"];
                    this.nombre = resp.data["nombre"];
                    this.descripcion = resp.data["descripcion"];
                });
        },
        validaCampos() {
            this.errors = [];
            if (!this.nombre) {
                this.errors.push("Ingrese Nombre.");
            }
            if (!this.descripcion) {
                this.errors.push("Ingrese Descripción.");
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
                        .put("/api/categoria/editar", {
                            id: this.cat_id,
                            nombre: this.nombre,
                            descripcion: this.descripcion
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/categorias");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        }
    },
    mounted() {
        this.detalle();
    }
};
</script>
