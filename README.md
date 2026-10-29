# CAIPA - Sistema de Gestión de Expedientes y Control de Personal

## De qué trata

CAIPA es un sistema web de gestión académica, administrativa y de expedientes desarrollado para el Centro de Atención Integral para Personas con Autismo (CAIPA), ubicado en el estado Trujillo, Venezuela. Su objetivo principal es centralizar, organizar y agilizar el registro socio-educativo de los estudiantes, su núcleo familiar, sus requerimientos clínicos y alimentarios, así como el control integral del talento humano y la organización académica de la institución.

## Resumen

La aplicación está construida bajo el patrón arquitectónico Modelo-Vista-Controlador (MVC) utilizando PHP nativo sin dependencias externas pesadas, conexión segura a base de datos relacional MySQL mediante PDO y generación local de documentos oficiales en formato PDF a través de FPDF.

El sistema reemplaza el manejo manual de fichas físicas por una plataforma centralizada que articula:
- Expedientes completos de estudiantes (datos demográficos, medidas y tallas, control de medicamentos, Programa de Alimentación Escolar PMA, representantes legales, madre y padre).
- Control administrativo y laboral del personal docente, administrativo y obrero adscrito a distintas dependencias institucionales.
- Organización académica por grados, asignación de profesores por turno, creación de clases y control de matrícula estudiantil.
- Emisión instantánea de listados y fichas oficiales en PDF, respaldos SQL de base de datos, control de acceso por roles y personalización visual mediante temas.

---

## Instalación Paso a Paso

### Requisitos previos

- Servidor web local o en red con Apache (WAMPServer, XAMPP, Laragon o entorno LAMP en Linux).
- PHP 8.0 o superior (probado en PHP 8.3) con las extensiones `pdo_mysql` y `mbstring` habilitadas.
- Servidor de base de datos MySQL 8.0+ o MariaDB 10.4+.
- Herramienta de administración de base de datos como phpMyAdmin o cliente MySQL por consola.

### Paso 1: Obtener el código fuente

