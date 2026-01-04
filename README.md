ph# Mi Tienda - Sistema de Gestión de Inventario

Un panel de control para gestión de inventario desarrollado con **PHP puro** y **MySQL**, con arquitectura MVC y manejo de variables de entorno.

## 📋 Descripción del Proyecto

Este proyecto fue creado para demostrar buenas prácticas en desarrollo PHP, incluyendo:

- **Arquitectura MVC**: Separación clara entre lógica, vistas y datos
- **Gestión de Dependencias**: Uso de Composer con autoload PSR-4
- **Variables de Entorno**: Configuración segura con phpdotenv
- **Base de Datos**: MySQL con PDO
- **Componentes Reutilizables**: Sistema de includes PHP modular
- **Estilos Modernos**: Tailwind CSS para interfaz responsiva

## 🚀 Instalación y Configuración

### Requisitos Previos

- **PHP** 7.4 o superior
- **MySQL** o **MariaDB**
- **Composer** instalado

### Paso 1: Clonar o Descargar el Proyecto

```bash
git clone <tu-repositorio>
cd php_experiment
```

### Paso 2: Instalar Dependencias de PHP

```bash
composer install
```

Esto instalará automáticamente:
- `vlucas/phpdotenv`: Para cargar variables de entorno

### Paso 3: Configurar la Base de Datos

#### 3.1. Asegúrate de que MySQL está corriendo

```bash
# En Linux/Mac
sudo systemctl start mysql
# o
sudo service mysql start

# En Windows, abre Services y busca MySQL
```

#### 3.2. Crear usuario para desarrollo (Recomendado)

```bash
sudo mysql -e "CREATE USER IF NOT EXISTS 'dev'@'localhost' IDENTIFIED BY ''; GRANT ALL PRIVILEGES ON *.* TO 'dev'@'localhost'; FLUSH PRIVILEGES;"
```

#### 3.3. Importar la estructura de BD

```bash
mysql -h 127.0.0.1 -u dev < src/table/database.sql
```

**Nota:** Se usa `127.0.0.1` (TCP) en lugar de `localhost` para evitar problemas con socket.

Esto creará:
- Base de datos: `mi_tienda`
- Tabla: `productos` (id, nombre, precio, stock, creado_en)

### Paso 4: Configurar Variables de Entorno

Crea o actualiza el archivo `.env` en la **raíz del proyecto**:

```env
DB_HOST=127.0.0.1
DB_USER=dev
DB_PASS=
DB_NAME=mi_tienda
```

**Nota:** 
- `DB_HOST`: Usa `127.0.0.1` (TCP) en lugar de `localhost` para evitar problemas de socket
- `DB_USER`: `dev` (usuario creado en paso 3.2)
- `DB_PASS`: Déjalo vacío (sin contraseña para desarrollo)
- `DB_NAME`: `mi_tienda` (base de datos)

### Paso 5: Iniciar el Servidor Local

#### Opción A: Con PHP Built-in Server (Recomendado para desarrollo)

```bash
php -S localhost:8000 -t src/public/
```

Luego accede a: **http://localhost:8000**

#### Opción B: Con Apache (Si lo tienes configurado)

Configura un VirtualHost que apunte a la carpeta `src/public/`

## 📁 Estructura del Proyecto

```
php_experiment/
├── src/
│   ├── Config/
│   │   └── Database.php          # Clase de conexión a BD (PDO)
│   ├── Models/
│   │   ├── Articulo.php          # Modelo de artículos
│   │   └── StatusManager.php     # Gestor de estados del usuario
│   ├── Views/
│   │   └── header.php            # Vistas compartidas
│   ├── public/
│   │   ├── index.php             # Página principal (entrada)
│   │   ├── procesar.php          # Procesamiento de formularios
│   │   ├── components/           # Componentes reutilizables
│   │   │   ├── header.php
│   │   │   ├── inventory.php     # Tabla de productos
│   │   │   ├── product-form.php  # Formulario agregar producto
│   │   │   └── status-alert.php
│   │   ├── config/               # Configuraciones front
│   │   │   ├── products.php
│   │   │   └── states.php
│   │   └── styles/
│   │       └── main.css          # Estilos personalizados
│   └── table/
│       └── database.sql          # Script de BD
├── vendor/                        # Dependencias (generado por Composer)
├── .env                           # Variables de entorno (crear)
├── .gitignore                     # Archivos ignorados por git
├── composer.json                  # Dependencias y autoload
└── README.md                      # Este archivo
```

## 🔧 Solución de Problemas Comunes

### Error: "Can't connect to local MySQL server"

**Solución 1:** MySQL no está corriendo.

```bash
# Verificar estado
sudo systemctl status mysql

# Iniciar MySQL
sudo systemctl start mysql

# Para Ubuntu/Debian, si usas MariaDB:
sudo systemctl start mariadb
```

**Solución 2:** Problema de socket - Usa TCP en lugar de socket

```bash
# En lugar de:
mysql -u dev < src/table/database.sql

# Usa:
mysql -h 127.0.0.1 -u dev < src/table/database.sql
```

**Solución 3:** Asegúrate de que el usuario `dev` existe

```bash
sudo mysql -e "SELECT user FROM mysql.user;"
```

### Error: "Class 'Dotenv\Dotenv' not found"

**Solución:** Las dependencias no están instaladas.

```bash
composer install
```

### Error: "Database 'mi_tienda' doesn't exist"

**Solución:** Importar el script SQL no funcionó correctamente.

```bash
# Intenta con más detalles
mysql -u root -p -v < src/table/database.sql

# O importar manualmente en MySQL
mysql -u root -p
mysql> CREATE DATABASE IF NOT EXISTS mi_tienda;
mysql> USE mi_tienda;
mysql> source src/table/database.sql;
```

### PHP Report: "Port 8000 is already in use"

**Solución:** Usa otro puerto.

```bash
php -S localhost:8001 -t src/public/
```

## 📚 Tecnologías Utilizadas

| Tecnología | Versión | Uso |
|------------|---------|-----|
| PHP | 7.4+ | Backend |
| MySQL | 5.7+ | Base de datos |
| Composer | Última | Gestor de dependencias |
| Tailwind CSS | 3.x | Estilos frontend |
| PDO | Nativa | ORM/Conexión BD |

## 🎯 Características Principales

✅ Gestión de productos (CRUD completo)
✅ Sistema de estados de usuario (Online/Offline)
✅ Interfaz responsiva
✅ Manejo seguro de conexiones BD
✅ Variables de entorno para configuración
✅ Autoload automático con Composer

## 👨‍💻 Notas de Desarrollo

### Agregar Nueva Dependencia

```bash
composer require vendor/package
```

### Actualizar Dependencias

```bash
composer update
```

### Estructura de Componentes

Los componentes en `src/public/components/` son archivos PHP reutilizables que se incluyen en la vista principal. Cada componente maneja su propia lógica de presentación.

### Variables de Sesión

El proyecto usa `$_SESSION` para mantener el estado del usuario (ej: estado online/offline) entre peticiones.

## 📝 Licencia

Proyecto educativo de demostración.

## 📧 Contacto

**Autor:** Vladimir  
**Última actualización:** 4 de enero de 2026

---

¿Necesitas ayuda? Revisa los pasos de instalación en orden y ejecuta los comandos tal como se muestran.
