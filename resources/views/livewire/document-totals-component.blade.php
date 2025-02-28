<?php
    $netPrice = 0;
    $vatPrice = 0;
    $grossPrice = 0;
?>

<div>
    <div class="flex justify-between py-2">
        <span class="font-thin text-gray-700">Totale imponibile</span>
        <span class="font-bold">{{money(number_format($netPrice, 2, '.', ''), 'EUR')}}</span>
    </div>
    <div class="flex justify-between py-2">
        <span class="font-thin text-gray-700">Totale IVA</span>
        <span class="font-bold">{{money(number_format($vatPrice, 2, '.', ''), 'EUR')}}</span>
    </div>
    <div class="flex justify-between py-2">
        <span class="font-thin text-gray-700">Totale documento</span>
        <span class="font-bold">{{money(number_format($grossPrice, 2, '.', ''), 'EUR')}}</span>
    </div>
</div>
