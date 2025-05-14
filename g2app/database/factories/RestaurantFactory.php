<?php

namespace Database\Factories;

use App\Models\Municipio;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Symfony\Component\Finder\Finder;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('es_ES'); // Spanish locale

        // Directori de les imatges dels restaurants
        $imageDirectory = 'restaurants';
        $imagePath = public_path('images/restaurants'); // Path to the folder containing images

        // S'assegura que el directori existeix
        Storage::disk('public')->makeDirectory($imageDirectory);

        // Usa Symfony Finder per obtenir tots els fitxers del directori
        $finder = new Finder();
        $finder->files()->in($imagePath);

        $images = [];
        foreach ($finder as $file) {
            $images[] = $file->getFilename();
        }

        // Pick a random image from the array of images
        if (count($images) > 0) {
            $imageName = $faker->randomElement($images);
            $imageFullPath = $imageDirectory . '/' . $imageName;


            // Copiar la imatge a la carpeta de storage
            Storage::disk('public')->put(
                $imageFullPath,
                file_get_contents($imagePath . '/' . $imageName)
            );
        } else {
            $imageFullPath = null;
        }

        return [
            'nom' => $faker->randomElement(Restaurant::$noms),
            'municipio_id' => Municipio::inRandomOrder()->first()->id,
            'descripcio' => $faker->paragraph,
            'telefon' => $faker->phoneNumber,
            'tipus_cuina' => $faker->randomElement(Restaurant::$TIPUS_CUINA),
            'hora_obertura' => $faker->time('H:i'),
            'hora_tancament' => $faker->time('H:i'),
            'carrer' => $faker->streetAddress,
            'profile_image' => $imageFullPath,
        ];
    }
}
