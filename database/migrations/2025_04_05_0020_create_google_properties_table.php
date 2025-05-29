<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::create('google_properties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('google_account_id')->constrained()->onDelete('cascade');
                $table->string('ga_property_id')->unique(); // e.g., "properties/123456"
                $table->string('display_name');
                $table->string('currency')->nullable();
                $table->string('time_zone')->nullable();
                $table->string('industry')->nullable();
                $table->string('service_level')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void {
            Schema::dropIfExists('google_properties');
        }
    };
