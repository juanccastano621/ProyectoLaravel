# Sistema de Reserva de Auditorios - Universidad de Caldas

Proyecto desarrollado para la gestión de reservas de auditorios de la Universidad de Caldas.

**Desarrollado por:** Juan Camilo Castaño Ocampo
**Universidad:** Universidad de Caldas
**Tecnologías:** Laravel, Vue.js, Inertia.js, Tailwind CSS, PostgreSQL, Jetstream

---

# Descripción del Proyecto

El Sistema de Reserva de Auditorios permite gestionar la disponibilidad y reserva de auditorios de manera pública y administrativa.

Los usuarios pueden consultar auditorios disponibles, revisar horarios, verificar disponibilidad y realizar solicitudes de reserva. Desde el panel administrativo se pueden aprobar, rechazar, cancelar o finalizar reservas, administrar auditorios, bloquear horarios y consultar estadísticas del sistema.

---

# Funcionalidades Implementadas

## Módulo Público

* Catálogo de auditorios disponibles.
* Visualización del detalle de cada auditorio.
* Consulta de disponibilidad por fecha.
* Visualización de próximos horarios disponibles.
* Registro de solicitudes de reserva.
* Validación de conflictos de horarios.
* Restricción de horarios ya ocupados o bloqueados.
* Restricción de reservas en horas pasadas.

## Módulo Administrativo

* Inicio de sesión mediante Jetstream.
* Dashboard administrativo con métricas.
* Visualización de estadísticas y gráficas.
* Gestión completa de auditorios (CRUD).
* Configuración de horarios de disponibilidad.
* Gestión de reservas.
* Aprobación de reservas.
* Rechazo de reservas.
* Cancelación de reservas.
* Finalización automática de reservas.
* Bloqueo manual de horarios.
* Calendario semanal de auditorios.
* Filtros por estado, fecha y auditorio.
* Correos automáticos de notificación.

---

# Tecnologías Utilizadas

## Backend

* Laravel 13
* PHP 8.4
* Eloquent ORM
* Laravel Mail
* Jetstream

## Frontend

* Vue.js 3
* Inertia.js
* Tailwind CSS

## Base de Datos

* PostgreSQL

## Herramientas Adicionales

* Mailtrap
* Composer
* NPM
* Vite

---

# Instalación del Proyecto

## 1. Clonar el repositorio

```bash
git clone https://github.com/juanccastano621/ProyectoLaravel.git
```

Ingresar al proyecto:

```bash
cd reservas-auditorios
```

---

## 2. Instalar dependencias

### Dependencias PHP

```bash
composer install
```

### Dependencias Frontend

```bash
npm install
```

---

## 3. Configurar archivo `.env`

Duplicar el archivo:

```bash
cp .env.example .env
```

Generar llave de aplicación:

```bash
php artisan key:generate
```

---

## 4. Configuración de Base de Datos

Configurar PostgreSQL en el archivo `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=reservas_auditorios
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```

---

## 5. Configuración de Mailtrap

Agregar las credenciales de Mailtrap en el archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=TU_USERNAME
MAIL_PASSWORD=TU_PASSWORD
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@universidadcaldas.edu.co"
MAIL_FROM_NAME="Sistema Reserva Auditorios"
```

---

## 6. Ejecutar migraciones y seeders

Para crear toda la estructura del proyecto y cargar datos de prueba:

```bash
php artisan migrate:fresh --seed
```

Esto generará:

* Auditorios de prueba.
* Horarios de disponibilidad.
* Reservas de prueba.
* Usuario administrador.

---

## 7. Crear enlace de almacenamiento

```bash
php artisan storage:link
```

---

## 8. Ejecutar el proyecto

### Backend

```bash
php artisan serve
```

### Frontend

```bash
npm run dev
```

Abrir en navegador:

```txt
http://127.0.0.1:8000
```

---

# Credenciales de Administrador

Correo:

```txt
admin@admin.com
```

Contraseña:

```txt
password
```

---

# Flujo General del Sistema

1. El usuario ingresa al catálogo público.
2. Consulta un auditorio.
3. Verifica horarios disponibles.
4. Realiza una solicitud de reserva.
5. El administrador recibe la solicitud.
6. Desde el panel administrativo se aprueba, rechaza o cancela.
7. El sistema envía correos automáticos al solicitante.
8. Las reservas finalizan automáticamente al terminar el horario.

---

# Estructura General del Sistema

## Público

* Exploración de auditorios.
* Consulta de disponibilidad.
* Solicitud de reservas.

## Administrativo

* Dashboard.
* Gestión de auditorios.
* Gestión de reservas.
* Calendario.
* Bloqueos.
* Estadísticas.

---

# Autor

**Juan Camilo Castaño Ocampo**
Universidad de Caldas
Proyecto académico universitario
