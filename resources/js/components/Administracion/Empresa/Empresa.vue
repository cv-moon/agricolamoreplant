<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Empresa
            </h3>
        </div>
        <div class="card-body">
            <form enctype="multipart/form-data" class="form-horizontal">
                <b class="text-primary">Datos Generales</b>
                <hr class="mt-0" />
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group row">
                            <label
                                for="raz_social"
                                class="col-sm-12 col-form-label"
                                >Razón Social:
                            </label>
                            <div class="col-sm-12">
                                <input
                                    type="text"
                                    v-model="raz_social"
                                    class="form-control"
                                    placeholder="Razón Social."
                                    maxlength="100"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ruc" class="col-sm-2 col-form-label"
                                >RUC:
                            </label>
                            <div class="col-sm-4">
                                <input
                                    type="text"
                                    v-model="ruc"
                                    class="form-control"
                                    placeholder="RUC."
                                    maxlength="13"
                                />
                            </div>
                            <label
                                for="telefonos"
                                class="col-sm-2 col-form-label"
                                >Teléfonos:
                            </label>
                            <div class="col-sm-4">
                                <input
                                    type="text"
                                    v-model="telefonos"
                                    class="form-control"
                                    maxlength="50"
                                    placeholder="Teléfonos."
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="url" class="col-sm-12 col-form-label"
                                >Email:</label
                            >
                            <div class="col-sm-12">
                                <input
                                    type="url"
                                    v-model="url"
                                    class="form-control"
                                    maxlength="100"
                                    placeholder="Email."
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label
                                for="direccion"
                                class="col-sm-12 col-form-label"
                                >Dirección:</label
                            >
                            <div class="col-sm-12">
                                <input
                                    type="text"
                                    v-model="direccion"
                                    class="form-control"
                                    maxlength="200"
                                    placeholder="Dirección"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="logo" class="col-sm-12 col-form-label"
                            >Logo:</label
                        >
                        <div class="col-sm-10">
                            <input
                                type="file"
                                class="input-file"
                                placeholder="Logo."
                                @change="getImage"
                            />
                            <figure align="center">
                                <img
                                    width="200"
                                    height="200"
                                    :src="
                                        image ? image : '/img/cloud-upload.png'
                                    "
                                    alt="Logo Empresa"
                                />
                            </figure>
                        </div>
                    </div>
                </div>
                <b class="text-primary">Datos para Facturación</b>
                <hr class="mt-0" />
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label
                            for="cont_resolucion"
                            class="col-sm-12 col-form-label"
                            ># Cont. Especial:</label
                        >
                        <input
                            type="number"
                            class="form-control"
                            maxlength="5"
                            v-model="cont_resolucion"
                        />
                    </div>
                    <div class="col-sm-4">
                        <label
                            for="obli_contabilidad"
                            class="col-sm-12 col-form-label"
                            >Obligado Contabilidad:</label
                        >
                        <select
                            v-model="obli_contabilidad"
                            class="form-control"
                        >
                            <option value="x" disabled>Seleccione...</option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label
                            for="reg_microempresa"
                            class="col-sm-12 col-form-label"
                            >Reg. Microempresa:</label
                        >
                        <select v-model="reg_microempresa" class="form-control">
                            <option value="x" disabled>Seleccione...</option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label
                            for="age_retencion"
                            class="col-sm-12 col-form-label"
                            >Age. Retención:</label
                        >
                        <select v-model="age_retencion" class="form-control">
                            <option value="x" disabled>Seleccione...</option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label
                            for="tip_ambiente"
                            class="col-sm-12 col-form-label"
                            >Tipo Ambiente:</label
                        >
                        <select v-model="tip_ambiente" class="form-control">
                            <option value="x" disabled>Seleccione...</option>
                            <option value="0">PRUEBA</option>
                            <option value="1">PRODUCCIÓN</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label
                            for="tip_emision"
                            class="col-sm-12 col-form-label"
                            >Tipo Emisión:</label
                        >
                        <select v-model="tip_emision" class="form-control">
                            <option value="x" disabled>Seleccione...</option>
                            <option value="1">NORMAL</option>
                        </select>
                    </div>
                </div>
                <hr />
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="firma" class="col-sm-12 col-form-label"
                            >Firma Electrónica:</label
                        >
                        <input
                            type="file"
                            class="input-file"
                            placeholder="Firma."
                            @change="getFirma"
                        />
                    </div>
                    <div class="col-sm-4">
                        <label for="fir_clave" class="col-sm-12 col-form-label"
                            >Clave de Firma:</label
                        >
                        <input
                            type="password"
                            class="form-control"
                            v-model="fir_clave"
                        />
                    </div>
                    <div class="col-sm-4">
                        <label
                            for="fir_vencimiento"
                            class="col-sm-12 col-form-label"
                            >Fec. Vencimiento:</label
                        >
                        <input
                            type="date"
                            class="form-control"
                            v-model="fir_vencimiento"
                        />
                    </div>
                </div>
                <hr />
                <div class="form-group row">
                    <label for="corr_servidor" class="col-sm-4 col-form-label"
                        >Servidor de Correo SMTP:
                    </label>
                    <div class="col-sm-4">
                        <input
                            type="text"
                            v-model="corr_servidor"
                            class="form-control"
                            placeholder="Servidor SMTP"
                        />
                    </div>
                    <label for="corr_puerto" class="col-sm-2 col-form-label"
                        >Puerto:
                    </label>
                    <div class="col-sm-2">
                        <input
                            type="number"
                            v-model="corr_puerto"
                            class="form-control"
                            placeholder="Puerto"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="corr_seguridad" class="col-sm-4 col-form-label"
                        >Seguridad (ssl/tls o en blanco):
                    </label>
                    <div class="col-sm-4">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Seguridad"
                            v-model="corr_seguridad"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label
                        for="corr_autenticacion"
                        class="col-sm-4 col-form-label"
                        >Requiere Autenticación:
                    </label>
                    <div class="col-sm-4">
                        <input type="checkbox" v-model="corr_autenticacion" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="corr_usuario" class="col-sm-3 col-form-label"
                        >Correo Destinatario:
                    </label>
                    <div class="col-sm-3">
                        <input
                            type="email"
                            class="form-control"
                            placeholder="Correo"
                            v-model="corr_usuario"
                        />
                    </div>
                    <label for="corr_password" class="col-sm-3 col-form-label"
                        >Contraseña Correo:
                    </label>
                    <div class="col-sm-3">
                        <input
                            type="password"
                            v-model="corr_password"
                            class="form-control"
                            placeholder="Contraseña del Correo."
                        />
                    </div>
                </div>
            </form>
            <div v-if="errors.length" class="alert alert-danger">
                <div>
                    <div v-for="error in errors" :key="error">
                        {{ error }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <template v-if="tipoAccion == 1">
                <button type="button" class="btn btn-success" @click="guardar">
                    Guardar
                </button>
            </template>
            <template v-else-if="tipoAccion == 2">
                <button type="button" class="btn btn-primary" @click="editar">
                    Actualizar
                </button>
            </template>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            empresa_id: 0,
            raz_social: "",
            ruc: "",
            direccion: "",
            telefonos: "",
            url: "",
            logo: "",
            cont_resolucion: "",
            obli_contabilidad: "x",
            reg_microempresa: "x",
            age_retencion: "x",
            firma: "",
            fir_clave: "",
            fir_vencimiento: "",
            tip_ambiente: 0,
            tip_emision: 1,
            corr_servidor: "",
            corr_puerto: 0,
            corr_seguridad: "",
            corr_autenticacion: 1,
            corr_usuario: "",
            corr_password: "",
            tipoAccion: 0,
            errors: [],
            imgDemo: ""
        };
    },
    methods: {
        detalle() {
            axios.get("/api/empresa/detalle").then(resp => {
                if (resp.data) {
                    this.rellenar(resp.data);
                } else {
                    this.tipoAccion = 1;
                }
            });
        },
        validaCampos() {
            this.errors = [];
            if (!this.raz_social) {
                this.errors.push("Ingrese Razón Social.");
            }
            if (!this.ruc) {
                this.errors.push("Ingrese RUC.");
            }
            if (!this.direccion) {
                this.errors.push("Ingrese Dirección.");
            }
            if (this.obli_contabilidad === "x") {
                this.errors.push(
                    "Seleccione si esta obligado a llevar contabilidad."
                );
            }
            if (this.reg_microempresa === "x") {
                this.errors.push(
                    "Seleccione si pertenece a régimen microempresa."
                );
            }
            if (this.age_retencion === "x") {
                this.errors.push("Seleccione si es agente de retención.");
            }
            if (this.tip_ambiente === "x") {
                this.errors.push("Seleccione tipo de ambiente.");
            }
            if (this.tip_emision === "x") {
                this.errors.push("Seleccione tipo de emision.");
            }
            if (!this.firma) {
                this.errors.push("Seleccione Firma Electrónica.");
            }
            if (!this.fir_clave) {
                this.errors.push("Ingrese Contraeña de Firma Electronica.");
            }
            if (!this.corr_servidor) {
                this.errors.push("Ingrese servidor de correo.");
            }
            if (!this.corr_puerto) {
                this.errors.push("Ingrese puerto.");
            }
            if (!this.corr_seguridad) {
                this.errors.push("Ingrese tipo de seguridad.");
            }
            if (!this.corr_usuario) {
                this.errors.push("Ingrese correo destinatario.");
            }
            if (!this.corr_password) {
                this.errors.push("Ingrese contraseña de correo destinatario.");
            }

            return this.errors;
        },
        guardar() {
            const condiciones = this.validaCampos();
            if (condiciones.length) {
                return;
            }

            let form = new FormData();
            form.append("raz_social", this.raz_social);
            form.append("ruc", this.ruc);
            form.append("direccion", this.direccion);
            form.append("telefonos", this.telefonos);
            form.append("url", this.url);
            form.append("logo", this.logo);
            form.append("cont_resolucion", this.cont_resolucion);
            form.append("obli_contabilidad", this.obli_contabilidad);
            form.append("reg_microempresa", this.reg_microempresa);
            form.append("age_retencion", this.age_retencion);
            form.append("firma", this.firma);
            form.append("fir_clave", this.fir_clave);
            form.append("fir_vencimiento", this.fir_vencimiento);
            form.append("tip_ambiente", this.tip_ambiente);
            form.append("tip_emision", this.tip_emision);
            form.append("corr_servidor", this.corr_servidor);
            form.append("corr_puerto", this.corr_puerto);
            form.append("corr_seguridad", this.corr_seguridad);
            form.append("corr_autenticacion", this.corr_autenticacion);
            form.append("corr_usuario", this.corr_usuario);
            form.append("corr_password", this.corr_password);

            Swal.fire({
                title: "Espere...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();

                    axios
                        .post("/api/empresa/guardar", form)
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.detalle();
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
            // axios
            //   .post("/api/empresa/guardar", form)
            //   .then((resp) => {
            //     Swal.fire("Bien!", "El registro se guardó con éxito.", "success");
            //     this.detalle();
            //   })
            //   .catch((error) => {
            //     Swal.fire(
            //       "Error!",
            //       "No se pudo realizar el registro. " + error,
            //       "error"
            //     );
            //   });
        },
        editar() {
            axios
                .put("/api/empresa/editar", {
                    id: this.empresa_id,
                    raz_social: this.raz_social,
                    ruc: this.ruc,
                    direccion: this.direccion,
                    telefonos: this.telefonos,
                    url: this.url,
                    cont_resolucion: this.cont_resolucion,
                    obli_contabilidad: this.obli_contabilidad,
                    reg_microempresa: this.reg_microempresa,
                    age_retencion: this.age_retencion,
                    fir_clave: this.fir_clave,
                    fir_vencimiento: this.fir_vencimiento,
                    tip_ambiente: this.tip_ambiente,
                    tip_emision: this.tip_emision,
                    corr_servidor: this.corr_servidor,
                    corr_puerto: this.corr_puerto,
                    corr_seguridad: this.corr_seguridad,
                    corr_autenticacion: this.corr_autenticacion,
                    corr_usuario: this.corr_usuario,
                    corr_password: this.corr_password
                })
                .then(resp => {
                    Swal.fire(
                        "Bien!",
                        "El registro se actualizó con éxito.",
                        "success"
                    );
                    this.detalle();
                })
                .catch(error => {
                    Swal.fire(
                        "Error!",
                        "No se pudo editar el registro. " + error,
                        "error"
                    );
                });
        },
        rellenar(data = []) {
            this.tipoAccion = 2;
            this.empresa_id = data["id"];
            this.raz_social = data["raz_social"];
            this.ruc = data["ruc"];
            this.direccion = data["direccion"];
            this.telefonos = data["telefonos"];
            this.url = data["url"];
            this.imgDemo = data["logo"];
            this.cont_resolucion = data["cont_resolucion"];
            this.obli_contabilidad = data["obli_contabilidad"];
            this.reg_microempresa = data["reg_microempresa"];
            this.age_retencion = data["age_retencion"];
            this.firma = data["firma"];
            this.fir_clave = data["fir_clave"];
            this.fir_vencimiento = data["fir_vencimiento"];
            this.tip_ambiente = data["tip_ambiente"];
            this.tip_emision = data["tip_emision"];
            this.corr_servidor = data["corr_servidor"];
            this.corr_puerto = data["corr_puerto"];
            this.corr_seguridad = data["corr_seguridad"];
            this.corr_autenticacion = data["corr_autenticacion"];
            this.corr_usuario = data["corr_usuario"];
            this.corr_password = data["corr_password"];
        },
        getImage(e) {
            let file = e.target.files[0];
            this.logo = file;
            this.renderImage(file);
        },
        getFirma(e) {
            let firma = e.target.files[0];
            this.firma = firma;
        },
        renderImage(file) {
            let reader = new FileReader();
            reader.onload = e => {
                this.imgDemo = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    },

    computed: {
        image() {
            return this.imgDemo;
        }
    },
    mounted() {
        this.detalle();
    }
};
</script>
