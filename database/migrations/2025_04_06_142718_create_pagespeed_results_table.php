<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() {
            Schema::create('page_speed_results', function (Blueprint $table) {
                $table->id();
                $table->string('property_id');
                $table->string('url');
                $table->json('metrics')->nullable();
                $table->integer('performance_score')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void {
            Schema::dropIfExists('page_speed_results');
        }
    };
