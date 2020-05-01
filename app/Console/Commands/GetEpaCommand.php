<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetEpaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epa:get_file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // TODO: Add in backoffice frontend the option for the admins to change the URL and options like timeout
        // TODO: Add a helper for downloading files instead of using the code here
        // $epaUrl = 'http://ita.ee.lbl.gov/html/contrib/EPA-HTTP.html';
        $epaUrl = 'https://ita.ee.lbl.gov/html/contrib/EPA-HTTP.html'; // Currently it works with https only
        // TODO: remove this error_log
        error_log(time() . ': here run GetEpaCommand ', 3, '/tmp/log');

        $url  = $epaUrl;
        $path = getcwd().'/storage/EPA-HTTP.txt';
        $fp = fopen($path, 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch,CURLOPT_TIMEOUT,0); // Define a timeout to avoid failing on long time download
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        fclose($fp);

        if (isset($error_msg)) {
            // TODO - Handle cURL error accordingly
            // TODO: define what to do on failes (re run command, inform via email, save it into database, etc)
            error_log(time() . ': Downloading file failed: ' . $error_msg . "\n", 3, '/tmp/log');
        }
        print_r($data);
    }
}
