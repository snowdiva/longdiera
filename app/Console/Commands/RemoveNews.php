<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RemoveNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '批量转移文章内容:从数据库到文件';

    protected $newsTable = 'sc_news';

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
        DB::table($this->newsTable)
            ->where('type', '<>', 2)
            ->orderBy('id')
            ->chunk(100, function ($news) {
                foreach ($news as $item) {
                    $path = 'news/' . floor($item->id / 1000);
                    Storage::put($path . '/' . $item->id . '.txt', $item->content);
                }
            });
    }
}
