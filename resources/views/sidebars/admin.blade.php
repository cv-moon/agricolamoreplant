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
                            <router-link to="/productos" class="nav-link">
                                <i class="fab fa-product-hunt nav-icon"></i>
                                <p>Productos</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/categorias" class="nav-link">
                                <i class="fas fa-layer-group nav-icon"></i>
                                <p>Categorias</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/inventarios" class="nav-link">
                                <i class="fas fa-boxes nav-icon"></i>
                                <p>Inventario</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/unidades" class="nav-link">
                                <i class="fas fa-balance-scale-left nav-icon"></i>
                                <p>Uni. de Medida</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>
                            Compras
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/compras" class="nav-link">
                                <i class="fas fa-shopping-cart nav-icon"></i>
                                <p>Compras</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/proveedores" class="nav-link">
                                <i class="fas fa-address-book nav-icon"></i>
                                <p>Proveedores</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/deudas" class="nav-link">
                                <i class="fas fa-money-check-alt nav-icon"></i>
                                <p>Cuentas por Pagar</p>
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
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-truck-moving"></i>
                        <p>
                            Guías
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/guias" class="nav-link">
                                <i class="fas fa-truck-loading nav-icon"></i>
                                <p>Guías de Remisión</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/transportistas" class="nav-link">
                                <i class="fas fa-truck nav-icon"></i>
                                <p>Transportistas</p>
                            </router-link>
                        </li>
                    </ul>
                </li> -->
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
                        <i class="nav-icon fas fa-industry"></i>
                        <p>
                            Producción
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/producciones" class="nav-link">
                                <i class="fas fa-seedling nav-icon"></i>
                                <p>Plantas</p>
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
                            <router-link to="/reporte/ventas" class="nav-link">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>Ventas</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/reporte/compras" class="nav-link">
                                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                <p>Compras</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>
                            Administración
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/establecimientos" class="nav-link">
                                <i class="fas fa-store nav-icon"></i>
                                <p>Establecimientos</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/puntos-emision" class="nav-link">
                                <i class="fas fa-cash-register nav-icon"></i>
                                <p>Punto de Emisión</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/roles-pago" class="nav-link">
                                <i class="fas fa-handshake nav-icon"></i>
                                <p>Roles de Pago</p>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tools"></i>
                        <p>
                            Generalidades
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <router-link to="/empleados" class="nav-link">
                                <i class="fas fa-users nav-icon"></i>
                                <p>Empleados</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/empresa" class="nav-link">
                                <i class="fas fa-chess-rook nav-icon"></i>
                                <p>Empresa</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/roles" class="nav-link">
                                <i class="fas fa-wrench nav-icon"></i>
                                <p>Roles de Usuario</p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/impuestos" class="nav-link">
                                <i class="fas fa-percentage nav-icon"></i>
                                <p>Impuestos</p>
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