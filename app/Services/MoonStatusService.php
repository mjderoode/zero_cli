<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MoonStatusService
{
    public function getIllumination()
    {
        return Cache::remember('moon_illumination', 3600, function () {
            $url = 'https://www.timeanddate.com/moon/phases/';

            $response = Http::get($url); 

            $body = $response->body();

            // Extract the illumination percentage from the body
            preg_match('/Moon: <span id=cur-moon-percent>(\d+\.\d+)/', $body, $matches);
            $illumination = $matches[1];

            return $illumination;
        });
    }
}