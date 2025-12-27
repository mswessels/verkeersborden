<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCbrFieldsToRijscholen extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('rijscholen')) {
            Schema::create('rijscholen', function (Blueprint $table) {
                $table->id();
                $table->string('email')->default('');
                $table->timestamps();
            });
        }

        Schema::table('rijscholen', function (Blueprint $table) {
            if (!Schema::hasColumn('rijscholen', 'name')) {
                $table->string('name')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'cbr_url')) {
                $table->string('cbr_url')->nullable()->unique();
            }
            if (!Schema::hasColumn('rijscholen', 'cbr_slug')) {
                $table->string('cbr_slug')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'street')) {
                $table->string('street')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'postal_code')) {
                $table->string('postal_code')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'city')) {
                $table->string('city')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'phone')) {
                $table->string('phone')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'website')) {
                $table->string('website')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'address_raw')) {
                $table->string('address_raw')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'rijschoolnummer')) {
                $table->string('rijschoolnummer')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'kvk_nummer')) {
                $table->string('kvk_nummer')->default('');
            }
            if (!Schema::hasColumn('rijscholen', 'praktijkopleidingen')) {
                $table->json('praktijkopleidingen')->nullable();
            }
            if (!Schema::hasColumn('rijscholen', 'theorieopleidingen')) {
                $table->json('theorieopleidingen')->nullable();
            }
            if (!Schema::hasColumn('rijscholen', 'beroepsopleidingen')) {
                $table->json('beroepsopleidingen')->nullable();
            }
            if (!Schema::hasColumn('rijscholen', 'bijzonderheden')) {
                $table->json('bijzonderheden')->nullable();
            }
            if (!Schema::hasColumn('rijscholen', 'exam_results')) {
                $table->json('exam_results')->nullable();
            }
            if (!Schema::hasColumn('rijscholen', 'coordinates')) {
                $table->json('coordinates')->nullable();
            }
            if (!Schema::hasColumn('rijscholen', 'cbr_modified_at')) {
                $table->timestamp('cbr_modified_at')->nullable();
            }
            if (!Schema::hasColumn('rijscholen', 'crawled_at')) {
                $table->timestamp('crawled_at')->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('rijscholen')) {
            return;
        }

        Schema::table('rijscholen', function (Blueprint $table) {
            if (Schema::hasColumn('rijscholen', 'crawled_at')) {
                $table->dropColumn('crawled_at');
            }
            if (Schema::hasColumn('rijscholen', 'cbr_modified_at')) {
                $table->dropColumn('cbr_modified_at');
            }
            if (Schema::hasColumn('rijscholen', 'coordinates')) {
                $table->dropColumn('coordinates');
            }
            if (Schema::hasColumn('rijscholen', 'exam_results')) {
                $table->dropColumn('exam_results');
            }
            if (Schema::hasColumn('rijscholen', 'bijzonderheden')) {
                $table->dropColumn('bijzonderheden');
            }
            if (Schema::hasColumn('rijscholen', 'beroepsopleidingen')) {
                $table->dropColumn('beroepsopleidingen');
            }
            if (Schema::hasColumn('rijscholen', 'theorieopleidingen')) {
                $table->dropColumn('theorieopleidingen');
            }
            if (Schema::hasColumn('rijscholen', 'praktijkopleidingen')) {
                $table->dropColumn('praktijkopleidingen');
            }
            if (Schema::hasColumn('rijscholen', 'kvk_nummer')) {
                $table->dropColumn('kvk_nummer');
            }
            if (Schema::hasColumn('rijscholen', 'rijschoolnummer')) {
                $table->dropColumn('rijschoolnummer');
            }
            if (Schema::hasColumn('rijscholen', 'address_raw')) {
                $table->dropColumn('address_raw');
            }
            if (Schema::hasColumn('rijscholen', 'website')) {
                $table->dropColumn('website');
            }
            if (Schema::hasColumn('rijscholen', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('rijscholen', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('rijscholen', 'postal_code')) {
                $table->dropColumn('postal_code');
            }
            if (Schema::hasColumn('rijscholen', 'street')) {
                $table->dropColumn('street');
            }
            if (Schema::hasColumn('rijscholen', 'cbr_slug')) {
                $table->dropColumn('cbr_slug');
            }
            if (Schema::hasColumn('rijscholen', 'cbr_url')) {
                $table->dropColumn('cbr_url');
            }
            if (Schema::hasColumn('rijscholen', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
}
