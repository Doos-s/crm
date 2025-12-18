<?php
session_start();

if (file_exists("funciones.php")) {
    include_once "funciones.php";
}

$fechaInicio = isset($_POST["inicio"]) ? $_POST["inicio"] : date("Y-m-01");
$fechaFin = isset($_POST["fin"]) ? $_POST["fin"] : date("Y-m-d");

$ventas = [];
if (function_exists('obtenerVentasPorRango')) {
    $ventas = obtenerVentasPorRango($fechaInicio, $fechaFin);
}

if (empty($ventas)) {
    $obj1 = new stdClass(); $obj1->fecha = date("Y-m-d"); $obj1->nombre = "CLIENTE ACTUAL (2025)"; $obj1->monto = 150.00;
    $anioPasado = date("Y") - 1;
    $obj2 = new stdClass(); $obj2->fecha = "$anioPasado-05-15"; $obj2->nombre = "CLIENTE AÑO PASADO ($anioPasado)"; $obj2->monto = 500.00;
    $haceDosAnios = date("Y") - 2;
    $obj3 = new stdClass(); $obj3->fecha = "$haceDosAnios-10-20"; $obj3->nombre = "CLIENTE ANTIGUO ($haceDosAnios)"; $obj3->monto = 220.00;
    $ventas = [$obj1, $obj2, $obj3];
    $mensaje_prueba = "⚠ MODO PRUEBA: Datos ficticios.";
}

