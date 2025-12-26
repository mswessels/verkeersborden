<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLegacyCashierColumnsFromUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'stripe_active')) {
                $table->dropColumn('stripe_active');
            }

            if (Schema::hasColumn('users', 'stripe_subscription')) {
                $table->dropColumn('stripe_subscription');
            }

            if (Schema::hasColumn('users', 'stripe_plan')) {
                $table->dropColumn('stripe_plan');
            }

            if (Schema::hasColumn('users', 'last_four')) {
                $table->dropColumn('last_four');
            }

            if (Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->dropColumn('subscription_ends_at');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'stripe_active')) {
                $table->tinyInteger('stripe_active')->default(0);
            }

            if (! Schema::hasColumn('users', 'stripe_subscription')) {
                $table->string('stripe_subscription')->nullable();
            }

            if (! Schema::hasColumn('users', 'stripe_plan')) {
                $table->string('stripe_plan', 100)->nullable();
            }

            if (! Schema::hasColumn('users', 'last_four')) {
                $table->string('last_four', 4)->nullable();
            }

            if (! Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable();
            }
        });
    }
}
