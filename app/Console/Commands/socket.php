<?php

namespace App\Console\Commands;

use App\Http\Controllers\web\RatChetController;
use Illuminate\Console\Command;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
class socket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will start php socket server.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Socket server running at : '.env('SOCKET_HOST').":".env('SOCKET_PORT'));
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new RatChetController()
                )
            ),
            env('SOCKET_PORT'),
            env('SOCKET_HOST')
        );

        $server->run();
    }

}
