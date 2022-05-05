<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Puntos de Emisión
            </h3>
            <div class="card-tools">
                <router-link
                    to="/puntos-emision/agregar"
                    class="btn btn-success"
                >
                    <i class="fas fa-plus"></i> Nuevo
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <table
                id="tabla"
                class="table table-bordered dt-responsive nowrap"
                style="width: 100%"
            >
                <thead>
                    <tr>
                        <th>Acción</th>
                        <th>Establecimiento</th>
                        <th>Nombre</th>
                        <th># Fact.</th>
                        <th># Ret.</th>
                        <th># Gui.</th>
                        <th># Ord.</th>
                        <th># Rec.</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="punto in arrayPunto"
                        :key="punto.id"
                        :class="
                            punto.estado == 1 ? 'table-success' : 'table-danger'
                        "
                    >
                        <td>
                            <router-link
                                type="button"
                                title="Editar"
                                :to="'/puntos-emision/editar/' + punto.id"
                                class="btn btn-warning btn-xs"
                            >
                                <i class="fas fa-pen"></i>
                            </router-link>
                            <template v-if="punto.estado == 0">
                                <button
                                    type="button"
                                    title="Habilitar"
                                    class="btn btn-primary btn-xs"
                                    @click="habilitar(punto.id)"
                                >
                                    <i class="far fa-check-circle"></i>
                                </button>
                            </template>
                            <template v-else-if="punto.estado == 1">
                                <button
                                    type="button"
                                    title="Deshabilitar"
                                    class="btn btn-danger btn-xs"
                                    @click="deshabilitar(punto.id)"
                                >
                                    <i class="fas fa-ban"></i>
                                </button>
                            </template>
                        </td>
                        <td v-text="punto.nom_referencia"></td>
                        <td
                            v-text="punto.pun_codigo + ' - ' + punto.nombre"
                        ></td>
                        <td v-text="punto.sec_factura" align="right"></td>
                        <td v-text="punto.sec_retencion" align="right"></td>
                        <td v-text="punto.sec_gui_remision" align="right"></td>
                        <td v-text="punto.sec_orden_trabajo" align="right"></td>
                        <td v-text="punto.sec_recibo_cobro" align="right"></td>
                        <td v-text="punto.usuario"></td>
                        <td>
                            <div v-if="punto.estado == 1">
                                <span class="badge badge-success">Activo</span>
                            </div>
                            <div v-else-if="punto.estado == 0">
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
            arrayPunto: []
        };
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        listar() {
            axios.get("/api/puntos").then(resp => {
                this.arrayPunto = resp.data;
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
                        .put("/api/punto/activar", {
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
                        .put("/api/punto/desactivar", {
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
