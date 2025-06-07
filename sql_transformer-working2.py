import re
import json
from datetime import datetime

# Output data
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

def parse_line(line):
    # Extract values assuming they are wrapped in parentheses
    match = re.search(r'\((.*)\)', line)
    if not match:
        return

    raw = match.group(1)

    # Split by "', '" but keep the quotes
    values = re.findall(r"'(?:[^']|'')*'|[^,]+", raw)
    values = [v.strip().strip("'") if v.strip().startswith("'") else v.strip() for v in values]

    if len(values) < 70:
        return

    legacy_id = int(values[0])

    # --- Trip Mapping ---
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
    # Only add trip if it has a date or title
    if trip["date"] or trip["title"]:
        trips.append(trip)

    # --- Catch Mapping ---
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

    # --- Image Mapping ---
    for i in range(3):
        img = normalize(values[60 + i])
        if img:
            images.append({
                "trip_legacy_id": legacy_id,
                "image_path": f"trip_images/{img}",
                "caption": None
            })

# --- MAIN LOOP ---
sql_file_path = "SQL-fishinglog-5-06-2013.sql"
with open(sql_file_path, 'r', encoding='utf-8') as f:
    for line_num, line in enumerate(f, 1):
        if line.strip().startswith("INSERT INTO") or line.strip().startswith("--"):
            continue
        parse_line(line)

# --- Output Files ---
with open("trips.json", "w") as f: json.dump(trips, f, indent=2)
with open("catches.json", "w") as f: json.dump(catches, f, indent=2)
with open("images.json", "w") as f: json.dump(images, f, indent=2)

# --- Summary ---
print("\n--- Summary ---")
print(f"Trips parsed: {len(trips)}")
print(f"Catches parsed: {len(catches)}")
print(f"Images parsed: {len(images)}")