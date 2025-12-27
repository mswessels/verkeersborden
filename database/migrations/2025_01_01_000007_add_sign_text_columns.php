<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignTextColumns extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('signs')) {
            return;
        }

        Schema::table('signs', function (Blueprint $table) {
            if (!Schema::hasColumn('signs', 'meaning')) {
                $table->text('meaning')->nullable();
            }

            if (!Schema::hasColumn('signs', 'mnemonic')) {
                $table->text('mnemonic')->nullable();
            }

            if (!Schema::hasColumn('signs', 'mistake')) {
                $table->text('mistake')->nullable();
            }

            if (!Schema::hasColumn('signs', 'practice_questions')) {
                $table->text('practice_questions')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('signs')) {
            return;
        }

        Schema::table('signs', function (Blueprint $table) {
            if (Schema::hasColumn('signs', 'practice_questions')) {
                $table->dropColumn('practice_questions');
            }

            if (Schema::hasColumn('signs', 'mistake')) {
                $table->dropColumn('mistake');
            }

            if (Schema::hasColumn('signs', 'mnemonic')) {
                $table->dropColumn('mnemonic');
            }

            if (Schema::hasColumn('signs', 'meaning')) {
                $table->dropColumn('meaning');
            }
        });
    }
}
