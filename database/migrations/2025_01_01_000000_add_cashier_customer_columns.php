<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashierCustomerColumns extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'stripe_id')) {
                $table->string('stripe_id')->nullable()->index();
            }

            if (! Schema::hasColumn('users', 'card_brand')) {
                $table->string('card_brand')->nullable();
            }

            if (! Schema::hasColumn('users', 'card_last_four')) {
                $table->string('card_last_four', 4)->nullable();
            }

            if (! Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'card_brand')) {
                $table->dropColumn('card_brand');
            }

            if (Schema::hasColumn('users', 'card_last_four')) {
                $table->dropColumn('card_last_four');
            }
        });
    }
}
