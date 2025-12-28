<?php

namespace App\Models;

/**
 * Clase StatusManager - Maneja la lógica de estados del usuario
 * Controla las transiciones de estado y validaciones
 */
class StatusManager
{
    /**
     * Estados válidos disponibles en el sistema
     */
    private array $validStates = [
        'online' => 'En línea',
        'offline' => 'Desconectado',
        'away' => 'Ausente',
        'getOut' => 'Afuera'
    ];

    /**
     * Estado actual del usuario
     */
    private string $currentStatus = 'getOut';

    /**
     * Constructor
     * 
     * @param string $initialStatus Estado inicial del usuario
     */
    public function __construct(string $initialStatus = 'getOut')
    {
        if ($this->isValidStatus($initialStatus)) {
            $this->currentStatus = $initialStatus;
        }
    }

    /**
     * Obtiene el estado actual del usuario
     * 
     * @return string Estado actual
     */
    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    /**
     * Establece un nuevo estado
     * 
     * @param string $newStatus Nuevo estado a establecer
     * @return bool true si el estado fue cambiado, false si es inválido
     */
    public function setStatus(string $newStatus): bool
    {
        if ($this->isValidStatus($newStatus)) {
            $this->currentStatus = $newStatus;
            return true;
        }
        return false;
    }

    /**
     * Valida si un estado es válido
     * 
     * @param string $status Estado a validar
     * @return bool true si el estado es válido
     */
    public function isValidStatus(string $status): bool
    {
        return array_key_exists($status, $this->validStates);
    }

    /**
     * Obtiene todos los estados disponibles
     * 
     * @return array Array con estados y sus etiquetas
     */
    public function getAvailableStates(): array
    {
        return $this->validStates;
    }

    /**
     * Obtiene la etiqueta de un estado específico
     * 
     * @param string $status Estado a consultar
     * @return string|null Etiqueta del estado o null si no existe
     */
    public function getStatusLabel(string $status): ?string
    {
        return $this->validStates[$status] ?? null;
    }

    /**
     * Obtiene la etiqueta del estado actual
     * 
     * @return string Etiqueta del estado actual
     */
    public function getCurrentStatusLabel(): string
    {
        return $this->validStates[$this->currentStatus];
    }

    /**
     * Verifica si el usuario está en línea
     * 
     * @return bool true si el estado es 'online'
     */
    public function isOnline(): bool
    {
        return $this->currentStatus === 'online';
    }

    /**
     * Verifica si el usuario está desconectado
     * 
     * @return bool true si el estado es 'offline'
     */
    public function isOffline(): bool
    {
        return $this->currentStatus === 'offline';
    }

    /**
     * Verifica si el usuario está ausente
     * 
     * @return bool true si el estado es 'away'
     */
    public function isAway(): bool
    {
        return $this->currentStatus === 'away';
    }
}
