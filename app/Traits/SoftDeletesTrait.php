<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait SoftDeletesTrait
{
    
    /**
     * Elimina el registro si no tiene relaciones (o desactiva la eliminación por defecto).
     *
     * @param mixed $item
     * @param string|null $relationCheck Nombre de la relación para verificar antes de eliminar
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyTrait($item, $relationCheck = null)
{
    // Validación de relaciones (opcional)
    if ($relationCheck && $item->$relationCheck()->exists()) {
        return redirect()
            ->route($this->getRouteIndex())
            ->with('error', $this->getEntityName() . ' "' . $item->nombre . '" tiene relaciones. No se puede eliminar.');
    }

    try {
        $item->delete(); // Soft delete
        return redirect()
            ->route($this->getRouteIndex())
            ->with('success', $this->getEntityName() . ' "' . $item->nombre . '" eliminada correctamente.');
    } catch (\Exception $e) {
        return redirect()
            ->route($this->getRouteIndex())
            ->with('error', 'Error al eliminar ' . $this->getEntityName() . '. Contacte soporte.');
    }
}


    /**
     * Listar registros eliminados
     */
    public function eliminadas()
    {
        $modelClass = $this->getModelClass();
        $items = $modelClass::onlyTrashed()->orderBy('nombre')->paginate(10);
        return view($this->getViewFolder() . '.eliminadas', compact('items'));
    }

    /**
     * Restaurar registro
     */
    public function restaurar($id)
    {
        $modelClass = $this->getModelClass();
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()
            ->route($this->getRouteIndex())
            ->with('success', $this->getEntityName() . ' "' . $item->nombre . '" restaurado correctamente.');
    }

    /**
     * Eliminar registro definitivamente
     */
    public function borrarDefinitivo($id)
    {
        $modelClass = $this->getModelClass();
        $item = $modelClass::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return redirect()
            ->route($this->getRouteEliminadas())
            ->with('success', $this->getEntityName() . ' "' . $item->nombre . '" eliminada definitivamente.');
    }

    /**
     * Métodos abstractos que cada controlador debe definir
     */
    abstract protected function getModelClass(): string;
    abstract protected function getViewFolder(): string;
    abstract protected function getRouteIndex(): string;
    abstract protected function getRouteEliminadas(): string;
    abstract protected function getEntityName(): string;
}
