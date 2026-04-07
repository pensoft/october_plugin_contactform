<?php namespace Pensoft\ContactForm\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftContactformData extends Migration
{
    public function up(): void
    {
        Schema::table('pensoft_contactform_data', function(Blueprint $table)
        {
            $table->string('organisation')->nullable();
            $table->string('subject')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pensoft_contactform_data', function(Blueprint $table)
        {
            $table->dropColumn('organisation');
            $table->dropColumn('subject');
        });
    }
}