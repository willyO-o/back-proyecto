<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categorias = [
            [
                'nombre' => 'Cafeterias',
                'descripcion' =>  'establecimeintos enfocados a la venta de cafe y productos relacionados',
                'icono' => 'fa-solid fa-mug-saucer'
            ],
            [
                'nombre' => 'Restaurantes',
                'descripcion' =>  'establecimeintos enfocados a la venta de comida y productos relacionados',
                'icono' => 'fa-solid fa-utensils'
            ],
            [
                'nombre' => 'Gimnasios',
                'descripcion' =>  'establecimeintos enfocados a la venta de cafe y productos relacionados',
                'icono' => 'fa-solid fa-dumbbell'
            ],
            [
                'nombre' => 'Librerias',
                'descripcion' =>  'establecimeintos enfocados a la venta productos de lectura',
                'icono' => 'fa-solid fa-book-open'
            ],
            [
                'nombre' => 'Tiendas de ropa',
                'descripcion' =>  'establecimeintos enfocados a la venta de ropa y productos relacionados',
                'icono' => 'fa-solid fa-shop'
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
