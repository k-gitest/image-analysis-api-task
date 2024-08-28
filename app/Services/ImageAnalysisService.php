<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\AIAnalysisLog;
use Carbon\Carbon;
use Log;

class ImageAnalysisService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.base_url');
    }

    public function analyze(string $imagePath): array
    {
        $requestTimestamp = Carbon::now()->timestamp;

        try{
            $response = $this->callAnalysisApi($imagePath);

            $responseTimestamp = Carbon::now();

            $this->logAnalysis($imagePath, $response, $requestTimestamp, $responseTimestamp);

            return $response;
        }
        catch(\Exception $e){
            Log::error('Error: ' . $e->getMessage(), ['image_path' => $imagePath]);

            // Apiエラーを例外とした場合の返り値
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'estimated_data' => []
            ];
            
        }
        
    }

    private function callAnalysisApi(string $imagePath): array
    {
        try{
            $response = Http::timeout(30) 
            ->retry(3, 100)
            ->post($this->baseUrl, [
                'image_path' => $imagePath
            ])
            ->throw();

            if (!$response['success']) {
                Log::error('API Error: ' . $response['message'], ['image_path' => $imagePath]);
                // APIエラーを例外とする場合
                throw new \Exception($response['message']);
            }

            return $response->json();
        }
        catch(\Exception $e){
            throw $e;
        }
        
    }

    private function logAnalysis(string $imagePath, array $response, int $requestTimestamp, Carbon $responseTimestamp): void
    {
        $log = new AIAnalysisLog();
        $log->image_path = $imagePath;
        $log->success = $response['success'];
        $log->message = $response['message'];
        $log->class = $response['estimated_data']['class'] ?? null;
        $log->confidence = $response['estimated_data']['confidence'] ?? null;
        $log->request_timestamp = $requestTimestamp;
        $log->response_timestamp = $responseTimestamp;
        $log->save();
    }
}
