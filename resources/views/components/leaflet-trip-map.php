@props(['trips'])

@if ($trips->count())
<div class="w-full max-w-3xl mt-10">
    <h2 class="text-xl font-semibold mb-2">🗺️ Your Trip Locations</h2>
    <div id="map" class="w-full h-80 rounded-lg shadow-md"></div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trips = @json($trips);

        if (trips.length) {
            const map = L.map('map').setView([trips[0].latitude, trips[0].longitude], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            trips.forEach(trip => {
                if (trip.latitude && trip.longitude) {
                    L.marker([trip.latitude, trip.longitude])
                        .addTo(map)
                        .bindPopup(`<strong>Trip Location</strong>`);
                }
            });
        }
    });
</script>
@endpush
@endif