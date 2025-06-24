# Food Bridge NGO

A complete food donation platform to collect, manage, and track food contributions from organizations like restaurants, events, and caterers. This system streamlines the donation process and helps NGOs monitor food availability, pickup status, and expiry timelines efficiently.

---

## Features

- Multi-step food donation form with dynamic fields
- NGO admin login and secure dashboard
- View active and picked donations
- Auto-hide expired donations from the dashboard
- Mark donations as picked with timestamp
- Export donations to Excel with full details
- Accurate IST timestamps for submissions and pickups

---

## Tech Stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL  
- **Hosting:** Render  
- **Version Control:** Git & GitHub

---

## Live Links

- **Donation Form:**  
  https://food-bridge-ngo.onrender.com/public/index.html

- **Admin Dashboard:**  
  https://food-bridge-ngo.onrender.com/backend/dashboard.php

- **Excel Export:**  
  https://food-bridge-ngo.onrender.com/backend/export_excel.php

---

## Getting Started

1. Clone the repository:
   ```bash
   git clone https://github.com/Sridhar-0306/Food_Bridge_NGO.git
   ```

2. Import the MySQL table named `system` using your hosting or local DB tool.

3. Update `connect.php` with your database credentials.

4. Deploy the `/public` and `/backend` folders using any PHP hosting (e.g., Render, cPanel, XAMPP).

---

## Team

- [Sridhar U](https://github.com/Sridhar-0306)
- [Manikandan](https://github.com/manikandan-11-12-2006)
- [Manoj](https://github.com/Manoj-git-33)

---

## Folder Structure

```
/public
   └── index.html           # Multi-step donation form

/backend
   ├── admin_login.php      # Admin login page
   ├── dashboard.php        # NGO dashboard
   ├── submit_donation.php  # Handles donation form submission
   ├── mark_picked.php      # Marks donations as picked
   ├── export_excel.php     # Generates downloadable Excel report
   └── connect.php          # DB credentials
```

---

## License

This project was developed for both academic use and real-world NGO implementation. It may be reused or extended for nonprofit food distribution purposes.
