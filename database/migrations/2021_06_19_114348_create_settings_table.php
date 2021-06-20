<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('dateBegin')->comment('Дата заезда');
            $table->timestamp('dateEnd')->comment('Дата отъезда');
            $table->integer('people')->comment('Количество проживающих');
            $table->float('price')->comment('Базовая цена');
            $table->float('seasonPrice')->nullable()->comment('Сезонная цена');
            $table->timestamp('seasonDateBegin')->nullable()->comment('Старт сезона');
            $table->timestamp('seasonDateEnd')->nullable()->comment('Окончание сезона');
            $table->integer('maxPeople')->nullable()->comment('Максимальное число проживающих');
            $table->float('pricePeople')->nullable()->comment('Цена за дополнительного проживающего');            
            $table->string('discount')->nullable()->comment('Скидка');
            $table->integer('discountDays')->nullable()->comment('Скидка от числа дней');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
