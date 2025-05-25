@once
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
@endonce

<div class="w-full h-[400px] sm:h-[500px] md:h-[600px] mt-4 rounded border dark:border-gray-600 shadow" id="trip-map">



    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const latitude = {{ $latitude }};
                const longitude = {{ $longitude }};
                const label = @json($label ?? 'Fishing Spot');

                const map = L.map('trip-map').setView([latitude, longitude], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                L.marker([latitude, longitude]).addTo(map).bindPopup(label).openPopup();

                setTimeout(() => map.invalidateSize(), 200);
            });
        </script>

    @endpush
</div>