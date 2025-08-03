# Coffee Project

This is a simple coffee shop website running on localhost using XAMPP.

## Getting Started

1. **Install XAMPP:**  
    Download and install [XAMPP](https://www.apachefriends.org/index.html).

2. **Clone or Copy Files:**  
    Place the project files in `C:/xampp/htdocs/coffee/`.

3. **Start Apache:**  
    Open XAMPP Control Panel and start the Apache server.

4. **Access the Site:**  
    Open your browser and go to:  
    `http://localhost/coffee/`

## Features

- Homepage for coffee shop
- Easy to customize

## Contributing

Feel free to fork and submit pull requests.

## License

This project is open source.



## Database Setup

To create a local database and a `users` table:

1. **Open phpMyAdmin:**  
    Go to `http://localhost/phpmyadmin/`.

2. **Create Database:**  
    Click "New" and name your database (e.g., `coffee_shop`).

3. **Create Users Table:**  
    Run the following SQL in the SQL tab:

    ```sql
    CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(100) NOT NULL,
      email VARCHAR(100) NOT NULL,
      password VARCHAR(255) NOT NULL,
      serial_number VARCHAR(50) NOT NULL
    );
    ```

Now your database and users table are ready for use.

### Create Purchase Table

To add a `purchase` table for tracking coffee purchases, run this SQL in phpMyAdmin:

```sql
CREATE TABLE purchase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100) NOT NULL,
    coffee_id INT NOT NULL,
    coffee_title VARCHAR(100) NOT NULL,
    amount INT NOT NULL,
    purchase_at DATETIME NOT NULL
);
```

### User Profile Image

To display a user profile image on your website, use the following path for the image:

```
C:/xampp/htdocs/coffee/image/user.png
```

In your HTML, reference the image like this:

```html
<img src="https://github.com/sajjadjim-cse/web-lab/blob/main/coffee/image/purchase.png?raw=true" alt="User Profile" />
```

This table stores each purchase with the user's email, coffee details, amount, and purchase date.
