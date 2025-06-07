Here is a comprehensive README.md file for your Laravel-based Fishing Trip Logger project, including all steps for deployment and importing the legacy JSON data:

⸻

🐟 Fishing Trip Logger – Legacy Data Migration & Deployment Guide

This Laravel application logs fishing trips, catches, and images, and allows importing legacy data from structured JSON files.

⸻

🚀 Deployment Guide

1. Clone the repository


cd fishing-trip-logger


⸻

2. Install dependencies

composer install


⸻

3. Set up your environment

Copy the .env example file and configure database and other settings:

cp .env.example .env
php artisan key:generate

Edit .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password


⸻

4. Set directory permissions (Linux/macOS)

chmod -R 775 storage bootstrap/cache


⸻

5. Run migrations

php artisan migrate


⸻

6. Seed a default user (optional)

php artisan db:seed
⸻

🧩 Importing Legacy Data

The project includes a custom Artisan command that imports legacy fishing data from JSON files.

1. Prepare import files

Place your JSON files inside:

storage/app/import/files/

File structure must include:
	•	trips.json – List of trips (must contain legacy_id)
	•	catches.json – Flat list of catches with legacy_id mapping back to a trip
	•	images.json – Flat list of images with legacy_id mapping back to a trip


⸻

2. Run the import command

php artisan legacy:import

This will:
	•	Import trips using trips.json
	•	Import related catches using catches.json
	•	Import images using images.json

It logs:
	•	Success counts
	•	Skipped entries
	•	Error details if any issue occurs


⸻


📬 Support

For help, reach out to the repository maintainer or open an issue.

⸻
