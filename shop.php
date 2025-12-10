<?php 
include 'header.php'; 
include 'functions.php';
$producten = getProducten();

// Filters
$catFilter = isset($_GET['cat']) ? $_GET['cat'] : [];
$zoek = isset($_GET['q']) ? strtolower($_GET['q']) : '';

$gefilterdeProducten = [];
foreach($producten as $p) {
    $matchCat = empty($catFilter) || in_array($p['categorie'], $catFilter);
    $matchZoek = empty($zoek) || strpos(strtolower($p['naam'] . ' ' . $p['merk']), $zoek) !== false;
    if ($matchCat && $matchZoek) $gefilterdeProducten[] = $p;
}
?>

<div style="text-align: center; margin-bottom: 60px;">
    <h2>De Collectie</h2>
    <p style="color: #666;">Exclusieve geuren voor de moderne gentleman.</p>
</div>

<div class="shop-layout">
    <div class="sidebar">
        <form action="shop.php" method="GET">
            <div class="filter-group">
                <h4>ZOEKEN</h4>
                <input type="text" name="q" value="<?php echo htmlspecialchars($zoek); ?>" placeholder="Naam geur..." style="width: 100%; padding: 10px; background: transparent; border: 1px solid #444; color: white;">
            </div>
            <div class="filter-group">
                <h4>CATEGORIE</h4>
                <label class="filter-option"><input type="checkbox" name="cat[]" value="niche" <?php if(in_array('niche', $catFilter)) echo 'checked'; ?>> 💎 Niche / Luxury</label>
                <label class="filter-option"><input type="checkbox" name="cat[]" value="school" <?php if(in_array('school', $catFilter)) echo 'checked'; ?>> 🏢 Office / Daily</label>
                <label class="filter-option"><input type="checkbox" name="cat[]" value="uitgaan" <?php if(in_array('uitgaan', $catFilter)) echo 'checked'; ?>> 🍸 Club / Night</label>
                <label class="filter-option"><input type="checkbox" name="cat[]" value="date" <?php if(in_array('date', $catFilter)) echo 'checked'; ?>> 🌹 Date Night</label>
            </div>
            <button type="submit" class="btn-gold" style="width: 100%;">FILTER</button>
        </form>
    </div>

    <div style="flex: 1;">
        <div class="grid">
            <?php foreach($gefilterdeProducten as $p): ?>
                <div class='card'>
                    <a href="detail.php?id=<?php echo $p['id']; ?>">
                        <div class='card-img-container'>
                            <img src='<?php echo $p['img']; ?>' alt='<?php echo $p['naam']; ?>'>
                        </div>
                    </a>
                    <div class='card-content'>
                        <span class='brand'><?php echo $p['merk']; ?></span>
                        <h4><a href="detail.php?id=<?php echo $p['id']; ?>"><?php echo $p['naam']; ?></a></h4>
                        
                        <!-- Laagste prijs berekenen -->
                        <?php 
                        $minPrice = 1000;
                        foreach($p['shops'] as $s) {
                            if($s !== null && $s['prijs'] < $minPrice) $minPrice = $s['prijs'];
                        }
                        ?>
                        <div class='card-price'>Vanaf € <?php echo number_format($minPrice, 2, ',', '.'); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>