$totalVendido = 0;
foreach ($ventas as $venta) {
    $totalVendido += $venta->monto;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Roboto', sans-serif; background-color: #fafaf9; display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: linear-gradient(180deg, #7c2d12 0%, #991b1b 100%); color: white; padding: 30px 0; position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; }
        .main-content { margin-left: 260px; flex: 1; padding: 30px; background: linear-gradient(135deg, #fafaf9 0%, #f5f5f4 100%); width: calc(100% - 260px); }
        
        .sidebar-logo { padding: 0 25px 30px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(251, 191, 36, 0.2); margin-bottom: 20px; }
        .sidebar-logo-icon { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; padding: 5px; }
        .sidebar-logo-icon img { width: 100%; height: 100%; object-fit: contain; border-radius: 50%; }
        .sidebar-logo h2 { font-size: 24px; color: #fbbf24; margin: 0; font-weight: 700; }
        
        .sidebar-menu { list-style: none; padding: 0 15px; }
        .sidebar-menu li { margin-bottom: 5px; }
        .sidebar-menu a { display: flex; align-items: center; padding: 12px 15px; color: rgba(254, 243, 199, 0.7); text-decoration: none; border-radius: 8px; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background-color: rgba(251, 191, 36, 0.15); color: #fef3c7; }
        .sidebar-menu i { margin-right: 10px; width: 20px; text-align: center; }

        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-header h1 { font-size: 32px; color: #7c2d12; font-weight: 700; margin: 0; }
        
        .content-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-top: 4px solid #dc2626; margin-bottom: 30px; }
        .btn-search { background: #dc2626; color: white; border: none; padding: 10px 20px; border-radius: 5px; width: 100%; }
        
        .btn-print { background: #444; color: white; border: none; padding: 10px 20px; border-radius: 5px; }
        .btn-export { background: #15803d; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-left: 10px; }
        .btn-export:hover { background: #166534; color: white; }

        .table-custom thead th { background-color: #7c2d12; color: white; }
        .table-custom tfoot tr { background-color: #44403c; color: white; }
        
        .modal-header, .btn-dynamic { transition: background-color 0.3s ease, border-color 0.3s ease; }

        @media print {
            .no-print, .sidebar, .btn-search, .btn-print, .btn-export, form, .modal { display: none !important; }
            body { background-color: white !important; display: block !important; }
            .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; background: white !important; }
            .content-card { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; }
            .d-none { display: none !important; } 
        }
    </style>
</head>
<body>

    <aside class="sidebar no-print">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="img/logocrm.png" alt="Logo" onerror="this.style.display='none'">
            </div>
            <h2>Reportes</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
            <li><a href="ventas.php"><i class="fas fa-shopping-cart"></i> <span>Ventas</span></a></li>
            <li><a href="reportes.php" class="active"><i class="fas fa-chart-bar"></i> <span>Reportes</span></a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <div>
                <h1><i class="fas fa-file-invoice-dollar" style="color: #a31621;"></i> Reporte de Ventas</h1>
                <?php if(isset($mensaje_prueba)) { echo "<small style='color:orange;'>$mensaje_prueba</small>"; } ?>
            </div>
            <div class="no-print">
                <i class="fas fa-bell fa-lg text-secondary me-3"></i>
                <i class="fas fa-user-circle fa-lg text-secondary"></i>
            </div>
        </div>

        <div class="content-card no-print">
            <form method="POST" action="reportes.php">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label>Fecha Inicio</label>
                        <input type="date" name="inicio" value="<?php echo $fechaInicio ?>" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Fecha Fin</label>
                        <input type="date" name="fin" value="<?php echo $fechaFin ?>" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (count($ventas) > 0) { ?>
        <div class="content-card">
            <h4 class="mb-4 text-center" style="color: #7c2d12; font-weight: bold;">
                Detalle de Transacciones
            </h4>
            
            <div class="table-responsive">
                <table class="table table-bordered table-custom" id="tablaVentas">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody id="tablaVentasBody">
                        <?php foreach ($ventas as $venta) { ?>
                            <tr>
                                <td><?php echo date("d/m/Y", strtotime($venta->fecha)); ?></td>
                                <td><?php echo $venta->nombre; ?></td>
                                <td>$<?php echo number_format($venta->monto, 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end py-3"><strong>TOTAL ACUMULADO:</strong></td>
                            <td class="py-3"><strong>$<?php echo number_format($totalVendido, 2); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="text-end mt-3 no-print">
                <button type="button" class="btn btn-print" data-bs-toggle="modal" data-bs-target="#modalImprimir">
                    <i class="fa fa-print"></i> IMPRIMIR
                </button>
                
                <button type="button" class="btn btn-export" data-bs-toggle="modal" data-bs-target="#modalExportar">
                    <i class="fas fa-file-export"></i> EXPORTAR DATOS
                </button>
            </div>
        </div>
        <?php } ?>

    </main>

    <div class="modal fade no-print" id="modalImprimir" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #444; color: white;">
            <h5 class="modal-title"><i class="fas fa-print"></i> Imprimir Reporte</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body text-center">
            <p class="mb-4">Seleccione el periodo a imprimir:</p>
            <div class="d-grid gap-2">
                <button onclick="filtrarYImprimir('todo')" class="btn btn-secondary py-2">Todo lo visible</button>
                <button onclick="filtrarYImprimir('mes')" class="btn btn-danger py-2">Solo Mes Actual</button>
                <button onclick="filtrarYImprimir('anio')" class="btn btn-outline-danger py-2">Solo Año Actual</button>
                <button onclick="filtrarYImprimir('anio_pasado')" class="btn btn-outline-secondary py-2">Solo Año Pasado</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade no-print" id="modalExportar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" id="headerExportar" style="background-color: #15803d; color: white;">
            <h5 class="modal-title">
                <i id="iconExportar" class="fas fa-file-excel"></i> Exportar Datos
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            
            <div class="mb-4">
                <label class="form-label fw-bold">1. Elija el formato:</label>
                <select id="formatoExportacion" class="form-select" onchange="cambiarColorExportacion()">
                    <option value="xlsx">Excel (.xlsx)</option>
                    <option value="csv">Hoja de cálculo (.csv)</option>
                    <option value="doc">Word (.doc)</option>
                </select>
            </div>

            <label class="form-label fw-bold text-center d-block">2. Elija qué datos descargar:</label>
            <div class="d-grid gap-2">
                <button onclick="exportarReporte('todo')" class="btn btn-success py-2 btn-dynamic">
                    <i class="fas fa-download"></i> Todo lo visible
                </button>
                
                <button onclick="exportarReporte('mes')" class="btn btn-success py-2 btn-dynamic" style="opacity: 0.9;">
                    <i class="fas fa-calendar-alt"></i> Mes Actual
                </button>
                
                <button onclick="exportarReporte('anio')" class="btn btn-outline-success py-2 btn-dynamic-outline">
                    <i class="fas fa-calendar"></i> Año Actual
                </button>

                <button onclick="exportarReporte('anio_pasado')" class="btn btn-outline-secondary py-2">
                    <i class="fas fa-history"></i> Año Pasado
                </button>
            </div>

          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <script>
    function cambiarColorExportacion() {
        const formato = document.getElementById('formatoExportacion').value;
        const header = document.getElementById('headerExportar');
        const icon = document.getElementById('iconExportar');
        const botonesSolidos = document.querySelectorAll('.btn-dynamic'); 
        const botonesBorde = document.querySelectorAll('.btn-dynamic-outline'); 
        
        let colorFondo = '';
        let claseIcono = '';

        if (formato === 'xlsx') {
            colorFondo = '#15803d'; 
            claseIcono = 'fa-file-excel';
        } else if (formato === 'csv') {
            colorFondo = '#f97316'; 
            claseIcono = 'fa-file-csv';
        } else if (formato === 'doc') {
            colorFondo = '#0891b2'; 
            claseIcono = 'fa-file-word';
        }

        header.style.backgroundColor = colorFondo;
        icon.className = `fas ${claseIcono}`;

        botonesSolidos.forEach(btn => {
            btn.style.backgroundColor = colorFondo;
            btn.style.borderColor = colorFondo;
            btn.style.color = 'white'; 
        });

        botonesBorde.forEach(btn => {
            btn.style.color = colorFondo;
            btn.style.borderColor = colorFondo;
            btn.onmouseover = function() { 
                this.style.backgroundColor = colorFondo; 
                this.style.color = 'white'; 
            };
            btn.onmouseout = function() { 
                this.style.backgroundColor = 'transparent'; 
                this.style.color = colorFondo; 
            };
        });
    }

    function filtrarYImprimir(periodo) {
        var myModalEl = document.getElementById('modalImprimir');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();

        const filas = document.querySelectorAll('#tablaVentasBody tr');
        const hoy = new Date();
        const anioActual = hoy.getFullYear();
        let filasOcultas = [];

        filas.forEach(fila => {
            let textoFecha = fila.cells[0].innerText.trim(); 
            let partes = textoFecha.split('/');
            let fechaVenta = new Date(partes[2], partes[1] - 1, partes[0]);
            let anioVenta = fechaVenta.getFullYear();
            let mostrar = true;

            if (periodo === 'mes') {
                if (fechaVenta.getMonth() !== hoy.getMonth() || anioVenta !== anioActual) mostrar = false;
            } else if (periodo === 'anio') {
                if (anioVenta !== anioActual) mostrar = false;
            } else if (periodo === 'anio_pasado') {
                if (anioVenta !== (anioActual - 1)) mostrar = false;
            }

            if (!mostrar) {
                fila.classList.add('d-none');
                filasOcultas.push(fila);
            }
        });

        setTimeout(() => { window.print(); }, 500);
        window.onafterprint = function() { filasOcultas.forEach(fila => fila.classList.remove('d-none')); };
        setTimeout(() => { filasOcultas.forEach(fila => fila.classList.remove('d-none')); }, 3000);
    }

    function exportarReporte(periodo) {
        const formato = document.getElementById('formatoExportacion').value;
        var myModalEl = document.getElementById('modalExportar');
        var modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();

        const filas = document.querySelectorAll('#tablaVentasBody tr');
        const hoy = new Date();
        const anioActual = hoy.getFullYear();
        
        let datosExportar = [["Fecha", "Cliente", "Monto"]]; 
        let total = 0;

        filas.forEach(fila => {
            let textoFecha = fila.cells[0].innerText.trim();
            let cliente = fila.cells[1].innerText.trim();
            let textoMonto = fila.cells[2].innerText.replace('$', '').replace(',', '').trim();
            let monto = parseFloat(textoMonto);

            let partes = textoFecha.split('/');
            let fechaVenta = new Date(partes[2], partes[1] - 1, partes[0]);
            let anioVenta = fechaVenta.getFullYear();
            let incluir = true;

            if (periodo === 'mes') {
                if (fechaVenta.getMonth() !== hoy.getMonth() || anioVenta !== anioActual) incluir = false;
            } else if (periodo === 'anio') {
                if (anioVenta !== anioActual) incluir = false;
            } else if (periodo === 'anio_pasado') {
                if (anioVenta !== (anioActual - 1)) incluir = false;
            }

            if (incluir) {
                datosExportar.push([textoFecha, cliente, monto]);
                total += monto;
            }
        });

        datosExportar.push(["", "TOTAL ACUMULADO", total]);

        if (formato === 'xlsx' || formato === 'csv') {
            let wb = XLSX.utils.book_new();
            let ws = XLSX.utils.aoa_to_sheet(datosExportar);
            XLSX.utils.book_append_sheet(wb, ws, "Reporte");
            let extension = formato === 'xlsx' ? 'xlsx' : 'csv';
            XLSX.writeFile(wb, `Reporte_Ventas_${periodo}.${extension}`);
        } else if (formato === 'doc') {
            let htmlContent = `
                <h2 style="color:#7c2d12;">Reporte de Ventas (${periodo})</h2>
                <table border="1" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr style="background-color: #7c2d12; color: white;">
                            <th>Fecha</th><th>Cliente</th><th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>`;
            for(let i=1; i < datosExportar.length; i++) {
                let row = datosExportar[i];
                let style = (i === datosExportar.length -1) ? "font-weight:bold; background:#eee;" : "";
                htmlContent += `<tr style="${style}"><td>${row[0]}</td><td>${row[1]}</td><td>${typeof row[2] === 'number' ? '$'+row[2].toFixed(2) : row[2]}</td></tr>`;
            }
            htmlContent += `</tbody></table>`;
            var blob = new Blob(['\ufeff', htmlContent], { type: 'application/msword' });
            saveAs(blob, `Reporte_Ventas_${periodo}.doc`);
        }
    }
    </script>

</body>
</html>