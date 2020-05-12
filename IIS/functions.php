<?php
function dbconnect(){
	$db = new mysqli("","","","");
    $db->set_charset("UTF8");
    return $db;
}

function getOpName($id){
    $sql = "SELECT jmeno, prijmeni FROM user WHERE id = $id";
    $db = dbconnect();
    $celejmeno = "";
    if($getname = $db->prepare($sql)){
        $getname->execute();
        $getname->bind_result($jmeno, $prijmeni);
        if($getname->fetch())
            $celejmeno = $jmeno." ".$prijmeni;
        $getname->close();
    }
    $db->close();
    return $celejmeno;
}

function getMestaDovozu($id){
    $sql = "SELECT mesto FROM mesta_dovozu WHERE jidelna = $id";
    $db = dbconnect();
    $mesta_dovozu = "";
    $prvni = true;
    $load_mesta_dovozu = $db->query($sql);
    if($load_mesta_dovozu->num_rows>0){
        while($row = $load_mesta_dovozu->fetch_assoc()){
            if($prvni){
                $mesta_dovozu = $row['mesto'];
                $prvni = false;
            }else{
                $mesta_dovozu = $mesta_dovozu.", ".$row['mesto'];
            }
        }
    }
    $load_mesta_dovozu->close();
    $db->close();
    return $mesta_dovozu == "" ? "-" : $mesta_dovozu;
}

//YYYY-MM-DD -> DD-MM-YYYY
function dateDTH(string $a){
    $rok = substr($a, 0, 4);
    $mes = substr($a, 5, 2);
    $den = substr($a, 8, 2);
    return "$den-$mes-$rok";
}

//DD-MM-YYYY -> YYYY-MM-DD
function dateHTD(string $a){
    $den = substr($a, 0, 2);
    $mes = substr($a, 3, 2);
    $rok = substr($a, 6, 4);
    return "$rok-$mes-$den";
}

//Generuje nový kód objednávky -> kód je číslo s 9ciframa
function genNewKod(){
    $db = dbconnect();
    while(1){
        $kod = random_int(100000000, 999999999);
        $sql = "SELECT id FROM objednavka WHERE kod = $kod";
        if($check_kod = $db->query($sql)){
            if(!$check_kod->num_rows > 0){
                return $kod;
            }
            $check_kod->close();
        }   
    }
    $db->close();
}

function emailExists($email){
    $db = dbconnect();
    $sql = "SELECT id FROM user WHERE email = '$email'";
    if($stat = $db->prepare($sql)){
        $stat->execute();
        $stat->bind_result($id);
        if($stat->fetch()){
            $stat->close();
            $db->close();
            return $id; 
        }
    }    
    $db->close();
    return 0; 
}

function strankovani($number, $offset, $stranka){
    if(ceil($number/10) > 1){
        echo "<nav aria-label='pages' class='mx-4 mb-4'>
                <ul class='pagination'>
                    ";
        if($offset == 0){
            $podm = ceil($number/10) < 3 ? ceil($number/10) : 3;
            echo "
            <li class='page-item'>
                <a class='page-link' href=\"".$stranka."page=1\"><b>1</b></a>
            </li>";
            for ($i=2; $i <= $podm; $i++)
                echo "
                <li class='page-item'>
                    <a class='page-link' href=\"".$stranka."page=$i\">$i</a>
                </li>";
            if(1 < $podm)
                echo "
                <li class='page-item'>
                    <a class='page-link' aria-label='Next' href=\"".$stranka."page=2\">
                        <span aria-hidden='true'>&raquo;</span>
                    </a>
                </li>";
        }else{
            $cur_page = $offset/10;
            echo "
            <li class='page-item'>
                <a class='page-link' aria-label='Previous' href=\"".$stranka."page=$cur_page\">
                    <span aria-hidden='true'>&laquo;</span>
                </a>
            </li>";               
            $podm = $cur_page > 3 ? $cur_page-2 : 1;
            for ($i=$podm; $i < $cur_page+1; $i++)
                echo "   
                <li class='page-item'>
                    <a class='page-link' href=\"".$stranka."page=$i\">$i</a>
                </li>";
                echo "
                <li class='page-item'>
                    <a class='page-link' href=\"".$stranka."page=".($cur_page+1)."\"><b>".($cur_page+1)."</b></a>
                </li>";
            $podm = ceil($number/10) < $cur_page+3 ? ceil($number/10) : $cur_page+4;
            for ($i=$cur_page+2; $i <= $podm; $i++)
                echo "       
                <li class='page-item'>
                    <a class='page-link' href=\"".$stranka."page=$i\">$i</a>
                </li>";
            if($cur_page+1 < $podm)
                echo "               
                <li class='page-item'>
                    <a class='page-link' aria-label='Next' href=\"".$stranka."page=".($cur_page+2)."\">
                        <span aria-hidden='true'>&raquo;</span>
                    </a>
                </li>";
        }
    }
    echo "</ul></nav>";
}

?>
<script>
	function Home(where, what){
        clearTimeout(what);
        window.location = where;
    }
</script>