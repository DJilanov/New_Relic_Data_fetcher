<?php
  function get_newrelic_data_errors(){
    // Here we load the file from the folder where is the script!
    $string = file_get_contents(realpath(dirname(__FILE__).'/settings.json'));
    // We decode the code 
    $json=json_decode($string,true);
    // Asign the servers that are loaded for checking to a value
    $ServersIds = $json["Newrelic"]["Servers"];
    // Here we will save the response
    $AllServersInfo;
    // Here we will set the begin time
    $startTime=date(DATE_ATOM, mktime()- $json["Newrelic"]["hoursBefore"]);
    // Here we will set the end time
    $endTime=date(DATE_ATOM, mktime());
    $curl = curl_init();
    for($counterOfTheServers = 0; $counterOfTheServers < count($ServersIds); $counterOfTheServers++){
        // Set target URL
      curl_setopt($curl, CURLOPT_URL, "https://api.newrelic.com/api/v1/accounts/102603/metrics/data.json?begin=".$startTime."Z&end=".$endTime."Z&metrics=Errors/all&field=errors_per_minute&data_access_key=".$json["Newrelic"]["app-key"]."&agent_id=".$ServersIds[$counterOfTheServers]);
        // Set the desired HTTP method (GET is default, see the documentation for each request)
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        // Ask cURL to return the result as a string
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

      $curl_res = curl_exec($curl);
      $response = json_decode($curl_res, true);
        // Check for errors returned by the API
      if (isset($response['error'])) {
          print "Error: " . $response['error']['errormessage'] . "\n";
          exit;
      }
      $AllServersInfo[] = $response;
    }
    return $AllServersInfo;
  }

 function cronExec(){
      //load the file where we will save everything.
      $filepath = realpath(dirname(__FILE__).'/storageNewRelicErrors.json');
      $statusData = array();
      $fileData = array();
      // open it as read + write
      fopen($filepath, 'r+');
      $fileData = json_decode(file_get_contents($filepath));
      //check is it stale ( pingdom dont give infinity hook ups and we must care about how many times we fetch info from their api)
      $statusData['objects'] = get_newrelic_data_errors(); // make the call
      $statusData['timestamp'] = time();
      // if we gonna check did it get itself to the finale
      echo "execute ";
      file_put_contents($filepath, json_encode($statusData)); // write to file
    
  }
  cronExec();
?>