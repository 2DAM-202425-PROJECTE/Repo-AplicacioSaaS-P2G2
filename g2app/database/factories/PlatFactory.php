<?php

namespace Database\Factories;

use App\Models\Plat;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatFactory extends Factory
{
    protected $model = Plat::class;

    public function definition()
    {
        // Custom plat names
        $platNames = [
            'Paella Valenciana',
            'Pizza Margherita',
            'Hamburguesa Clásica',
            'Ensalada César',
            'Sushi Variado',
            'Tacos al Pastor',
            'Curry de Pollo',
            'Sopa de Cebolla',
            'Risotto de Champiñones',
            'Pasta Carbonara',
            'Lasaña Boloñesa',
            'Costillas a la Barbacoa',
            'Pulpo a la Gallega',
            'Croquetas de Jamón',
            'Gazpacho Andaluz',
            'Tortilla Española',
            'Crepes de Nutella',
            'Tiramisú',
            'Brownie con Helado',
            'Arroz con Leche',
        ];

        // Custom plat descriptions
        $platDescriptions = [
            'Arroz con pollo, conejo y verduras frescas.',
            'Tomate, mozzarella y albahaca fresca.',
            'Carne de res, lechuga, tomate y queso.',
            'Lechuga romana, pollo, crutones y aderezo César.',
            'Variedad de pescado fresco y arroz.',
            'Carne de cerdo marinada y piña.',
            'Pollo con salsa de curry y arroz basmati.',
            'Cebolla caramelizada y caldo de carne.',
            'Arroz cremoso con champiñones y parmesano.',
            'Pasta con huevo, panceta y queso pecorino.',
            'Pasta con carne, tomate y bechamel.',
            'Costillas de cerdo con salsa barbacoa.',
            'Pulpo cocido con pimentón y aceite de oliva.',
            'Croquetas caseras de jamón ibérico.',
            'Sopa fría de tomate y verduras.',
            'Tortilla de patatas y cebolla.',
            'Crepes con crema de avellanas y chocolate.',
            'Postre italiano de café y mascarpone.',
            'Brownie con helado de vainilla.',
            'Postre de arroz con leche, canela y limón.',
        ];

        return [
            'nom' => $this->faker->randomElement($platNames),
            'descripcio' => $this->faker->randomElement($platDescriptions),
            'id_restaurant' => Restaurant::inRandomOrder()->first()->id,
            'foto' => $this->faker->imageUrl(640, 480, 'food', true),
            'preu' => $this->faker->randomFloat(2, 5, 30),
            'vegetaria' => $this->faker->boolean(25),
            'vega' => $this->faker->boolean(10),
            'kosher' => $this->faker->boolean(5),
            'halal' => $this->faker->boolean(5),
            'keto' => $this->faker->boolean(10),
            'carn_vermella' => $this->faker->boolean(30),
            'gluten' => $this->faker->boolean(15),
            'lactics' => $this->faker->boolean(25),
            'crustaci' => $this->faker->boolean(30),
            'ous' => $this->faker->boolean(30),
            'lupines' => $this->faker->boolean(25),
            'mostassa' => $this->faker->boolean(35),
            'cacahuats' => $this->faker->boolean(20),
            'soja' => $this->faker->boolean(40),
        ];
    }
}
