<?php

namespace App\Commands;

use DOMXPath;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class LaravelDocsHelperCommand extends Command
{
    protected $signature = 'docs';

    protected string $htmlContent = ''; 

    // protected $description = 'Command description';

    public function __construct(
        protected string $base_url = "https://laravel.com"
    )
    {
        parent::__construct();

        $response = Http::baseUrl($this->base_url)
            ->withHeaders([
                "User-Agent" => "LaravelZero/1.0",
            ])->get('/docs/11.x');
            
        $this->htmlContent = $response->body();
    }

    public function handle()
    {
        $main_items = $this->extractHtml(".//div[@class=\"docs_sidebar\"]/ul/li/h2", true, true);

        $choice = $this->choice(
            'Choose the main item from the list',
            $main_items,
            null
        );
        
        $this->comment("You have chosen: $choice");

        $index = array_search($choice, $main_items) + 1;
        $sub_items_stripped = $this->extractHtml("(//div[@class=\"docs_sidebar\"]//li/ul)[".$index."]//li/a", true);
        $sub_items = $this->extractHtml("(//div[@class=\"docs_sidebar\"]//li/ul)[".$index."]//li/a", false);
        
        $choice = $this->choice(
            'Make a choice:',
            $sub_items_stripped,
            0
        );

        preg_match('/<a\s+href=["\']([^"\']+)["\'].*?>/i', $sub_items[array_search($choice, $sub_items_stripped)], $matches); 

        shell_exec("open '$this->base_url.$matches[1]'");

        $this->comment("END OF THE LINE");
    }

    public function extractHtml($query, $stripper = true, $is_main_item = false)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($this->htmlContent);

        $divs = (new DOMXPath($dom))->query($query);

        $main_items = [];
        $x = 0;
        foreach ($divs as $div) {
            if ($stripper) {
                $main_items [$x]= strip_tags($dom->saveHTML($div));

                if ($is_main_item) {
                    
                    $index = $x + 1;
                    $collected = collect($this->extractHtml("(//div[@class=\"docs_sidebar\"]//li/ul)[$index]//li/a", true));
                    $mapped = $collected->map(function($item, $index) {
                        return "\t ==> " . $item . " [$index]";
                    })->join("\n");

                    $main_items[$x] .= "\n $mapped";
                }
            } else {
                $main_items [$x]= $dom->saveHTML($div);
            }
            $x++;
        }

        return $main_items; 
    }
}
