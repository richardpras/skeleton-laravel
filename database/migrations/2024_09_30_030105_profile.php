<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('profiles', function (Blueprint $table) {
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('committee_id');
            $table->string('email')->unique();
            $table->string('district')->nullable();
            $table->string('phone')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('sub_district')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('profile',function (Blueprint $table){
            $table->dropForeign('lists_user_id_foreign');
            $table->dropIndex('lists_user_id_index');
            $table->dropColumn('user_id');
        });
        Schema::dropIfExists('profiles');
    }
};
