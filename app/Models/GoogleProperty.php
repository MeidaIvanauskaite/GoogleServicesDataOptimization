<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class GoogleProperty extends Model {
        public function account() {
            return $this->belongsTo(GoogleAccount::class);
        }

        public function meta() {
            return $this->hasOne(PropertyMetadata::class, 'google_property_id');
        }

        public function pagespeed() {
            return $this->hasOne(PageSpeedResult::class, 'google_property_id');
        }

        public function property() {
            return $this->belongsTo(GoogleProperty::class, 'google_property_id');
        }

        protected $fillable = [
            'google_account_id',
            'ga_property_id',
            'display_name',
            'currency',
            'time_zone',
            'industry',
            'service_level',
        ];
    }
