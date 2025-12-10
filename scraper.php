<?php
function getLivePriceFromUrl($url, $defaultPrice) {
    // 1. Initialiseer Curl (de browser-simulator)
    $ch = curl_init();
    
    // 2. Instellingen om te lijken op een echte bezoeker (Google Chrome)
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Max 3 seconden wachten, anders wordt je site traag
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Voor XAMPP
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

    // 3. Voer uit
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 4. Als de site niet laadt of ons blokkeert (403), gebruik direct de standaardprijs
    if (!$html || $httpCode != 200) {
        return $defaultPrice;
    }

    // 5. Probeer de prijs te vinden in de "Google Data" (JSON-LD)
    // Dit is de data die webshops in hun code zetten speciaal voor zoekmachines
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $scripts = $dom->getElementsByTagName('script');
    
    foreach ($scripts as $script) {
        if ($script->getAttribute('type') === 'application/ld+json') {
            $json = json_decode($script->nodeValue, true);
            
            // Checken of we een prijs zien in de structuur
            if (isset($json['@type']) && ($json['@type'] === 'Product' || $json['@type'] === 'ProductGroup')) {
                if (isset($json['offers']['price'])) {
                    return (float)$json['offers']['price'];
                }
                if (isset($json['offers'][0]['price'])) {
                    return (float)$json['offers'][0]['price'];
                }
            }
        }
    }

    // 6. Als Plan A (JSON-LD) niet werkt, probeer Plan B (Meta tags)
    // Veel sites gebruiken OpenGraph tags voor Facebook
    $metas = $dom->getElementsByTagName('meta');
    foreach ($metas as $meta) {
        if ($meta->getAttribute('property') === 'product:price:amount' || $meta->getAttribute('property') === 'og:price:amount') {
            return (float)$meta->getAttribute('content');
        }
    }

    // 7. Als alles faalt: geef de standaardprijs uit je JSON terug.
    return $defaultPrice;
}
?>