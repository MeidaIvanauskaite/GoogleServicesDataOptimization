<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class PropertyMetadata extends Model {
        protected $fillable = ['google_property_id', 'tag', 'status', 'note', 'user_id'];

        public function property() {
            return $this->belongsTo(GoogleProperty::class, 'property_id', 'ga_property_id');
        }

        public function user() {
            return $this->belongsTo(User::class);
        }
    }
