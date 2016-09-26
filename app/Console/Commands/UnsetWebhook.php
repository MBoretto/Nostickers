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

class UnsetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:unset {botname?} {--y}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unset the webhook for a bot';

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

            //Useful for automatic startup
            if ($this->option('y')) {
                $result_text = $this->unsetWebhook($config['token'], $config['name']);
                $this->info('Unsetting webhook for: ' . $botkey . "\n" . 'result:' . "\n" . $result_text);
                return;
            }

            if ($this->confirm('Webhook will be unset for: ' . $botkey . "\n" . 'Do you wish to continue? [y|N]')) {
                $result_text = $this->unsetWebhook($config['token'], $config['name']);
                $this->info('Unsetting webhook for: ' . $botkey . "\n" . 'result:' . "\n" . $result_text);
            } else {
                $this->info('Webhook unsetting aborted by user');
            }
        } else {
            $this->info('Please insert: the name of the bot stored in the config file!');
        }
    }

    /**
     * unsetWebhook function
     *
     * @param srintg $token
     * @param string $name
     *
     * @return string
     */
    protected function unsetWebhook($token, $name)
    {
        try {
            // create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($token, $name);
            // set webhook
            $result = $telegram->unsetWebHook();
            if ($result->isOk()) {
                return $result->getDescription();
            } else {
                return $result->getDescription();
            }
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            return $e;
        }
    }
}
