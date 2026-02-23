<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->foreignId('car_id') // creates car_id
                ->constrained('cars') // References cars.id
                ->onDelete('cascade'); // If car deleted → delete quotes

            $table->string('overview_of_work', 256)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('repairer')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
