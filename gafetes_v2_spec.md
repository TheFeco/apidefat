# Especificación y Planificación de Backend: Proyecto API-DEFAIT (v2.0)

## 1. Contexto y Objetivos del Proyecto
El proyecto actualmente conocido como `apidefat` pasará por una reestructuración completa y será renombrado a **`api-defait`**. El objetivo es abandonar la arquitectura monolítica antigua basada en scripts sueltos (`pdf.php`, `certificadopdf.php`, etc.) y adoptar una arquitectura MVC / API RESTful con altos estándares bajo la metodología **Specification-Driven Development (SDD)**.

Este documento servirá como la **Fuente Única de la Verdad (SSOT)** para la lógica de negocio del backend, reportes y generación de PDFs, evitando cualquier codificación directa sin antes cumplir y validar estas especificaciones.

---

## 2. Sistema de Usuarios y Roles (Reglas de Negocio)

El sistema soporta dos tipos principales de usuarios con permisos estrictamente diferenciados.

### 2.1. Administrador (Rol 1)
El administrador tiene control total y visión global del sistema:
*   **Gestión de Cuentas:** Puede dar de alta nuevos usuarios operativos, cambiar sus contraseñas temporalmente, y habilitar/deshabilitar sus cuentas (activación/desactivación).
*   **Visión Global:** Puede ver **todos** los registros (participantes, deportistas, etc.) cargados en el sistema sin distinción de quién los haya creado.
*   **Reportes Generales:** Capacidad de imprimir gafetes y cédulas de registro filtrando por **Usuario Capturista**, por **Escuela** específica, o imprimir el **total general** usando filtros cruzados (Función, Deporte, Rama, Prueba).

### 2.2. Usuario Operativo / Capturista (Rol 2)
El usuario estándar cuenta con un acceso limitado a la información:
*   **Visión Limitada:** Única y exclusivamente puede ver, editar e imprimir los registros que él mismo dio de alta. No puede ver registros generados por otros usuarios.
*   **Alta de Registros:** Es el encargado de capturar toda la información requerida de los deportistas, documentos (fotos) y asignar la escuela, deporte, pruebas, etc., en base al SDD del Frontend.

---

## 3. Módulos de Generación de Documentos (PDF)

El sistema de PDF será rediseñado para ser completamente dinámico y atado a las configuraciones de cada **Ciclo Escolar**.

### 3.1. Gafetes Dinámicos (`GeneradorGafete`)
El código estático (`pdf.php`) se reemplaza por un flujo configurable.
*   **Plantillas (Imágenes) por Ciclo:** Cada ciclo escolar (ej. "Ciclo 2025-2026") debe permitir almacenar su propia plantilla de fondo en la base de datos, en lugar de leer siempre `imagenes/gafete.jpg`.
*   **Posicionamiento de Campos Dinámico:** El administrador podrá ajustar las coordenadas (X, Y) desde el Frontend. El backend proveerá/guardará un JSON de configuración por ciclo (ej. dónde ubicar el nombre, municipio, deporte, de qué tamaño y qué fuente) para no tocar código fuente en el futuro al cambiar de diseño.
*   **Incrustación Segura:** Todas las fotos de los deportistas se renderizarán convirtiéndose a `Base64` internamente para asegurar compatibilidad universal con mPDF.

### 3.2. Cédulas de Registro (`GeneradorCedula`)
La cédula antigua (`certificadopdf.php`) lista a todos los deportistas de una cierta búsqueda agrupados.
*   **Encabezado / Cintillo Dinámico:** Ya no utilizará la imagen quemada `imagenes/Cintillo.jpg`. Se permitirá configurar el Banner encabezado específicamente asociado al Ciclo Escolar.
*   **Filtros Globales de Impresión:** El Endpoint para generar la Cédula debe soportar parámetros nulos y completos. Se podrá filtrar jerárquicamente por:
    1.  `id_usuario` (Solo para casos logueados como Admin).
    2.  `id_escuela` / `cct` (Imprimir por centros de trabajo).
    3.  `funcion`, `deporte`, `rama`, `prueba`.

### 3.3. Mantenimiento y Rendimiento: Limpiador de Imágenes
Como el servidor requiere de imágenes de alta resolución guardadas físicamente (`/img`), se requiere una tarea automatizada (Cron/Comando) o botón administrativo:
*   **Eliminación de Residuos:** Un script o endpoint que identifique todas las imágenes (`foto`) pertenecientes a ciclos inactivos, anteriores o archivados, y elimine físicamente el recurso usando `unlink()`.
*   Esto prevendrá facturación excesiva en hosting y saturación de la memoria del servidor. Solo conservará imágenes del "Ciclo Activo" o en transición.

---

## 4. Task Tracker: Plan de Implementación (Fases)

*(Mantener las casillas `[ ]` y marcarlas `[x]` a medida que se cumplan las características. Prohibido codificar características no especificadas aquí)*

### Fase 1: Setup y Migración a API RESTful (`api-defait`)
- [x] Mover estructura heredada hacia un framework/arquitectura estructurada.
- [x] Conectar la base de datos `dummydefait_con_datos` existente al nuevo entorno.
- [x] Exponer Endpoints protegidos para login, logout y recuperar "Perfil/Rol del usuario actual".

### Fase 2: Gestión de Usuarios y Permisos
- [ ] Crear Service/Controller de Usuarios (Alta, edición, contraseña, toggle estado).
- [ ] Implementar el control lógico de "Visibilidad": Si `rol == 2`, inyectar siempre `WHERE id_usuario = Auth::id()`.
- [ ] Endpoint para que el Admin pueda solicitar las listas de otros usuarios.

### Fase 3: Configuración de Ciclos y Configurador PDF (Gafete y Cédula)
- [ ] Alterar o crear tablas anexas de `ciclos` añadiendo columnas (o un JSON relacional) para: `ruta_fondo_gafete`, `ruta_cintillo_cedula`, y el JSON de `coordenadas_gafete`.
- [ ] Endpoint `/admin/ciclos/configuracion` para subir los archivos de fondo/banner y recibir las variables (X, Y) del form.

### Fase 4: Refactorización del Generador Gafete (Base64 + Config Dynamic)
- [ ] Recrear el iterador del array en formato de clases.
- [ ] Inyectar CSS dinámico basado en las coordenadas leídas de la BD.
- [ ] Realizar pruebas exportando lotes grandes (hasta el límite de PCRE/Memoria).

### Fase 5: Refactorización Cedula Escolar (Plantilla e Impresión Total)
- [ ] Recrear la lógica de agrupación por Escuela de `certificadopdf`.
- [ ] Asegurarse de inyectar el Cintillo correcto leyendo el parámetro `cintillo_cedula` del ciclo actual.
- [ ] Aplicar filtros mixtos desde el frontend (solo Deporte, o Función + Escuela).

### Fase 6: Sistema de Mantenimiento Server (Purgador)
- [ ] Crear el Job (o endpoint `/admin/maintenance/purge-images`).
- [ ] Logica query: Recuperar `foto` de deportistas cuyo ciclo tenga flag `activo = 0` o el año indique que ya prescribió.
- [ ] Eliminar archivos del storage retornando un reporte con conteo de "MBs liberados".
