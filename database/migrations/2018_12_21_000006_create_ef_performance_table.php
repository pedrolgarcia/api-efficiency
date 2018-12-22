<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEfPerformanceTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'ef_performance';

    /**
     * Run the migrations.
     * @table ef_performance
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('created_projects');
            $table->integer('completed_projects');
            $table->integer('completed_ontime_projects');
            $table->integer('created_tasks');
            $table->integer('completed_tasks');
            $table->integer('completed_ontime_tasks');
            $table->integer('errors');
            $table->integer('logic_errors');
            $table->integer('compilation_errors');
            $table->integer('design_errors');
            $table->integer('modeling_tasks');
            $table->integer('documentation_tasks');
            $table->integer('programming_tasks');
            $table->integer('design_tasks');
            $table->integer('test_tasks');
            $table->integer('others_tasks');
            $table->time('time_completed_tasks');
            $table->integer('user_id')->unsigned();
            $table->nullableTimestamps();

            $table->foreign('user_id')
                ->references('id')->on('ef_user')
                ->onDelete('no action')
                ->onUpdate('no action')
                ->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->set_schema_table);
     }
}
