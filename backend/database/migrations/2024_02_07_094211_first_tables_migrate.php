<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    private $charset = 'utf8mb4';
    private $charset_lg = 'utf8mb4_spanish_ci';
    
    public function up(): void
    {
        Schema::create('cat_module', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('key', 20)->nullable();
            $table->string('description', 255);
            $table->boolean('active')->default(true);
            $table->boolean('catalog')->nullable()->default('false');
            $table->timestamps();
            $table->softDeletes();

            $table->charset = $this->charset;
            $table->collation = $this->charset_lg;
        });

        Schema::create('cat_permission_list', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('key', 20)->nullable();
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();

            $table->charset = $this->charset;
            $table->collation = $this->charset_lg;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_module');
        Schema::dropIfExists('cat_permission_list');
    }
};
