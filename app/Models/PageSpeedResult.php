<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class PageSpeedResult extends Model {
        protected $fillable = ['google_property_id', 'url', 'metrics', 'performance_score', 'user_id'];

        protected $casts = [
            'metrics' => 'array',
        ];

        public function user() {
            return $this->belongsTo(User::class);
        }
    }
