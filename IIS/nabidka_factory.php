<?php 
include 'functions.php';

$den = date('Y-m-d', strtotime("+1 day"));
$konec = date('Y-m-d', strtotime("10 February 2020"));
$addatte = 1;

$jidelna = 2;
$stav = 'Otevřeno';
echo "<form method='post' style='margin:30 0 0 30px; position: absolute;'><input type='submit' style='cursor:pointer; background-color: red; border: none;  width: 50px; height: 25px;' name='submit' value='Button'></form>";
echo "<form method='post' style='margin:30 0 0 115px; position: absolute; width: 25px;'><input type='submit' style='cursor:pointer;border: none;width: 50px; height: 25px;' name='submit2' value='Red'></form>";
echo "<b style='position: absolute; margin: 85 0 0 30px;'>Press with caution !</b>";
echo "<p style='font-size: 10px; color: #717171; margin: 115 0 0 42.5px; position: absolute;'>Hint : Press the red button</p>";
echo "<div style='position: absolute; margin: 150 0 0 15px;'>";
if(isset($_POST['submit'])){
    $db = dbconnect();
    while($den <= $konec)
    {
        $sql = "INSERT INTO nabidka (jidelna, den, stav) VALUES (?, ?, ?)";
        $vloz = $db->prepare($sql);
        $vloz->bind_param("iss", $jidelna, $den, $stav);
        $vloz->execute();
        $id = $vloz->insert_id;
        $vloz->close();

        $den = date('Y-m-d', strtotime("+".$addatte++." days"));
        var_dump($id);
        var_dump($den);
    }
    $db->close();
}


$nabidka = 4;
$konec = 99;
if(isset($_POST['submit2'])){
    $db = dbconnect();
    $h = [1,2,3,5,6,19,20]; //Hlavní jídla
    $p = [4,7,8];           //Polévky
    while($nabidka <= $konec){
        $h1 = rand(0, 6);
        $h2 = rand(0, 6);
        while($h1 == $h2){
            $h2 = rand(0, 6);
        }
        $h3 = rand(0, 6);
        while($h1 == $h3 || $h2 == $h3){
            $h3 = rand(0, 6);
        }
        $p1 = rand(0, 2);
        $jidla = [$h[$h1], $h[$h2], $h[$h3], $p[$p1]];
        $sql = "INSERT INTO jidla_v_nabidce(nabidka, jidlo) VALUES (?, ?)";        
        for ($i=0; $i <= 3; $i++) { 
            $vloz = $db->prepare($sql);
            $vloz->bind_param("ii", $nabidka, $jidla[$i]);
            $vloz->execute();
            $id = $vloz->insert_id;
            $vloz->close();
            var_dump($id);
            var_dump($jidla[$i]);
        }
        $nabidka++;
    }
    $db->close();
}
echo "</div>";
?>