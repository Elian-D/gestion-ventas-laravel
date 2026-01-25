# AJAX DataTable Component

![JavaScript ES6](https://img.shields.io/badge/JavaScript-ES6-yellow)
![Laravel](https://img.shields.io/badge/Laravel-Compatible-red)
![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-Compatible-blue)
![AJAX](https://img.shields.io/badge/AJAX-Fetch-green)

Sistema modular para el manejo de tablas dinámicas mediante **AJAX**, diseñado para permitir:

- Aplicación de filtros sin recargar la página  
- Paginación dinámica compatible con Laravel  
- Selección de filas con estado persistente  
- Gestión inteligente de columnas (desktop / mobile)  

Todo el comportamiento está centralizado en un componente reutilizable, desacoplado de vistas específicas.

---

## Tabla de Contenidos

- [Estructura de Archivos](#estructura-de-archivos)
- [Funcionamiento del Contexto (ctx)](#funcionamiento-del-contexto-ctx)
- [Implementación de Fuentes de Datos (Chips)](#implementación-de-fuentes-de-datos-chips)
- [Ejemplo de Uso](#ejemplo-de-uso)
- [Cómo agregar funcionalidades nuevas](#cómo-agregar-funcionalidades-nuevas)
- [Funciones Globales Disponibles](#funciones-globales-disponibles)

---
## Estructura de Archivos

```plaintext
resources/js/components/ajax-datatable/
├── index.js          # Punto de entrada del componente. Inicializa y orquesta el contexto (ctx).
├── state.js          # Definición del estado interno: selección de filas, timers, requests activos.
├── request.js        # Construcción de URLs y lógica de peticiones fetch con AbortController.
├── filters.js        # Serialización del formulario, aplicación y limpieza de filtros.
├── chips.js          # Renderizado y manejo de los chips de filtros activos.
├── pagination.js     # Intercepta y maneja enlaces de paginación de Laravel vía AJAX.
├── columns.js        # Control de visibilidad de columnas según dispositivo (móvil / desktop).
├── events.js         # Registro centralizado de todos los event listeners del componente.
└── utils.js          # Funciones auxiliares para etiquetas, mapeos y normalización de datos.
```

---

## Funcionamiento del Contexto (ctx)

> [!IMPORTANT] No modifique el objeto ctx fuera de las funciones designadas para evitar inconsistencias en el renderizado de la tabla

Para evitar la redundancia y mantener la sincronización, el componente utiliza un objeto central llamado ctx que contiene:

- `table`: Referencia al elemento DOM de la tabla.

- `form`: Referencia al elemento DOM del formulario de filtros.

- `config`: Objeto de configuración pasado durante la inicialización.

- `state`: Estado reactivo (IDs seleccionados, peticiones activas).

- `methods`: Funciones integradas para sincronizar checkboxes y estados visuales.

>[!NOTE]
Todos los módulos (filters, chips, pagination, columns) operan sobre el mismo ctx, garantizando consistencia global.

---

## Implementación de Fuentes de Datos (Chips)

Para que los chips muestren etiquetas legibles (ej: **"Activo"** en lugar de **"1"**), se utilizan dos métodos:

### 1. Valores Estáticos

Se definen directamente en la configuración de la instancia de JS.

>[!NOTE]
Recomendado para valores simples, booleanos o catálogos pequeños que no dependen de la base de datos.

### 2. Fuentes Globales (Dynamic Labels)

Para datos provenientes de la base de datos, se debe inyectar un objeto en el objeto global `window.filterSources` antes de inicializar la tabla.

Ejemplo en Blade:

```php

{{-- resources/views/clients/partials/filter-sources.blade.php --}}
<script>
    window.filterSources = {
        estadosClientes: JSON.parse('{!! addslashes(json_encode($estadosClientes->pluck("nombre", "id"))) !!}'),
        tiposNegocio: JSON.parse('{!! addslashes(json_encode($tiposNegocio->pluck("nombre", "id"))) !!}'),
    };
</script>
```
---

## Ejemplo de Uso

Para inicializar la tabla en una página específica (ej: `resources/js/pages/clients.js`):

```js
import AjaxDataTable from '../components/ajax-datatable/index';

document.addEventListener('DOMContentLoaded', () => {
    AjaxDataTable({
        tableId: 'clients-table',
        formId: 'clients-filters',
        debounce: 800,
        chips: {
            search: {
                label: 'Búsqueda'
            },
            active: {
                label: 'Estado Operativo',
                values: {
                    '1': 'Activo',
                    '0': 'Inactivo'
                }
            },
            estado_cliente: {
                label: 'Estado del Cliente',
                source: 'estadosClientes' // Referencia a window.filterSources.estadosClientes
            },
            business_type: {
                label: 'Tipo de Negocio',
                source: 'tiposNegocio'
            }
        }
    });
});
```
>[!NOTE]
La clave source debe coincidir exactamente con una propiedad existente en window.filterSources.
---

## Cómo agregar funcionalidades nuevas

1. **Si es lógica de datos**: Agregue la función en ``utils.js`` o ``state.js``.

2. **Si es un evento nuevo**: Registre el escuchador en ``events.js`` y asegúrese de que la lógica sea llamada desde ahí.

3. **Si es una nueva sección visual**: Cree un archivo ``.js`` específico (ej: ``export-data.js``) e impórtelo en ``index.js``, pasándole el objeto ``ctx``.

---

## Funciones Globales Disponibles

El componente expone las siguientes funciones al objeto ``window`` para ser usadas desde el HTML o componentes AlpineJS:

- ``window.resetTableColumns()``: Restaura las columnas por defecto según el dispositivo.

- ``window.clearTableSelection()``: Desmarca todas las filas y limpia el array de IDs seleccionados.