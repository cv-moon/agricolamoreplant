<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Perfil de Usuario
            </h3>
        </div>
        <div class="card-body">
            <b class="text-primary">Actualizar Datos</b>
            <hr class="mt-0" />
            <div class="form-grow row">
                <div class="col-sm-4">
                    <label for="nombres" class="col-form-label">Nombres:</label>
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Nombres."
                        maxlength="100"
                        v-model="nombres"
                        disabled
                    />
                </div>
                <div class="col-sm-4">
                    <label for="apellidos" class="col-form-label"
                        >Apellidos:</label
                    >
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Apellidos."
                        maxlength="100"
                        v-model="apellidos"
                        disabled
                    />
                </div>
            </div>
            <b class="text-primary">Cambio de Contraseña</b>
            <hr class="mt-0" />
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            nombres: "",
            apellidos: ""
        };
    },
    methods: {
        getData() {
            axios
                .get("/api/perfil")
                .then(resp => {
                    console.log(resp);
                })
                .catch(err => {
                    Swal.fire("Alto!", `Error: ${err}`, "error");
                });
        }
    },
    mounted() {
        this.getData();
    }
};
</script>
