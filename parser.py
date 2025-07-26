import mysql.connector
import json

config = {
    'host': '127.0.0.1',
    'user': 'admin',
    'password': 'admin',
    'database': 'fish'
}

columns = [
    "id",  # legacy_id
    "fishing_location", "date11_date", "date11_month", "date11_year",
    "action", "moon_phase", "wind_direction", "water_temp", "water_depth",
    "fish_species1", "bait_lure1", "photo1", "photo2", "photo3", "details"
]

trips, catches, images = [], [], []

# Initialize here to avoid NameError in finally block
conn = None
cursor = None

month_map = {
    "january": 1, "february": 2, "march": 3, "april": 4,
    "may": 5, "june": 6, "july": 7, "august": 8,
    "september": 9, "october": 10, "november": 11, "december": 12
}

try:
    conn = mysql.connector.connect(**config)
    cursor = conn.cursor(dictionary=True)
    cursor.execute(f"SELECT {', '.join(columns)} FROM fishinglog")

    for row in cursor.fetchall():
        legacy_id = row["id"]
        location = row["fishing_location"]
        if location in [None, '', 0]:
            continue

        # Safe date parsing
        date_str = None
        try:
            y = int(row["date11_year"]) if row["date11_year"] not in [None, '', 0] else None
            d = int(row["date11_date"]) if row["date11_date"] not in [None, '', 0] else None
            month_name = str(row["date11_month"]).strip().lower()
            m = month_map.get(month_name)

            if y and m and d and (1900 <= y <= 2100) and (1 <= m <= 12) and (1 <= d <= 31):
                date_str = f"{y:04d}-{m:02d}-{d:02d}"
            else:
                print(f"[DEBUG] Invalid date components for ID {legacy_id}: year={y}, month={month_name}, day={d}")
        except Exception as e:
            print(f"[!] Error parsing date for legacy_id {legacy_id}: {e}")

        # Trip
        trips.append({
            "legacy_id": legacy_id,
            "title": location,
            "latitude": None,
            "longitude": None,
            "location": location,
            "date": date_str,
            "notes": row["details"] if row["details"] not in [None, '', 0] else None,
            "precipitation": None,
            "moon_phase": row["moon_phase"] if row["moon_phase"] not in [None, '', 0] else None,
            "wind_speed": None,
            "wind_direction": row["wind_direction"] if row["wind_direction"] not in [None, '', 0] else None,
            "air_temp": None,
            "action": row["action"] if row["action"] not in [None, '', 0] else None
        })

        # Catch
        if row["fish_species1"] not in [None, '', 0]:
            catches.append({
                "legacy_id": legacy_id,
                "species": row["fish_species1"],
                "weight": None,
                "length": None,
                "quantity": 1,
                "bait": row["bait_lure1"] if row["bait_lure1"] not in [None, '', 0] else None,
                "depth": row["water_depth"] if row["water_depth"] not in [None, '', 0] else None,
                "water_temp": row["water_temp"] if row["water_temp"] not in [None, '', 0] else None,
                "notes": None
            })

        # Images
        for photo in ["photo1", "photo2", "photo3"]:
            img = row[photo]
            if img not in [None, '', 0, 'no_image.gif']:
                images.append({
                    "legacy_id": legacy_id,
                    "image_path": f"trip_images/{img}",
                    "caption": None
                })

    # Write to JSON files
    with open("trips1.json", "w", encoding="utf-8") as f:
        json.dump(trips, f, indent=4, ensure_ascii=False)

    with open("catches1.json", "w", encoding="utf-8") as f:
        json.dump(catches, f, indent=4, ensure_ascii=False)

    with open("images1.json", "w", encoding="utf-8") as f:
        json.dump(images, f, indent=4, ensure_ascii=False)

    print(f"Exported {len(trips)} trips, {len(catches)} catches, and {len(images)} images.")

except mysql.connector.Error as err:
    print(f"Database error: {err}")

finally:
    if cursor:
        cursor.close()
    if conn and conn.is_connected():
        conn.close()