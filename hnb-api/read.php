<?php

include('db.php');

$content = file_get_contents("https://api.hnb.hr/tecajn/v1?valuta=EUR&valuta=USD");
$result  = json_decode($content, true);

?>
<br>
<?php

foreach ($result as $currency) {
?>

    <div>
        <h2><?php echo $currency['Valuta'] ?></h2>
        <h3>Broj tečajnice: <?php echo $currency['Broj tečajnice'] ?></h3>
        <h3>Datum primjene: <?php echo $currency['Datum primjene'] ?></h3>
        <h3>Država: <?php echo $currency['Država'] ?></h3>
        <h3>Šifra valute: <?php echo $currency['Šifra valute'] ?></h3>
        <h3>Jedinica: <?php echo $currency['Jedinica'] ?></h3>
        <h3>Kupovni za devize: <?php echo $currency['Kupovni za devize'] ?> kn</h3>
        <h3>Srednji za devize: <?php echo $currency['Srednji za devize'] ?> kn</h3>
        <h3>Prodajni za devize: <?php echo $currency['Prodajni za devize'] ?> kn</h3>
    </div>



<?php
}
