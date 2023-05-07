<?php

function get_vector_representation($text) {
  $text = strtolower($text); 
  $text = preg_replace("/[^A-Za-z0-9]/",' ', $text); 
  $words = explode(" ", $text); 
  
  $vector = array(); 
  
  foreach($words as $word) {
    if($word != "") { 
      if(array_key_exists($word, $vector)) { 
        $vector[$word]++;
      } else { 
        $vector[$word] = 1;
      }
    }
  }
  
  return $vector;
}
function cosine_similarity($vec1, $vec2) {
  $dot_product = 0.0;
  $magnitude1 = 0.0;
  $magnitude2 = 0.0;
  
  foreach ($vec1 as $key => $value) {
    if (array_key_exists($key, $vec2)) {
      $dot_product += $value * $vec2[$key];
    }
    $magnitude1 += $value * $value;
  }
  
  foreach ($vec2 as $key => $value) {
    $magnitude2 += $value * $value;
  }
  
  $magnitude = sqrt($magnitude1) * sqrt($magnitude2);
  
  if ($magnitude == 0.0) {
    return 0.0;
  } else {
    return $dot_product / $magnitude;
  }
}
?>
