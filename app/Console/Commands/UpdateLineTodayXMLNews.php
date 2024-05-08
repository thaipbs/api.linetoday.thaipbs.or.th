<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use View;
use File;

class UpdateLineTodayXMLNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateLineTodayXMLNews:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update LineToday XML News every 10 minutes per daily';

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
     * @return int
     */
    public function handle()
    {
		
        date_default_timezone_set('Asia/Bangkok'); 
		$newsURL = "https://news.thaipbs.or.th/thaipbs-api/news?page=1&limit=20";
		$items =  Http::get($newsURL)->json()['data'];
		
		/*
		$newsURL2 = "https://news.thaipbs.or.th/api-v/line-data";
		
		$items2 =  Http::get($newsURL2)->json()['infographic']['items'];
		
		
		$items3 =  Http::get($newsURL2)->json()['nowContent']['items'];
		
		$items = array_merge($items1,$items2);
		$items = array_merge($items,$items3);
		*/
		
				
		$updateTime = array_column($items , 'lastUpdateTime');
		
		//var_dump($items[0]['media']['default']);
		
		$items[0]['uuID'] = '01'.$items[0]['id'].strtotime(max($updateTime)).'000';
		$items[0]['uuTime'] = strtotime(max($updateTime)).'000';
		
		if(!empty($items)){
			
			$content = View::make('linetodayNews', compact('items'))
						->withHeader([
								'Content-Type', 'text/xml',
								'Pragma: public',
								'Cache-control: private',
								'Expires: -1'
							])
						->render();
			$destinationPath = public_path()."/xml_linetoday/news.xml";
			
			File::put($destinationPath , $content);
			$this->info('Create XML Successfully');
			
		}
		else{
			$this->info('Cannot retrive News');
		}
		
    }
}
