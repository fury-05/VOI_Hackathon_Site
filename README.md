# ✨ DevLink Hub - Hackathon & Developer Portfolio Platform 🚀

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-8892BF.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-v10.x%2B-FF2D20.svg)](https://laravel.com)
DevLink Hub is an innovative web platform built with Laravel and Tailwind CSS, designed to be the central hub for developers looking to:
* Participate in exciting **hackathons** competição.
* Showcase their **projects** and skills 🛠️.
* Connect with a vibrant **developer community** 🤝.

Our goal is to provide a seamless and engaging experience for users to learn, collaborate, build, and get recognized for their talents. The platform features a dynamic public dashboard, user profiles, project submission capabilities, and comprehensive hackathon management tools for administrators.

## 🌟 Core Features

* 🔐 **User Authentication:** Secure sign-up and login (powered by Laravel Breeze).
* 👤 **User Profiles:**
    * Customizable profiles: bio, skills, GitHub username.
    * Ability to manage and showcase personal projects.
* 💡 **Project Showcase:**
    * Users can create, list, and detail their software projects.
    * Direct integration with GitHub repositories for fetching stats and information.
    * Public project detail pages.
* 🏆 **Hackathon Platform:**
    * **Admin Management:** Full CRUD capabilities for admins to create and manage hackathon events (details, dates, rules, prizes).
    * **Public Listings:** Browse upcoming, ongoing, and completed hackathons.
    * **Detailed View:** Each hackathon has a dedicated page with all relevant information.
    * **User Registration:** Easy registration for logged-in users.
    * **Project Submission:** Registered participants can submit their projects to hackathons.
* 📊 **Public Dashboard:**
    * At-a-glance overview of platform activity: total users, projects, active hackathons, overall commit statistics.
    * Dynamic cards for GitHub activity snapshots, top active projects, and announcements.
    * Visual charts for platform metrics (e.g., weekly activity, developer sign-ups, language distributions).
    * ☀️/🌙 Light and Dark mode support for comfortable viewing.
* 💬 **Interactive Comments:** Users can discuss projects and hackathons.
* ⚙️ **GitHub Integration (Core):**
    * Projects are linked via GitHub repository URLs.
    * Automated (planned) fetching of GitHub statistics: commits, stars, forks, open issues, contributors, primary languages.
    * Display of these stats on project detail pages and aggregated on the main dashboard.

## 🛠️ Tech Stack

* **Backend:** PHP, Laravel Framework
* **Frontend:** Blade Templates, Tailwind CSS, JavaScript
* **Databases:** MySQL (primarily used during development with XAMPP)
* **Charting:** Chart.js, Apache ECharts
* **Development Environment:** VSCode, XAMPP, Composer, Node.js/npm

## 🚀 Getting Started for Development

Follow these steps to get a local development environment up and running.

### Prerequisites

* PHP (version ^8.1 or higher recommended)
* Composer (PHP dependency manager)
* Node.js and npm (for frontend assets, if not solely relying on CDNs)
* A web server environment (e.g., XAMPP, WAMP, MAMP, Laravel Valet/Herd)
* MySQL database server

### Installation & Setup

1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git](https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git)
    cd YOUR_REPOSITORY_NAME
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Install Node.js Dependencies & Compile Assets:**
    *(If you're using Laravel's built-in frontend scaffolding like Breeze, these steps are necessary for CSS/JS processing).*
    ```bash
    npm install
    npm run dev
    ```
    *(Note: In our current development, we've also used CDNs for Tailwind, Chart.js, and ECharts for rapid setup. For production, a compiled asset approach is better).*

4.  **Environment Configuration:**
    * Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    * Open the `.env` file and update your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD), APP_URL, and any other necessary settings (e.g., mail configuration). For a typical XAMPP setup:
        ```ini
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=devlink_hub_db # Or your chosen name
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

6.  **Run Database Migrations:**
    This creates all the necessary tables in your database.
    ```bash
    php artisan migrate
    ```

7.  **Seed the Database (Highly Recommended for Development):**
    Populates your database with initial dummy data for users, projects, hackathons, etc., making it easier to test features.
    ```bash
    php artisan db:seed
    ```
    Or, to reset the database and then seed:
    ```bash
    php artisan migrate:fresh --seed
    ```

8.  **Start the Development Server:**
    ```bash
    php artisan serve
    ```
    Your DevLink Hub application should now be accessible, typically at `http://127.0.0.1:8000`.

## 🌐 Deployment Guide

For instructions on how to deploy this application to a live PHP server (e.g., using cPanel or similar), please refer to the `DEPLOYMENT_GUIDE.md` file in this repository.

## 🤝 How to Contribute

We welcome contributions to DevLink Hub! Whether it's bug fixes, feature enhancements, or documentation improvements, your help is appreciated.

Please read our `CONTRIBUTING.md` guide for details on our development process, coding standards, and how to submit pull requests.

## 📜 License

This project is licensed under the **MIT License**. This is a permissive open-source license that allows for broad use, modification, and distribution, provided the original copyright and license notice are included.

See the `LICENSE.md` file for the full license text.

## 🙏 Acknowledgements

* The Laravel Community for an amazing framework.
* Tailwind CSS for a utility-first CSS framework that speeds up UI development.
* Creators of Chart.js and Apache ECharts for powerful charting libraries.
* All future contributors and users of DevLink Hub! 🚀


Remember to replace `YOUR_USERNAME/YOUR_REPOSITORY_NAME` with your actual GitHub repository details to make the dynamic badges work correctly once you set them up. You can generate many of these badges at [Shields.io](https://shields.io/).
