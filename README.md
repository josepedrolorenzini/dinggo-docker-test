Laravel Inertia.js Dinggo Integration

This project is a Laravel 10 + Inertia.js (React) application that integrates with the Dinggo API to fetch and store car quotes. It uses PostgreSQL as the database and Docker for development.

---

Project Structure:
- app/Models
  - Car.php → Stores car information.
  - Quote.php → Stores quotes related to cars.
- app/Http/Controllers
  - DinggoController.php → Handles API calls to Dinggo and saves quotes.
- resources/js/Pages
  - DinggoCarQuotes.jsx → Displays quotes in the frontend using Inertia.js.
- database/migrations
  - create_cars_table.php → Creates cars table.
  - create_quotes_table.php → Creates quotes table with foreign key car_id.
- .env → Stores Dinggo credentials and database connection.

---

Installation:
1. Clone the repository:
   git clone <repo-url>
   cd project-folder

2. Copy .env.example to .env:
   cp .env.example .env

3. Set your environment variables:
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=laravel
   DB_USERNAME=postgres
   DB_PASSWORD=your_password

   DINGGO_API_URL=https://app.dev.aws.dinggo.com.au/phptest/
   DINGGO_API_CARS_ENDPOINT=/cars
   DINGGO_API_QUOTES_ENDPOINT=/quotes
   DINGGO_API_USERNAME=your_email@example.com
   DINGGO_API_KEY=your_api_key

4. Install dependencies:
   composer install
   npm install
   npm run dev

5. Run Docker (if using Docker Compose):
   docker compose up -d

---

Database Setup:
1. Create the database in PostgreSQL:
   CREATE DATABASE laravel;

2. Run migrations and seeders:
   php artisan migrate:fresh --seed

3. Verify tables:
   psql -U postgres -d laravel
   \d cars
   \d quotes

---

Adding Cars:
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

Notes / Best Practices:
- Always use foreign keys (car_id) to relate cars and quotes.
- updateOrCreate avoids duplicate quotes.
- Use snake_case in database fields: overview_of_work, car_id.
- API credentials should never be hardcoded—use .env.
- If Dinggo API returns null, make columns nullable or provide defaults.

---

Commands Quick Reference:
   php artisan migrate:fresh --seed
   composer install
   npm install
   npm run dev
   docker compose up -d
   php artisan tinker