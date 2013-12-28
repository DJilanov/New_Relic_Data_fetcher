<?php
  function get_newrelic_data(){
    // Here we load the file from the folder where is the script!
    $string = file_get_contents(realpath(dirname(__FILE__).'/settings.json'));
    // We decode the code 
    $json=json_decode($string,true);
    // Asign the ids that are loaded to be checked
    $ConnectionsToBeChecked = $json["Newrelic"]["ConnectionsIds"];
    // Asign the servers that are loaded for checking to a value
    $ServersIds = $json["Newrelic"]["Servers"];
    $Ids=[];
    foreach ($ConnectionsToBeChecked as  $value) {
      $Ids[]=$value;
    }
    // Here we will save the response
    $AllServersInfo;
    // Here we will set the begin time
    $startTime=date(DATE_ATOM, mktime()- $json["Newrelic"]["hoursBefore"]);
    // Here we will set the end time
    $endTime=date(DATE_ATOM, mktime());
    $curl = curl_init();

    for ($counterOfTheServers=0; $counterOfTheServers < count($ServersIds); $counterOfTheServers++) { 
    
      for($counterOfTheIds = 0; $counterOfTheIds < count($Ids); $counterOfTheIds++){
        // Set target URL
        curl_setopt($curl, CURLOPT_URL, "https://api.newrelic.com/api/v1/accounts/102603/metrics/data.json?begin=".$startTime."Z&end=".$endTime."Z&metrics=".$Ids[$counterOfTheIds]."&field=average_response_time&data_access_key=".$json["Newrelic"]["app-key"]."&agent_id=".$ServersIds[$counterOfTheServers]);
        // Set the desired HTTP method (GET is default, see the documentation for each request)
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        // Ask cURL to return the result as a string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Execute the request and decode the json result into an associative array
        $curl_res = curl_exec($curl);
        $response = json_decode($curl_res, true);

        // Test for errors returned by the API
        #print_r($response);
        if (isset($response['error'])) {
            print "Error: " . $response['error']['errormessage'] . "\n";
            // if we recieve error mostly the file that is written get incompleate....I personaly think its much better just to
            // exit the code whitotu saving anything so we dont break any markups
            exit;
        }
        $AllServersInfo[] = $response;
        
      }
    }
    return $AllServersInfo;
  }
  function Main(){
    //load the file where we will save everything.
    $filepath = realpath(dirname(__FILE__).'/storageNewRelic.json');
    $statusData = array();
    $fileData = array();
    // open it as read + write
    fopen($filepath, 'r+');
    $fileData = json_decode(file_get_contents($filepath));
    //main job. We load the calls and write it with timestamp so we knew how old is the info
 
    $statusData['objects'] = get_newrelic_data(); // make the call
    $statusData['timestamp'] = time();
    // if we gonna check did it get itself to the finale
    echo "execute ";
    file_put_contents($filepath, json_encode($statusData)); // write to file
   
  }
  Main();
?>
