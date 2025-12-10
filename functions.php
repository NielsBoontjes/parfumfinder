<?php
function getProducten() {
    $jsonFile = 'products.json';
    if (!file_exists($jsonFile)) return [];
    
    $jsonData = file_get_contents($jsonFile);
    $data = json_decode($jsonData, true);
    return $data ? $data : [];
}

function getProductById($id) {
    $producten = getProducten();
    foreach ($producten as $p) {
        if ($p['id'] == $id) return $p;
    }
    return null;
}
?>