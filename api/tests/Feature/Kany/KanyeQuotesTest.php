<?php

namespace Tests\Feature\Kany;


use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class KanyeQuotesTest extends TestCase
{

    protected function setUp(): void
	{
		parent::setUp();
		$this->seedPermissions();
	}

    /**
     * A basic feature test example.
     */
    public function test_kanye_quotes_not_token(): void
    {
        $response = $this->json('get', route('api.kanyequotes'),[]);

        $response->assertStatus(401)
            ->assertJsonMissing(['Cache']);
    }

    public function test_kanye_quotes_with_token(): void
    {
        $this->authUser();


        $response = $this->json('get', route('api.kanyequotes'),[]);

        $response->assertStatus(201);
        $res_array = (array)json_decode($response->content());
        $this->assertArrayHasKey('Cache', $res_array);
        $this->assertTrue(count($res_array) == 6);
    }

    public function test_kanye_quotes_clear_not_token(): void
    {
        $response = $this->json('get', route('api.kanyequotesclear'),[]);

        $response->assertStatus(401)
            ->assertJsonMissing(['message']);
    }

    public function test_kanye_quotes_clear_with_token(): void
    {
        $this->authUser();


        $response = $this->json('get', route('api.kanyequotesclear'),[]);

        $response->assertStatus(201);
        $res_array = (array)json_decode($response->content());
        $this->assertArrayHasKey('CacheClear', $res_array);
        $this->assertTrue(count($res_array) == 6 || count($res_array) == 1);
        $this->assertTrue($res_array['CacheClear']);
    }
}
