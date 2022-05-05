<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Agregar Usuario - Empleado
            </h3>
            <div class="card-tools">
                <router-link to="/empleados" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <form>
                <b class="text-primary">Datos Generales</b>
                <hr class="mt-0" />
                <div class="form-group row">
                    <label for="nombres" class="col-sm-2 col-form-label"
                        >Nombres:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Primer Nombre."
                            maxlength="100"
                            v-model="nom_primero"
                        />
                    </div>
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Segundo Nombre."
                            maxlength="100"
                            v-model="nom_segundo"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="apellidos" class="col-sm-2 col-form-label"
                        >Apellidos:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Primer Apellido."
                            maxlength="100"
                            v-model="ape_paterno"
                            @change="getUsuario"
                        />
                    </div>
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Segundo Apellido."
                            maxlength="100"
                            v-model="ape_materno"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        for="tip_identificacion"
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
                            placeholder="Cédula."
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
                <b class="text-primary">Datos de Acceso</b>
                <hr class="mt-0" />
                <p class="text-red mt-0">
                    *La contraseña del empleado se genera automáticamente y es
                    la misma del nombre de usuario
                </p>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label"
                        >e-mail:</label
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
                    <label for="usuario" class="col-sm-2 col-form-label"
                        >Usuario:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Usuario."
                            minlength="4"
                            v-model="usuario"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label"
                        >Contraseña:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="password"
                            class="form-control"
                            placeholder="Contraseña."
                            minlength="4"
                            v-model="password"
                        />
                    </div>
                </div>
                <b class="text-primary">Tipo de Trabajo</b>
                <hr class="mt-0" />
                <div class="form-group row">
                    <label for="rol_id_id" class="col-sm-2 col-form-label"
                        >Rol de Usuario:</label
                    >
                    <div class="col-sm-4">
                        <select v-model="rol_id" class="form-control">
                            <option value="0" disabled>Seleccione...</option>
                            <option
                                v-for="rol in arrayRoles"
                                :key="rol.id"
                                :value="rol.id"
                                v-text="rol.nombre"
                            ></option>
                        </select>
                    </div>
                    <label for="salario" class="col-sm-2 col-form-label"
                        >Salario:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="number"
                            class="form-control"
                            min="0"
                            v-model="salario"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="fec_ingreso" class="col-sm-2 col-form-label"
                        >Fecha de Ingreso:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="date"
                            class="form-control"
                            v-model="fec_ingreso"
                        />
                    </div>
                    <label for="fec_salida" class="col-sm-2 col-form-label"
                        >Fecha Salida:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="date"
                            class="form-control"
                            v-model="fec_salida"
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
            <router-link to="/empleados" class="btn btn-danger">
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
            rol_id: 0,
            tip_identificacion_id: 0,
            nom_primero: "",
            nom_segundo: "",
            ape_paterno: "",
            ape_materno: "",
            num_identificacion: "",
            salario: 0,
            direccion: "",
            telefonos: "",
            email: "",
            usuario: "",
            password: "",
            fec_ingreso: "",
            fec_salida: "",
            arrayRoles: [],
            arrayIdentificaciones: [],
            errors: []
        };
    },
    methods: {
        getUsuario() {
            let username = "";
            if (
                this.nom_primero != "" ||
                this.nom_segundo != "" ||
                this.ape_paterno != ""
            )
                username =
                    this.nom_primero.charAt(0) +
                    this.nom_segundo.charAt(0) +
                    this.ape_paterno;
            return (this.usuario = this.password = username.toLowerCase());
        },
        validaCampos() {
            this.errors = [];
            if (!this.nom_primero) {
                this.errors.push("Ingrese Primer Nombre.");
            }
            if (!this.nom_segundo) {
                this.errors.push("Ingrese Segundo Nombre.");
            }
            if (!this.ape_paterno) {
                this.errors.push("Ingrese Primer apellido.");
            }
            if (this.tip_identificacion_id == "0") {
                this.errors.push("Seleccione tipo de identificación.");
            }
            if (!this.num_identificacion) {
                this.errors.push("Ingrese número de identificación.");
            }
            if (!this.email) {
                this.errors.push("Ingrese email.");
            }
            if (!this.password) {
                this.errors.push("Ingrese contraseña.");
            }
            if (this.rol_id == "0") {
                this.errors.push("Seleccione rol.");
            }
            if (!this.salario) {
                this.errors.push("Ingrese el salario.");
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
                        .post("/api/empleado/guardar", {
                            rol_id: this.rol_id,
                            tip_identificacion_id: this.tip_identificacion_id,
                            nom_primero: this.nom_primero,
                            nom_segundo: this.nom_segundo,
                            ape_paterno: this.ape_paterno,
                            ape_materno: this.ape_materno,
                            num_identificacion: this.num_identificacion,
                            salario: this.salario,
                            direccion: this.direccion,
                            telefonos: this.telefonos,
                            email: this.email,
                            usuario: this.usuario,
                            password: this.password,
                            fec_ingreso: this.fec_ingreso,
                            fec_salida: this.fec_salida
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/empleados");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        selectRoles() {
            axios.get("/api/roles").then(resp => {
                this.arrayRoles = resp.data;
            });
        },
        selectIdenticaciones() {
            axios.get("/api/identificacion-empleado").then(resp => {
                this.arrayIdentificaciones = resp.data;
            });
        }
    },
    mounted() {
        this.selectRoles();
        this.selectIdenticaciones();
    }
};
</script>
