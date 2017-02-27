<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SetAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setarticle:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '设置已经到时间的定时审核文章';

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
            ->where('status', 2)
            ->where('audit_time', '<=', time())
            ->update(['status' => 1]);
    }
}
