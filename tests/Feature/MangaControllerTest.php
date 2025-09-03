<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class MangaControllerTest extends TestCase
{
    public function test_index_returns_mangas()
    {
        Http::fake([
            'https://example.com/api/v1/manga*' => Http::response(['data' => [['id' => 'm1', 'title' => 'M1']]], 200),
        ]);

        $response = $this->get('/manga');
        $response->assertStatus(200);
        $response->assertJson(['data' => [['id' => 'm1', 'title' => 'M1']]]);
    }
}