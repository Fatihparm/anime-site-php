# Anime Site PHP

This is a simple anime review site built with PHP and MySQL. Users can register, log in, view anime details, leave reviews, and manage their profile.

## Live server link
- http://anime-site-php.infinityfreeapp.com/index.php

## Features

- User registration and login
- View anime details and reviews
- Add, edit, and delete reviews
- Change user profile information
- Responsive design with a clean user interface

## Installation

To set up this project on your local machine, follow these steps:

### Prerequisites

- PHP >= 7.0
- MySQL or MariaDB
- Git (for cloning the repository)

### Steps

1. **Clone the repository:**

    ```bash
    git clone https://github.com/Fatihparm/anime-site-php.git
    cd anime-site-php
    ```

2. **Set up the database:**

    - Create a database named `anime_site`.
    - Import the provided `anime_site.sql` file into your database. This file contains the necessary tables and sample data.
    - You can manually connect it by phpmyadmin or you can run the code below.
    
    ```bash
    mysql -u your_username -p anime_site < anime_site.sql
    ```

4. **Run the application:**

    If you're using a local server like XAMPP or WAMP, place the project in the `htdocs` or `www` directory, respectively.

    Alternatively, you can use PHP's built-in server:

    ```bash
    php -S localhost:8000
    ```

    Then, open your browser and navigate to `http://localhost:8000`.

## Usage

### User Registration and Login

1. **Register a new account:**

    Go to the registration page, fill in your details, and submit the form to create a new account.

2. **Log in:**

    Use your registered email and password to log in.

### Viewing and Reviewing Anime

1. **View Anime Details:**

    On the homepage, click on an anime to view its details, including the description, score, and reviews.

2. **Add a Review:**

    If you are logged in, you can add a review by filling out the form at the bottom of the anime details page.

3. **Edit or Delete Your Review:**

    On your profile page, you can see all your reviews. You can edit or delete your reviews from there.

### Managing Profile

1. **Change Name:**

    On the profile page, you can change your name.

2. **Change Password:**

    On the profile page, you can change your password by providing your old password and the new password.

3. **Delete Account:**

    You can delete your account from the profile page.

## Scraping Anime Data (OPTIONAL)

The `scrape.py` script is used to scrape anime data from a source and populate the database. This script is written in Python and requires BeautifulSoup and requests libraries.
This script should only be used **once** if animes table somehow empty.
Please **don't run** this script if animes table is full. It will duplicate entire database and we don't want that.

### Running the Scraping Script

1. **Install the required Python libraries:**

    ```bash
    pip install beautifulsoup4 requests
    ```
2. **Add .env file:**

    ```bash
    DB_HOST=127.0.0.1
    DB_USER=root
    DB_PASSWORD=your_password
    DB_NAME=your_db_name
    ```

3. **Run the script:**

    ```bash
    python scrape.py
    ```

    The script will scrape anime data from the specified source and insert it into the `animes` table in the database.

### scrape.py Overview

The `scrape.py` script performs the following tasks:

- Connects to a specified anime data source.
- Scrapes relevant information such as title, description, image URL, score, rank, popularity, and season.
- Inserts the scraped data into the `animes` table in the database.

