-- Database schema for SQLite

-- Table for admins
CREATE TABLE IF NOT EXISTS admins (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    password TEXT NOT NULL
);

-- Table for property
CREATE TABLE IF NOT EXISTS property (
    id TEXT PRIMARY KEY,
    user_id TEXT NOT NULL,
    property_name TEXT NOT NULL,
    address TEXT NOT NULL,
    price TEXT NOT NULL,
    type TEXT NOT NULL,
    offer TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT '',
    furnished TEXT NOT NULL DEFAULT '',
    bhk TEXT NOT NULL DEFAULT '',
    deposite TEXT NOT NULL DEFAULT '',
    bedroom TEXT NOT NULL DEFAULT '',
    bathroom TEXT NOT NULL DEFAULT '',
    balcony TEXT NOT NULL DEFAULT '',
    carpet TEXT NOT NULL DEFAULT '',
    age TEXT NOT NULL DEFAULT '',
    total_floors TEXT NOT NULL DEFAULT '',
    room_floor TEXT NOT NULL DEFAULT '',
    loan TEXT NOT NULL DEFAULT '',
    lift TEXT NOT NULL DEFAULT 'no',
    security_guard TEXT NOT NULL DEFAULT 'no',
    play_ground TEXT NOT NULL DEFAULT 'no',
    garden TEXT NOT NULL DEFAULT 'no',
    water_supply TEXT NOT NULL DEFAULT 'no',
    power_backup TEXT NOT NULL DEFAULT 'no',
    parking_area TEXT NOT NULL DEFAULT 'no',
    gym TEXT NOT NULL DEFAULT 'no',
    shopping_mall TEXT NOT NULL DEFAULT 'no',
    hospital TEXT NOT NULL DEFAULT 'no',
    school TEXT NOT NULL DEFAULT 'no',
    market_area TEXT NOT NULL DEFAULT 'no',
    image_01 TEXT NOT NULL DEFAULT '',
    image_02 TEXT NOT NULL DEFAULT '',
    image_03 TEXT NOT NULL DEFAULT '',
    image_04 TEXT NOT NULL DEFAULT '',
    image_05 TEXT NOT NULL DEFAULT '',
    description TEXT NOT NULL DEFAULT '',
    date TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
