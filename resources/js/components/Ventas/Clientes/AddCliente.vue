<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Agregar Cliente
            </h3>
            <div class="card-tools">
                <router-link to="/clientes" class="btn btn-secondary btn-sm">
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
                </div>
                <div class="form-group row">
                    <label
                        for="tip_identificacion_id"
                        class="col-sm-2 col-form-label"
                        >Tipo Identificación:</label
                    >
                    <div class="col-sm-4">
                        <select
                            v-model="tip_identificacion_id"
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
                        <input
                            type="text"
                            class="form-control"
                            placeholder="No. Identificación."
                            :maxlength="
                                tip_identificacion_id == 2 ? '10' : '13'
                            "
                            v-model="num_identificacion"
                        />
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
                <b class="text-primary">Datos Adicionales</b>
                <hr class="mt-0" />
                <div class="form-group row">
                    <label for="lim_credito" class="col-sm-2 col-form-label"
                        >Lim. Crédito ($):</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="0"
                            min="0"
                            v-model="lim_credito"
                        />
                    </div>
                    <label for="descuento" class="col-sm-2 col-form-label"
                        >Descuento (%):</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            placeholder="0"
                            min="0"
                            v-model="descuento"
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
            <router-link to="/clientes" class="btn btn-danger">
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
            tip_identificacion_id: "0",
            nombre: "",
            num_identificacion: "",
            direccion: "",
            telefonos: "",
            email: "",
            lim_credito: 0,
            descuento: 0,
            arrayIdentificaciones: [],
            errors: []
        };
    },
    methods: {
        validaCampos() {
            this.errors = [];
            if (!this.nombre) {
                this.errors.push("Ingrese nombre.");
            }
            if (this.tip_identificacion_id == "0") {
                this.errors.push("Seleccione tipo de identificación.");
            }
            if (!this.num_identificacion) {
                this.errors.push("Ingrese número de identificación.");
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
                        .post("/api/cliente/guardar", {
                            nombre: this.nombre,
                            tip_identificacion_id: this.tip_identificacion_id,
                            num_identificacion: this.num_identificacion,
                            direccion: this.direccion,
                            telefonos: this.telefonos,
                            email: this.email,
                            lim_credito: this.lim_credito,
                            descuento: this.descuento
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/clientes");
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
        this.selectIdentificaciones();
    }
};
</script>
