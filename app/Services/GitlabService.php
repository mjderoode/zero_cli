<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitlabService
{
    public function test()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.gitlab.token'),
        ])->get("https://gitlab.concept7.nl/api/v4/projects");

        $data = collect($response->json()); 

        dd($data->only(['id', 'name_with_namespace', 'path_with_namespace', 'ssh_url_to_repo']));
        
        return 'Gitlab test';
    }
}