<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('Tasks', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description')->nullable();
      $table->foreignId('user_id')->references('id')->on('Users')->onUpdate('no action')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
      Schema::dropIfExists('Tasks');
  }
}
