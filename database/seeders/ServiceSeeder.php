<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Plomberie',
                'description' => 'Installation, réparation et maintenance des systèmes de plomberie.',
                'icon' => 'wrench',
                'is_active' => true,
            ],
            [
                'name' => 'Électricité',
                'description' => 'Installation électrique, dépannage et mise aux normes.',
                'icon' => 'lightning',
                'is_active' => true,
            ],
            [
                'name' => 'Climatisation',
                'description' => 'Installation et maintenance de systèmes de climatisation.',
                'icon' => 'snow',
                'is_active' => true,
            ],
            [
                'name' => 'Peinture',
                'description' => 'Travaux de peinture intérieure et extérieure.',
                'icon' => 'brush',
                'is_active' => true,
            ],
            [
                'name' => 'Rénovation',
                'description' => 'Travaux de rénovation complète ou partielle.',
                'icon' => 'hammer',
                'is_active' => true,
            ],
            [
                'name' => 'Nettoyage',
                'description' => 'Services de nettoyage professionnel.',
                'icon' => 'sparkles',
                'is_active' => true,
            ],
            [
                'name' => 'Dératisation',
                'description' => 'Extermination et prévention des nuisibles.',
                'icon' => 'bug',
                'is_active' => true,
            ],
            [
                'name' => 'Sécurité',
                'description' => 'Installation de systèmes de sécurité et alarmes.',
                'icon' => 'shield',
                'is_active' => true,
            ],
            [
                'name' => 'Déménagement',
                'description' => 'Services de déménagement et transport.',
                'icon' => 'truck',
                'is_active' => true,
            ],
            [
                'name' => 'Jardinage',
                'description' => 'Entretien d\'espaces verts et jardinage.',
                'icon' => 'leaf',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
