<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Perfil de Usuario
            </h3>
        </div>
        <div class="card-body">
            <div v-if="user_id != 1">
                <b class="text-primary">Actualizar Datos</b>
                <hr class="mt-0" />
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
                        <label for="tip_identificacion" class="col-form-label"
                            >Tipo Identificación:</label
                        >
                        <select
                            v-model="tip_identificacion"
                            class="form-control"
                        >
                            <option value="0" disabled>Seleccione...</option>
                            <option value="CED">CÉDULA</option>
                            <option value="RUC">RUC</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="num_identificacion" class="col-form-label"
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
                        <label for="email" class="col-form-label">E-mail:</label
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
            </div>
            <b class="text-primary">Cambio de Contraseña</b>
            <hr class="mt-0" />
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
                        :class="errors.password.length > 0 ? 'is-invalid' : ''"
                    />
                    <div v-if="errors.password.length>0">
                        <div
                            class="invalid-feedback"
                            v-for="err in errors.password"
                            :key="err"
                        >
                            {{ err }}
                        </div>
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
                    />
                </div>
                <div class="invalid-feedback"></div>
            </div>
            <div class="form-group row">
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="repeatPassword"
                >
                    Cambiar Contraseña
                </button>
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
                .get("/api/perfil")
                .then(resp => {
                    console.log(resp);
                    this.user_id = resp.data.id;
                    this.usuario = resp.data.usuario;
                    this.email = resp.data.email;
                })
                .catch(err => {
                    Swal.fire("Alto!", `Error: ${err}`, "error");
                });
        },
        repeatPassword() {
            this.errors.password = [];
            this.errors.password2 = [];
            if (!this.password) {
                this.errors.password.push("El campo no puede estar vacío");
            }
            if (!this.password2) {
                this.errors.password2.push("El campo puede estar vacío");
            }
            console.log(this.errors.password);
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
        }
    },
    mounted() {
        this.getData();
        this.getUsername();
    }
};
</script>
