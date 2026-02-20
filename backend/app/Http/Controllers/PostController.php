<?php

namespace App\Http\Controllers;

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
        //get post cars from request
        $client = new Client();
        $response = $client->post(
            config('services.dinggo.url'),
            [
                'json' => [
                    'username' => config('services.dinggo.username'),
                    "key" => config('services.dinggo.key'),
                ]
            ]
        );

        if (!$response->getStatusCode() == 200) {
            return response()->json([
                'success' => false,
                'message' => 'API request failed'
            ]);
        }

        $data = json_decode($response->getBody()->getContents(), true);
        // dd($data);
        return Inertia::render("Cars", [
            'cars' => $data
        ]);

        //      return response()->json($data); // for testing
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
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
