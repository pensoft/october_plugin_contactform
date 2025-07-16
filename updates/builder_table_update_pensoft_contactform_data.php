<?php namespace Pensoft\ContactForm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftContactformData extends Migration
{
    public function up()
    {
        Schema::table('pensoft_contactform_data', function($table)
        {
            $table->string('organisation')->nullable();
            $table->string('subject')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_contactform_data', function($table)
        {
            $table->dropColumn('organisation');
            $table->dropColumn('subject');
        });
    }
}
