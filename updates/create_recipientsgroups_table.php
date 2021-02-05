<?php namespace Pensoft\ContactForm\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateRecipientsgroupsTable extends Migration
{
	public function up()
	{
		if (!Schema::hasTable('pensoft_contactform_recipientsgroups')) {
			Schema::create('pensoft_contactform_recipientsgroups', function(Blueprint $table)
			{
				$table->engine = 'InnoDB';
				$table->increments('id')->unsigned();
				$table->string('title', 255);
				$table->string('emails', 255);
				$table->timestamp('created_at')->nullable();
				$table->timestamp('updated_at')->nullable();
				$table->smallInteger('type')->default(1);
			});
		}
	}

	public function down()
	{
		if (Schema::hasTable('pensoft_contactform_recipientsgroups')) {
			Schema::dropIfExists('pensoft_contactform_recipientsgroups');
		}
	}
}
