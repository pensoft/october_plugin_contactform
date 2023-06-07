<?php namespace Pensoft\ContactForm\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftContactformData extends Migration
{
    public function up()
    {
        Schema::create('pensoft_contactform_data', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('email')->nullable();
            $table->text('message')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('form')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_contactform_data');
    }
}
