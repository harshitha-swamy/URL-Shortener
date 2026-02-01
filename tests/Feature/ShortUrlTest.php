<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    // Declare class properties
    protected $superAdmin;
    protected $admin1;
    protected $admin2;
    protected $member1;
    protected $company1;
    protected $company2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create companies
        $this->company1 = Company::factory()->create();
        $this->company2 = Company::factory()->create();

        // Create users
        $this->superAdmin = User::factory()->create([
            'role' => 'SuperAdmin',
            'company_id' => null,
        ]);

        $this->admin1 = User::factory()->create([
            'role' => 'admin',
            'company_id' => $this->company1->id,
        ]);

        $this->member1 = User::factory()->create([
            'role' => 'member',
            'company_id' => $this->company1->id,
        ]);

        $this->admin2 = User::factory()->create([
            'role' => 'admin',
            'company_id' => $this->company2->id,
        ]);
    }

    /** @test */
    public function admin_can_create_short_url()
    {
        $this->actingAs($this->admin1, 'sanctum');

        $response = $this->postJson('/api/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('short_urls', [
            'user_id' => $this->admin1->id,
            'company_id' => $this->company1->id,
        ]);
    }

    /** @test */
    public function member_can_create_short_url()
    {
        $this->actingAs($this->member1, 'sanctum');

        $response = $this->postJson('/api/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('short_urls', [
            'user_id' => $this->member1->id,
        ]);
    }

    /** @test */
    public function superadmin_cannot_create_short_url()
    {
        $this->actingAs($this->superAdmin, 'sanctum');

        $response = $this->postJson('/api/short-urls', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_only_see_company_urls()
    {
        ShortUrl::factory()->create([
            'user_id' => $this->admin1->id,
            'company_id' => $this->company1->id,
        ]);

        ShortUrl::factory()->create([
            'user_id' => $this->admin2->id,
            'company_id' => $this->company2->id,
        ]);

        $this->actingAs($this->admin1, 'sanctum');

        $response = $this->getJson('/api/short-urls');
        $response->assertStatus(200);

        $data = $response->json();
        foreach ($data as $url) {
            $this->assertEquals($this->company1->id, $url['company_id']);
        }
    }

    /** @test */
    public function member_can_only_see_own_urls()
    {
        ShortUrl::factory()->create([
            'user_id' => $this->member1->id,
            'company_id' => $this->company1->id,
        ]);

        ShortUrl::factory()->create([
            'user_id' => $this->admin1->id,
            'company_id' => $this->company1->id,
        ]);

        $this->actingAs($this->member1, 'sanctum');

        $response = $this->getJson('/api/short-urls');
        $response->assertStatus(200);

        $data = $response->json();
        foreach ($data as $url) {
            $this->assertEquals($this->member1->id, $url['user_id']);
        }
    }

    /** @test */
    /** @test */
public function superadmin_cannot_see_any_urls()
{
    $this->actingAs($this->superAdmin, 'sanctum');

    $response = $this->getJson('/api/short-urls');

    $response->assertStatus(403); // correct behavior
}


    /** @test */
    public function short_url_redirects_to_original_url()
    {
        $shortUrl = ShortUrl::factory()->create([
            'original_url' => 'https://example.com',
            'short_code' => 'ABC123',
            'user_id' => $this->admin1->id,
            'company_id' => $this->company1->id,
        ]);

        // Simulate public route redirect
        $response = $this->get('/short/' . $shortUrl->short_code);

        $response->assertRedirect('https://example.com');
    }
}
