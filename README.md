# Fleet Geofence Tracker

## 🌍 Overview
This is a **real-time fleet tracking and geofencing system** built with PHP, Firebase, MySQL, and Google Maps. The system allows administrators to monitor **hundreds of trucks simultaneously**, view truck details, draw and manage **geofences**, and **log vehicle entries** into those geofenced zones.

## 📦 Features

- 🔴 **Live Tracking**: Real-time truck locations updated automatically on Google Maps
- 📌 **Geofence Drawing Tool**: Draw circular or polygonal geofences using a simple UI
- 🧭 **Geofence Entry Logging**: Automatically logs when a vehicle enters a geofence, with timestamp, geofence name, and truck ID
- 📋 **Truck Info Dashboard**: Lists all registered trucks with their latest GPS and status info
- 📍 **Geofence Viewer**: View all saved geofences on a map
- 🔄 **Data Sources**: Supports GPS data collection from IoT devices or smartphone apps
- 💾 **Database**: Uses Firebase for real-time updates and MySQL for persistent logging

---

## 📁 File Structure

```bash
.
├── firebaseRDB.php                            # Firebase Realtime Database PHP handler
├── index.php                                  # Dashboard showing all trucks with live data
├── live_location_of_trucks.php                # Live location map view for individual truck
├── map_form.php                               # Draw and save new geofences
├── showPoly.php                               # View all existing geofences on map
├── vehicles_geofence_log.php                  # Logs of vehicle entries into geofenced areas

```

---

## 🔧 Tech Stack
- **Frontend**: HTML, CSS, Bootstrap, JavaScript, Google Maps API
- **Backend**: PHP
- **Database**:
  - Firebase Realtime DB (for live data)
  - MySQL (for logs and storing data)
- **Other**: AJAX, jQuery, Cron Jobs (optional for syncing)

---

## 🔄 GPS Data Collection
Supports two modes:

1. **IoT GPS Devices** sending live GPS data
2. **Smartphones** with location tracking apps running in the background

### ➕ Data is stored into:
- **Firebase**: For real-time syncing and monitoring
- **MySQL**: For long-term historical analysis and reporting

---

## 🗺️ How it Works

1. **Drivers or IoT devices send GPS coordinates to Firebase**
2. **`index.php`** fetches live data from Firebase and displays all trucks
3. **Admins draw geofences** using `map_form.php` and save them to the DB
4. **`showPoly.php`** renders all saved geofences on a map
5. **`track.php`** shows each truck's live location on a separate map
6. **When a truck enters a geofence**, `vehicles.php` logs it with:
   - 📌 Truck name, registration ID
   - 🧭 Geofence name
   - 📅 Date & Time

---

## 🚀 Setup Instructions

1. Clone this repository:
```bash
git clone https://github.com/your-username/fleet-geofence-tracker.git
cd fleet-geofence-tracker
```

2. Add your Firebase URL to `firebaseRDB.php`:
```php
$firebase = new firebaseRDB("https://your-project.firebaseio.com/");
```

3. Configure your Google Maps API key in relevant files (e.g., `track.php`, `map_form.php`)

4. Deploy on your local server or live server with PHP 7+

---

## 📸 Screenshots 
![image](https://github.com/user-attachments/assets/d5173ffc-2c1e-43e3-9ae0-893a4e14c721)

![image](https://github.com/user-attachments/assets/ca4a642f-d9ba-43c3-ad82-46dc334d2ac7)

![image](https://github.com/user-attachments/assets/160bea71-a307-446a-b5c6-852ed651a7a7)

![image](https://github.com/user-attachments/assets/416d4648-aeab-49ba-9c41-c61381d9f96a)

![image](https://github.com/user-attachments/assets/5d707542-81e6-4009-9bfc-fd3c54449cc3)

![image](https://github.com/user-attachments/assets/94cc8d12-a06c-4192-b726-6c2e921e923a)

![image](https://github.com/user-attachments/assets/c95f9af5-53e7-4cd5-a40e-27d4dcdeb717)

![image](https://github.com/user-attachments/assets/a7895ac3-a236-4dc9-ba13-2ee265bef5af)



---

## 📜 License
This project is licensed under the **MIT License**.

---

## 👨‍💻 Author
**M. H. A. Afif**  
Founder & Lead Engineer – SystemSage Solutions  
🔗 [LinkedIn](https://www.linkedin.com/in/mhafif) 

---

> “Real-time fleet intelligence for smarter, safer, and more sustainable logistics.”
