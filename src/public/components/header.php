<?php
/**
 * Componente Header - Encabezado con selector de estado
 * 
 * Variables requeridas:
 * - $userName: Nombre del usuario
 * - $status: Estado actual del usuario
 * - $estadosDisponibles: Array de estados disponibles
 */
?>

<header class="flex justify-between items-center border-b pb-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Panel de Control</h1>
    
    <div class="flex items-center gap-2">
        <span class="font-medium"><?= htmlspecialchars($userName) ?></span>
        <span class="status-dot <?= $status ?>"></span>
        
        <form method="POST" class="ml-4">
            <select 
                name="nuevo_estado" 
                onchange="this.form.submit()"
                class="px-3 py-1 bg-white text-black rounded hover:bg-gray-200 text-sm cursor-pointer"
            >
                <?php foreach ($estadosDisponibles as $estado => $etiqueta): ?>
                    <option value="<?= $estado ?>" <?= $status === $estado ? 'selected' : '' ?>>
                        <?= htmlspecialchars($etiqueta) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</header>
