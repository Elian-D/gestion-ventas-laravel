export const buildUrl = (form) => {
    const formData = new FormData(form);
    const searchParams = new URLSearchParams();
    const base = window.location.pathname;

    for (const [key, value] of formData.entries()) {
        if (value === '') continue;
        searchParams.append(key, value);
    }
    const query = searchParams.toString();
    return query ? `${base}?${query}` : base;
};

export const fetchTable = async (ctx, url) => {
    if (ctx.state.currentRequest) ctx.state.currentRequest.abort();
    
    ctx.state.currentRequest = new AbortController();
    ctx.table.classList.add('opacity-50', 'pointer-events-none', 'cursor-wait');

    try {
        const res = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: ctx.state.currentRequest.signal
        });
        if (!res.ok) throw new Error('Error en servidor');
        
        ctx.table.innerHTML = await res.text();
        // Importación dinámica circular evitada llamando a una función de sync
        ctx.syncCheckboxes(); 
    } catch (error) {
        if (error.name !== 'AbortError') alert("No se pudo cargar la información.");
    } finally {
        ctx.table.classList.remove('opacity-50', 'pointer-events-none', 'cursor-wait');
    }
};