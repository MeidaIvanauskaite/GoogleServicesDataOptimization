<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class PageSpeedResult extends Model {
        protected $fillable = ['property_id', 'url', 'metrics', 'performance_score'];

        protected $casts = [
            'metrics' => 'array',
        ];
    }
