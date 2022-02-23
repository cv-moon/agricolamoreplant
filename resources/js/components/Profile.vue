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
        <div class="col-sm-6">
          <label for="nombres" class="col-form-label">Nombres:</label>
          <input
            type="text"
            class="form-control"
            placeholder="Nombres."
            maxlength="100"
            v-model="nombres"
          />
        </div>
        <div class="col-sm-6">
          <label for="apellidos" class="col-form-label">Apellidos:</label>
          <input
            type="text"
            class="form-control"
            placeholder="Apellidos."
            maxlength="100"
            v-model="apellidos"
          />
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-4">
          <label for="tip_identificacion" class="col-form-label"
            >Tipo Identificación:</label
          >
          <select v-model="tip_identificacion" class="form-control">
            <option value="0" disabled>Seleccione...</option>
            <option value="CED">CÉDULA</option>
            <option value="RUC">RUC</option>
          </select>
        </div>
        <div class="col-sm-4">
          <label for="num_identificacion" class="col-form-label"
            ># Identificación:</label
          >
          <template v-if="tip_identificacion === 'CED'">
            <input
              type="text"
              class="form-control"
              placeholder="Cédula."
              maxlength="10"
              v-model="num_identificacion"
            />
          </template>
          <template v-else-if="tip_identificacion === 'RUC'">
            <input
              type="text"
              class="form-control"
              placeholder="RUC."
              maxlength="13"
              v-model="num_identificacion"
            />
          </template>
        </div>
        <div class="col-sm-4">
          <label for="telefonos" class="col-form-label">Teléfonos:</label>
          <input
            type="text"
            class="form-control"
            placeholder="Teléfonos."
            minlength="7"
            maxlength="50"
            v-model="telefonos"
          />
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <label for="direccion" class="col-form-label">Dirección:</label>
          <input
            type="text"
            class="form-control"
            placeholder="Dirección."
            minlength="4"
            maxlength="200"
            v-model="direccion"
          />
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-4">
          <label for="email" class="col-form-label">E-mail:</label
          ><input
            type="email"
            class="form-control"
            placeholder="E-mail."
            maxlength="100"
            v-model="email"
          />
        </div>
        <div class="col-sm-4">
          <label for="usuario" class="col-form-label">Usuario:</label>
          <input
            type="text"
            class="form-control"
            placeholder="Usuario."
            minlength="4"
            v-model="usuario"
          />
        </div>
        <div class="col-sm-4">
          <label for="password" class="col-form-label">Contraseña:</label>
          <input
            type="password"
            class="form-control"
            placeholder="Contraseña."
            minlength="4"
            v-model="password"
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
      apellidos: "",
      tip_identificacion: "0",
      num_identificacion: "",
      direccion: "",
      telefonos: "",
      email: "",
      usuario: "",
      password: "",
    };
  },
  methods: {
    getData() {
      axios
        .get("/api/perfil")
        .then((resp) => {
          console.log(resp);
        })
        .catch((err) => {
          Swal.fire("Alto!", `Error: ${err}`, "error");
        });
    },
    getUsername() {
            let separado = [];
            let nombre = "";
            let apellido = "";
            separado = this.nombres.split(" ");
            for (let i = 0; i < separado.length; i++) {
                const element = separado[i].charAt(0);
                nombre = nombre + element;
            }
            apellido = this.apellidos.split(" ");
            nombre = nombre + apellido[0];
            return (this.usuario = this.password = nombre.toLowerCase());
        },
  },
  mounted() {
    this.getData();
    this.getUsername();
  },
};
</script>
