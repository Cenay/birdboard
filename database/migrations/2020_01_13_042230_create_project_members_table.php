<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// Pivot table
		// Defines members of a "project"
        Schema::create('project_members', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
			$table->timestamps();
			
			// Setup an index for both
			$table->index(['project_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_members');
    }
}
