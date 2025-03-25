<div>
    @foreach($getRecord()->tenants as $tenant)
        {{ $tenant->id }} <br>
    @endforeach
</div>
