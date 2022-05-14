<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary bg-navy elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link text-sm bg-primary text-center">
        <b>AGRICOLA MOREPLANT</b>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Escritorio</p>
                    </router-link>
                </li>
                <li class="nav-header">MENÚ</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Almacen
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/inventarios" class="nav-link">
                                <i class="fas fa-boxes nav-icon"></i>
                                <p>Inventario</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Ventas
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/facturacion" class="nav-link">
                                <i class="fas fa-dollar-sign nav-icon"></i>
                                <p>Facturación</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/odt" class="nav-link">
                                <i class="fas fa-file-signature nav-icon"></i>
                                <p>Orden de Trabajo</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/clientes" class="nav-link">
                                <i class="far fa-address-book nav-icon"></i>
                                <p>Clientes</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/creditos" class="nav-link">
                                <i class="fas fa-hand-holding-usd nav-icon"></i>
                                <p>Cuentas por Cobrar</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Caja
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/arqueos/abrir" class="nav-link">
                                <i class="fas fa-lock-open nav-icon"></i>
                                <p>Apertura</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/arqueos/cerrar" class="nav-link">
                                <i class="fas fa-lock nav-icon"></i>
                                <p>Cierre</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/arqueos" class="nav-link">
                                <i class="fas fa-book-open nav-icon"></i>
                                <p>Arqueos</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book-reader"></i>
                        <p>
                            Reportes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/reporte-ventas" class="nav-link">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>Ventas</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>