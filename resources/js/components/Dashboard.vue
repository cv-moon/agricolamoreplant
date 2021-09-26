<template>
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ vh_credito }}</h3>

            <p>Venta Diaria Crédito</p>
          </div>
          <div class="icon">
            <i class="fas fa-credit-card"></i>
          </div>
          <router-link to="/creditos" class="small-box-footer"
            >Más Info <i class="fas fa-arrow-circle-right"></i
          ></router-link>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ vh_efectivo }}</h3>

            <p>Venta Diaria Efectivo</p>
          </div>
          <div class="icon">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <router-link to="/facturacion" class="small-box-footer"
            >Más Info <i class="fas fa-arrow-circle-right"></i
          ></router-link>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ cpp }}</h3>

            <p>Cuentas Por Pagar</p>
          </div>
          <div class="icon">
            <i class="fas fa-money-check-alt"></i>
          </div>
          <router-link to="/deudas" class="small-box-footer"
            >Más Info <i class="fas fa-arrow-circle-right"></i
          ></router-link>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ p_agotarse }}</h3>

            <p>Productos por agotarse</p>
          </div>
          <div class="icon">
            <i class="fas fa-chart-pie"></i>
          </div>
          <router-link to="/inventarios" class="small-box-footer"
            >Más Info <i class="fas fa-arrow-circle-right"></i
          ></router-link>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title">Compras</h5>
            <div class="card-tools">
              <button
                type="button"
                class="btn btn-tool"
                data-card-widget="collapse"
              >
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart">
              <canvas id="compras"> </canvas>
            </div>
          </div>
        </div>
      </div>
      <!-- /.col-md-6 -->
      <div class="col-lg-6">
        <div class="card card-success card-outline">
          <div class="card-header">
            <h5 class="card-title">Ventas</h5>
            <div class="card-tools">
              <button
                type="button"
                class="btn btn-tool"
                data-card-widget="collapse"
              >
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart">
              <canvas id="ventas"> </canvas>
            </div>
          </div>
        </div>
      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
  </div>
</template>
<script>
export default {
  data() {
    return {
      vh_efectivo: 0,
      vh_credito: 0,
      p_agotarse: 0,
      cpp: 0,
      mesesv: [],
      totalv: [],
      mesesc: [],
      totalc: [],
      arrayCompras: [],
      arrayVentas: [],
    };
  },
  methods: {
    convertirMes(mes) {
      const meses = [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ];
      return meses[mes - 1];
    },
    getData() {
      axios.get("/api/dashboard").then((resp) => {
        this.arrayVentas = resp.data.ventas;
        this.arrayCompras = resp.data.compras;
        this.p_agotarse = resp.data.sold_out[0].agotados;
        this.cpp = resp.data.cpp.cpp;
        if (resp.data.vh_efectivo.venta_diaria != null) {
          this.vh_efectivo = resp.data.vh_efectivo;
        } else {
          this.vh_efectivo;
        }
        if (resp.data.vh_credito.venta_diaria != null) {
          this.vh_credito = resp.data.vh_credito;
        } else {
          this.vh_credito;
        }
        this.loadVentas();
      });
    },
    loadVentas() {
      this.arrayVentas.map((data) => {
        this.mesesv.push(this.convertirMes(data.mes));
        this.totalv.push(data.total);
      });
      this.arrayCompras.map((data) => {
        this.mesesc.push(this.convertirMes(data.mes));
        this.totalc.push(data.total);
      });
      var ctx = document.getElementById("ventas");
      var ctc = document.getElementById("compras");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: this.mesesv,
          datasets: [
            {
              label: "Ventas",
              data: this.totalv,
              borderColor: "rgba(54, 162, 235, 0.2)",
              backgroundColor: "rgba(54, 162, 235, 0.2)",
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
      new Chart(ctc, {
        type: "bar",
        data: {
          labels: this.mesesc,
          datasets: [
            {
              label: "Compras",
              data: this.totalc,
              borderColor: "rgba(255, 99, 132, 0.2)",
              backgroundColor: "rgba(255, 99, 132, 0.2)",
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    },
  },
  mounted() {
    //this.cargarInfo();
    this.getData();
  },
};
</script>