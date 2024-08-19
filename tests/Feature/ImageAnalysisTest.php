<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class ImageAnalysisTest extends TestCase
{
    use RefreshDatabase;

    protected $randomImagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $extension = '.jpg';
        $randomStringLength = 255 - strlen($extension);
        $this->randomImagePath = Str::random($randomStringLength) . $extension;
    }

    public function test_image_analysis_endpoint_success()
    {
        Http::fake([
            'http://example.com' => Http::response([
                'success' => true,
                'message' => 'success',
                'estimated_data' => [
                    'class' => 3,
                    'confidence' => 0.8683
                ],
            ], 200),
        ]);
        
        $response = $this->post('/analyze', [
            'image_path' => $this->randomImagePath,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'success',
            'estimated_data' => [
                'class' => 3,
                'confidence' => 0.8683
            ],
        ]);

        $this->assertDatabaseHas('ai_analysis_logs', [
            'image_path' => $this->randomImagePath,
            'success' => true,
            'message' => 'success',
            'class' => 3,
            'confidence' => 0.8683,
        ]);
    }

    public function test_image_analysis_endpoint_error()
    {
        Http::fake([
            'http://example.com' => Http::response([
                'success' => false,
                'message' => 'Error:E50012',
                'estimated_data' => [],
            ], 200),
        ]);

        $response = $this->post('/analyze', [
            'image_path' => $this->randomImagePath,
        ]);

        $response->assertStatus(500);

        $response->assertJson([
            'success' => false,
            'message' => 'Error:E50012',
            'estimated_data' => [],
        ]);

        $this->assertDatabaseMissing('ai_analysis_logs', [
            'image_path' => $this->randomImagePath,
        ]);
        
    }

    public function test_image_analysis_endpoint_empty_image_path()
    {
        $response = $this->post('/analyze', [
            'image_path' => '',
        ], [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['image_path']);

        $this->assertDatabaseMissing('ai_analysis_logs', [
            'image_path' => '',
        ]);
    }
}
