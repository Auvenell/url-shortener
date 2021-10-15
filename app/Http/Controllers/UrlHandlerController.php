<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Models\urlHandler;
use Illuminate\Http\Request;

class UrlHandlerController extends Controller
{
    public function shorten(Request $request){
        // Respond to web form entry with shortened link
        if($request->inputUrl) {
            // Initialize Validator 
            $validator = Validator::make($request->all(), [
                'inputUrl' => 'required|url'
            ]);
            // Return error or proceed to process URL 
            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
                return view('welcome')->with(['error' => $validator->messages()]);
            } else {
                $currentRecord = urlHandler::where('status', 1)
                                            ->limit(1)                                
                                            ->get();

                $originalUrl = $request->inputUrl;
                $shortUrl = url('') . $currentRecord[0]->short_url;

                urlHandler::where('status', 1)
                            ->limit(1)
                            ->update([
                                'long_url'  => $originalUrl,
                                'status'    => 0,
                                'hit_count' => 1
                            ]);
                
                return view('welcome')->with(
                        [
                            'shortUrl'      => $shortUrl,
                            'originalUrl'   => $originalUrl
                ]);
            }   
        };
        
        // Respond to curl API request with verbose information
        if($request->url) {
            // Initialize Validator 
            $validator = Validator::make($request->all(), [
                'url' => 'required|url'
            ]);
            // Return error or proceed to process URL 
            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
            } else {
                $currentRecord = urlHandler::where('status', 1)
                                            ->limit(1)                                
                                            ->get();

                $originalUrl = $request->url;
                //$originalUrl = $validator->validated('url');
                $shortUrl = url('') . $currentRecord[0]->short_url;

                urlHandler::where('status', 1)
                            ->limit(1)
                            ->update([
                                'long_url'  => $originalUrl,
                                'status'    => 0,
                                'hit_count' => 1
                            ]);

                $information = [];

                foreach ($currentRecord as $record){
                    $data[] = [
                    'Original URL'      =>  $originalUrl,
                    'Shortened URL'     =>  $shortUrl,
                    'Access/Hit Count'  =>  $record->hit_count
                ];

                    $information = $data;
                }

                print_r(json_encode($information, JSON_UNESCAPED_SLASHES));
            }
        }   
    }

    public function check(Request $request){
        // Check where a registered short urls leads to
        if($request->checkUrl){
            // Initialize Validator 
            $validator = Validator::make($request->all(), [
                'checkUrl' => 'required|url'
            ]);
            // Return error or proceed to process URL 
            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
                print_r(json_encode($validator->messages()));
            } else {
                // Get suffix from url - check DB and return match
                $urlParts[] = explode("/",$request->checkUrl);
                $suffix = "/". end($urlParts[0]);
    
                $currentRecord = urlHandler::where('short_url', $suffix)->get();
    
                $originalUrl = $currentRecord[0]->long_url;
                $checkUrl = $request->checkUrl;
    
                $information[] = [
                    'Checking URL' => $checkUrl,
                    'Directs to' => $originalUrl
                ];
    
                print_r(json_encode($information, JSON_UNESCAPED_SLASHES));
                
            }
        }
    }

    public function toplist(){
        // Return 100 most visited short urls
            $currentRecord = urlHandler::where('hit_count', '>=', 1)
                                        ->limit(100) 
                                        ->orderBy('hit_count', 'DESC')
                                        ->get();
            
            $information = [];

            foreach ($currentRecord as $record)
            {
                $shortUrl      =    url('') . $record->short_url;
                $originalUrl   =    $record->long_url;

                $data[] = [
                    'Access/Hit Count'  =>  $record->hit_count,
                    'Shortened URL'     =>  $shortUrl,
                    'Original URL'      =>  $originalUrl
                ];

                $information = $data;
            }
            
            print_r(json_encode($information, JSON_UNESCAPED_SLASHES));
    }

    public function top100(Request $request){
        // Return webpage with Top 100 visited URLS
        $currentRecord = urlHandler::where('hit_count', '>=', 1)
                                    ->limit(100)
                                    ->orderBy('hit_count', 'DESC') 
                                    ->get();
        
        $information = [];
        $positionCounter = 0;
        foreach ($currentRecord as $record){
            $shortUrl      =    url('') . $record->short_url;
            $originalUrl   =    $record->long_url;
            $positionCounter++;
            $data[] = [
                'Popularity'        =>  $positionCounter,
                'Access/Hit Count'  =>  $record->hit_count,
                'Shortened URL'     =>  $shortUrl,
                'Original URL'      =>  $originalUrl
            ];

            $information = $data;
        }
        
        return view('top100')->with('data', $information);
    }

    public function redirectUrl(){
        // Get URL entered by visitor
        $baseUrl = url('');
        $suffix = $_SERVER['REQUEST_URI'];
        $currentUrl[] = ['url' => $baseUrl . $suffix];
        // Initialize Validator 
        $validator = Validator::make($currentUrl[0], [
            'url' => 'required|url'
        ]);
        // Return error or proceed to process URL 
        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return view('welcome')->with(['error' => $validator->messages()]);
        } else {
            $currentRecord = urlHandler::where('short_url', $suffix)->get();                                                        
            if($currentRecord[0]->long_url == ''){
                return view('urlRedirect')->with('data', 'This URL hasn\'t been created');
            } else {
                urlHandler::where('short_url', $suffix)
                            ->limit(1)
                            ->update([
                                'hit_count' => DB::raw('hit_count+1')
                            ]);
                $targetUrl = $currentRecord[0]->long_url;
                return view('urlRedirect')->with('data', $targetUrl);
            }
        }
    }
}