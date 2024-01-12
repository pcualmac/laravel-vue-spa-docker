<?php

namespace App\Http\Controllers\Kanye;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class KanyeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function kanyequotes()
    {
        if (Cache::has('quotes')) {
            $quotes = Cache::get('quotes');
            $quotes['Cache'] = true;
            return response($quotes , 201);
        }
        for ($i=0; $i < 5; $i++) { 
            $response = Http::withoutVerifying()->get('https://api.kanye.rest');
            if ($response->ok()) {
                $quotes[] = $response->json();
            } else {
                $res = [
                    'Error' => 'Generic Error',
                ];
                return response($res , 405);
            }
        }
        Cache::store('file')->put('quotes', $quotes);
        $quotes['Cache'] = false;
        return response($quotes , 201);

    }

    public function kanyeQuotesClear()
    {
        if (Cache::has('quotes')) {
            $quotes = Cache::get('quotes');
            Cache::forget('quotes');
            $quotes['CacheClear'] = true;
            return response($quotes , 201);
        }
        $quotes['CacheClear'] = true;
        return response($quotes , 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
