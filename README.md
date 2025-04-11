# Sistema de Gestión de Hojas de Vida de Equipos - Emcaservicios

<p align="center">
  <img src="public/imgs/Emcaservicios.png" alt="Logo Emcaservicios" width="200">
</p>

## Descripción del Proyecto

El Sistema de Gestión de Hojas de Vida de Equipos para Emcaservicios es una aplicación web diseñada para administrar y mantener un registro detallado de los equipos informáticos y tecnológicos de la empresa. Esta plataforma permite llevar un control completo sobre el ciclo de vida de los equipos, desde su adquisición hasta su mantenimiento y eventual retiro.

## Objetivos

- Centralizar la información de todos los equipos tecnológicos de la empresa
- Facilitar el seguimiento y control del estado de los equipos
- Registrar y programar mantenimientos preventivos y correctivos
- Generar reportes detallados sobre el estado de los equipos
- Optimizar los procesos de gestión de activos tecnológicos
- Mejorar la toma de decisiones basada en datos históricos de los equipos

## Características Principales

### Gestión de Equipos
- Registro detallado de equipos (computadores, impresoras, UPS, escáneres, telefonía, etc.)
- Categorización por áreas y tipos
- Seguimiento de información técnica (especificaciones, números de serie, fechas de adquisición)
- Control de garantías y valorización de activos

### Mantenimientos
- Programación de mantenimientos preventivos
- Registro de mantenimientos correctivos
- Seguimiento de instalaciones y desinstalaciones
- Historial completo de intervenciones técnicas

### Sistema de Imágenes
- Almacenamiento de imágenes de los equipos
- Documentación visual del estado de los equipos

### Reportes
- Generación de PDF con la hoja de vida completa de cada equipo
- Estadísticas de estado y mantenimientos

## Tecnologías Utilizadas

### Backend
- **PHP 8.x**
- **Laravel 10.x**: Framework PHP para el desarrollo del backend
- **MySQL**: Sistema de gestión de base de datos relacional

### Frontend
- **Blade**: Motor de plantillas de Laravel
- **Bootstrap**: Framework CSS para el diseño responsive
- **JavaScript/jQuery**: Para interactividad en el lado del cliente

### Herramientas y Librerías
- **DomPDF**: Para la generación de reportes en formato PDF
- **Laravel Sanctum**: Para la autenticación de usuarios
- **Laravel Storage**: Para la gestión de archivos e imágenes

## Estructura de la Base de Datos

El sistema se basa en las siguientes entidades principales:

1. **Users**: Administradores del sistema
2. **Areas**: Departamentos o secciones de la empresa
3. **Equipment**: Registro de todos los equipos tecnológicos
4. **Maintenances**: Historial de mantenimientos realizados
5. **Images**: Fotografías asociadas a los equipos

## Requisitos del Sistema

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Servidor web (Apache o Nginx)

## Instalación

1. Clonar el repositorio:
   ```
   git clone [url-del-repositorio]
   ```

2. Instalar dependencias:
   ```
   composer install
   ```

3. Crear archivo de entorno:
   ```
   cp .env.example .env
   ```

4. Configurar la base de datos en el archivo `.env`

5. Generar clave de aplicación:
   ```
   php artisan key:generate
   ```

6. Ejecutar migraciones:
   ```
   php artisan migrate
   ```

7. Ejecutar seeders (opcional):
   ```
   php artisan db:seed
   ```

8. Crear enlace simbólico para el almacenamiento:
   ```
   php artisan storage:link
   ```

## Uso

1. Iniciar sesión con las credenciales de administrador
2. Navegar por el menú principal para acceder a las diferentes secciones
3. Gestionar equipos, áreas y mantenimientos según las necesidades

## Mantenimiento

- Realizar respaldos periódicos de la base de datos
- Actualizar el sistema operativo y dependencias regularmente
- Revisar y limpiar los archivos de almacenamiento según sea necesario

## Desarrollado por

👨‍💻 Haiver Velasco (Praticante SENA - Tecnologo en Analisis y Desarrollo de Sofware)

👨‍💻 Fabian Mazorra (Ingeniero en Sistemas de EMCASERVICIOS)
## Licencia

Este proyecto es propiedad de Emcaservicios y su uso está restringido según los términos acordados.

---

© 2025 Emcaservicios. Todos los derechos reservados.
