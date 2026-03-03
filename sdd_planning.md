# Spec-Driven Development (SDD) - API Defat (Laravel + Docker + JWT)

## 1. Contexto y Objetivos
Migración y mejora del proyecto actual a una nueva arquitectura basada en **Laravel, Docker y autenticación vía JWT**. Se mejorará la base de datos existente basándose en el análisis de `dummydefait_con_datos.sql` y se agregará una funcionalidad específica para la gestión de **gafetes**.

## 2. Tecnologías y Herramientas (Stack)
- **Framework:** Laravel (última versión)
- **Entorno:** Docker (utilizando Laravel Sail)
- **Seguridad:** Autenticación mediante JSON Web Tokens (JWT) usando `tymon/jwt-auth`.
- **Base de datos:** MariaDB/MySQL (Heredada y mejorada)

## 3. Mejoras a la Base de Datos Existente
Se analizó el esquema existente (`dummydefait_con_datos.sql`). Se proponen las siguientes mejoras y refactorizaciones para la nueva versión de Laravel (Migraciones):
- **Correcciones ortográficas y de nomenclaturas:** 
  - El campo `deteled_at` en la tabla `deportistas` debe ser corregido a `deleted_at` para soportar *Soft Deletes* nativos de Laravel.
  - Normalización general de campos `created_at` y `updated_at`.
- **Integridad referencial y Tipos de datos:** 
  - Cambiar los campos binarios/tinyblob como `is_active` en `deportes` por el tipo **boolean** (`tinyint(1)`).
  - Configurar correctamente Llaves Foráneas (Foreign Keys) para asegurar integridad referencial y evitar huérfanos.
- **Índices Estratégicos:** Indexar campos muy consultados, como el `curp` y `folio`.

## 4. Nueva Funcionalidad: Gestión de Gafetes
Se añade un formato y modelo dedicado a la generación y control de gafetes de los participantes (`deportistas`).

**Reglas de negocio (SDD):**
1. Debe existir **solo un gafete válido** por participante, vinculado específicamente a su `ciclo_id`.
2. El registro del gafete debe guardar el path de su foto representativa, y además un campo aditivo (`foto_ubicacion`) que define exactamente dónde va conectada o configurada esa foto en la plantilla del gafete.

**Propuesta del Esquema `gafetes` (Migración):**
```sql
CREATE TABLE `gafetes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `deportista_id` int(11) NOT NULL,
  `ciclo_id` int(11) NOT NULL,
  `foto_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_ubicacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lugar o coordenada donde se colocará la foto en el formato',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unico_gafete_por_ciclo` (`deportista_id`, `ciclo_id`),
  CONSTRAINT `fk_gafetes_deportistas` FOREIGN KEY (`deportista_id`) REFERENCES `deportistas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_gafetes_ciclos` FOREIGN KEY (`ciclo_id`) REFERENCES `ciclos` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 5. Endpoints Principales (REST API)
Todos los endpoints están protegidos por Middleware JWT a excepción del login.

### Autenticación
- `POST /api/auth/login` - Obtención y generación de token JWT.
- `POST /api/auth/logout` - Invalida el token.
- `GET /api/auth/me` - Trae informacíon del usuario autenticado.

### Endpoint Gafetes
- `GET /api/gafetes` - Listado paginado de gafetes.
- `POST /api/gafetes` - Creación de un gafete. *Valida Unique(deportista_id, ciclo_id).*
- `GET /api/gafetes/{id}` - Detalles de un gafete (incluyendo la foto_ubicacion).
- `PUT /api/gafetes/{id}` - Modificar o re-asignar detalles del gafete existente.
- `DELETE /api/gafetes/{id}` - Soft delete de un registro.

---

## 6. Bitácora de Cambios (Changelog & Context Tracker)
*Este apartado sirve para documentar todos los cambios atómicos en el sistema, mitigando la pérdida de contexto entre sesiones técnicas.*

