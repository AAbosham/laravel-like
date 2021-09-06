<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid');
                $table->unsignedBigInteger('user_id')->index();
                $table->morphs('likeable');
                $table->tinyInteger('type');
                $table->tinyInteger('isdeleted')
                    ->default(0);
                $table->unsignedBigInteger('deleted_by')
                    ->nullable();
                $table->dateTime('deleted_at')
                    ->nullable();

                $table->index([
                    'likeable_id',
                    'likeable_type'
                ], 'like_likeable_id_likeable_type_index');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
