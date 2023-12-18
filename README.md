Installation Guide:
--
1. Installing Node.js:
  Visit the official Node.js website: Node.js Downloads.
  Download the latest version of the Node.js installer for Windows.
  Run the installer and follow the prompts.
  Accept the default settings and click "Next" until the installation is complete.

To verify the installation, open a command prompt and type:
> node -v
> npm -v

2. Clone Github Repository:
   Download repository or clone it.

3. FrontEnd (Nuxt JS):
Once you're in the project directory, install the project dependencies using npm:
  > npm install
Run Development Server:
  > npm run dev
4. Backend (Laravel):
  -- import .sql file into your database.
# To run Laravel, use command below:
  > composer install
# Create a .env File:
Duplicate the .env.example file and save it as .env. Update the configuration settings in the .env file, such as database connection details and application key.

# Run Migrations:
If the project has a database, you may need to run migrations and seed data:
  > php artisan migrate

# Start the Laravel Development Server:
Laravel comes with a built-in development server. Run the following command to start it:
  > php artisan serve

Dont Forget to use npm run dev for frontend and php artisan serve for backend.
