<?php

include('db.php');
/*
$year = date('Y');
$month = date('m');
$day = date('d');
//$content = file_get_contents("https://api.hnb.hr/tecajn/v1?datum=" . $year . "-" . $month . "-" . $day);
*/
$content = file_get_contents("https://api.hnb.hr/tecajn/v1?valuta=EUR&valuta=USD");
$result  = json_decode($content, true);


function floatvalue($val)
{
    $val = str_replace(",", ".", $val);
    $val = preg_replace('/\.(?=.*\.)/', '', $val);
    return floatval($val);
}

function convertValues($kup, $sre, $pro)
{
    $kupovni = floatvalue($kup);
    $srednji = floatvalue($sre);
    $prodajni = floatvalue($pro);
    return array($kupovni, $srednji, $prodajni);
}

function checkDB($date, $currency)
{
    global $conn;
    $stmt = $conn->prepare("SELECT valuta FROM currencies WHERE datum_primjene = ? AND valuta = ?");
    $stmt->bind_param('ss', $date, $currency);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = mysqli_num_rows($result);
    return $num;
}

?>
<br>
<?php

foreach ($result as $currency) {

    global $conn;
    $newDate = date("Y-m-d", strtotime($currency['Datum primjene']));
    list($kupovni, $srednji, $prodajni) = convertValues($currency['Kupovni za devize'], $currency['Srednji za devize'], $currency['Prodajni za devize']);

    $num = checkDB($newDate, $currency['Valuta']);
    if ($num == 0) {
        $stmt = $conn->prepare("INSERT INTO currencies (sifra_valute,broj_tecajnice,drzava,valuta,jedinica,kupovni_dev,srednji_dev,prodajni_dev,datum_primjene) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('iissiddds', $currency['Šifra valute'], $currency['Broj tečajnice'], $currency['Država'], $currency['Valuta'], $currency['Jedinica'], $kupovni, $srednji, $prodajni, $newDate);
        if ($stmt->execute()) {
            echo "Podaci spremljeni za sljedeću valutu:" . $currency['Valuta'] . '<br>';
        } else {
            echo $conn->error;
        }
    } else {
        echo "Podaci za ovaj dan za sljedeću valutu:" . $currency['Valuta'] . " su već spremljeni" . "<br>";
    }

?>

<?php
}
