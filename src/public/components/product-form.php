<?php
/**
 * Componente ProductForm - Formulario para agregar nuevos productos
 */
?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-xl font-bold mb-4 text-gray-700">Agregar Nuevo Producto</h2>
    
    <form action="procesar.php" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
            <input type="text" name="nombre" required 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Precio ($)</label>
                <input type="number" name="precio" step="0.01" required 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stock Inicial</label>
                <input type="number" name="stock" required 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">
            </div>
        </div>

        <button type="submit" 
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors">
            Guardar Producto
        </button>
    </form>
</div>
