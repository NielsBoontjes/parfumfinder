<?php 
include 'header.php'; 
include 'functions.php';
include 'scraper.php'; // We laden de scraper in

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$p = getProductById($id);

if (!$p) {
    echo "<div class='container'><h2>Niet gevonden</h2><a href='shop.php'>Terug naar shop</a></div>";
    include 'footer.php';
    exit;
}
?>

<a href="shop.php" style="color: #666; font-size: 0.8rem; letter-spacing: 1px;">&larr; TERUG NAAR OVERZICHT</a>

<div class="detail-wrapper">
    <div class="detail-left">
        <img src="<?php echo $p['img']; ?>" alt="<?php echo $p['naam']; ?>">
    </div>
    
    <div class="detail-right">
        <span class="detail-type"><?php echo $p['merk']; ?></span>
        <h1 class="detail-title"><?php echo $p['naam']; ?></h1>
        <span style="font-size: 0.9rem; color: #fff; border: 1px solid #333; padding: 5px 15px; display: inline-block; margin-bottom: 20px;"><?php echo $p['type']; ?></span>
        
        <p class="detail-desc"><?php echo $p['desc']; ?></p>
        
        <div class="notes-grid">
            <div class="note-item">
                <h5>GEURNOTEN</h5>
                <span><?php echo $p['noten']; ?></span>
            </div>
            <div class="note-item">
                <h5>CATEGORIE</h5>
                <span><?php echo ucfirst($p['categorie']); ?></span>
            </div>
        </div>

        <h3 style="color: #D4AF37; font-size: 1rem; margin-bottom: 15px;">ACTUELE PRIJZEN & BESCHIKBAARHEID</h3>
        
        <div class="shop-list">
            <?php foreach($p['shops'] as $shopNaam => $data): ?>
                <div class="shop-row">
                    <div class="shop-logo"><?php echo $shopNaam; ?></div>
                    
                    <?php if($data !== null): ?>
                        <?php 
                            // --- HIER GEBEURT DE MAGIE ---
                            // We proberen de prijs live op te halen.
                            // Als het faalt (door beveiliging), gebruiken we $data['prijs'] uit de JSON.
                            
                            // Let op: Live scrapen maakt de pagina trager. 
                            // Voor de demo doen we het, maar in het echt cache je dit.
                            $livePrijs = getLivePriceFromUrl($data['link'], $data['prijs']);
                        ?>

                        <div class="shop-price">
                            <!-- We tonen de prijs. Als de live prijs verschilt, maken we hem groen -->
                            <?php if($livePrijs < $data['prijs']): ?>
                                <span style="text-decoration: line-through; color: #666; font-size: 0.8rem;">€ <?php echo number_format($data['prijs'], 2, ',', '.'); ?></span>
                                <span style="color: #00ff88;">€ <?php echo number_format($livePrijs, 2, ',', '.'); ?></span>
                            <?php else: ?>
                                € <?php echo number_format($livePrijs, 2, ',', '.'); ?>
                            <?php endif; ?>
                        </div>
                        
                        <a href="<?php echo $data['link']; ?>" target="_blank" class="btn-gold" style="padding: 10px 25px; font-size: 0.7rem;">BEKIJK BIJ <?php echo strtoupper($shopNaam); ?></a>
                    <?php else: ?>
                        <div class="status-unavailable">Niet leverbaar</div>
                        <div style="width: 100px;"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <p style="font-size: 0.7rem; color: #444; margin-top: 10px;">* Prijzen worden live gecontroleerd. Indien de verbinding faalt, tonen we de laatst bekende prijs.</p>
    </div>
</div>

<?php include 'footer.php'; ?>