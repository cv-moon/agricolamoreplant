<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('auth.login');
})->middleware('guest');

Auth::routes();
Route::middleware('auth')->group(function () {
    // Rutas Solitarias
    // Identificaciones
    Route::get('api/identificaciones', 'IdentificacionController@index');

    // Formas de Pago
    Route::get('api/formas_pago', 'FormaPagoController@index');

    // Rutas de Administración
    // Roles
    Route::get('api/roles', 'RolController@index');

    // Empresa
    Route::get('api/empresa/detalle', 'EmpresaController@detail');
    Route::post('api/empresa/guardar', 'EmpresaController@store');
    Route::put('api/empresa/editar', 'EmpresaController@update');

    // Impuesto
    Route::get('api/impuestos-iva', 'ImpuestoController@index');
    
    // Tarifas IVA
    Route::get('api/tarifas-iva', 'TarifaController@index');
    Route::post('api/tarifa-iva/guardar', 'TarifaController@store');
    Route::put('api/tarifa-iva/editar', 'TarifaController@update');
    Route::get('api/tarifa-iva/detalle', 'TarifaController@detail');
    
    // Impuesto Retención
    Route::get('api/impuestos-retencion', 'ImpuestoRetencionController@index');
    
    // Tarifas Retención
    Route::get('api/tarifas-retencion', 'TarifaRetencionController@index');
    Route::post('api/tarifa-retencion/guardar', 'TarifaRetencionController@store');
    Route::put('api/tarifa-retencion/editar', 'TarifaRetencionController@update');
    Route::get('api/tarifa-retencion/detalle', 'TarifaRetencionController@detail');
    Route::get('api/tarifa-retencion/impuesto', 'TarifaRetencionController@taxes');

    // Establecimientos
    Route::get('api/establecimientos', 'EstablecimientoController@index');
    Route::post('api/establecimiento/guardar', 'EstablecimientoController@store');
    Route::get('api/establecimiento/detalle', 'EstablecimientoController@detail');
    Route::put('api/establecimiento/editar', 'EstablecimientoController@update');
    Route::put('api/establecimiento/activar', 'EstablecimientoController@activate');
    Route::put('api/establecimiento/desactivar', 'EstablecimientoController@deactivate');
    Route::GET('api/establecimiento/activos', 'EstablecimientoController@onlyActive');

    // Empleados
    Route::get('api/empleados', 'EmpleadoController@index');
    Route::post('api/empleado/guardar', 'EmpleadoController@store');
    Route::get('api/empleado/detalle', 'EmpleadoController@detail');
    Route::put('api/empleado/editar', 'EmpleadoController@update');
    Route::put('api/empleado/activar', 'EmpleadoController@activate');
    Route::put('api/empleado/desactivar', 'EmpleadoController@deactivate');

    // Usuarios
    Route::get('api/users', 'UserController@index');

    // Roles de Pago
    Route::get('api/roles_pago', 'RolPagoController@index');
    Route::get('api/roles_pago/datos', 'RolPagoController@getData');
    Route::post('api/roles_pago/guardar', 'RolPagoController@store');
    Route::get('api/roles_pago/detalle', 'RolPagoController@detail');
    Route::put('api/roles_pago/editar', 'RolPagoController@update');
    Route::post('api/roles_pago/pagar', 'RolPagoController@payRol');
    Route::get('api/roles_pago/imprimir/{id}', 'RolPagoController@printRol');

    // Meses
    Route::get('api/meses', 'MesController@index');


    // Puntos de Emisión
    Route::get('api/puntos', 'PuntoEmisionController@index');
    Route::post('api/punto/guardar', 'PuntoEmisionController@store');
    Route::get('api/punto/detalle', 'PuntoEmisionController@detail');
    Route::put('api/punto/editar', 'PuntoEmisionController@update');
    Route::put('api/punto/activar', 'PuntoEmisionController@activate');
    Route::put('api/punto/desactivar', 'PuntoEmisionController@deactivate');
    Route::get('api/punto/valida/nombre', 'PuntoEmisionController@validateName');
    Route::get('api/punto/valida/codigo', 'PuntoEmisionController@validateCode');
    Route::get('api/punto/responsables', 'PuntoEmisionController@responsibles');
    Route::get('api/punto/establecimientos', 'PuntoEmisionController@establishments');

    // Rutas de Almacen
    //Categorias
    Route::get('api/categorias', 'CategoriaController@index');
    Route::post('api/categoria/guardar', 'CategoriaController@store');
    Route::get('api/categoria/detalle', 'CategoriaController@detail');
    Route::put('api/categoria/editar', 'CategoriaController@update');
    Route::get('api/categoria/valida/nombre', 'CategoriaController@validateName');

    // Unidades de Medida
    Route::get('api/unidades', 'UnidadController@index');
    Route::post('api/unidad/guardar', 'UnidadController@store');
    Route::get('api/unidad/detalle', 'UnidadController@detail');
    Route::put('api/unidad/editar', 'UnidadController@update');
    Route::get('api/unidad/valida/nombre', 'UnidadController@validateName');

    // Productos
    Route::get('api/productos', 'ProductoController@index');
    Route::post('api/producto/guardar', 'ProductoController@store');
    Route::get('api/producto/detalle', 'ProductoController@detail');
    Route::put('api/producto/editar', 'ProductoController@update');
    Route::get('api/productos/sin_registro', 'ProductoController@notRegistered');
    Route::get('api/productos/establecimiento', 'ProductoController@productForEstablishment');
    Route::get('api/productos/plantas', 'ProductoController@plants');
    Route::get('api/productos/venta', 'ProductoController@forSale');
    Route::get('api/productos/conteo', 'ProductoController@counter');
    Route::get('api/producto/valida/nombre', 'ProductoController@validateName');

    //Inventarios
    Route::get('api/inventarios', 'InventarioController@index');
    Route::post('api/inventario/guardar', 'InventarioController@store');

    // Kardex
    Route::get('api/kardex/detalle', 'KardexController@detail');

    // Rutas de Compras
    // Proveedores
    Route::get('api/proveedores', 'ProveedorController@index');
    Route::post('api/proveedor/guardar', 'ProveedorController@store');
    Route::get('api/proveedor/detalle', 'ProveedorController@detail');
    Route::put('api/proveedor/editar', 'ProveedorController@update');
    Route::get('api/proveedor/buscar', 'ProveedorController@find');
    
    // Compras
    Route::get('api/compras', 'CompraController@index');
    Route::post('api/compra/guardar', 'CompraController@store');
    Route::put('api/compra/anular', 'CompraController@cancel');
    Route::get('api/compra/detalle', 'CompraController@detail');
    Route::get('api/compra/buscar', 'CompraController@find');

    // Cuentas Por Pagar - Deudas
    Route::get('api/deudas', 'DeudaController@index');
    Route::get('api/deuda/detalle', 'DeudaController@detail');
    Route::post('api/deuda/abonar', 'DeudaController@payment');

    // Rutas de Producción
    // Plantas
    Route::get('api/producciones', 'ProduccionController@index');
    Route::post('api/produccion/guardar', 'ProduccionController@store');
    Route::get('api/produccion/detalle', 'ProduccionController@detail');
    Route::put('api/produccion/editar', 'ProduccionController@update');
    Route::put('api/produccion/finalizar', 'ProduccionController@finished');

    // Rutas de Ventas
    // Clientes
    Route::get('api/clientes', 'ClienteController@index');
    Route::post('api/cliente/guardar', 'ClienteController@store');
    Route::get('api/cliente/detalle', 'ClienteController@detail');
    Route::put('api/cliente/editar', 'ClienteController@update');
    Route::get('api/cliente/buscar', 'ClienteController@find');
    
    // Factura
    Route::get('api/facturas', 'FacturaController@index');
    Route::post('api/factura/guardar', 'FacturaController@store');
    Route::get('api/factura/detalle', 'FacturaController@detail');
    Route::get('api/factura/comprobante', 'FacturaController@getInvoice');
    Route::get('api/factura/pdf/{clave}', 'FacturaController@individualPdf');
    Route::get('api/factura/xml/{clave}', 'FacturaController@individualXml');
    Route::post('api/factura/reenvio', 'FacturaController@forwardingInvoice');
    
    // Cuentas por Cobrar - Créditos
    Route::get('api/creditos', 'CreditoController@index');
    Route::get('api/credito/detalle', 'CreditoController@detail');
    Route::post('api/credito/abonar', 'CreditoController@payment');
    
    // Facturación - Proceso
    Route::post('api/leerFacturaphp', 'FacturacionController@leerFactura');
    Route::post('api/firmaphp', 'FacturacionController@firmaphp');
    Route::post('api/validarComprobantephp', 'FacturacionController@validarComprobantephp');
    Route::post('api/autorizacionComprobantephp', 'FacturacionController@autorizacionComprobantephp');
    Route::post('api/validarFechaCertificadophp', 'FacturacionController@validarFechaCertificadophp');
    Route::post('api/respfactura', 'FacturacionController@respfactura');
    
    // Rutas de XML
    Route::post('api/factura/xml_factura', 'XmlController@efactura');
    Route::post('api/factura/xml_guia', 'XmlController@e_guia');
    Route::post('api/factura/xml_nota_credito', 'XmlController@enotacredito');
    Route::post('api/factura/xml_nota_debito', 'XmlController@enotadebito');
    Route::post('api/factura/xml_retencion', 'XmlController@eRetencion');
    
    // Rutas de Arqueos - "Cajas"
    Route::get('api/arqueos', 'ArqueoController@index');
    Route::post('api/arqueo/guardar', 'ArqueoController@store');
    Route::put('api/arqueo/cerrar', 'ArqueoController@update');
    Route::get('api/arqueo/detalle', 'ArqueoController@detail');
    Route::get('api/arqueo/validar', 'ArqueoController@validateToday');
    Route::get('api/arqueo/datos', 'ArqueoController@getArqueo');
    Route::get('api/arqueo/reporte/{id}', 'ArqueoController@printArqueo');
    
    // Rutas Egresos
    Route::get('api/egresos', 'EgresoController@index');
    Route::post('api/egreso/guardar', 'EgresoController@store');
    Route::get('api/egreso/eliminar/{id}', 'EgresoController@drop');
    
    // Rutas de Guías
    // Transportistas
    Route::get('api/transportistas', 'TransportistaController@index');
    Route::post('api/transportista/guardar', 'TransportistaController@store');
    Route::get('api/transportista/detalle', 'TransportistaController@detail');
    Route::put('api/transportista/editar', 'TransportistaController@update');
    Route::get('api/transportista/buscar', 'TransportistaController@find');
    
    // Guías de Remisión
    Route::get('api/guias', 'GuiaController@index');
    Route::post('api/guia/guardar', 'GuiaController@store');
    Route::get('api/guia/detalle', 'GuiaController@detail');
    Route::put('api/guia/editar', 'GuiaController@update');
    Route::get('api/guia/buscar', 'GuiaController@find');
    Route::get('api/guia/comprobante', 'GuiaController@getGuide');
    Route::get('api/guia/facturas', 'GuiaController@getInvoices');
    Route::get('api/guia/detalles', 'GuiaController@getDetails');
    
    // Rutas de Retenciones
    // Retención
    Route::get('api/retenciones', 'RetencionController@index');
    Route::post('api/retencion/guardar', 'RetencionController@store');
    Route::get('api/retencion/detalle', 'RetencionController@detail');
    Route::put('api/retencion/editar', 'RetencionController@update');
    Route::get('api/retencion/buscar', 'RetencionController@find');
    Route::get('api/retencion/comprobante', 'RetencionController@getRetention');
    Route::get('api/retencion/facturas', 'RetencionController@getInvoices');
    Route::get('api/retencion/detalles', 'RetencionController@getDetails');
    Route::get('api/retencion/pdf/{clave}', 'RetencionController@individualPdf');
    Route::get('api/retencion/xml/{clave}', 'RetencionController@individualXml');
    
    // Ruta Dashboard
    Route::get('api/dashboard', 'DashboardController');
    Route::get('api/dashboard/datos', 'DashboardController@index');
    
    // Rutas de Reportes
    Route::get('api/reporte/ventas', 'ReporteController@reportSales');
    Route::get('api/reporte/compras', 'ReporteController@reportPurchases');

    // Ruta para aceptar rutas vue (siempre al final)
    Route::get('/{any}', 'HomeController')->where('any', '.*');
});
