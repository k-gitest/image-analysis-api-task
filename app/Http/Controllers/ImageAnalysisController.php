<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ImageAnalysisService;

class ImageAnalysisController extends Controller
{
    protected $imageAnalysisService;

    public function __construct(ImageAnalysisService $imageAnalysisService){
        $this->imageAnalysisService = $imageAnalysisService;
    }
    
    public function analyze (Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image_path' => 'required|string|max:255',
        ]);

        $response = $this->imageAnalysisService->analyze($validated['image_path']);

        if (!$response['success']) {
            return response()->json($response, 500);
        }
        
        return response()->json($response);
            
    }
}
