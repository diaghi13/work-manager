<?php

namespace Database\Seeders;

use App\Models\MeasureUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeasureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeasureUnit::create(["abbreviation" => "NR", "description" => "Numero"]);
        MeasureUnit::create(["abbreviation" => "PZ", "description" => "Pezzo"]);
        MeasureUnit::create(["abbreviation" => "CT", "description" => "Cartone"]);
        MeasureUnit::create(["abbreviation" => "CF", "description" => "Confezione"]);
        MeasureUnit::create(["abbreviation" => "GR", "description" => "Grammo"]);
        MeasureUnit::create(["abbreviation" => "KG", "description" => "Chilogramm0"]);
        MeasureUnit::create(["abbreviation" => "Q", "description" => "Quintale"]);
        MeasureUnit::create(["abbreviation" => "T", "description" => "Tonnellata"]);
        MeasureUnit::create(["abbreviation" => "mL", "description" => "Millimetro"]);
        MeasureUnit::create(["abbreviation" => "CM", "description" => "Centimetro"]);
        MeasureUnit::create(["abbreviation" => "MT", "description" => "Metro"]);
        MeasureUnit::create(["abbreviation" => "ML", "description" => "Metro lineare"]);
        MeasureUnit::create(["abbreviation" => "KM", "description" => "Chilometro"]);
        MeasureUnit::create(["abbreviation" => "DQ", "description" => "Decimetro quadro"]);
        MeasureUnit::create(["abbreviation" => "MQ", "description" => "Metro quadro"]);
        MeasureUnit::create(["abbreviation" => "MC", "description" => "Metro cubo"]);
        MeasureUnit::create(["abbreviation" => "TM", "description" => "Piede quadro"]);
        MeasureUnit::create(["abbreviation" => "HA", "description" => "Ettaro"]);
        MeasureUnit::create(["abbreviation" => "MIN", "description" => "Minuto"]);
        MeasureUnit::create(["abbreviation" => "H", "description" => "Ora"]);
        MeasureUnit::create(["abbreviation" => "GG", "description" => "Giorno"]);
        MeasureUnit::create(["abbreviation" => "L", "description" => "Litro"]);
        MeasureUnit::create(["abbreviation" => "HL", "description" => "Ettolitro"]);
        MeasureUnit::create(["abbreviation" => "PA", "description" => "Paio"]);
        MeasureUnit::create(["abbreviation" => "KWh", "description" => "Kilowattora"]);
        MeasureUnit::create(["abbreviation" => "MWh", "description" => "Megawattora"]);
        MeasureUnit::create(["abbreviation" => "KT", "description" => "Carato"]);
        MeasureUnit::create(["abbreviation" => "Kh", "description" => "Kilogrado"]);
        MeasureUnit::create(["abbreviation" => "DZ", "description" => "Dozzina"]);
        MeasureUnit::create(["abbreviation" => "grs", "description" => "Grossa"]);
    }
}
