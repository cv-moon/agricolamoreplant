<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Clientes
            </h3>
            <div class="card-tools">
                <router-link to="/clientes/agregar" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo
                </router-link>
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
                        <th>Nombre</th>
                        <th>T. Iden</th>
                        <th># Iden</th>
                        <th>Lím. Créd.</th>
                        <th>(%) Desc.</th>
                        <th>Teléfonos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cliente in arrayClientes" :key="cliente.id">
                        <td>
                            <router-link
                                type="button"
                                title="Editar"
                                :to="'/clientes/editar/' + cliente.id"
                                class="btn btn-warning btn-xs"
                            >
                                <i class="fas fa-pen"></i>
                            </router-link>
                        </td>
                        <td v-text="cliente.nombre"></td>
                        <td v-text="cliente.tip_identificacion"></td>
                        <td v-text="cliente.num_identificacion"></td>
                        <td align="right" v-text="cliente.lim_credito"></td>
                        <td align="right" v-text="cliente.descuento"></td>
                        <td v-text="cliente.telefonos"></td>
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
            arrayClientes: []
        };
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        listar() {
            axios.get("/api/clientes").then(resp => {
                this.arrayClientes = resp.data;
                this.myTable();
            });
        }
    },
    mounted() {
        this.listar();
    }
};
</script>
