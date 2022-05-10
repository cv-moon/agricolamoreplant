<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Editar Proveedor: {{ raz_social }}
            </h3>
            <div class="card-tools">
                <router-link to="/proveedores" class="btn btn-secondary btn-sm">
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
                        >Nombre/Razón Social:</label
                    >
                    <div class="col-sm-10">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Nombre/Razón Social."
                            maxlength="100"
                            v-model="raz_social"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        for="tip_identificacion"
                        class="col-sm-2 col-form-label"
                        >Tipo Identificación:</label
                    >
                    <div class="col-sm-2">
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
                    <div class="col-sm-2">
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
                    <label for="localidad" class="col-sm-2 col-form-label"
                        >Localidad/Ciudad:</label
                    >
                    <div class="col-sm-2">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Localidad."
                            maxlength="100"
                            v-model="localidad"
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
                            maxlength="300"
                            v-model="direccion"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label"
                        >Correo para retención:</label
                    >
                    <div class="col-sm-10">
                        <input
                            type="email"
                            class="form-control"
                            placeholder="E-mail."
                            maxlength="100"
                            v-model="email"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="telefonos" class="col-sm-2 col-form-label"
                        >Telefono 1:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Tel. 1."
                            maxlength="10"
                            v-model="tel_uno"
                        />
                    </div>
                    <label for="telefonos" class="col-sm-2 col-form-label"
                        >Teléfono 2:</label
                    >
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Tel. 2."
                            maxlength="10"
                            v-model="tel_dos"
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
            <router-link to="/proveedores" class="btn btn-danger">
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
            proveedor_id: 0,
            tip_identificacion_id: 0,
            raz_social: "",
            num_identificacion: "",
            localidad: "",
            direccion: "",
            email: "",
            tel_uno: "",
            tel_dos: "",
            arrayIdentificaciones: [],
            errors: []
        };
    },
    methods: {
        detalle() {
            axios
                .get("/api/proveedor/detalle", {
                    params: {
                        id: this.$route.params.id
                    }
                })
                .then(resp => {
                    this.proveedor_id = resp.data["id"];
                    this.tip_identificacion_id =
                        resp.data["tip_identificacion_id"];
                    this.raz_social = resp.data["raz_social"];
                    this.num_identificacion = resp.data["num_identificacion"];
                    this.localidad = resp.data["localidad"];
                    this.direccion = resp.data["direccion"];
                    this.email = resp.data["email"];
                    this.tel_uno = resp.data["tel_uno"];
                    this.tel_dos = resp.data["tel_dos"];
                });
        },
        validaCampos() {
            this.errors = [];
            if (!this.raz_social) {
                this.errors.push("Ingrese razón social.");
            }
            if (this.tip_identificacion_id == 0) {
                this.errors.push("Seleccione tipo de identificación.");
            }
            if (!this.num_identificacion) {
                this.errors.push("Ingrese número de identificación.");
            }
            if (!this.tel_uno) {
                this.errors.push("Ingrese al menos un teléfono.");
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
                        .put("/api/proveedor/editar", {
                            id: this.proveedor_id,
                            tip_identificacion_id: this.tip_identificacion_id,
                            raz_social: this.raz_social,
                            num_identificacion: this.num_identificacion,
                            localidad: this.localidad,
                            direccion: this.direccion,
                            email: this.email,
                            tel_uno: this.tel_uno,
                            tel_dos: this.tel_dos
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se actualizó con éxito.",
                                "success"
                            );
                            this.$router.push("/proveedores");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        selectIdenticaciones() {
            axios.get("/api/identificaciones").then(resp => {
                this.arrayIdentificaciones = resp.data;
            });
        }
    },
    mounted() {
        this.detalle();
        this.selectIdenticaciones();
    }
};
</script>
