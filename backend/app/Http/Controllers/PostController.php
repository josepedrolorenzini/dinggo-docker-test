<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Post;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Application;
use GuzzleHttp\Client;
use Inertia\Inertia;
use Inertia\Response;

use function GuzzleHttp\json_decode;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //// fetching joseplorenzini.com/api/posts
        $client = new Client();
        // $response = $client->get('http://joseplorenzini.com/api/posts');
        // $response = $client->get('https://app.dev.aws.dinggo.com.au/phptest/test');
        $response = $client->get(config('services.dinggo.base_url') . '/test');
        $posts = json_decode($response->getBody(), true);
        $dataPosts = response()->json($posts);

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'dataPosts' => $dataPosts,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function postCars()
    {
        //only post cars data to the database without rendering the view
        $client = new Client();
        // Make a POST request to the Dinggo API with the required credentials

        $response = $client->post(config('services.dinggo.base_url') . '/' . config('services.dinggo.cars_endpoint'), [
            'json' => [
                'username' => config('services.dinggo.username'),
                "key" => config('services.dinggo.key'),
            ]
        ]);
        // Check if the response is successful
        if ($response->getStatusCode() !== 200) {
            return response()->json([
                'success' => false,
                'message' => 'API request failed'
            ]);
        }

        $data = json_decode($response->getBody()->getContents(), true);
        $cars = $data['cars'] ?? [];

        if (empty($cars)) {
            return response()->json([
                'success' => false,
                'message' => 'No cars data returned from API'
            ]);
        }
        ;

        //insert post cars data into cars database table
        foreach ($cars as $carData) {
            Cars::firstOrCreate(
                ['vin' => $carData['vin']],
                [
                    'colour' => $carData['colour'] ?? null,
                    'licensePlate' => $carData['licensePlate'] ?? null,
                    'licenseState' => $carData['licenseState'] ?? null,
                    'make' => $carData['make'] ?? null,
                    'model' => $carData['model'] ?? null,
                    'year' => $carData['year'] ?? null
                ]
            );
        }
        //dd($cars);
        // redirect to dinggo jsx
        return redirect()->route('cars.show');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCars(Request $request)
    {
        // car Model
        $car = new Cars();
        //get post cars from request
        $client = new Client();

        // Make a POST request to the Dinggo API with the required credentials
        $response = $client->post(
            config('services.dinggo.base_url') . '/' . config('services.dinggo.cars_endpoint'),
            [
                'json' => [
                    'username' => config('services.dinggo.username'),
                    "key" => config('services.dinggo.key'),
                ]
            ]
        );

        // Check if the response is successful
        if ($response->getStatusCode() !== 200) {
            return response()->json([
                'success' => false,
                'message' => 'API request failed'
            ]);
        }

        // Get the response body and decode the JSON data
        $body = $response->getBody()->getContents();
        // dd($body);

        // Decode the JSON response into an associative array
        $data = json_decode($body, true);
        // dd($data);

        // Extract the 'cars' data from the response
        $cars = $data['cars'] ?? [];

        //insert post cars data into cars database table
        foreach ($cars as $carData) {
            Cars::firstOrCreate(
                ['vin' => $carData['vin']],
                [
                    'colour' => $carData['colour'] ?? null,
                    'licensePlate' => $carData['licensePlate'] ?? null,
                    'licenseState' => $carData['licenseState'] ?? null,
                    'make' => $carData['make'] ?? null,
                    'model' => $carData['model'] ?? null,
                    'year' => $carData['year'] ?? null
                ]
            );
        }

        ////  dd($cars);

        // redirect to dinggo jsx
        return redirect()->route('cars.show');

        /*
        return Inertia::render("Cars", [
           'cars' => $data
       ]);
       // return response()->json($data); // for testing
       */
    }

    /**
     * Display the specified resource.
     */
    public function showCars()
    {
        //instance of car model
        $car = new Cars();

        //render the cars data to the view
        return Inertia::render("DinggoCars", [
            'cars' => $car::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getQuotes($licensePlate, $licenseState)
    {
        //client instance
        $client = new Client();

        // Make a POST request to the Dinggo API with the required credentials and license plate and state
        $response = $client->post(config('services.dinggo.base_url') . '/' . config('services.dinggo.quotes_endpoint'), [
            'json' => [
                'username' => config('services.dinggo.username'),
                "key" => config('services.dinggo.key'),
                "licensePlate" => $licensePlate,
                "licenseState" => $licenseState
            ]
        ]);

        // Check if the response is successful
        if ($response->getStatusCode() !== 200) {
            return response()->json([
                'success' => false,
                'message' => "API request failed with status code " . $response->getStatusCode()
            ], 500);
        }

        //  dd($vin, $response->getBody()->getContents());
        $data = json_decode($response->getBody()->getContents(), true);

        $car = Cars::where('licensePlate', $licensePlate)
            ->where('licenseState', $licenseState)
            ->firstOrFail();

        //dd($car, $data);

        // ✅ Insert each quote into the database using Eloquent
        if (!empty($data['quotes'])) {
            foreach ($data['quotes'] as $q) {
                // Avoid duplicates if needed
                Quote::updateOrCreate(
                    [
                        'car_id' => $car->id,
                        'overview_of_work' => $q['overviewOfWork'],
                    ],
                    [
                        'price' => $q['price'],
                        'repairer' => $q['repairer'],
                    ]
                );
            }
        }
        return Inertia::render("DinggoCarQuotes", [
            'success' => true,
            'message' => "Quotes retrieved successfully for license plate $licensePlate in state $licenseState",
            'quotes' => $data ?? [],
            'car' => $car ?? []
        ]);


    }

    /**
     * Update the specified resource in storage.
     */
    public function postQuotes(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
