<?php
/**
 * Componente StatusAlert - Alertas de operación exitosa o error
 * 
 * Variables requeridas:
 * - $statusQuery: Estado de la query (success, error o null)
 */
?>

<div class="max-w-2xl mx-auto">
    <?php if ($statusQuery === 'success'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded" role="alert">
            <p class="font-bold text-lg">✅ ¡Operación Exitosa!</p>
            <p>El producto se ha guardado correctamente en el sistema. Recarga la página para ver los cambios.</p>
        </div>

    <?php elseif ($statusQuery === 'error'): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded" role="alert">
            <p class="font-bold text-lg">❌ Hubo un error</p>
            <p>Por favor verifica que el nombre, precio y stock sean válidos.</p>
            <?php if (isset($_SESSION['error_message'])): ?>
                <p class="text-sm mt-2"><strong>Detalles:</strong> <?= htmlspecialchars($_SESSION['error_message']) ?></p>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
