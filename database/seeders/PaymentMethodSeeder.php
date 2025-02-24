<?php

namespace Database\Seeders;


use App\Models\PaymentMethod;
use App\Models\PaymentMethodInstallment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create(
            ["name" => "A vista", "payment_method" => "MP01 - Contanti", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Assegni bancari", "payment_method" => "MP02 - Assegno", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Assegni circolari", "payment_method" => "MP03 - Assegno circolare", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 120 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 120])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 15 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 15])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 180 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 180])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 180 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 180])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30-60 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30-60 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30-60-90 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 3, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30-60-90 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 3, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30-60-90-120 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 4, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 30-60-90-120 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 4, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 45 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 45 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 60 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 60 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 60-90 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 60-90 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 60-90-120 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 3, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 60-90-120 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 3, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 90 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 90 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 90-120 gg data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico 90-120 gg fine mese", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120])
        ]);
        PaymentMethod::create(
            ["name" => "Bonifico a vista fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        // TODO: check for manual expiration
        PaymentMethod::create(
            ["name" => "Bonifico data scadenza", "payment_method" => "MP05 - Bonifico", "end_of_month" => false]
        );
        PaymentMethod::create(
            ["name" => "Bonifico fine mese data fattura", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Carta di credito", "payment_method" => "MP08 - Carta di pagamento", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Contanti", "payment_method" => "MP01 - Contanti", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Contrassegno", "payment_method" => "MP01 - Contanti", "number_of_installments" => 1, "end_of_month" => false]
        );
        PaymentMethod::create(
            ["name" => "Pagamento effettuato con assegno", "payment_method" => "MP02 - Assegno", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Pagamento effettuato con bonifico", "payment_method" => "MP05 - Bonifico", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Pagamento effettuato in contanti", "payment_method" => "MP01 - Contanti", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Pagamento POS", "payment_method" => "MP16 - Domiciliazione bancaria", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "Paypal", "payment_method" => "MP08 - Carta di pagamento", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 100 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 100])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 120 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 120])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 120 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 120])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 180 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 180])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 180 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 180])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30-60 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30-60 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30-60-90 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 3, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30-60-90 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 3, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30-60-90-120 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 4, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 30-60-90-120 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 4, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 35 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 35])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 40 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 40])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 45 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 45 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 45-75 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45]),
            new PaymentMethodInstallment(["days" => 75]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 45-75 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45]),
            new PaymentMethodInstallment(["days" => 75]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 60 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 60 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 60-90 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 60-90 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 60-90-120 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 3, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 60-90-120 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 3, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 85 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 85])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 85 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 85])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 90 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 90 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 90-120 gg data fattura", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. 90-120 gg fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "RI.BA. data fattura fine mese", "payment_method" => "MP12 - Riba", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(["name" => "RI.BA. data scadenza", "payment_method" => "MP12 - Riba", "end_of_month" => false]);
        PaymentMethod::create(
            ["name" => "Rimessa diretta", "payment_method" => "MP01 - Contanti", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30-60 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30-60 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30-60-90 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 3, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30-60-90 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 3, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30-60-90-120 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 4, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 30-60-90-120 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 4, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 30]),
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
            new PaymentMethodInstallment(["days" => 120]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 45 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 45 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 45])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 60 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 60 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 60-90 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 2, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 60-90 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 2, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 60]),
            new PaymentMethodInstallment(["days" => 90]),
        ]);
        PaymentMethod::create(
            ["name" => "SDD 90 gg data fattura", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => false]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "SDD 90 gg fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 90])
        ]);
        PaymentMethod::create(
            ["name" => "SDD data fattura fine mese", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => true]
        )->paymentMethodInstallments()->saveMany([
            new PaymentMethodInstallment(["days" => 0])
        ]);
        PaymentMethod::create(
            ["name" => "SDD data scadenza", "payment_method" => "MP19 - SEPA Direct Debit", "number_of_installments" => 1, "end_of_month" => false]
        );
        PaymentMethod::create(
            ["name" => "Trattenuta su somme già riscosse", "payment_method" => "MP22 - Trattenuta su somme già riscosse", "number_of_installments" => 0, "end_of_month" => false]
        );
    }
}
