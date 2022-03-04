<template>
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mt-2">
                <i class="fas fa-align-justify"></i>
                Traslado de productos entre almacenes
            </h3>
            <div class="card-tools">
                <router-link to="/inventarios" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Regresar
                </router-link>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="establecimiento" class="col-form-label"
                        >Establecimiento Origen:</label
                    >
                    <select
                        v-model="estab_origen_id"
                        class="form-control"
                        @change="listar(estab_origen_id)"
                    >
                        <option value="0">Seleccione...</option>
                        <option
                            v-for="establecimiento in arrayEstabOrigen"
                            :key="establecimiento.id"
                            :value="establecimiento.id"
                            v-text="
                                establecimiento.nom_comercial +
                                    ' - ' +
                                    establecimiento.nom_referencia
                            "
                        ></option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="establecimiento" class="col-form-label"
                        >Establecimiento Destino:</label
                    >
                    <select v-model="estab_destino_id" class="form-control">
                        <option value="0">Seleccione...</option>
                        <option
                            v-for="establecimiento in arrayEstabDestino"
                            :key="establecimiento.id"
                            :value="establecimiento.id"
                            v-text="
                                establecimiento.nom_comercial +
                                    ' - ' +
                                    establecimiento.nom_referencia
                            "
                        ></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock Inicial</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="arrayProductos.length">
                                    <tr
                                        v-for="producto in arrayProductos"
                                        :key="producto.id"
                                    >
                                        <td v-text="producto.nombre"></td>
                                        <td v-text="producto.dis_stock"></td>
                                        <!-- <td>
                                            <input
                                                type="number"
                                                placeholder="0"
                                                min="0"
                                                v-model="producto.dis_stock"
                                            />
                                        </td> -->
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td align="center" colspan="3">
                                            No data available in table
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock Inicial</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="arrayProductos.length">
                                    <tr
                                        v-for="producto in arrayProductos"
                                        :key="producto.id"
                                    >
                                        <td v-text="producto.nombre"></td>
                                        <td>
                                            <input
                                                type="number"
                                                placeholder="0"
                                                min="0"
                                                v-model="producto.dis_stock"
                                            />
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td align="center" colspan="3">
                                            No data available in table
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <router-link to="/inventarios" class="btn btn-danger">
                Cancelar
            </router-link>
            <button type="button" class="btn btn-success" @click="guardar">
                Guardar
            </button>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            estab_origen_id: 0,
            estab_destino_id: 0,
            arrayProductos: [],
            arrayEstabOrigen: [],
            arrayEstabDestino: []
        };
    },
    methods: {
        guardar() {
            Swal.fire({
                title: "Espere...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();

                    axios
                        .post("/api/inventario/guardar", {
                            establecimiento_id: this.establecimiento_id,
                            productos: this.arrayProductos
                        })
                        .then(resp => {
                            Swal.fire(
                                "Bien!",
                                "El registro se guardó con éxito.",
                                "success"
                            );
                            this.$router.push("/inventarios");
                        })
                        .catch(err => {
                            Swal.fire("Alto!", `Error: ${err}`, "error");
                        });
                }
            });
        },
        listar(id = 0) {
            axios
                .get("/api/inventarios?establecimiento=" + id)
                .then(resp => {
                    this.arrayProductos = resp.data;
                });
        },
        selectEstablecimientos() {
            axios.get("/api/establecimientos").then(resp => {
                this.arrayEstabOrigen = resp.data;
                this.arrayEstabDestino = resp.data;
            });
        }
    },
    mounted() {
        this.selectEstablecimientos();
    }
};
</script>
