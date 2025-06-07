import re
import json
from datetime import datetime

processed_ids = set()
sql_file_path = "SQL-fishinglog-5-06-2013.sql"

trips, catches, images = [], [], []

def is_number(val):
    try:
        float(val)
        return True
    except:
        return False

def normalize(val):
    val = val.strip().strip("'").strip()
    return val if val not in ['0', '', 'None', 'no_image.gif'] else None

def parse_entry(entry, line_num=None):
    try:
        # Smart split: handles quoted strings and commas
        values = re.findall(r"'(?:\\'|[^'])*'|[^,()]+", entry.strip().strip(',()'))
        values = [v.strip().strip("'") if v.strip().startswith("'") else v.strip() for v in values]

        if len(values) < 68:
            print(f"Line {line_num}: Skipped due to insufficient values ({len(values)})")
            return

        legacy_id = int(values[0])
        if legacy_id in processed_ids:
            return
        processed_ids.add(legacy_id)

        # --- Trip ---
        date_str = f"{values[6]}-{values[5]}-{values[4]}"
        try:
            date = datetime.strptime(date_str, '%Y-%B-%d').date().isoformat()
        except ValueError:
            date = None

        trip = {
            "legacy_id": legacy_id,
            "user_id": 1,
            "title": normalize(values[2]),
            "location": ", ".join(filter(None, [normalize(values[2]), normalize(values[9]), normalize(values[8])])),
            "date": date,
            "notes": normalize(values[64]),
            "precipitation": float(values[16]) if is_number(values[16]) else None,
            "moon_phase": normalize(values[12]),
            "wind_speed": float(values[18].split()[0]) if is_number(values[18].split()[0]) else None,
            "wind_direction": normalize(values[19]),
            "air_temp": float(values[13]) if is_number(values[13]) else None,
            "latitude": float(values[66]) if is_number(values[66]) else None,
            "longitude": float(values[67]) if is_number(values[67]) else None
        }
        trips.append(trip)

        # --- Catches ---
        for i in range(1, 6):
            base = 30 + (i - 1) * 7
            if base >= len(values):
                continue
            qty = int(values[base]) if values[base].isdigit() else 0
            if qty > 0:
                catch = {
                    "trip_legacy_id": legacy_id,
                    "species": normalize(values[base + 1]),
                    "weight": float(values[base + 2]) if is_number(values[base + 2]) else None,
                    "length": float(values[base + 3]) if is_number(values[base + 3]) else None,
                    "bait": normalize(values[base + 5]),
                    "quantity": qty,
                    "depth": normalize(values[27]),
                    "water_temp": float(values[23]) if is_number(values[23]) else None,
                    "notes": None
                }
                catches.append(catch)

        # --- Images ---
        for i in range(3):
            idx = 61 + i
            if idx >= len(values):
                continue
            img = normalize(values[idx])
            if img:
                images.append({
                    "trip_legacy_id": legacy_id,
                    "image_path": f"trip_images/{img}",
                    "caption": None
                })

    except Exception as e:
        print(f"[Line {line_num}] Error parsing entry: {e}")

# --- MAIN ---
entries_processed = 0

with open(sql_file_path, 'r', encoding='utf-8') as f:
    for line_num, line in enumerate(f, 1):
        line = line.strip()
        if not line or not line.lower().startswith("insert into"):
            continue

        # Correct regex to extract each (....) value group from the line
        matches = re.findall(r"\(([^()]+)\)", line)
        if not matches:
            print(f"[Line {line_num}] No match found")
            continue

        for match in matches:
            parse_entry(match, line_num)
            entries_processed += 1

# --- Save Output ---
with open("trips.json", "w") as f: json.dump(trips, f, indent=2)
with open("catches.json", "w") as f: json.dump(catches, f, indent=2)
with open("images.json", "w") as f: json.dump(images, f, indent=2)

# --- Summary ---
print("\n--- Summary ---")
print(f"Entries processed: {entries_processed}")
print(f"Trips parsed: {len(trips)}")
print(f"Catches parsed: {len(catches)}")
print(f"Images parsed: {len(images)}")