<?php
require 'vendor/autoload.php'; // Require the autoload file to load Guzzle HTTP client.

use GuzzleHttp\Client; // Import the Guzzle HTTP client namespace.
use GuzzleHttp\Psr7\Request; // Import the PSR-7 Request class.
use GuzzleHttp\Psr7\Utils; // Import the PSR-7 Utils class for working with streams.

$upload_client = new Client(['http_errors' => false]);
$upload_headers = [
  'api-key' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
  'content-filename' => 'filename.jpg',
  'Content-Type' => 'application/octet-stream'
];
$upload_body = file_get_contents('/path/to/file');
$upload_request = new Request('POST', 'https://api.pdfrest.com/upload', $upload_headers, $upload_body);
$upload_res = $upload_client->sendAsync($upload_request)->wait();
echo $upload_res->getBody() . PHP_EOL;

$upload_response_json = json_decode($upload_res->getBody());

$uploaded_id = $upload_response_json->{'files'}[0]->{'id'};

echo "Successfully uploaded with an id of: " . $uploaded_id . PHP_EOL;

$pdf_client = new Client(['http_errors' => false]);
$pdf_headers = [
  'api-key' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
  'Content-Type' => 'application/json'
];
$pdf_body = '{"id":"'.$uploaded_id.'"}';
$pdf_request = new Request('POST', 'https://api.pdfrest.com/pdf', $pdf_headers, $pdf_body);
$pdf_res = $pdf_client->sendAsync($pdf_request)->wait();
echo $pdf_res->getBody() . PHP_EOL;
