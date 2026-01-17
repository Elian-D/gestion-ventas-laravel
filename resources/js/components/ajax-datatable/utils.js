export const resolveLabel = (config, value) => {
    if (!config) return value;
    if (config.values) return config.values[value] ?? value;
    if (config.source && window.filterSources?.[config.source]) {
        return window.filterSources[config.source][value] ?? value;
    }
    return value;
};