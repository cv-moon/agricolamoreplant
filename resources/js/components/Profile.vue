<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Perfil
            </h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs justify-content-center">
                <li class="nav-item" v-if="user_id != 1">
                    <a
                        class="nav-link active"
                        id="pills-profile-tab"
                        data-toggle="pill"
                        href="#pills-profile"
                        role="tab"
                        aria-controls="pills-profile"
                        aria-selected="true"
                        >Datos Personales</a
                    >
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        id="pills-change-tab"
                        data-toggle="pill"
                        href="#pills-change"
                        role="tab"
                        aria-controls="pills-change"
                        aria-selected="false"
                        >Cambio de Contraseña</a
                    >
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div
                    class="tab-pane fade show active"
                    id="pills-profile"
                    role="tabpanel"
                    aria-labelledby="pills-home-tab"
                    v-if="user_id != 1"
                >
                    <div class="form-grow row">
                        <div class="col-sm-6">
                            <label for="nombres" class="col-form-label"
                                >Nombres:</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Nombres."
                                maxlength="100"
                                v-model="nombres"
                            />
                        </div>
                        <div class="col-sm-6">
                            <label for="apellidos" class="col-form-label"
                                >Apellidos:</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Apellidos."
                                maxlength="100"
                                v-model="apellidos"
                            />
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label
                                for="tip_identificacion"
                                class="col-form-label"
                                >Tipo Identificación:</label
                            >
                            <select
                                v-model="tip_identificacion"
                                class="form-control"
                            >
                                <option value="0" disabled
                                    >Seleccione...</option
                                >
                                <option value="CED">CÉDULA</option>
                                <option value="RUC">RUC</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label
                                for="num_identificacion"
                                class="col-form-label"
                                ># Identificación:</label
                            >
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
                        <div class="col-sm-4">
                            <label for="telefonos" class="col-form-label"
                                >Teléfonos:</label
                            >
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
                        <div class="col-sm-12">
                            <label for="direccion" class="col-form-label"
                                >Dirección:</label
                            >
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
                        <div class="col-sm-4">
                            <label for="email" class="col-form-label"
                                >E-mail:</label
                            ><input
                                type="email"
                                class="form-control"
                                placeholder="E-mail."
                                maxlength="100"
                                v-model="email"
                            />
                        </div>
                        <div class="col-sm-4">
                            <label for="usuario" class="col-form-label"
                                >Usuario:</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Usuario."
                                minlength="4"
                                v-model="usuario"
                            />
                        </div>
                    </div>
                    <div class="text-center">
                        <button
                            type="button"
                            class="btn btn-warning"
                            @click="updateProfile"
                        >
                            Actualizar Datos
                        </button>
                    </div>
                </div>
                <div
                    class="tab-pane fade"
                    id="pills-change"
                    role="tabpanel"
                    aria-labelledby="pills-profile-tab"
                    :class="user_id == 1 ? 'show active' : ''"
                >
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="password" class="col-form-label"
                                >Contraseña:</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Contraseña."
                                minlength="4"
                                v-model="password"
                                :class="
                                    errors.password.length > 0
                                        ? 'is-invalid'
                                        : ''
                                "
                                @keyup="validPassword"
                            />
                            <div
                                class="invalid-feedback"
                                v-for="err in errors.password"
                                :key="err"
                            >
                                {{ err }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label for="password" class="col-form-label"
                                >Repetir Contraseña:</label
                            >
                            <input
                                type="password"
                                class="form-control"
                                placeholder="Repita Contraseña."
                                minlength="4"
                                v-model="password2"
                                :class="
                                    errors.password2.length > 0
                                        ? 'is-invalid'
                                        : ''
                                "
                                @keyup="repeatPassword"
                            />
                            <div
                                class="invalid-feedback"
                                v-for="err in errors.password2"
                                :key="err"
                            >
                                {{ err }}
                            </div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="text-center">
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="changePassword"
                        >
                            Cambiar Contraseña
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            user_id: 0,
            nombres: "",
            apellidos: "",
            tip_identificacion: "0",
            num_identificacion: "",
            direccion: "",
            telefonos: "",
            email: "",
            usuario: "",
            password: "",
            password2: "",
            errors: {
                password: [],
                password2: []
            }
        };
    },
    methods: {
        getData() {
            axios
                .get("/api/users/perfil")
                .then(resp => {
                    this.user_id = resp.data.id;
                    this.usuario = resp.data.usuario;
                    this.email = resp.data.email;
                    this.nombres = resp.data.nombres;
                    this.apellidos = resp.data.apellidos;
                    this.tip_identificacion = resp.data.tip_identificacion;
                    this.num_identificacion = resp.data.num_identificacion;
                    this.telefonos = resp.data.telefonos;
                    this.direccion = resp.data.direccion;
                })
                .catch(err => {
                    Swal.fire("Alto!", `Error: ${err}`, "error");
                });
        },
        validPassword() {
            this.errors.password = [];
            if (!this.password) {
                this.errors.password.push("El campo no puede estar vacío");
            }
            if (this.password.length < 4) {
                this.errors.password.push(
                    "La contraseña debe tener más de 4 caracteres"
                );
            }
        },
        repeatPassword() {
            this.errors.password2 = [];
            if (!this.password2) {
                this.errors.password2.push("El campo no puede estar vacío");
            }
            if (this.password2.length < 4) {
                this.errors.password2.push(
                    "La contraseña debe tener más de 4 caracteres"
                );
            }
            if (this.password2 !== this.password) {
                this.errors.password2.push(
                    "La contraseña no es igual a la ingresada"
                );
            }
        },
        updateProfile() {
            Swal.fire({
                title: "Espere...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    axios
                        .put("/api/users/actualiza_perfil", {
                            id: this.user_id,
                            usuario: this.usuario,
                            email: this.email,
                            nombres: this.nombres,
                            apellidos: this.apellidos,
                            tip_identificacion: this.tip_identificacion,
                            num_identificacion: this.num_identificacion,
                            telefonos: this.telefonos,
                            direccion: this.direccion
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "La actualización se realizó con éxito.",
                                "success"
                            );
                            this.getData();
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        changePassword() {
            Swal.fire({
                title: "Espere...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    axios
                        .put("/api/users/cambio_pass", {
                            id: this.user_id,
                            password: this.password
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El cambio de contraseña se realizó con éxito.",
                                "success"
                            );
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        }
    },
    mounted() {
        this.getData();
    }
};
</script>
