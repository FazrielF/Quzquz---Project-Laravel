<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCorrectToOptionsTable extends Migration
{
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            if (!Schema::hasColumn('options', 'is_correct')) {
                $table->boolean('is_correct')->default(false)->after('option_text');
            }
        });
    }

    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('is_correct');
        });
    }
}