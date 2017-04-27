  error_log("--getFlightObject creation --> " . print_r($fly, true));
        foreach($fly as $flight)
        {
        $flightIternary = '';

        $flyIternary = new Flight();
        $originPos = 0;

        $arrivalPos = count($flight) - 1;
   
        $flyIternary->totalPrice = $iternary['TotalPrice'];
        $flyIternary->basePrice = $iternary['BasePrice'];
        $flyIternary->taxes = $iternary['Taxes'];
        $flyIternary->orgin = $flight[$originPos]['@attributes']['Origin'];
        $flyIternary->depatureTime = date("h:i a", strtotime($flight[$originPos]['@attributes']['DepartureTime']));
        $flyIternary->depatureDate = date("D, d M Y", strtotime($flight[$originPos]['@attributes']['DepartureTime']));
        $flyIternary->arrivalTime = date("h:i a", strtotime($flight[$arrivalPos]['@attributes']['ArrivalTime']));
        $flyIternary->arrivalDate = date("D, d M Y", strtotime($flight[$arrivalPos]['@attributes']['ArrivalTime']));
        $flyIternary->journyTime = getTimeDiff($flyIternary->depatureTime, $flyIternary->arrivalTime);
        $flyIternary->airline = $flight[$originPos]['@attributes']['Carrier'];
        $flyIternary->stops = 1;
        foreach ($fly as $flightIndex) 
            {

            $flightIternary[] = $flightIndex;
              }
        }
        $flyIternary->flightDetails = $flightIternary;
        // /error_log("--flight --> ".print_r($flight,true));
        $flyseg[] = $flyIternary;