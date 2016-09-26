<?php
/**
 * This file is part of the nostikers bot.
 *
 * (c) Marco Boretto <marco.bore@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class SetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:set {botname?} {--y}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the webhook for a bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $botkey = $this->argument('botname');

        if ($botkey) {
            $config = Config::get('telegram.' . $botkey);

            $link = $config['link'];

            //Useful for automatic startup
            if ($this->option('y')) {
                $result_message = $this->setWebhook($config['token'], $config['name'], $link);
                $this->info('Setting webhook for: ' . $botkey . "\n" . ' on: ' . $link . "\n" . 'result:' . "\n" . $result_message);
                return;
            }

            if ($this->confirm('Webhook will be set for: ' . $botkey . "\n" . ' on: ' . $link . "\n" .'Do you wish to continue? [y|N]')) {
                $result_message = $this->setWebhook($config['token'], $config['name'], $link);
                $this->info('Setting webhook for: ' . $botkey . "\n" . ' on: ' . $link . "\n" . 'result:' . "\n" . $result_message);
            } else {
                $this->info('Webhook setting aborted by user');
            }
        } else {
            $this->info('Please insert: the name of the bot stored in the config file!');
        }
    }

    /**
     * setWebhook function
     *
     * @param srintg $token
     * @param string $name
     * @param string $link
     *
     * @return string
     */
    protected function setWebhook($token, $name, $link)
    {
        try {
            // create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($token, $name);
            $result = $telegram->setWebHook($link);
            if ($result->isOk()) {
                return $result->getDescription();
            } else {
                return $result->getDescription();
            }
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            return $e->getMessage();
        }
    }
}
