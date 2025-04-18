<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() {
            Schema::create('property_metadata', function (Blueprint $table) {
                $table->id();
                $table->string('property_id');
                $table->string('tag')->nullable();
                $table->string('status')->default('Needs Review');
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void {
            Schema::dropIfExists('property_metadata');
        }
    };
