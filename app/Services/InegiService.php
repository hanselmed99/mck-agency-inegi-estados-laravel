<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InegiService
{
    protected string $baseUrl = 'https://gaia.inegi.org.mx/wscatgeo/mgee/';

    /**
     * Obtiene los 32 estados de México desde el servicio INEGI.
     * Retorna un array con los datos o lanza una excepción con mensaje descriptivo.
     */
    public function obtenerEstados(): array
    {
        try {
            $response = Http::timeout(15)->get($this->baseUrl);

            if ($response->failed()) {
                throw new \Exception("El servicio INEGI respondió con status: " . $response->status());
            }

            $data = $response->json();

            if (!isset($data['datos']) || !is_array($data['datos'])) {
                throw new \Exception("La respuesta del servicio no contiene la clave 'datos'.");
            }

            return $data['datos'];

        } catch (\Exception $e) {
            Log::error('InegiService Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
