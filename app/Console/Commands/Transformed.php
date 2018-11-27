<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Transformed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Transformed:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集的程序处理';

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
        $files = Storage::allFiles('data');
        foreach ($files as $item){
            $file =  file_get_contents(public_path($item));
            $a = mb_convert_encoding($file, "UTF-8", "GBK");
            $str = str_replace(array("  "," ","\r\n", "\r", "\n","\t"), "", $a);
            $myfile = fopen(public_path($item), "w") or die("Unable to open file!");
            fwrite($myfile, $str);
            fclose($myfile);
            $this->info('《'.$item.'》转码完毕'.PHP_EOL);
        }

        $files = Storage::allFiles('data');
        foreach ($files as $item){
            $file =  file(public_path($item));
            file_put_contents(public_path("new.txt"), $file[0]."\n", FILE_APPEND);
            $this->info($item.'文件追加成功'.PHP_EOL);
        }

        $files = Storage::allFiles('data');
        foreach ($files as $item){
            Storage::disk('')->delete(($item));
        }
        $this->info('文件清除成功');



    }
}
