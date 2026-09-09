<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Zona;
use App\Models\Llamado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. ELIMINAR PRIMERO LOS LLAMADOS (por la foreign key)
        Llamado::query()->delete();

        // 2. ELIMINAR LAS ZONAS
        Zona::query()->delete();

        // 3. CREAR LAS 3 ZONAS QUE NECESITAS
        $zonas = [
            'Invernadero' => ['humedad' => 65, 'temperatura' => 22],
            'Hidroponía' => ['humedad' => 45, 'temperatura' => 18],
            'Mantenimiento' => ['humedad' => 50, 'temperatura' => 16],
        ];

        foreach ($zonas as $nombre => $datos) {
            Zona::create([
                'nombre' => $nombre,
                'humedad' => $datos['humedad'],
                'temperatura' => $datos['temperatura'],
                'tiene_alerta' => false,
            ]);
        }

        $this->command->info('✅ Zonas creadas: ' . count($zonas));

        // 4. CREAR ADMIN
        User::firstOrCreate(
            ['email' => 'admin@parque.gob'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'rol' => 'admin',
            ]
        );

        // 5. CREAR EMPLEADO DE PRUEBA
        User::firstOrCreate(
            ['email' => 'laura@parque.gob'],
            [
                'name' => 'Laura García',
                'password' => bcrypt('emp123'),
                'rol' => 'empleado',
                'tarea' => 'Encargada de mantenimiento',
                'telefono' => '221-555-1234',
            ]
        );

        // 6. CREAR LLAMADOS DE EJEMPLO
        $zonasDb = Zona::all();
        $admin = User::where('email', 'admin@parque.gob')->first();

        if ($zonasDb->isNotEmpty() && $admin) {
            $llamados = [
                [
                    'zona_nombre' => 'Invernaderos',
                    'tipo' => 'Emergencia',
                    'estado' => 'Pendiente',
                    'descripcion' => 'Temperatura crítica: 42°C detectada en sector norte',
                    'dias_atras' => 0,
                ],
                [
                    'zona_nombre' => 'Hidroponía',
                    'tipo' => 'Emergencia',
                    'estado' => 'Atendido',
                    'descripcion' => 'Humedad por debajo del umbral mínimo (38%)',
                    'dias_atras' => 1,
                ],
                [
                    'zona_nombre' => 'Invernaderos',
                    'tipo' => 'Normal',
                    'estado' => 'Atendido',
                    'descripcion' => 'Mantenimiento preventivo de sensores',
                    'dias_atras' => 3,
                ],
                [
                    'zona_nombre' => 'Mantenimiento',
                    'tipo' => 'Normal',
                    'estado' => 'Pendiente',
                    'descripcion' => 'Solicitud de insumos para mantenimiento',
                    'dias_atras' => 1,
                ],
                [
                    'zona_nombre' => 'Hidroponía',
                    'tipo' => 'Emergencia',
                    'estado' => 'Atendido',
                    'descripcion' => 'Falla en sistema de bombeo',
                    'dias_atras' => 2,
                ],
                [
                    'zona_nombre' => 'Invernaderos',
                    'tipo' => 'Normal',
                    'estado' => 'Atendido',
                    'descripcion' => 'Revisión de sistemas de riego',
                    'dias_atras' => 5,
                ],
            ];

            foreach ($llamados as $llamado) {
                $zona = $zonasDb->firstWhere('nombre', $llamado['zona_nombre']);
                if ($zona) {
                    Llamado::create([
                        'zona_id' => $zona->id,
                        'user_id' => $admin->id,
                        'tipo' => $llamado['tipo'],
                        'estado' => $llamado['estado'],
                        'descripcion' => $llamado['descripcion'],
                        'created_at' => now()->subDays($llamado['dias_atras']),
                        'updated_at' => now()->subDays($llamado['dias_atras']),
                    ]);
                }
            }

            $this->command->info('✅ Llamados de ejemplo creados.');
        }
    }
}