<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FirstVersion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Table schema from php telegram bot
        //Copied from longman telegram bot version:0.35 
        $sql = file_get_contents('database/migrations/structure.sql');
        $result = DB::unprepared($sql);
        if ($result) {
            echo 'Error Telegram library not created';
        }

        $prefix = 'nostk_';
        Schema::rename('botan_shortener', $prefix . 'botan_shortener');
        Schema::rename('conversation', $prefix . 'conversation');
        Schema::rename('telegram_update', $prefix . 'telegram_update');
        Schema::rename('edited_message', $prefix . 'edited_message');
        Schema::rename('callback_query', $prefix . 'callback_query');
        Schema::rename('message', $prefix . 'message');
        Schema::rename('chosen_inline_result', $prefix . 'chosen_inline_result');
        Schema::rename('inline_query', $prefix . 'inline_query');
        Schema::rename('user_chat', $prefix . 'user_chat');
        Schema::rename('chat', $prefix . 'chat');
        Schema::rename('user', $prefix . 'user');

        Schema::create('nostk_settings', function (Blueprint $table) {
            $prefix = 'nostk_';
            $table->bigInteger('chat_id')->comment('chat unique id');
            $table->bigInteger('updated_by')->nullable()->comment('Who do the latest changes');

            $table->tinyInteger('ban_sticker')->default(1)->comment('1 ban sticker');
            $table->tinyInteger('ban_gif')->default(1)->comment('1 ban gif');
            $table->tinyInteger('ban_voice')->default(1)->comment('1 ban voice');
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on($prefix . 'chat');
            $table->foreign('updated_by')->references('id')->on($prefix . 'user');

            $table->index(['updated_by', 'ban_sticker', 'ban_voice', 'ban_gif']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = 'nostk_';
        Schema::drop($prefix . 'settings');

        //Php telegram bot schema
        Schema::drop($prefix . 'botan_shortener');
        Schema::drop($prefix . 'conversation');
        Schema::drop($prefix . 'telegram_update');
        Schema::drop($prefix . 'edited_message');
        Schema::drop($prefix . 'callback_query');
        Schema::drop($prefix . 'message');
        Schema::drop($prefix . 'chosen_inline_result');
        Schema::drop($prefix . 'inline_query');
        Schema::drop($prefix . 'user_chat');
        Schema::drop($prefix . 'chat');
        Schema::drop($prefix . 'user');
    }
}
