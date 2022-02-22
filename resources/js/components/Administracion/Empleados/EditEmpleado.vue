<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Editar Empleado: {{ apellidos + " " + nombres }}
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
                            placeholder="Nombres."
                            maxlength="100"
                            v-model="nombres"
                        />
                    </div>
                    <label for="apellidos" class="col-sm-2 col-form-label"
                        >Apellidos:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Apellidos."
                            maxlength="100"
                            @change="getUsername"
                            v-model="apellidos"
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
                            v-model="tip_identificacion"
                            class="form-control"
                        >
                            <option value="0" disabled>Seleccione...</option>
                            <option value="CED">CÉDULA</option>
                            <option value="RUC">RUC</option>
                        </select>
                    </div>
                    <label
                        for="num_identificacion"
                        class="col-sm-2 col-form-label"
                        ># Identificación:</label
                    >
                    <div class="col-sm-4">
                        <template v-if="tip_identificacion === 'CED'">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Cédula."
                                maxlength="10"
                                v-model="num_identificacion"
                            />
                        </template>
                        <template v-else-if="tip_identificacion === 'RUC'">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="RUC."
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
            user_id: 0,
            rol_id: 0,
            nombres: "",
            apellidos: "",
            tip_identificacion: "0",
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
            errors: []
        };
    },
    methods: {
        detalle() {
            axios
                .get("/api/empleado/detalle", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(resp => {
                    this.user_id = resp.data["id"];
                    this.rol_id = resp.data["rol_id"];
                    this.nombres = resp.data["nombres"];
                    this.apellidos = resp.data["apellidos"];
                    this.tip_identificacion = resp.data["tip_identificacion"];
                    this.num_identificacion = resp.data["num_identificacion"];
                    this.salario = resp.data["salario"];
                    this.direccion = resp.data["direccion"];
                    this.telefonos = resp.data["telefonos"];
                    this.email = resp.data["email"];
                    this.usuario = resp.data["usuario"];
                    this.password = resp.data["usuario"];
                    this.fec_ingreso = resp.data["fec_ingreso"];
                    this.fec_salida = resp.data["fec_salida"];
                });
        },
        getUsername() {
            let separado = [];
            let nombre = "";
            let apellido = "";
            separado = this.nombres.split(" ");
            for (let i = 0; i < separado.length; i++) {
                const element = separado[i].charAt(0);
                nombre = nombre + element;
            }
            apellido = this.apellidos.split(" ");
            nombre = nombre + apellido[0];
            return (this.usuario = this.password = nombre.toLowerCase());
        },
        validaCampos() {
            this.errors = [];
            if (!this.nombres) {
                this.errors.push("Ingrese nombres.");
            }
            if (!this.apellidos) {
                this.errors.push("Ingrese apellidos.");
            }
            if (this.tip_identificacion == "0") {
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
                        .put("/api/empleado/editar", {
                            id: this.user_id,
                            rol_id: this.rol_id,
                            nombres: this.nombres,
                            apellidos: this.apellidos,
                            tip_identificacion: this.tip_identificacion,
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
                                "El registro se actualizó con éxito.",
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
        }
    },
    mounted() {
        this.detalle();
        this.selectRoles();
    }
};
</script>
