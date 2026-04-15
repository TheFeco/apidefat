# API Defat (Backend)

Este es el proyecto backend del sistema **Defat**, desarrollado en PHP. Proporciona una serie de servicios y endpoints para la gestión de deportistas, usuarios, catálogos deportivos y la generación de documentos en formato PDF y Excel (Reportes).

## 🚀 Características Principales

- **Gestión de Deportistas**: Registro, actualización, eliminación y listado de deportistas.
- **Autenticación y Autorización**: Sistema de acceso seguro mediante tokens JWT para administradores y usuarios del sistema.
- **Generación de PDFs**:
  - `pdf.php`: Creación dinámica de **Gafetes** (ID badges) utilizando mPDF, con fondos y posiciones configurables.
  - `certificadopdf.php`: Generación de **Certificados** de registro de los participantes en formato PDF.
- **Exportación de Datos**: Exportación de todos los registros a Excel (`exportExcel.php`), incluyendo campos de validación e imágenes subidas.
- **Gestión de Catálogos**: Endpoints en formato JSON para consultar datos de escuelas (`escuelas.php`), deportes (`deportes.php`), pruebas (`getPruebas.php`), categorías, pesos y ciclos.
- **Gestión de Archivos**: Subida de documentos (INE, CURP, fotos, actas) vinculados a cada registro de atleta.

## 🛠️ Tecnologías y Dependencias

- **PHP 7/8+**: Lenguaje principal de los endpoints.
- **MySQL / MariaDB**: Sistema de gestión de base de datos.
- **mPDF**: Librería para la renderización avanzada de documentos PDF a partir de HTML/CSS.
- **Composer**: Gestor de dependencias de PHP.
- **Extensión GD / PCRE**: Utilizadas para el procesamiento de imágenes y manejo del tamaño de strings en la generación de HTML.

## 📂 Organización del Proyecto

- **`/clases/`**: Archivos de clases y utilidades de PHP (como la conexión de base de datos y queries).
- **`/vendor/`**: Dependencias generadas por Composer (como mPDF).
- **`/imagenes/`**: Fondos, logos y archivos multimedia usados en los gafetes y certificados.
- **Archivos de gestión de usuarios**: `login.php`, `usuarios.php`, `cambiarPasswordUsuario.php`, `validarToken.php`.
- **Archivos de gestión de deportistas**: `storeRegistro.php`, `editDeportistas.php`, `getDeportistas.php`, `deleteRegistro.php`, `updateformatos.php`.

## ⚙️ Instalación local (Entorno Laragon/XAMPP)

1. Aloja los archivos del repositorio dentro de tu directorio público de servidor (por ejemplo: `c:\laragon\www\apidefat`).
2. Importa la estructura de base de datos en tu manejador (MySQL/MariaDB).
3. Asegúrate de tener **Composer** instalado globalmente o localmente.
4. Abre la terminal en el directorio del proyecto y ejecuta el siguiente comando para instalar mPDF y el resto de dependencias listadas en el `composer.json`:
   ```bash
   composer install
   ```
5. Verifica las variables de acceso a la Base de Datos dentro del directorio de clases.
6. Ajusta variables de PHP como el `memory_limit`, `post_max_size`, y `pcre.backtrack_limit` en caso de que requieras generar PDFs muy pesados o exportar muchos registros simultáneamente.

## 📝 Documentación técnica adjunta

La planificación y definiciones específicas bajo **Specification-Driven Development (SDD)** se encuentran en:
- `sdd_planning.md`
- `gafetes_v2_spec.md`
