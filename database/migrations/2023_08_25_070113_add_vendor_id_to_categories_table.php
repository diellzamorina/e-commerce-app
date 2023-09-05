<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorIdToCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Add the vendor_id column as an unsigned big integer
            $table->unsignedBigInteger('vendor_id')->nullable();

            // Add a foreign key constraint to link to the users (vendors) table
            $table->foreign('vendor_id')->references('id')->on('users');

            // Add an index on the vendor_id column
            $table->index('vendor_id');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Remove the foreign key and index
            $table->dropForeign(['vendor_id']);
            $table->dropIndex(['vendor_id']);

            // Remove the vendor_id column
            $table->dropColumn('vendor_id');
        });
    }
}
