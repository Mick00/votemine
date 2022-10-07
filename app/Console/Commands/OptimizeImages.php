<?php

namespace App\Console\Commands;

use App\Image;
use Illuminate\Console\Command;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimizes all the uploaded images';

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
        Image::all()->each(function (Image $image) {
            $image->createOptimizedFormats();
        });
    }
}
