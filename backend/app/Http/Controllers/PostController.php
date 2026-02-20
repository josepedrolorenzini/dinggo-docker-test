<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Application;
use GuzzleHttp\Client;
use Inertia\Inertia;
use Inertia\Response;


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
        $response = $client->get('https://app.dev.aws.dinggo.com.au/phptest/test');
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
    public function create()
    {
        //
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
            config('services.dinggo.url'),
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
                    'license_plate' => $carData['license_plate'] ?? null,
                    'license_state' => $carData['license_state'] ?? null,
                    'make' => $carData['make'] ?? null,
                    'model' => $carData['model'] ?? null,
                    'year' => $carData['year'] ?? null
                ]
            );
        }

        dd($cars);
        // redirect to dinggo jsx
        return redirect()->route('cars.show');

         /*
         return Inertia::render("Cars", [
            'cars' => $data
        ]);

        //      return response()->json($data); // for testing
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
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
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
