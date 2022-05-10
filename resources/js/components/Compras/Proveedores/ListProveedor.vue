<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Proveedores
            </h3>
            <div class="card-tools">
                <router-link to="/proveedores/agregar" class="btn btn-success">
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
                        <th># Iden</th>
                        <th>Teléfonos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="proveedor in arrayProveedores"
                        :key="proveedor.id"
                    >
                        <td>
                            <router-link
                                type="button"
                                title="Editar"
                                :to="'/proveedores/editar/' + proveedor.id"
                                class="btn btn-warning btn-xs"
                            >
                                <i class="fas fa-pen"></i>
                            </router-link>
                            <!-- <button
                                type="button"
                                title="Ver Contactos"
                                class="btn btn-info btn-xs"
                            >
                                <i class="far fa-address-book"></i>
                            </button> -->
                        </td>
                        <td v-text="proveedor.raz_social"></td>
                        <td
                            v-text="
                                proveedor.nombre +
                                    ': ' +
                                    proveedor.num_identificacion
                            "
                        ></td>
                        <td
                            v-text="proveedor.tel_uno + ' / ' + proveedor.tel_dos"
                        ></td>
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
            arrayProveedores: []
        };
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        listar() {
            axios.get("/api/proveedores").then(resp => {
                this.arrayProveedores = resp.data;
                this.myTable();
            });
        }
    },
    mounted() {
        this.listar();
    }
};
</script>
