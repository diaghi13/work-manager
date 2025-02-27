<div>
    <div class="flex justify-between text-xs p-3">
        <span>CF: {{$customer?->vat_id}}</span>
        <span>P.IVA: {{$customer?->vat_code}}</span>
        <span>C. Dest.: {{$customer?->sdi_code}}</span>
    </div>
    <div class="text-xs p-3">
        <h6 class="font-bold">Indirizzo</h6>
        <p>{{$customer?->street}}, {{$customer?->number}}</p>
        <p>{{$customer?->city}}</p>
        <p>{{$customer?->zip_code}}</p>
        <p>({{$customer?->state}})</p>
    </div>
</div>
