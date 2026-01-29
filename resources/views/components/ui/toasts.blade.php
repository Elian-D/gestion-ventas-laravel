<div class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-full max-w-sm px-4 md:px-0">
    @if (session('success'))
        <x-ui.toast-item type="success" title="¡Éxito!" :message="session('success')" />
    @endif

    @if (session('error'))
        <x-ui.toast-item type="error" title="Atención" :message="session('error')" :duration="8000" />
    @endif

    @if (session('info'))
        <x-ui.toast-item type="info" title="Información" :message="session('info')" />
    @endif
</div>