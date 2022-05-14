<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                órdenes de Trabajo
            </h3>
            <div class="card-tools">
                <button class="btn btn-success" @click="validaCaja">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>
        </div>
        <div class="card-body">
            <table
                id="tabla"
                class="table table-striped table-bordered dt-responsive nowrap"
                style="width: 100%"
            >
                <thead>
                    <tr>
                        <th>Acción</th>
                        <th>F. Emi.</th>
                        <th># ODT.</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="odt in arrayOdts"
                        :key="odt.id"
                        :class="
                            odt.estado == 'OTC'
                                ? 'table-success'
                                : odt.estado == 'CAN'
                                ? 'table-danger'
                                : odt.estado == 'OTC'
                                ? 'table-warning'
                                : ''
                        "
                    >
                        <td>
                            <router-link
                                type="button"
                                title="Ver"
                                :to="'/odt/detalle/' + compra.id"
                                class="btn btn-info btn-xs"
                            >
                                <i class="fas fa-eye"></i>
                            </router-link>
                            <template v-if="compra.estado == 1">
                                <button
                                    type="button"
                                    title="Anular"
                                    class="btn btn-danger btn-xs"
                                    @click="anular(compra.id)"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </template>
                        </td>
                        <td v-text="odt.fec_emision"></td>
                        <td v-text="odt.num_secuencial"></td>
                        <td v-text="odt.cliente"></td>
                        <td align="right" v-text="odt.val_total"></td>
                        <td v-text="odt.usuario"></td>
                        <td>
                            Estado
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
            arrayOdts: []
        };
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        listar() {
            axios.get("/api/odts").then(resp => {
                this.arrayOdts = resp.data;
                this.myTable();
            });
        },
        validaCaja() {
            axios.get("/api/arqueo/validar").then(resp => {
                console.log(resp.data);
                if (resp.data !== "open") {
                    Swal.fire({
                        title: "Alto!",
                        text: "La caja de hoy aún no ha sido aperturado",
                        icon: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "OK"
                    }).then(result => {
                        if (result.isConfirmed) {
                            this.$router.push("/arqueos/abrir");
                        }
                    });
                } else {
                    this.$router.push("/odt/agregar");
                }
            });
        }
    },
    mounted() {
        this.listar();
    }
};
</script>
