<?php

namespace Database\Seeders;

use App\Models\Estado;
use App\Services\InegiService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = new InegiService();

        $this->command->info('Consultando servicio INEGI...');

        try {
            $estados = $service->obtenerEstados();

            foreach ($estados as $estadoRaw) {
                // Normalizar claves a minúsculas para evitar inconsistencias
                $estado = array_change_key_case($estadoRaw, CASE_LOWER);
                Estado::updateOrCreate(
                    ['cvegeo' => $estado['cvegeo']],
                    [
                        'cve_ent' => $estado['cve_agee'] ?? null,
                        'nomgeo' => $estado['nom_agee'] ?? 'Sin nombre',
                        'nom_abrev' => $estado['nom_abrev'] ?? null,
                        'pob_total' => $estado['pob'] ?? null,
                        'pob_femenina' => $estado['pob_fem'] ?? null,
                        'pob_masculina' => $estado['pob_mas'] ?? null,
                        'total_viviendas_habitadas' => $estado['viv'] ?? null,
                    ]
                );
            }

            $this->command->info('✅ ' . count($estados) . ' estados importados correctamente.');
        } catch (\Exception $e) {
            $this->command->error('❌ Error al importar: ' . $e->getMessage());
        }
    }
}
