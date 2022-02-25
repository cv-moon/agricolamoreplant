<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Editar Establecimiento:
                {{ `${nom_comercial} - ${nom_referencia}` }}
            </h3>
            <div class="card-tools">
                <router-link
                    to="/establecimientos"
                    class="btn btn-secondary btn-sm"
                >
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <form>
                <b class="text-primary">Datos Generales</b>
                <hr class="mt-0" />
                <div class="form-group row">
                    <label for="numero" class="col-sm-2 col-form-label"
                        >No.:</label
                    >
                    <div class="col-sm-2">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="0"
                            min="0"
                            max="999"
                            disabled
                            v-model="numero"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nom_comercial" class="col-sm-2 col-form-label"
                        >Nombre Comercial:</label
                    >
                    <div class="col-sm-6">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nombre Comercial."
                            maxlength="300"
                            v-model="nom_comercial"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nom_referencia" class="col-sm-2 col-form-label"
                        >Nombre Referencia:</label
                    >
                    <div class="col-sm-6">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nombre Referencia."
                            maxlength="300"
                            v-model="nom_referencia"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="direccion" class="col-sm-2 col-form-label"
                        >Dirección:</label
                    >
                    <div class="col-sm-6">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Dirección."
                            minlength="4"
                            maxlength="200"
                            v-model="direccion"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="telefonos" class="col-sm-2 col-form-label"
                        >Teléfonos:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Teléfonos."
                            minlength="7"
                            maxlength="50"
                            v-model="telefonos"
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
            </form>
        </div>
        <div class="card-footer">
            <router-link to="/establecimientos" class="btn btn-danger">
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
            est_id: 0,
            numero: 0,
            nom_comercial: "",
            nom_referencia: "",
            direccion: "",
            telefonos: "",
            arrayRoles: [],
            errors: []
        };
    },
    methods: {
        detalle() {
            axios
                .get("/api/establecimiento/detalle", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(resp => {
                    this.est_id = resp.data["id"];
                    this.numero = resp.data["numero"];
                    this.nom_comercial = resp.data["nom_comercial"];
                    this.nom_referencia = resp.data["nom_referencia"];
                    this.direccion = resp.data["direccion"];
                    this.telefonos = resp.data["telefonos"];
                });
        },
        validaCampos() {
            this.errors = [];
            if (!this.numero || this.numero == 0) {
                this.errors.push("Ingrese Número de Establecimiento.");
            }
            if (!this.nom_comercial) {
                this.errors.push("Ingrese Nombre Comercial.");
            }
            if (!this.nom_referencia) {
                this.errors.push("Ingrese Nombre Refencia.");
            }
            if (!this.direccion) {
                this.errors.push("Ingrese Dirección.");
            }
            if (!this.telefonos) {
                this.errors.push("Ingrese telefonos.");
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
                        .put("/api/establecimiento/editar", {
                            id: this.est_id,
                            numero: this.numero,
                            nom_comercial: this.nom_comercial,
                            nom_referencia: this.nom_referencia,
                            direccion: this.direccion,
                            telefonos: this.telefonos
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/establecimientos");
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
