<?php
// filepath: /var/www/html/laraveles/WallapopApp/database/migrations/2023_10_01_000003_create_sales_table.php



use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('product');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->boolean('isSold')->default(false);
            $table->binary('img');
            $table->timestamps();
        });

        // Insert a random product with a binary image
        DB::table('sales')->insert([
            'category_id' => 1, // Assuming category_id 1 exists
            'user_id' => 1, // Assuming user_id 1 exists
            'product' => 'Random Product ' . Str::random(5),
            'description' => 'This is a random product description.',
            'price' => rand(10, 100),
            'isSold' => false,
            'img' => base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAUA'
            . 'AAAFCAYAAACNbyblAAAAHElEQVQI12P4'
            . '//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg=='), // Example binary data
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};