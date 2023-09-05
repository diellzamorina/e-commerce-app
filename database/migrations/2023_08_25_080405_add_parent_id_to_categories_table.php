<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Add the parent_id column
            $table->unsignedBigInteger('parent_id')->nullable();

            // Create a foreign key constraint
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop the foreign key constraint and the column
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
