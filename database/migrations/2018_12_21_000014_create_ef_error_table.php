<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEfErrorTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'errors';

    /**
     * Run the migrations.
     * @table ef_error
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('occurrences');
            $table->integer('error_type_id')->unsigned();
            $table->integer('attempts_solve');
            $table->timestamp('discovery_at');
            $table->timestamp('removed_at')->nullable();
            $table->text('description');
            $table->integer('error_report_id')->unsigned();


            $table->foreign('error_type_id')
                ->references('id')->on('error_types')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('error_report_id')
                ->references('id')->on('error_reports')
                ->onDelete('no action')
                ->onUpdate('no action');
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
