# Symfony Project Setup Guide

## Table of Contents
1. [Requirements](#requirements)
2. [Initial Setup](#initial-setup)
3. [Environment Configuration](#environment-configuration)
4. [Installing Dependencies](#installing-dependencies)
5. [Database Setup](#database-setup)
6. [Running the Application](#running-the-application)
7. [Running Tests](#running-tests)

## Requirements
Ensure you have the following installed on your machine:
- **PHP** (version 8.1 or later)
- **Composer** (latest version)
- **MySQL** (depending on the environment)
- **Symfony CLI** (optional, but recommended)

## Initial Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/rodortega/flexisource-code-challenge.git
   cd your-repository

## Environment Configuration
1. **Copy .env.example to .env**:
   ```bash
   cp .env.example .env
   ```

2. **Edit .env**:
- Open the .env file and update the database connection string, mailer settings, and other environment variables as needed.
- Example:
   ```bash
   DATABASE_URL="mysql://root:password@127.0.0.1:3306/your_database?serverVersion=8.0"
   ```

## Installing Dependencies
1. **Install Dependencies**:
   ```bash
   composer install
   ```

## Database Setup
1. **Create Database**:
   ```bash
   php bin/console doctrine:database:create
   ```

2. **Run Migrations**:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

## Running the Application
1. **Start the Application**:
   ```bash
   php -S localhost:8000 -t public
   ```

## Running Tests
1. **Run Tests**:
   ```bash
   php bin/phpunit
   ```