Clone el repositorio dentro del directorio público de su servidor web (por ejemplo `C:\wamp64\www\` en WAMP o `/var/www/html/` en Linux):

```bash
git clone https://github.com/Zhailox/CAIPA.git
```

Si utiliza WAMPServer, verifique que la carpeta quede ubicada en:

```text
C:\wamp64\www\CAIPA
```

### Paso 2: Crear la base de datos

1. Inicie los servicios de Apache y MySQL en su entorno local.
2. Acceda a phpMyAdmin (`http://localhost/phpmyadmin`) o a la consola de MySQL.
3. Cree una nueva base de datos llamada `_cai_db` con cotejamiento `utf8mb4_unicode_ci`:

```sql
CREATE DATABASE _cai_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Paso 3: Importar el esquema y los datos iniciales

Importe el archivo `_cai_db.sql` incluido en la raíz del proyecto dentro de la base de datos `_cai_db`:

- **Desde phpMyAdmin**: seleccione la base de datos `_cai_db`, abra la pestaña **Importar**, elija el archivo `_cai_db.sql` y presione **Continuar**.
- **Desde terminal**:

```bash
mysql -u root -p _cai_db < _cai_db.sql
```

### Paso 4: Configurar las credenciales de conexión

Abra el archivo `config/Database.php` y ajuste los parámetros de conexión si su entorno MySQL utiliza un usuario, puerto o contraseña distintos a los valores predeterminados de WAMP:

```php
private $host = "localhost";
private $db_name = "_cai_db";
private $username = "root";
private $password = "";
```

### Paso 5: Acceder al sistema

Abra su navegador web e ingrese a:

```text
http://localhost/CAIPA/
```

El volcado inicial incluye cuentas de prueba listas para verificar el funcionamiento según el nivel de privilegios:

- **Cuenta Administrador**:
  - Usuario: `ADMIN`
  - Contraseña: `ADMIN`
- **Cuenta Invitado (solo lectura)**:
  - Usuario: `ale`
  - Contraseña: `ale`

Se recomienda modificar la contraseña de la cuenta administradora inmediatamente después del primer inicio de sesión desde el módulo **Configuraciones**.

---

## Características Principales

### 1. Gestión Integral de Estudiantes y Núcleo Familiar
- **Asistente de registro multipaso (Stepper)**: formulario estructurado en 5 etapas con validación de campos obligatorios y restricción numérica en tiempo real desde el navegador:
  1. Datos básicos del estudiante (nombre, apellido, cédula opcional, fecha de nacimiento, sexo, municipio, institución y tipo de estudiante: universitario, liceísta o foráneo).
  2. Detalles físicos, clínicos y alimentarios (talla de zapatos, camisa y pantalón, medicamentos asignados y registro del Programa de Alimentación Escolar PMA principal y primario).
  3. Datos del representante legal principal.
  4. Datos socioeconómicos y de contacto de la madre.
  5. Datos socioeconómicos y de contacto del padre.
- **Expediente individual (Perfil del Estudiante)**: vista consolidada para consultar y administrar de forma independiente los datos personales, representantes asociados, medicamentos, registros PMA y clases inscritas mediante ventanas modales.
- **Búsqueda y filtrado avanzado**: localización rápida por cédula desde el panel principal, buscador general paginado, tarjetas de estadísticas y filtros combinados por municipio, institución y tipo de estudiante.

### 2. Administración de Personal Institucional
- Registro completo del talento humano clasificado en personal **Docente**, **Administrativo** y **Obrero**.
- Ficha laboral detallada: datos personales, teléfonos de habitación/celular/familiar, correo electrónico, formación académica (pregrado, postgrado y estudios en curso), fecha de ingreso al MPPE, código RAC, cargo, carga horaria, dependencia administrativa, institución de adscripción, matrícula docente, situación laboral y datos de cuenta nómina.
- Directorio paginado con indicadores estadísticos, búsqueda por nombre o cédula y filtros por tipo de personal y dependencia.

### 3. Organización Académica (Grados, Profesores y Clases)
- **Grados**: administración del catálogo de niveles o grados académicos (`1er Grado` a `6to Grado` y niveles adicionales).
- **Profesores**: vinculación directa (relación 1:1) con trabajadores registrados como personal docente y asignación de turno operativo (`Mañana` o `Tarde`).
- **Clases y matrícula**: creación de aulas/clases asociadas a un grado y un profesor responsable, con panel dedicado para inscribir o retirar estudiantes mediante su número de cédula evitando inscripciones duplicadas.

### 4. Generación de Reportes Oficiales en PDF
- Motor de exportación integrado con **FPDF** (exclusivo para administradores) con encabezados institucionales y numeración automática de páginas.
- Emisión de reportes filtrables por rango de fechas de registro y límite de filas:
  - Listado General de Estudiantes.
  - Listado General de Personal.
  - Listado General de Profesores.
  - Listado de Clases Activas.
- Emisión de la **Ficha Oficial del Estudiante** mediante consulta individual por número de cédula, incluyendo datos personales y representantes legales registrados.

### 5. Configuración Maestra, Usuarios y Respaldos
- **Catálogos dinámicos**: creación, edición y eliminación controlada (con protección contra borrado de registros en uso) de municipios, instituciones, dependencias, grados, turnos y sexos.
- **Gestión de usuarios y roles**: administración de cuentas registradas, cambio de contraseñas, asignación de roles y protección contra autoeliminación de la sesión activa.
- **Respaldo de base de datos**: generación y descarga directa de volcados completos en formato `.sql` (`AdminController::backup`).

### 6. Sistema de Temas Visuales
- Soporte nativo para 6 esquemas visuales mediante variables CSS: `Nord Light`, `Light`, `Solarized Light`, `Dark`, `Tokyo Night` y `Dracula`.
- Preferencia visual persistente por cada cuenta de usuario en base de datos, además de configuración del tema global predeterminado para el sistema.

---

## Tecnologías Utilizadas

- **Backend**: PHP 8 (Programación Orientada a Objetos, arquitectura MVC y enrutador frontal en `index.php`).
- **Base de datos**: MySQL / MariaDB accedida exclusivamente mediante `PDO` con codificación `utf8mb4`.
- **Frontend**: HTML5 semántico, CSS3 nativo con propiedades personalizadas (CSS Custom Properties) e iconografía SVG integrada (`views/components/icons.php`), JavaScript ES6 Vanilla (`assets/js/steppers.js`).
- **Reportes**: Librería `FPDF` integrada localmente en el directorio `fpdf/` sin requerir Composer.

---

## Estructura del Proyecto

```text
CAIPA/
├── assets/
│   ├── css/
│   │   └── main.css                # Estilos generales y definición de los 6 temas visuales
│   ├── img/
│   │   └── login-bg.png            # Fondo visual de las vistas de autenticación
│   └── js/
│       └── steppers.js             # Lógica de formularios multipaso y validación en cliente
├── config/
│   └── Database.php                # Clase de conexión PDO a MySQL
├── controllers/
│   ├── AdminController.php         # Panel administrativo y exportación de respaldos SQL
│   ├── AuthController.php          # Inicio de sesión, registro y cierre de sesión
│   ├── ClaseController.php         # CRUD de clases y gestión de matrícula estudiantil
│   ├── ConfiguracionController.php # Catálogos maestros, usuarios y temas visuales
│   ├── DashboardController.php     # Panel de inicio, accesos rápidos y búsqueda por cédula
│   ├── EstudianteController.php    # Listado, filtros, registro integral y edición de estudiantes
│   ├── GradoController.php         # CRUD de grados académicos
│   ├── PerfilController.php        # Gestión detallada del expediente individual del estudiante
│   ├── PersonalController.php      # CRUD y ficha laboral del personal de la institución
│   ├── ProfesorController.php      # Vinculación de personal docente y asignación de turnos
│   └── ReporteController.php       # Generación de reportes y fichas oficiales en PDF
├── fpdf/                           # Librería FPDF para generación nativa de archivos PDF
├── models/
│   ├── BaseModel.php               # Clase abstracta con operaciones CRUD preparadas en PDO
│   ├── Clase.php                   # Consultas de clases e inscripción de alumnos
│   ├── Configuracion.php           # Gestión de catálogos, usuarios y parámetros del sistema
│   ├── Estudiante.php              # Transacciones de estudiante, familia, salud y PMA
│   ├── Grado.php                   # Modelo de grados escolares
│   ├── Personal.php                # Modelo de talento humano y estadísticas laborales
│   ├── Profesor.php                # Modelo de docentes activos por turno
│   └── Usuario.php                 # Autenticación y registro con hash Bcrypt
├── recursos/                       # Recursos gráficos auxiliares
├── views/
│   ├── admin/                      # Vista de utilidades administrativas
│   ├── auth/                       # Vistas de inicio de sesión y registro de cuenta
│   ├── clases/                     # Vistas de listado, creación, edición y matrícula de clases
│   ├── components/                 # Componentes reutilizables e íconos SVG
│   ├── configuraciones/            # Panel de administración de catálogos, usuarios y temas
│   ├── dashboard/                  # Vista principal de bienvenida y búsqueda rápida
│   ├── estudiantes/                # Directorio filtrable y formularios multipaso
│   ├── grados/                     # Gestión de grados
│   ├── layouts/                    # Plantilla maestra con barra lateral y control de sesión
│   ├── perfil/                     # Expediente integral del estudiante y modales CRUD
│   ├── personal/                   # Directorio, formularios y ficha completa del personal
│   ├── profesores/                 # Gestión de profesores y turnos
│   └── reportes/                   # Formulario generador de reportes PDF
├── _cai_db.sql                     # Esquema completo de base de datos MySQL y datos semilla
├── index.php                       # Punto de entrada único (Front Controller) y control de rutas
└── update_db.php                   # Script auxiliar de migración estructural
```

---

## Roles y Seguridad

### Control de acceso basado en roles
El sistema distingue dos perfiles operativos almacenados en la tabla `roles`:
- **Administrador (`id_rol = 1`)**: acceso total a creación, edición y eliminación de expedientes estudiantiles, núcleo familiar, personal, profesores, grados y clases; acceso exclusivo a la generación de **Reportes PDF**, **Configuraciones** (catálogos maestros y usuarios) y respaldos de base de datos.
- **Invitado (`id_rol = 2`)**: rol asignado por defecto a las cuentas nuevas creadas desde el formulario de registro. Permite consultar directorios, paneles, clases y fichas, así como personalizar su propio tema visual, restringiendo cualquier operación de modificación o acceso administrativo tanto en la interfaz como en los controladores del servidor.

### Medidas de seguridad implementadas
- **Prevención de Inyección SQL**: uso estricto de sentencias preparadas en `PDO` con emulación desactivada (`PDO::ATTR_EMULATE_PREPARES => false`).
- **Almacenamiento seguro de credenciales**: cifrado irreversible de contraseñas mediante `password_hash(..., PASSWORD_DEFAULT)` y verificación con `password_verify()`.
- **Protección contra CSRF**: generación y validación de tokens criptográficos (`random_bytes(32)`) en los formularios de autenticación y operaciones críticas.
- **Gestión segura de sesiones**: regeneración del identificador de sesión (`session_regenerate_id(true)`) tras la autenticación exitosa y bloqueo en el enrutador frontal para cualquier ruta no pública.
- **Mitigación de XSS**: limpieza y escape sistemático de entradas y salidas mediante `htmlspecialchars()` y `strip_tags()`.

---

## Esquema de Base de Datos

La base de datos `_cai_db` está conformada por 20 tablas normalizadas con integridad referencial (`InnoDB`, eliminación en cascada para registros dependientes del estudiante):

- **Núcleo estudiantil, clínico y familiar**: `estudiantes`, `representantes`, `madres`, `padres`, `medicamentos`, `pma_tp`.
- **Núcleo académico**: `grados`, `profesores`, `clases`, `estudiantes_clases`, `turno`.
- **Núcleo de recursos humanos**: `personal`, `dependencias`.
- **Catálogos territoriales e institucionales**: `municipios`, `instituciones`, `sexo`.
- **Seguridad, auditoría y preferencias**: `usuarios`, `roles`, `configuracion_sistema`, `historial`.
