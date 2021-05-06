<div class="max-w-md py-4 px-8 bg-white shadow-lg rounded-lg my-20 mx-auto">
    <div class="items-center">
        <h2 class="text-gray-800 text-3xl font-semibold"></h2>
        <div class="flex flex-col max-w-lg">
            @if(!empty($logo))
                <div class="py2 px-4">
                    <img src="{{ $logo }}" alt="{{ $logo_alt ?? 'Company logo' }}">
                </div>
            @endif
            <div class="flex flex-col justify-between">
                <img src="data:image/png;base64,{{ $pix }}" alt="PIX QR Code">
                <p class="text-md">{{ $qrCodeDescription ?? '' }}</p>
            </div>
        </div>
    </div>
</div>