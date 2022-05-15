<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Establecimientos
            </h3>
            <div class="card-tools">
                <router-link
                    to="/establecimientos/agregar"
                    class="btn btn-success"
                >
                    <i class="fas fa-plus"></i> Nuevo
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <table
                id="tabla"
                class="table table-bordered dt-responsive nowrap table-sm"
                style="width: 100%"
            >
                <thead>
                    <tr>
                        <th>Acción</th>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Nom. Referencia</th>
                        <th>Dirección</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="establecimiento in arrayEstablecimiento"
                        :key="establecimiento.id"
                        :class="
                            establecimiento.estado == 1
                                ? 'table-success'
                                : 'table-danger'
                        "
                    >
                        <td>
                            <router-link
                                type="button"
                                title="Editar"
                                :to="
                                    '/establecimientos/editar/' +
                                        establecimiento.id
                                "
                                class="btn btn-warning btn-xs"
                            >
                                <i class="fas fa-pen"></i>
                            </router-link>
                            <template v-if="establecimiento.estado == 0">
                                <button
                                    type="button"
                                    title="Habilitar"
                                    class="btn btn-primary btn-xs"
                                    @click="habilitar(establecimiento.id)"
                                >
                                    <i class="far fa-check-circle"></i>
                                </button>
                            </template>
                            <template v-else-if="establecimiento.estado == 1">
                                <button
                                    type="button"
                                    title="Deshabilitar"
                                    class="btn btn-danger btn-xs"
                                    @click="deshabilitar(establecimiento.id)"
                                >
                                    <i class="fas fa-ban"></i>
                                </button>
                            </template>
                        </td>
                        <td v-text="establecimiento.est_codigo" align="right"></td>
                        <td v-text="establecimiento.nom_comercial"></td>
                        <td v-text="establecimiento.nom_referencia"></td>
                        <td v-text="establecimiento.direccion"></td>
                        <td v-text="establecimiento.usuario"></td>
                        <td>
                            <div v-if="establecimiento.estado == 1">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else-if="establecimiento.estado == 0">
                                <span class="badge badge-danger">Inactivo</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            arrayEstablecimiento: []
        };
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        listar() {
            axios.get("/api/establecimientos").then(resp => {
                this.arrayEstablecimiento = resp.data;
                $("#tabla")
                    .DataTable()
                    .destroy();
                this.myTable();
            });
        },
        habilitar(id) {
            Swal.fire({
                title: "Desea habilitar este registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, habilitar!",
                cancelButtonText: "No, cancelar!"
            }).then(result => {
                if (result.isConfirmed) {
                    axios
                        .put("/api/establecimiento/activar", {
                            id: id
                        })
                        .then(resp => {
                            Swal.fire(
                                "Habilitado!",
                                "El registro ha sido habilitado.",
                                "success"
                            );
                            this.listar();
                        })
                        .catch(err => {
                            Swal.fire(
                                "Error!",
                                "No se pudo habilitar el registro. " + err,
                                "error"
                            );
                        });
                }
            });
        },
        deshabilitar(id) {
            Swal.fire({
                title: "Desea deshabilitar este registro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, deshabilitar!",
                cancelButtonText: "No, cancelar!"
            }).then(result => {
                if (result.isConfirmed) {
                    axios
                        .put("/api/establecimiento/desactivar", {
                            id: id
                        })
                        .then(resp => {
                            Swal.fire(
                                "Deshabilitado!",
                                "El registro ha sido deshabilitado.",
                                "success"
                            );
                            this.listar();
                        })
                        .catch(err => {
                            Swal.fire(
                                "Error!",
                                "No se pudo dehabilitar el registro. " + err,
                                "error"
                            );
                        });
                }
            });
        }
    },
    mounted() {
        this.listar();
    }
};
</script>
