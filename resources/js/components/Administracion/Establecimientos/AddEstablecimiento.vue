<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Agregar Establecimiento
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
                    <label for="est_codigo" class="col-sm-2 col-form-label"
                        >No.:</label
                    >
                    <div class="col-sm-2">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="0"
                            min="0"
                            max="999"
                            v-model="est_codigo"
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
                <div class="form-group row">
                    <label for="user_id" class="col-sm-2 col-form-label"
                        >Responsable:</label
                    >
                    <div class="col-sm-4">
                        <select v-model="user_id" class="form-control">
                            <option value="0" disabled>Seleccione...</option>
                            <option
                                v-for="user in arrayResponsables"
                                :key="user.id"
                                :value="user.id"
                                v-text="user.usuario"
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
            </form>
        </div>
        <div class="card-footer">
            <router-link to="/establecimientos" class="btn btn-danger">
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
            est_codigo: 0,
            user_id: 0,
            nom_comercial: "",
            nom_referencia: "",
            direccion: "",
            telefonos: "",
            arrayRoles: [],
            arrayResponsables: [],
            errors: []
        };
    },
    methods: {
        validaCampos() {
            this.errors = [];
            if (!this.est_codigo || this.est_codigo == 0) {
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
            if (this.user_id == 0) {
                this.errors.push("Seleccione responsable.");
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
                        .post("/api/establecimiento/guardar", {
                            user_id: this.user_id,
                            est_codigo: this.est_codigo,
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
        },
        selectResponsable() {
            axios.get("/api/establecimiento/responsables").then(resp => {
                this.arrayResponsables = resp.data;
            });
        }
    },
    mounted() {
        this.selectResponsable();
    }
};
</script>
