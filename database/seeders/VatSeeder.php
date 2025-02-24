<?php

namespace Database\Seeders;

use App\Models\Vat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vat::create(["code" => "22", "description" => "Iva 22%", "vat_nature" => "", "value" => 22]);
        Vat::create(["code" => "10", "description" => "Iva 10%", "vat_nature" => "", "value" => 10]);
        Vat::create(["code" => "04", "description" => "Iva 4%", "vat_nature" => "", "value" => 4]);
        Vat::create(["code" => "05", "description" => "Iva 5%", "vat_nature" => "", "value" => 5]);
        Vat::create(["code" => "21", "description" => "Iva 21%", "vat_nature" => "", "value" => 21]);
        Vat::create(["code" => "20", "description" => "Iva 20%", "vat_nature" => "", "value" => 20]);
        Vat::create(["code" => "A124COV", "description" => "Art.124 DL34/2020 e successive modifiche - misure contenimento COVID-19", "vat_nature" => "N4", "value" => 0]);
        Vat::create(["code" => "L178COV", "description" => "Iva 20%", "vat_nature" => "Art. 1 comma 453 L. 178/2020 - Vaccini COVID-19", "value" => 0]);
        Vat::create(["code" => "200", "description" => "Iva 2% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 2]);
        Vat::create(["code" => "400", "description" => "Iva 4% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 4]);
        Vat::create(["code" => "600", "description" => "Iva 6% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 6]);
        Vat::create(["code" => "640", "description" => "Iva 6,40% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 6.4]);
        Vat::create(["code" => "700", "description" => "Iva 7% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 7]);
        Vat::create(["code" => "730", "description" => "Iva 7,30% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 7.3]);
        Vat::create(["code" => "750", "description" => "Iva 7,50% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 7.5]);
        Vat::create(["code" => "765", "description" => "Iva 7,65% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 7.65]);
        Vat::create(["code" => "795", "description" => "Iva 7,95% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 7.95]);
        Vat::create(["code" => "830", "description" => "Iva 8,30% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 8.3]);
        Vat::create(["code" => "850", "description" => "Iva 8,50% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 8.5]);
        Vat::create(["code" => "880", "description" => "Iva 8,80% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 8.8]);
        Vat::create(["code" => "950", "description" => "Iva 9,50% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 9.5]);
        Vat::create(["code" => "1000", "description" => "Iva 10% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 10]);
        Vat::create(["code" => "1230", "description" => "Iva 12,30% - Agricoltori esonerati o conferimenti prodotti agricoli a cooperative", "vat_nature" => "", "value" => 12.3]);
        Vat::create(["code" => "A1001", "description" => "Esente art.10 n.1 - Crediti", "vat_nature" => "N4", "value" => 0]);
        Vat::create(["code" => "A1002", "description" => "Esente art.10 n.2 - Assicurazioni", "vat_nature" => "N4", "value" => 0]);
        Vat::create(["code" => "A1003", "description" => "Esente art.10 n.3 - Valute estere", "vat_nature" => "N4", "value" => 0]);
        Vat::create(["code" => "A1004", "description" => "Esente art.10 n.4 - Azioni obbligazioni quote", "vat_nature" => "N4", "value" => 0]);
        Vat::create(["code" => "A1005", "description" => "Esente art.10 n.5 - Riscossione tributi", "vat_nature" => "N4", "value" => 0]);
        Vat::create(["code" => "A1006", "description" => "Esente art.10 n.6 - Lotto lotterie concorsi", "vat_nature" => "N4", "value" => 0]);
        Vat::create(["code" => "A1007", "description" => "Esente art.10 n.7 - Scommesse", "vat_nature" => "N4", "value" => 0]);
    }
}
