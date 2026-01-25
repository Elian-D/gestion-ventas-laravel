# Arquitectura de Módulos y Guía de Desarrollo (ERP Pattern)

Este proyecto utiliza una arquitectura desacoplada basada en servicios y capas de responsabilidad para garantizar **"Skinny Controllers"** (controladores delgados), código altamente reutilizable y facilidad para realizar pruebas unitarias.

---

## Tabla de Contenido

1. [Arquitectura de Módulos y Guía de Desarrollo (ERP Pattern)](#arquitectura-de-módulos-y-guía-de-desarrollo-erp-pattern)
2. [Estructura de Capas y Responsabilidades](#estructura-de-capas-y-responsabilidades)
   - [1. Capa de Datos (Modelo)](#1-capa-de-datos-modelo)
   - [2. Capa de Configuración UI (Tables)](#2-capa-de-configuración-ui-tables)
   - [3. Capa de Filtrado (Pipeline Pattern)](#3-capa-de-filtrado-pipeline-pattern)
   - [4. Capa de Validación y Seguridad (Form Requests)](#4-capa-de-validación-y-seguridad-form-requests)
   - [5. Capa de Servicios (Business Logic)](#5-capa-de-servicios-business-logic)
   - [6. Controlador (Orquestador)](#6-controlador-orquestador)
3. [Checklist de Implementación para Nuevos Módulos](#checklist-de-implementación-para-nuevos-módulos)
   - [Base de Datos y Seguridad](#base-de-datos-y-seguridad)
   - [Backend y Lógica](#backend-y-lógica)
   - [HTTP y Rutas](#http-y-rutas)
   - [Frontend](#frontend)
4. [Ejemplo de Flujo Estándar (Store)](#ejemplo-de-flujo-estándar-store)


--- 

## Estructura de Capas y Responsabilidades

### 1. Capa de Datos (Modelo)

- **Ubicación:** `app/Models/[Module].php`

- **Responsabilidad:** Gestionar la persistencia y relaciones base.

- **Tarea:** Definir siempre un `scopeWithIndexRelations($query)` para centralizar el *Eager Loading* que usarán tanto la tabla web como las exportaciones a Excel.

### 2. Capa de Configuración UI (Tables)

- **Ubicación:** app/Tables/[Module]Table.php

- **Responsabilidad:** Centralizar nombres de columnas y lógica de visibilidad.

- **Métodos**: 

    - `allColumns()`: Etiquetas legibles.

    - `defaultDesktop()` y `defaultMobile()`: Columnas visibles por defecto según el dispositivo.

### 3. Capa de Filtrado (Pipeline Pattern)

- **Ubicación:** `app/Filters/[Module]/`

- **Responsabilidad:** Limpiar los controladores de cláusulas `where`. Cada filtro (Fecha, Texto, Select) es una clase independiente.

- **Uso:** `$query = (new ModuloFilters($request))->apply(Model::query())`.

### 4. Capa de Validación y Seguridad (Form Requests)

- **Ubicación:** `app/Http/Requests/[Module]/`

- **Responsabilidad:** Validar datos y verificar permisos de Spatie antes de que el controlador ejecute cualquier lógica.

- **Archivos:** `Store[Module]Request.php`, `Update[Module]Request.php`, `Bulk[Module]Request.php`.


### 5. Capa de Servicios (Business Logic)

El controlador nunca debe hacer `Model::create()` ni gestionar `DB::transaction()`.

- **Catalog Service:** `app/Services/[Module]/[Module]CatalogService.php`. Suministra datos para los `<select>` filtrando por `country_id` u otros parámetros globales.

- **Business Service:** `app/Services/[Module]/[Module]Service.php`. Ejecuta acciones de escritura, cálculos complejos y procesos masivos (`performBulkAction`).

### 6. Controlador (Orquestador)

- **Responsabilidad:** Recibir la petición, llamar a los servicios necesarios y retornar la respuesta (Vista o JSON). No contiene lógica de negocio.

--- 

## Checklist de Implementación para Nuevos Módulos

> *Copia este checklist en cada nuevo módulo para asegurar la consistencia del sistema.*

### Base de Datos y Seguridad

- [ ] Migración creada y ejecutada con SoftDeletes.

- [ ] Seeder de Permisos creado y ejecutado ([Modulo]PermissionsSeeder).

- [ ] Modelo configurado con el scope de relaciones necesarias.

### Backend y Lógica

- [ ] Clase [Modulo]Table definida con sus columnas.

- [ ] Pipeline de filtros creado en app/Filters/[Modulo].

- [ ] CatalogService implementado (filtrando por país si aplica).

- [ ] Service de negocio con métodos create, update y bulk.

### HTTP y Rutas
- [ ] FormRequests creados (Store, Update, Bulk) con validación de permisos.

- [ ] Rutas registradas en web.php (Index, Create, Store, Edit, Update, Destroy, Bulk, Export).

- [ ] Controlador configurado inyectando los servicios en el constructor o métodos.

### Frontend

- [ ] Vista index con tabla AJAX y componente de filtros.

- [ ] JS: Configuración de window.filterSources para renderizar Chips.

- [ ] Formularios de Create/Edit alimentados por el CatalogService.

---

### Ejemplo de Flujo Estándar (Store)
```php
/**
 * Almacenar un nuevo registro
 * El StoreModuloRequest se encarga de:
 * 1. Validar permisos (authorize)
 * 2. Validar datos (rules)
 */
public function store(StoreModuloRequest $request, ModuloService $service)
{
    // El Service centraliza la creación y lógica de base de datos
    $model = $service->create($request->validated());

    return redirect()
        ->route('modulo.index')
        ->with('success', "Registro {$model->name} creado correctamente.");
}
```