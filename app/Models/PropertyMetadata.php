<?php
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;

    class PropertyMetadata extends Model {
        protected $fillable = ['property_id', 'tag', 'status', 'note'];
    }
