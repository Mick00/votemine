<?php

namespace App\Jobs;

use App\Player;
use App\Server;
use App\VoteSite;
use App\VoteValidation\VoteHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $site;
    protected $server;
    protected $player;
    public function __construct(VoteSite $site, Server $server, Player $player)
    {
        $this->site = $site;
        $this->server = $server;
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            VoteHandler::notifyServer($this->site, $this->server, $this->player);
        } catch (\Exception $e){
            echo $e;
        }
    }
}
