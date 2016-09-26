<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Facades\Config;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Nostickers
|
*/
$app->post('/', function () {
    try {
        $config = Config::get('telegram.nostickers');

        // Create Telegram API object
        $telegram = new \Longman\TelegramBot\Telegram($config['token'], $config['name']);

        //Accessing the underlying pdo instance
        $pdo = DB::connection('mysql')->getPdo();
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        $telegram->enableExternalMySQL($pdo, 'nostk_');

        //Linking commands dirs
        $sys_dir = __DIR__.'/../Commands/SystemCommands';
        $usr_dir = __DIR__.'/../Commands/UserCommands';
        $telegram->addCommandsPath($sys_dir);
        $telegram->addCommandsPath($usr_dir);

        $telegram->enableBotan($config['botan_token']);

        $error_path = storage_path() . '/logs/' . $config['name'] . '-error.log';
        \Longman\TelegramBot\TelegramLog::initErrorLog($error_path);
        //\Longman\TelegramBot\TelegramLog::initDebugLog(storage_path() . '/logs/' . $config['name'] . '-debug.log');
        \Longman\TelegramBot\TelegramLog::initUpdateLog(storage_path() . '/logs/' . $config['name'] . '.log');

        // Handle telegram webhook request
        $telegram->handle();
    } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
        // log telegram errors
        \Longman\TelegramBot\TelegramLog::error($e);
        // Silence is golden!
        //echo $e;
    }
    return null;
});
