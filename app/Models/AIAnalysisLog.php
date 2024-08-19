<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIAnalysisLog extends Model
{
    use HasFactory;
    
    protected $table = 'ai_analysis_logs';
    public $timestamps = false;

    protected $fillable = [
        'image_path',
        'success',
        'message',
        'class',
        'confidence',
        'request_timestamp',
        'response_timestamp',
    ];

    protected $casts = [
        'success' => 'boolean',
        'class' => 'integer',
        'confidence' => 'float',
        'request_timestamp' => 'datetime',
        'response_timestamp' => 'datetime',
    ];
    
}
