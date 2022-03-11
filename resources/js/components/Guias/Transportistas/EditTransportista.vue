<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Editar Transportista: {{ nombre }}
            </h3>
            <div class="card-tools">
                <router-link
                    to="/transportistas"
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
                    <label for="nombre" class="col-sm-2 col-form-label"
                        >Nombre:</label
                    >
                    <div class="col-sm-6">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nombre."
                            maxlength="100"
                            v-model="nombre"
                        />
                    </div>
                    <label for="placa" class="col-sm-1 col-form-label"
                        >Placa:</label
                    >
                    <div class="col-sm-3">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Placa."
                            maxlength="7"
                            v-model="placa"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        for="identificacion_id"
                        class="col-sm-2 col-form-label"
                        >Tipo Identificación:</label
                    >
                    <div class="col-sm-4">
                        <select
                            v-model="identificacion_id"
                            class="form-control"
                        >
                            <option value="0" disabled>Seleccione...</option>
                            <option
                                v-for="identificacion in arrayIdentificaciones"
                                :key="identificacion.id"
                                :value="identificacion.id"
                                v-text="identificacion.nombre"
                            ></option>
                        </select>
                    </div>
                    <label
                        for="num_identificacion"
                        class="col-sm-2 col-form-label"
                        ># Identificación:</label
                    >
                    <div class="col-sm-4">
                        <template v-if="identificacion_id === 2">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Cédula."
                                maxlength="10"
                                v-model="num_identificacion"
                            />
                        </template>
                        <template v-else-if="identificacion_id === 1">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="RUC."
                                maxlength="13"
                                v-model="num_identificacion"
                            />
                        </template>
                        <template v-else-if="identificacion_id === 3">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Pasaporte."
                                maxlength="13"
                                v-model="num_identificacion"
                            />
                        </template>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="direccion" class="col-sm-2 col-form-label"
                        >Dirección:</label
                    >
                    <div class="col-sm-10">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Dirección."
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
                            maxlength="50"
                            v-model="telefonos"
                        />
                    </div>
                    <label for="email" class="col-sm-2 col-form-label"
                        >Site/e-mail:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="email"
                            class="form-control"
                            placeholder="E-mail."
                            maxlength="100"
                            v-model="email"
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
            <router-link to="/transportistas" class="btn btn-danger">
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
            transportista_id: 0,
            identificacion_id: 0,
            nombre: "",
            num_identificacion: "",
            direccion: "",
            telefonos: "",
            email: "",
            placa: 0,
            arrayIdentificaciones: [],
            errors: []
        };
    },
    methods: {
        detalle() {
            axios
                .get("/api/transportista/detalle", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(resp => {
                    this.transportista_id = resp.data["id"];
                    this.identificacion_id = resp.data["identificacion_id"];
                    this.nombre = resp.data["nombre"];
                    this.num_identificacion = resp.data["num_identificacion"];
                    this.direccion = resp.data["direccion"];
                    this.telefonos = resp.data["telefonos"];
                    this.email = resp.data["email"];
                    this.placa = resp.data["placa"];
                });
        },
        validaCampos() {
            this.errors = [];
            if (!this.nombre) {
                this.errors.push("Ingrese Nombre.");
            }
            if (!this.placa) {
                this.errors.push("Ingrese Placa.");
            }
            if (this.identificacion_id == "0") {
                this.errors.push("Seleccione tipo de identificación.");
            }
            if (!this.num_identificacion) {
                this.errors.push("Ingrese número de identificación.");
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
                        .put("/api/transportista/editar", {
                            id: this.transportista_id,
                            identificacion_id: this.identificacion_id,
                            nombre: this.nombre,
                            num_identificacion: this.num_identificacion,
                            direccion: this.direccion,
                            telefonos: this.telefonos,
                            email: this.email,
                            placa: this.placa
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se actualizó con éxito.",
                                "success"
                            );
                            this.$router.push("/transportistas");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        selectIdentificaciones() {
            axios.get("/api/identificaciones").then(resp => {
                this.arrayIdentificaciones = resp.data;
            });
        }
    },
    mounted() {
        this.detalle();
        this.selectIdentificaciones();
    }
};
</script>
