<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use View;
use File;

class UpdateLineTodayXML extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateLineTodayXML:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for update LineToday XML every 10 minutes per daily';

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
		
        $program = array(
						2687, //วันใหม่ไทยพีบีเอส
						800339, //วันใหม่วาไรตี้
						800020, //คนสู้โรค
						103, //สอนศิลป์
						800155, //สีสันทันโลก
						800283, //สะเทือนไทย
						800252, //ความจริงไม่ตาย
						800188, //Kid Rangers ปฏิบัติการเด็กช่างคิด
						800366, //Chris Jobs
						800315, //ยินดีที่ได้รู้จัก
						991, //ไทยบันเทิง
						86, //สถานีประชาชน
						129, //ขบวนการ Fun น้ำนม
						376, //Foodwork
						800358, //มหาอำนาจบ้านนา
						790, //กินอยู่คือ
						255, //เที่ยวไทยไม่ตกยุค
						800343, //ซีรีส์วิถีคน
						800353, //คิดสิ...ต้องรอด
						331, //ข่าวค่ำ มิติใหม่ทั่วไทย
						635, //ทุกทิศทั่วไทย
						274, //ที่นี่ Thai PBS
						800334, //COME HOME บ้านที่กลับมา
						43, //ภัตตาคารบ้านทุ่ง
						214, //ดูให้รู้ Dohiru
						800351, //A Life on the Road  ถนน คน ชีวิต
						800434 //บ้าน-พลัง-ใจ
					);
		
		$newsURL = "https://news.thaipbs.or.th/thaipbs-api/news?page=1&limit=10";
		//$newsURL = "https://news.thaipbs.or.th/api-v/infographic";
		$programURL = "https://program.thaipbs.or.th/api/videos?program=".implode(',',$program)."&mp4_media_status=available&include_mp4_media_url=1&limit=30";
		
		
		$getNews =  Http::get($newsURL)->json()['data'];
		$responseProgram =  Http::get($programURL)->json()['data'];
		
		if(!empty($getNews) && !empty($responseProgram)){
		
			$responseNews = array_map(function($tag) {

				$newArray = $tag;
				$newArray['start_publish'] = $tag['publishTime'];
				$newArray['updated_at'] = $tag['lastUpdateTime'];
				$newArray['isNews'] = true;
				
				if (in_array('COVID-19', $tag['tags'])) {
					$newArray['categoryName'] = 'โควิด 19';
				} else {
					$newArray['categoryName'] = 'ข่าว' .$tag['categoryName'];
				}
				
				unset($newArray['publishTime']);
				unset($newArray['lastUpdateTime']);
				
				return $newArray;
				
			}, $getNews);
			
			$items = Arr::collapse([$responseNews ,$responseProgram]);
			
			$sort_field = 'start_publish';
			usort($items, function ($a, $b) use (&$sort_field) {
				return strtotime($b[$sort_field]) - strtotime($a[$sort_field]);
			});
			
			$content = View::make('linetoday', compact('items'))
						->withHeader([
								'Content-Type', 'text/xml',
								'Pragma: public',
								'Cache-control: private',
								'Expires: -1'
							])
						->render();
			$destinationPath = public_path()."/linetoday.xml";
			File::put($destinationPath , $content);
			$this->info('Create XML Successfully');
		}
		else{	
			$this->info('Cannot retrive News or Program');
		}
		
    }
}
