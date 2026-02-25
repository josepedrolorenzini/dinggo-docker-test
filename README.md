# Laravel  PHP + React JS (Inertia.js) + Postgresql  -> Dinggo API Application using Docker.com

Stack :
https://docs.guzzlephp.org/en/stable/quickstart.html
https://laravel.com/docs/12.x/eloquent
https://inertiajs.com/ 
https://packagist.org/packages/laravel/breeze
https://react.dev/
https://tailwindcss.com/
https://www.docker.com/


This project is a Laravel 10 + Inertia.js (React) application that integrates with the Dinggo API to fetch and store car quotes. It uses PostgreSQL as the database and Docker for development.

---

Project Structure:
# - app/Models(laravel eloquent PHP)
  - Car.php → Stores car Schema fields  and protected table.
  - Quote.php → Stores quotes related to cars.
# - app/Http/Controllers(laravel eloquent PHP)
  - PostController.php → Handles API calls to Dinggo and saves quotes.
# - resources/js/Pages (React)
  - Welcome.jsx   my dinggo home page , please click the button sync cars you will be automatic redirect to show/cars
  - DinggoCars.jsx fetch all the cars , you must select one and then will display the quotes for the selected car.
  - DinggoCarQuotes.jsx → Displays quotes fo the car selected quotes in the frontend using Inertia.js.
# - database/migrations
  - create_cars_table.php → Creates cars table (schema).
  - create_quotes_table.php → Creates quotes table with foreign key car_id(schema).
  - .env → Stores Dinggo credentials and database connection. (email to dev )

# database 

# migrations:
  create_cars_table.php
  create_quotes_table.php
# seeders:
  CarSeeder.php
  QuoteSeeder.php



---

Installation:
1. Clone the repository:
   git clone  https://github.com/josepedrolorenzini/dinggo-docker-test.git

2. Navigate to the project folder:
   cd Dinggo
   cd /backend

3. Copy .env.example to .env:
   cp .env.example .env

4. Set your environment variables:
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=laravel
   DB_USERNAME=postgres
   DB_PASSWORD=your_postgres_password

   DINGGO_API_URL=https://app.dev.aws.dinggo.com.au/phptest/
   DINGGO_API_CARS_ENDPOINT=/cars
   DINGGO_API_QUOTES_ENDPOINT=/quotes
   DINGGO_API_USERNAME=your_email@example.com
   DINGGO_API_KEY=your_api_key

5. Install dependencies:
   composer install
   npm install
   npm run dev

6. Run Docker (if using Docker Compose):
   docker compose up -d
   docker compose down 

---
Keygenerate after installation.
docker compose exec app php artisan key:generate

---
Database Setup:
1. Create the database in PostgreSQL:
   CREATE DATABASE laravel;

2. Run migrations and seeders:
   php artisan migrate:fresh --seed
   docker compose exec app php artisan migrate:fresh --seed

3. Verify tables:
   psql -U postgres -d laravel
   \d cars
   \d quotes



after installation run this 
# Keygenerate
docker compose exec app php artisan key:generate
# this will refresh database and seed data / database/migrations database/seed
docker compose exec app php artisan migrate:fresh --seed





Notes / Best Practices:
- Always use foreign keys (car_id) to relate cars and quotes.
- updateOrCreate avoids duplicate quotes.
- Use snake_case in database fields: overview_of_work, car_id.
- API credentials should never be hardcoded—use .env.
- If Dinggo API returns null, make columns nullable or provide defaults.

---

# Commands Quick Reference:
   php artisan migrate:fresh --seed
   composer install
   npm install
   npm run dev
   docker compose up -d
   php artisan tinker


#   clean cache :
   docker compose exec app php artisan cache:clear    
   docker compose exec app php artisan view:clear    
   docker compose exec app php artisan config:clear 
   docker compose restart







  
.env file:
.env()
i adding extra security layer in http/config/services file :

    'dinggo' => [
        'base_url' => env('DINGGO_API_URL'),
        'cars_endpoint' => env('DINGGO_API_CARS_ENDPOINT'),
        'quotes_endpoint' => env('DINGGO_API_QUOTES_ENDPOINT'),
        'username' => env('DINGGO_API_USERNAME'),
        'key' => env('DINGGO_API_KEY'),
    ], // Add your Dinggo API credentials

# Adding Cars:
Use tinker or a seeder:
   php artisan tinker
   >>> Car::create([
         'colour' => 'Yellow',
         'licensePlate' => 'QWE12E',
         'licenseState' => 'NSW',
         'make' => 'Mitsubishi',
         'model' => 'Eclipse',
         'vin' => '5TDBW5G16BS451467',
         'year' => 2002
   ]);

---

Fetching and Storing Quotes from Dinggo (DinggoController.php):
   $client = new \GuzzleHttp\Client();
   $response = $client->post(config('services.dinggo.base_url') . '/' . config('services.dinggo.quotes_endpoint'), [
       'json' => [
           'username' => config('services.dinggo.username'),
           'key' => config('services.dinggo.key'),
           'licensePlate' => $licensePlate,
           'licenseState' => $licenseState
       ]
   ]);

   $data = json_decode($response->getBody()->getContents(), true);

   $car = Car::where('licensePlate', $licensePlate)
       ->where('licenseState', $licenseState)
       ->firstOrFail();

   if (!empty($data['quotes'])) {
       foreach ($data['quotes'] as $q) {
           Quote::updateOrCreate([
               'car_id' => $car->id,
               'overview_of_work' => $q['overviewOfWork'] ?? 'No description',
           ],[
               'price' => $q['price'] ?? 0,
               'repairer' => $q['repairer'] ?? 'Unknown',
           ]);
       }
   }

---

Frontend (Inertia.js):
- Page: DinggoCarQuotes.jsx
- Props passed:
  - quotes → Array of quotes for the car
  - car → Car details
- Example usage:
   {quotes.map(q => (
       <div key={q.overviewOfWork}>
           <h3>{q.repairer}</h3>
           <p>{q.overviewOfWork}</p>
           <p>${q.price}</p>
       </div>
   ))}

---

docker compose exec postgres pg_dump -U postgres laravel > laravel_dump.sql


  please contact me if you have any issues: josephlorenzini81@gmail.com  