| Estatus | Fecha | Componente | Descripción de la Tarea / Cambio Logrado |
| :---: | :--- | :--- | :--- |
| 🔲 | - | Setup/Docker | Crear nuevo proyecto Laravel (`laravel new defat-api`) y configurar Laravel Sail (Docker). |
| 🔲 | - | Setup/JWT | Instalar y publicar configuración de `tymon/jwt-auth`. Adaptar modelo User. |
| 🔲 | - | BD/Migraciones | Pasar el script `dummydefait_con_datos` al esquema `Schema::create` (DB de Eloquent). |
| 🔲 | - | BD/Seeders | Migrar los registros "viejos" estáticos (Ciclos, Deportes, Categorías) vía Seeders. |
| 🔲 | - | Gafete/BD | Crear migración de la tabla `gafetes` agregando llaves foráneas y unicidad por ciclo. |
| 🔲 | - | Gafete/API | Crear el Controller `GafeteController` y Form Request validando unicidad en `store`. |

---

## 7. Documentación de API para Frontend (OpenAPI / Swagger)
*Sección destinada para proporcionar especificaciones concisas al desarrollador Frontend (Angular/React/Vue) sobre cómo integrarse con nuestra nueva API Laravel.*

### Estándares de Petición
Todas las peticiones a endpoints privados deben incluir en sus cabeceras (Headers):
- `Authorization`: `Bearer {token_jwt_aqui}`
- `Accept`: `application/json`

### Respuestas Estandarizadas
La API responderá bajo la siguiente estructura genérica (Formato JSON):
```json
{
  "success": true,
  "data": { ... },
  "message": "Operación completada exitosamente.",
  "errors": null
}
```

### Especificación de Endpoints Principales

#### 🔸 Login POST `/api/auth/login`
- **Body Request:**
  `email` (string), `password` (string)
- **Regla de Negocio:**
  Validar en login si el usuario está activo (`estado === "activo"` o `1`).
- **Success Response (200 OK):** 
  Retorna token JWT junto al objeto del usuario (data.user).
- **Error Response (401 Unauthorized / 403 Forbidden):**
  Credenciales incorrectas o el usuario está inactivo.

#### 🔸 Obtener Gafetes GET `/api/gafetes`
- **Params (Query):**
  `page` (int, opcional), `ciclo_id` (int, filtro opcional), `deportista_id` (int, filtro opcional)
- **Success Response (200 OK):**
  Lista paginada de todos los gafetes, incluye la relación a `ciclo` y `deportista`.

#### 🔸 Crear Gafete POST `/api/gafetes`
- **Body Request (Form-Data / JSON):**
  `deportista_id` (int, requerido), `ciclo_id` (int, requerido), `foto` (file/nullable), `foto_ubicacion` (string, ej. "top-right")
- **Error Response (422 Unprocessable Entity):**
  Si el deportista ya cuenta con un gafete en ese ciclo, retornará un error explícito.

#### 🔸 Ver Gafete GET `/api/gafetes/{id}`
- **Success Response (200 OK):**
  Un solo recurso con todos sus detalles para maquetar la plantilla PDF / visual en HTML.


---

## 8. Gestión de Usuarios y Control de Acceso
El sistema requerirá dos niveles de interacción básicos basados en roles/permisos. Existen administradores del sistema, los cuales tienen control total sobre:
- **Gestión (CRUD) de Usuarios**: 
  Capacidad del administrador para Crear, Leer, Actualizar (inclusive resetear su contraseña) o Eliminar lógicamente (`Soft Deletes`) cualquier cuenta de usuario.
- **Activación / Inactivación**:
  Capacidad de activar o pausar el acceso de un usuario específico sin eliminarlo completamente de la base de datos (campo estatus o `is_active` en tabla usuarios). En el flujo de **Login**, si es detectado que una cuenta está "Inactiva", es devuelto un error `403 Forbidden` informando que el usuario carece de los accesos necesarios.

---
> Nota de sistema: El documento ha sido actualizado en cumplimiento con los requerimientos agregando una sección sobre la administración de usuarios y roles y se refactorizó el comportamiento del login.
