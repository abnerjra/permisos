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
    private $collation = 'utf8mb4_spanish_ci';

    public function up(): void
    {
        Schema::create('cat_structure', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('acronym', 50);
            $table->string('color', 7);
            $table->boolean('active')->nullable()->default(true);

            $table->timestamps();
            $table->softDeletes();
            
            $table->charset = $this->charset;
            $table->collation = $this->collation;
        });
        
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_last_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('active')->default(false);

            $table->unsignedBigInteger('structure_id')->nullable();
            $table->foreign('structure_id')->references('id')->on('cat_structure')->onDelete('restrict')->onUpdate('restrict');
            
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('cat_structure');
    }
};
