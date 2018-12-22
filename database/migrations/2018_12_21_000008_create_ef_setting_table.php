<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEfSettingTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'ef_setting';

    /**
     * Run the migrations.
     * @table ef_setting
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('tips')->nullable();
            $table->integer('language_id')->unsigned();
            $table->integer('theme_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->nullableTimestamps();

            $table->foreign('language_id')
                ->references('id')->on('ef_language')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_id')
                ->references('id')->on('ef_user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('theme_id')
                ->references('id')->on('ef_theme')
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
