<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitive_games', function (Blueprint $table) {
            $table->id();
            $table->unique(['user_id', 'date']);

            $table->foreignIdFor(User::class);
            $table->date('date');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->boolean('completed')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitive_games');
    }
};
