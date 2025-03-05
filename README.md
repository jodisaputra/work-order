# Work Order Management System

A web application for managing work orders in a manufacturing process, including creation, tracking, and updating of work orders with role-based access control (RBAC).

## Features

### Role-Based Access Control
- **Production Manager:** Can create work orders, assign operators, update status, and view reports.
- **Operator:** Can view assigned work orders and update their status from Pending to In Progress or In Progress to Completed.

### Work Order Management
- Create work orders with auto-generated order numbers (format: WO-YYYYMMDD-001)
- Track work order status (Pending, In Progress, Completed, Canceled)
- Assign operators to work orders
- Filter work orders by status, date, and operator

### Work Order Progress Tracking
- Track status changes and timestamps
- Record quantity processed for each status update
- Add notes for production progress updates

### Reporting
- Summary report of work orders by product and status
- Operator performance report with completed work statistics

## Tech Stack

- **Framework:** Laravel
- **Database:** MySQL
- **Frontend:** Bootstrap 5
- **Authentication:** Laravel UI

## Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL
- Node.js and NPM

### Installation Steps

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/work-order-system.git
   cd work-order-system
   ```

2. Create a `.env` file by copying the example:
   ```
   cp .env.example .env
   ```

3. Update the environment variables in the `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=work_order_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. Install Composer dependencies:
   ```
   composer install
   ```

5. Generate application key:
   ```
   php artisan key:generate
   ```

6. Install Laravel UI for authentication scaffolding:
   ```
   composer require laravel/ui
   php artisan ui bootstrap --auth
   ```

7. Run database migrations and seeders:
   ```
   php artisan migrate --seed
   ```

8. Install and build frontend assets:
   ```
   npm install
   npm run dev
   ```

9. Start the local development server:
   ```
   php artisan serve
   ```

10. Access the application at: http://localhost:8000

## Default Users

After installation, the following users will be available:

- **Production Manager:**
  - Email: manager@example.com
  - Password: password

- **Operator 1:**
  - Email: operator1@example.com
  - Password: password

- **Operator 2:**
  - Email: operator2@example.com
  - Password: password

## Database Structure

- **users:** Stores user details and role association
- **roles:** Defines user roles and permissions
- **work_orders:** Stores work order details including status and assignments
- **work_order_progress:** Tracks status changes and progress updates

## Core Functionalities

### For Production Managers:
- Create new work orders with product details, quantities, and deadlines
- Assign operators to work orders
- Update work order status (including cancellation)
- View all work orders in the system
- Generate and view reports on work order status and operator performance

### For Operators:
- View assigned work orders
- Update work order status from Pending to In Progress
- Update work order status from In Progress to Completed
- Add progress notes and track quantity processed
- View history of their assigned work orders

## Troubleshooting

### Common Issues:

1. **No work orders appearing in the list:**
   - Verify that database migrations and seeders have run successfully
   - Check if the filter criteria are too restrictive
   - Ensure the database connection is configured correctly

2. **Status transition errors:**
   - Operators can only change status from Pending to In Progress, or from In Progress to Completed
   - For other status changes, a Production Manager account is required

3. **Authentication issues:**
   - Ensure Laravel UI is properly installed and configured
   - Verify the database contains the seeded user accounts

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
