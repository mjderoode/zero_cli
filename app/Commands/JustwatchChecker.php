<?php

namespace App\Commands;

use DOMXPath;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class JustwatchChecker extends Command
{
    protected $signature = 'xxx';

    protected $description = 'Command description';

    protected $hidden = true;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch the HTML content from the URL
        $response = Http::get('https://www.justwatch.com/nl/tv-series/suits');

        $htmlContent = $response->body();

        // Load the HTML into a DOMDocument
        $dom = new DOMDocument();
        // Suppress warnings due to malformed HTML
        @$dom->loadHTML($htmlContent);

        // Create a new XPath object
        $xpath = new DOMXPath($dom);

        // Define the XPath query to find all <label> tags
        $query = "//label";
        $labels = $xpath->query($query);

        // Remove each <label> tag found
        foreach ($labels as $label) {
            $label->parentNode->removeChild($label);
        }


        // Define the XPath query for the div with class 'buybox-row stream'
        // $query = "//div[@class='buybox-row stream']";
        $query = "//div[@class='buybox-row offers AppleQualityTvBuyBox']";
        $divs = $xpath->query($query);

        // Output the content inside the selected div
        $new = '';
        foreach ($divs as $div) {
            // Output everything inside the div
            $new .= $dom->saveHTML($div);
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////

        $dom = new DOMDocument;
        libxml_use_internal_errors(true); // Suppress warnings
        $dom->loadHTML($new); // Load your HTML
        libxml_clear_errors(); // Clear any errors

        $elements = $dom->getElementsByTagName('*'); // Get all elements

        dd($elements); 

        foreach ($elements as $element) {
            // echo $element->nodeName . ": " . $element->nodeValue . "\n";
        }

        dd($new);
    }
}
