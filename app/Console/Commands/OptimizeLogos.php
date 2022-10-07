<?php

namespace App\Console\Commands;

use App\Image;
use Illuminate\Console\Command;
use Imagick;
use ImagickPixel;

class OptimizeLogos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:logos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize images in public folder';

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
     * @throws \ImagickException
     */
    public function handle()
    {
        Image::optimizeVotingSiteLogos();
    }
}
