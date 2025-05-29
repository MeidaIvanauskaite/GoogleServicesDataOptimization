<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class GoogleAccount extends Model {
        public function properties() {
            return $this->hasMany(GoogleProperty::class);
        }
        
        public function property() {
            return $this->belongsTo(GoogleProperty::class, 'google_property_id');
        }

        protected $fillable = [
            'ga_account_id',
            'name',
        ];
    }
