<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestsController extends Controller
{

    function fileToJson (Request $urlRequest) {
        $regexpression = '/"([^"]+)"/'; // Regular expression to extract quoted text
        $filePath = getcwd() . '/../storage/epa-http-demo.txt';

        if (file_exists($filePath)) {
            $fileData = file($filePath);

            $results = [];
            foreach($fileData as $line) {
                $quoted = false;
                if (preg_match($regexpression, $line, $m)) {
                    $quoted = isset($m[1]) ? $m[1] : false;
                } // "has no quoted", shouldn't happen
                $l = explode(" ", $line);

                // TODO: check if it's an IP or a host to set the array/json key
                $time = explode(':', $l[1]);
                $request = explode(' ', $quoted);

                if (count($request) > 1) { // has also method
                    $protocols = (isset($request[2]) ? explode('/', $request[2]) : '');
                    $requestData = [
                        'method' => $request[0],
                        'url' => isset($request[1]) ? $request[1] : '',
                        'protocol' => isset($protocols[0]) ? $protocols[0] : $protocols,
                        'protocol_version' => isset($protocols[1]) ? $protocols[1] : ''
                    ];
                }

                $newArr = [
                    'host' => $l[0],
                    'datetime' => [
                        'day' => str_replace('[', '', $time[0]),
                        'hour' => $time[1],
                        'minute' => $time[2],
                        'second' => str_replace(']', '', $time[3])
                    ],
                    'request' => isset($requestData) ? $requestData : '',
                    'response_code' => (isset($l[5]) ? $l[5] : ''),
                    'document_size' => (isset($l[6]) ? $l[6] : '')
                ]; // TODO: add more fields, there are no more fields in current files format
                $results[] = $newArr;
            }
            $limit = $urlRequest->get('limit') ? $urlRequest->get('limit') : 100;
            $offset = $urlRequest->get('offset') ? $urlRequest->get('offset') : 0;
            $total = count($results);

            $limitedResults = [];
            for ($i = $offset; $i <= ($offset + $limit); $i++) {
                $limitedResults[] = $results[$i];
            }
            return response()->json([
                'success' => true,
                'limit' => $limit,
                'offset' => $offset,
                'total' => $total,
                'data' => $limitedResults
            ]);
        } else {
            echo "file not found $filePath";
            // TODO: decide here if notify the user in frontend, show an specific error,
            // return a different error code instead of 200, etc
            return response()->json([
                'success' => 'false',
                'error' => 'file not found'
            ]);
        }
    }

}
