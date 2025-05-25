@once
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
@endonce

<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Select Location on Map
    </label>
    <div id="picker-map" class="rounded-lg shadow border border-gray-300 dark:border-gray-600" style="height: 300px;">
    </div>

    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
</div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const pickerMap = L.map('picker-map').setView([-30.000, 135.000], 6); // South Australia center
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(pickerMap);

            let marker;

            pickerMap.on('click', function (e) {
                if (marker) {
                    pickerMap.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(pickerMap);
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
            });

            setTimeout(() => pickerMap.invalidateSize(), 200);
        });
    </script>
@endpush