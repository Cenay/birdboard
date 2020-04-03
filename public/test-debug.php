<?php
//
//function isOfDrivingAge($age) {
//    return $age >= 16;
//}
//
//function notifyUserOfDriverStatus( $name, $age) {
//    $message = isOfDrivingAge($age) ? 'You may drive.' : 'You may not drive';
//    return "{$name}: {$message}";
//}
//
//$status = notifyUserOfDriverStatus('John Doe', 17);
//echo sprintf( "<h3>We made it: %s</h3>", $status );
//
////var_dump($status);


$text1 = 'Party Info: Business Party - Latin Cuisine
Guests: 14 (Estimated)
Occasion: Team Building Event
Wine Option: None
Beer Option: None
Salad: Mojito Tropical Salad
Appetizer: Seafood Causa with Huancaina Sauce
Entree: Lomo Saltado
Dessert: Flan';


$text2 = 'Party Info: Business Party - Latin Cuisine
Guests: 35 (Estimated)
Occasion: Team Building Event
Wine Option: None
Beer Option: None
Salad: Mojito Tropical Salad
Appetizer: Seafood Causa with Huancaina Sauce
Entree: Seafood and Vegetable Paella
Dessert: Pineapple Rum Cake and Ice Cream';

similar_text( $text1, $text2, $percent );

echo $percent;

