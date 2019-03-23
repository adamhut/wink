<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('wink_tags')) {
            Schema::table('wink_tags', function (Blueprint $table) {
                if (!Schema::hasColumn('wink_tags', 'meta')) {
                    $table->text('meta')->nullable();
                }
            });
        }

        if (Schema::hasTable('wink_pages')) {
            Schema::table('wink_pages', function (Blueprint $table) {
                if (!Schema::hasColumn('wink_pages', 'meta')) {
                    $table->text('meta')->nullable();
                }
            });
        }

        if (Schema::hasTable('wink_authors')) {
            Schema::table('wink_authors', function (Blueprint $table) {
                if (!Schema::hasColumn('wink_authors', 'meta')) {
                    $table->text('meta')->nullable();
                }
            });
        }

        if (Schema::hasTable('wink_posts')) {
            Schema::table('wink_posts', function (Blueprint $table) {
                if (!Schema::hasColumn('wink_posts', 'meta')) {
                    $table->text('meta')->nullable();
                }
            });
        }

        if (Schema::hasTable( 'wink_categories')) {
            Schema::table( 'wink_categories', function (Blueprint $table) {
                if (!Schema::hasColumn( 'wink_categories', 'meta')) {
                    $table->text('meta')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::table('wink_tags', function (Blueprint $table) {
            $table->dropColumn('meta');
        });


        Schema::table('wink_pages', function (Blueprint $table) {
            $table->dropColumn('meta');
        });

        Schema::table('wink_authors', function (Blueprint $table) {
            $table->dropColumn('meta');
        });

        Schema::table('wink_posts', function (Blueprint $table) {
            $table->dropColumn('meta');
        });

        if (Schema::hasTable('wink_categories')) {
            Schema::table('wink_categories', function (Blueprint $table) {
                if (Schema::hasColumn('wink_categories', 'meta')) {
                    $table->dropColumn('meta');
                }
            });
        }

    }
}
