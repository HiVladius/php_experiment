<?php
/**
 * Componente Inventory - Lista de productos en inventario
 * 
 * Variables requeridas:
 * - $productos: Array de productos con estructura [nombre, precio, stock]
 */
?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Inventario</h2>

    <?php if (empty($productos)): ?>
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">📦 No hay productos en el inventario</p>
            <p class="text-gray-400 text-sm mt-2">Agrega tu primer producto usando el formulario de abajo</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($productos as $item): ?>
                <div class="flex justify-between items-center p-4 border rounded-md <?= $item['stock'] === 0 ? 'bg-red-50 border-red-200' : 'bg-gray-50' ?>" id="producto-<?= $item['id'] ?>">
                    
                    <div>
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($item['nombre']) ?></p>
                        <p class="text-sm text-gray-500">$<?= number_format($item['precio'], 2) ?></p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div>
                            <?php if ($item['stock'] > 0): ?>
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs" id="stock-<?= $item['id'] ?>">
                                    <?= $item['stock'] ?> en stock
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold" id="stock-<?= $item['id'] ?>">
                                    AGOTADO
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="flex gap-2">
                            <button 
                                class="px-3 py-1 bg-green-100 text-green-700 rounded-md text-xs hover:bg-green-200 transition-colors"
                                onclick="agregarStock(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre']) ?>')">
                                ➕ Agregar
                            </button>

                            <button 
                                class="px-3 py-1 bg-orange-100 text-orange-700 rounded-md text-xs hover:bg-orange-200 transition-colors"
                                onclick="quitarStock(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre']) ?>')"
                                <?= $item['stock'] === 0 ? 'disabled' : '' ?>>
                                ➖ Quitar
                            </button>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<script>
let enviandoRequest = false; // Flag para prevenir dobles envíos

function actualizarEstadoStock(productoId, nuevoStock) {
    const stockElement = document.getElementById('stock-' + productoId);
    const producto = document.getElementById('producto-' + productoId);
    const quitarBtn = producto.querySelector('button[onclick*="quitarStock"]');

    // Actualizar el contenido y estilos del badge de stock
    if (nuevoStock > 0) {
        stockElement.innerHTML = nuevoStock + ' en stock';
        stockElement.className = 'px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs';
        if (quitarBtn) quitarBtn.disabled = false;
        
        // Actualizar estilos del contenedor
        producto.classList.remove('bg-red-50', 'border-red-200');
        producto.classList.add('bg-gray-50');
    } else {
        stockElement.innerHTML = 'AGOTADO';
        stockElement.className = 'px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold';
        if (quitarBtn) quitarBtn.disabled = true;
        
        // Actualizar estilos del contenedor
        producto.classList.remove('bg-gray-50');
        producto.classList.add('bg-red-50', 'border-red-200');
    }
}

async function quitarStock(productoId, nombreProducto) {
    if (enviandoRequest) return; // Prevenir dobles envíos
    
    // Obtener el stock actual del DOM (no del parámetro inicial)
    const stockElement = document.getElementById('stock-' + productoId);
    let stockActual = 0;
    
    // Extraer el número del texto "X en stock" o 0 si está "AGOTADO"
    const stockText = stockElement.textContent;
    if (stockText.includes('AGOTADO')) {
        stockActual = 0;
    } else {
        stockActual = parseInt(stockText.match(/\d+/)[0]);
    }
    
    const cantidad = prompt(`¿Cuántas unidades deseas quitar de "${nombreProducto}"? (Stock disponible: ${stockActual})`, '1');
    
    if (cantidad === null) return; // Usuario canceló
    
    const cantidadNum = parseInt(cantidad);
    
    if (isNaN(cantidadNum) || cantidadNum <= 0) {
        alert('❌ Por favor ingresa un número válido mayor a 0');
        return;
    }

    if (cantidadNum > stockActual) {
        alert(`❌ No puedes quitar ${cantidadNum} unidades. Solo hay ${stockActual} en stock.`);
        return;
    }

    enviandoRequest = true;
    try {
        const response = await fetch('actualizar_stock.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: productoId,
                cantidad: cantidadNum
            })
        });

        const data = await response.json();

        if (data.success) {
            actualizarEstadoStock(productoId, data.nuevoStock);
            alert('✅ Stock actualizado correctamente');
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al actualizar el stock: ' + error.message);
    } finally {
        enviandoRequest = false;
    }
}

async function agregarStock(productoId, nombreProducto) {
    if (enviandoRequest) return; // Prevenir dobles envíos
    
    const cantidad = prompt(`¿Cuántas unidades deseas agregar a "${nombreProducto}"?`, '1');
    
    if (cantidad === null) return; // Usuario canceló
    
    const cantidadNum = parseInt(cantidad);
    
    if (isNaN(cantidadNum) || cantidadNum <= 0) {
        alert('❌ Por favor ingresa un número válido mayor a 0');
        return;
    }

    enviandoRequest = true;
    try {
        const response = await fetch('actualizar_stock.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: productoId,
                cantidad: cantidadNum,
                operacion: 'agregar'
            })
        });

        const data = await response.json();

        if (data.success) {
            actualizarEstadoStock(productoId, data.nuevoStock);
            alert('✅ Stock agregado correctamente');
        } else {
            alert('❌ Error: ' + data.message);
        }
    } catch (error) {
        alert('Error al actualizar el stock: ' + error.message);
    } finally {
        enviandoRequest = false;
    }
}
</script>