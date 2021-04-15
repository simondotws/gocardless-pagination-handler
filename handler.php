<?php

require("vendor/autoload.php");
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new \GoCardlessPro\Client(array(
  'access_token' => $_ENV['GC_ACCESS_TOKEN'],
  'environment'  => \GoCardlessPro\Environment::SANDBOX
));

//Set the date ranges
$from = new DateTime('03/01/2021');
$to = new DateTime('03/02/2021');

//Set empty array
$arr = [];

//Search for Payment Records (Replace with your search query)
$result = $client->payments()->list([
  "params" => [
    "created_at[gte]" => $from->format('c'),
    "created_at[lte]" => $to->format('c'),
    "status" => "paid_out"
  ]
]);

//Push all PaymentID's into array
foreach($result->records as $record){
  $arr[] = $record->id;
}

//Logic to deal with pagination
do{
  //Search for Payment Records (Replace with your search query)
  $result = $client->payments()->list([
    "params" => [
      "after" => $result->after,
      "created_at[gte]" => $from->format('c'),
      "created_at[lte]" => $to->format('c'),
      "status" => "paid_out"
    ]
  ]);
  //Push all PaymentID's into array
  foreach($result->records as $record){
    $arr[] = $record->id;
  }
} while ($result->after); //Only while the after variable is not null will this logic run

//Dump out the array
echo "<pre>";
print_r($arr);

?>
