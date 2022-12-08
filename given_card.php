<?php 
# DECLARE VARIABLES
$aJson = array("return_status"=>1, "return_message"=>"success", "data"=>array());

# VERIFY PARAMETERS
if (!isset($_REQUEST["txtNumPeople"]) || empty($_REQUEST["txtNumPeople"])) {
	$vErrorMessage = "Number of people is required.";
    $aJson["return_status"] = 0;
    $aJson["return_message"] = strip_tags($vErrorMessage);
    echo json_encode($aJson);
    exit;
}
if (!is_numeric($_REQUEST["txtNumPeople"])) {
	$vErrorMessage = "Please enter number of people with a valid number.";
    $aJson["return_status"] = 0;
    $aJson["return_message"] = strip_tags($vErrorMessage);
    echo json_encode($aJson);
    exit;
}
if ($_REQUEST["txtNumPeople"] < 1) {
	$vErrorMessage = "Please enter number of people greater than or equal to 1.";
    $aJson["return_status"] = 0;
    $aJson["return_message"] = strip_tags($vErrorMessage);
    echo json_encode($aJson);
    exit;
}

// Note: cards can be get by json (preset card data)
// I use loop array to generate Total 52 cards 
$arrCardType = ['S', 'H', 'D', 'C'];
$arrCardNum = ['A', '2', '3', '4', '5', '6', '7', '8', '9', 'X', 'J', 'Q', 'K'];
$arrCards = [];
for ($i=0; $i < sizeof($arrCardType); $i++) { 
	for ($j=0; $j < sizeof($arrCardNum); $j++) { 
		array_push($arrCards, $arrCardType[$i]."-".$arrCardNum[$j]);
	}
}

shuffle($arrCards); // randomize the order of the elements in the array
$numPeople = intval($_REQUEST["txtNumPeople"]);

if ($numPeople <= 4) {
	$maxCardsGiven = 13;
} else {
	$maxCardsGiven = sizeof($arrCards) / $numPeople;
}

for ($i=0; $i < $maxCardsGiven; $i++) { 
	for ($j=0; $j < $numPeople; $j++) { 
		if (!isset($player[$j])) {
			$player[$j]["cards"] = [];
			$player[$j]["card_count"] = 0;
		}
		
		if (sizeof($arrCards) > 0) {
			$firstCard = array_shift($arrCards); // remove the first element from an array, and return the value of the removed element
			array_push($player[$j]["cards"], $firstCard);
			$player[$j]["card_count"] = $player[$j]["card_count"] + 1;
		}
	}
}
$aJson["data"] = $player;

echo json_encode($aJson);
exit;
?>