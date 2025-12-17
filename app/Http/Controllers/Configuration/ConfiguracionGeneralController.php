<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Configuration\ConfiguracionGeneral;
use App\Models\Configuration\Impuesto;
use App\Models\Configuration\Moneda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionGeneralController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $configuracionGeneral = ConfiguracionGeneral::first();
        $monedas = Moneda::orderBy('nombre')->get();
        $impuestos = Impuesto::activo()->orderBy('nombre')->get();

        return view(
            'configuration.general.edit',
            compact('configuracionGeneral', 'monedas', 'impuestos')
        );
    }


    public function update(Request $request)
    {
        $configuracionGeneral = ConfiguracionGeneral::actual() ?? new ConfiguracionGeneral(); // Obtener o crear una instancia

        $validated = $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:255',
            'pais' => 'nullable|string|max:255',
            'moneda_id' => 'required|exists:monedas,id',
            'impuesto_id' => 'required|exists:impuestos,id',
            'timezone' => 'required|string|timezone',
        ]);

        if ($request->hasFile('logo')) {
            
            // 1. Eliminar logo anterior (si existe)
            if ($configuracionGeneral->logo) {
                Storage::disk('public')->delete($configuracionGeneral->logo);
            }
            
            // 2. Almacenar el nuevo logo en 'config' dentro del disco 'public'
            // El resultado será una ruta como 'config/nombre_hash.png'
            $validated['logo'] = $request->file('logo')->store('config', 'public');
        } else {
            // Si no se sube un nuevo archivo, conservar el existente
            if (!$configuracionGeneral->exists) {
                 // Si es la primera creación y no hay logo, se queda null
                 $validated['logo'] = null;
            } else {
                // Si estamos actualizando, conservamos el logo anterior si no se subió uno nuevo
                $validated['logo'] = $configuracionGeneral->logo;
            }
        }

        ConfiguracionGeneral::updateOrCreate(['id' => 1], $validated);

        return back()->with('success', 'Configuración actualizada correctamente.');
    }

}
