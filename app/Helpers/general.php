<?php

use App\Models\Configuration\ConfiguracionGeneral;

if (! function_exists('general_config')) {

    /**
     * Obtiene la configuración general cacheada en memoria
     */
    function general_config(): ?ConfiguracionGeneral
    {
        static $config = null;

        if ($config === null) {
            $config = ConfiguracionGeneral::actual();
        }

        return $config;
    }
}
