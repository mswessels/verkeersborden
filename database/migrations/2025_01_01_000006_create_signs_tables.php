<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignsTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('signs_categories')) {
            Schema::create('signs_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('letter');
            });
        }

        if (Schema::hasTable('signs')) {
            return;
        }

        Schema::create('signs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id')->index();
            $table->string('name')->default('');
            $table->string('description')->default('');
            $table->string('description_short')->default('');
            $table->string('image')->default('');
            $table->string('code')->index();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signs');
        Schema::dropIfExists('signs_categories');
    }
}
