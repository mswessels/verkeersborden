<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashierTables extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->string('name');
                $table->string('stripe_id');
                $table->string('stripe_status');
                $table->string('stripe_plan')->nullable();
                $table->integer('quantity')->nullable();
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'stripe_status']);
            });
        }

        if (! Schema::hasTable('subscription_items')) {
            Schema::create('subscription_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('subscription_id');
                $table->string('stripe_id')->index();
                $table->string('stripe_plan');
                $table->integer('quantity')->nullable();
                $table->timestamps();

                $table->unique(['subscription_id', 'stripe_plan']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('subscription_items');
        Schema::dropIfExists('subscriptions');
    }
}
