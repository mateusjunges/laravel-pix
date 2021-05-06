<link rel="stylesheet" href="{{ asset('vendor/laravel-pix/css/app.css') }}">

<div class="flex flex-col ">
    @if(!empty($logo))
        <div class="py2 px-4">
            <img src="{{ $logo }}" alt="{{ $logo_alt ?? 'Company logo' }}">
        </div>
    @endif
    <div class="flex flex-col justify-between">
        <img src="data:image/png;base64,{{ $image }}" alt="PIX QR Code">
        <p>{{ $qrCodeDescription ?? '' }}</p>
    </div>
</div>