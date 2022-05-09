<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Productos
            </h3>
            <div class="card-tools">
                <router-link to="/productos/agregar" class="btn btn-success">
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
                        <th>Categoría</th>
                        <th>Nombre</th>
                        <th>Cod. Prin.</th>
                        <th>P.V.P.</th>
                        <th>Desc.</th>
                        <th>Imp.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="producto in arrayProductos" :key="producto.id">
                        <td>
                            <router-link
                                type="button"
                                title="Editar"
                                :to="'/productos/editar/' + producto.id"
                                class="btn btn-warning btn-xs"
                            >
                                <i class="fas fa-pen"></i>
                            </router-link>
                        </td>
                        <td v-text="producto.categoria"></td>
                        <td
                            v-text="
                                producto.nombre +
                                    ' ' +
                                    producto.presentacion +
                                    ' ' +
                                    producto.sigla
                            "
                        ></td>
                        <td v-text="producto.cod_principal"></td>
                        <td v-text="producto.pre_venta"></td>
                        <td v-text="producto.por_descuento"></td>
                        <td v-text="producto.impuesto"></td>
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
            arrayProductos: []
        };
    },
    methods: {
        myTable() {
            this.$nextTick(() => {
                $("#tabla").DataTable();
            });
        },
        listar() {
            axios.get("/api/productos").then(resp => {
                this.arrayProductos = resp.data;
                this.myTable();
            });
        }
    },
    mounted() {
        this.listar();
    }
};
</script>
