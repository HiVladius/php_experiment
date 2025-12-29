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
                <div class="flex justify-between items-center p-4 border rounded-md <?= $item['stock'] === 0 ? 'bg-red-50 border-red-200' : 'bg-gray-50' ?>">
                    
                    <div>
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($item['nombre']) ?></p>
                        <p class="text-sm text-gray-500">$<?= number_format($item['precio'], 2) ?></p>
                    </div>

                    <div>
                        <?php if ($item['stock'] > 0): ?>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                <?= $item['stock'] ?> en stock
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                AGOTADO
                            </span>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
