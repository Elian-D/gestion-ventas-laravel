export const resolveLabel = (config, value) => {
    if (!config) return value;
    
    // Prioridad 1: Si definiste una función format, la usamos (Aquí se arregla la fecha)
    if (typeof config.format === 'function') {
        return config.format(value);
    }

    // Prioridad 2: Valores estáticos (Active: '1' -> 'Activo')
    if (config.values) return config.values[value] ?? value;

    // Prioridad 3: Fuentes dinámicas (window.filterSources)
    if (config.source && window.filterSources?.[config.source]) {
        return window.filterSources[config.source][value] ?? value;
    }

    return value;
};