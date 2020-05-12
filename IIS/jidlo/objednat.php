<?php 
session_start();
include '../functions.php';
$jidelna = filter_input(INPUT_POST, "jidelna");
$den = filter_input(INPUT_POST, "den", FILTER_SANITIZE_STRING);
$podm = true;
?>
<!DOCTYPE html>
<html>
<head>
        <!-- META TAGS -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	    <meta name="Jidelna" content="IIS Project Jidelna" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous" />

        <!-- REGULAR CSS -->
        <link rel="stylesheet" href="../styles/styles.css" />

        <!-- FAVICON -->
		<link rel="icon" href="../pic/ico.ico" type="image/x-icon" />
        
        <!-- TITLE -->
        <title>Objednat | Jidelna IS</title>
    </head>
    <body>
        <nav class='mb-4 navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container'>
                <a class='navbar-brand' href='../index.php'><img src='../pic/logo/logo.png' /></a>
                    <button class='navbar-toggler' type='button' data-togle='collapse' data-target='#navbarSupportedContent-4' aria-controls='navbarSupportedContent-4' aria-expanded='false' aria-label='Toggle navigation'>
                        <span class='navbar-toggler-icon'></span>
                    </button>
                    <div class='collapse navbar-collapse' id='navbarSupportedContent-4'>
                        <ul class='navbar-nav ml-auto'>
                        <?php
                            if(isset($_SESSION['jmeno'])){
                                echo "
                                <li class='nav-link dropdown'>       
                                    <span class='nav-link dropdown-toggle' id='navbarDropdownMenuLink-4' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Přihlášen jako: 
                                    <b>".$_SESSION['jmeno']."</b>
                                    </span>    
                                    <div class='dropdown-menu dropdown-menu-right' aria-labelledby='navbarDropdownMenuLink-4'>
                                    ";
                                echo "<a class='dropdown-item' href=\"../account/user.php?user=".$_SESSION['id']."\">Můj účet</a>";
                                echo "<a class='dropdown-item' href='../account/moje_objednavky.php'>Moje objednávky</a>";
                            if($_SESSION['prava'] == 2){
                                echo "<a class='dropdown-item' href='../op/moje_jidelny.php'>Moje jídelny</a>";
                                echo "<a class='dropdown-item' href='../op/dat_zakazky.php'>Nové zakázky</a>";
                            }
                            if($_SESSION['prava'] <= 2){
                                echo "<a class='dropdown-item' href='../op/add_jidlo.php'>Vložení jídla do DB</a>";
                                if($_SESSION['prava'] == 1){
                                    echo "<a class='dropdown-item' href='../admin/accounts.php'>Účty</a>";
                                    echo "<a class='dropdown-item' href='../admin/add_jidelna.php'>Vložení jídelny do DB</a>";
                                    echo "<a class='dropdown-item' href='../admin/jidelny.php'>Jídelny</a>";
                                }
                            }
                            if($_SESSION['prava'] == 3) {
                                echo "<a class='dropdown-item' href='../ridic/zakazky.php?search=&f_akt=akt'>Zakázky</a>";
                            }
                            echo "<div class='dropdown-divider'></div>";
                            echo "<a class='dropdown-item' href='../account/login.php?action=off'>Odhlásit se</a>";
                            echo "</div></nav>";
                            }else{
                            echo "
                                <li class='nav-item'>
                                    <a class='nav-link' href='../account/register.php'><button class='btn btn-outline-info'>Registrace</button></a>
                                </li>
                                <li class='nav-item'>
                                    <a class='nav-link' href='../account/login.php'><button class='btn btn-outline-warning'>Přihlášení</button></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                ";
            }
        ?>

    <main class="container">
        <section class="row justify-content-md-center">
            <div class="col col-md-12">
                <div class="card shadow-lg border-dark">
                <h5 class="card-header">Objednat</h5>
                    <div class="card-body">
        <?php 
            $db = dbconnect();
            
            if(isset($_POST['submitobj'])){
                $mestoobj = filter_input(INPUT_POST, "mesto", FILTER_SANITIZE_STRING);
                $adresaobj = filter_input(INPUT_POST, "adresa", FILTER_SANITIZE_STRING);         
                if(!isset($_SESSION['id'])){
                    $telefon = filter_input(INPUT_POST, "telefon", FILTER_SANITIZE_STRING);
                    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
                    if(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
                        $email_kon = emailExists($email);
                        if($email_kon == ""){
                            $sql_new_acc = "INSERT INTO user (email, telefon) VALUES (?, ?)";
                            if ($new_acc = $db->prepare($sql_new_acc)){
                                $new_acc->bind_param("ss", $email, $telefon);
                                $new_acc->execute();
                                $idUser = $new_acc->insert_id;
                                $new_acc->close();
                            }
                        }
                    }else{
                        echo "<p class='alert alert-danger border-danger text-center my-2'>Špatně zadaný email!</p>";
                    }
                }else{
                    $idUser = $_SESSION['id'];
                }

                if(isset($idUser)){
                    $sql_obj = "INSERT INTO objednavka (user, cena, cas_objednani, den_dodani, mesto, adresa, jidelna, kod) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)";
                    if($obj = $db->prepare($sql_obj)){
                        $celkem = filter_input(INPUT_POST, "celkem");
                        $kod = genNewKod();
                        $obj->bind_param("iisssii", $idUser, $celkem, $den, $mestoobj, $adresaobj, $jidelna, $kod);
                        $obj->execute();
                        $id_obj = $obj->insert_id;
                        $obj->close();
                        if($id_obj != 0){
                            if(isset($_SESSION['id']))
                                echo "<p class='alert alert-info border-info text-center my-2'>Objednávku můžete sledovat <a class='text-decoration-none' href=\"./objednavka.php?obj=$id_obj\">zde</a> nebo pomocí kódu <b>$kod</b></p>";
                            else
                                echo "<p class='alert alert-info border-info text-center my-2'>Objednávku můžete sledovat pomocí kódu <b>$kod</b></p>";
                            $podm = false;
                            for ($i=1; $i < 5; $i++) { 
                                if(isset($_POST["jidlo$i"])){
                                    $jidlo = filter_input(INPUT_POST, "jidlo$i");
                                    $num = filter_input(INPUT_POST, "num$i");
                                    $sql_jidla_v_obj = "INSERT INTO objednana_jidla (objednavka, jidlo, pocet) VALUES (?, ?, ?)";
                                    if($jidla_v_obj = $db->prepare($sql_jidla_v_obj)){
                                        $jidla_v_obj->bind_param("iii", $id_obj, $jidlo, $num);
                                        $jidla_v_obj->execute();
                                        $jidla_v_obj->close();
                                    }
                                }
                                
                            }                                        
                        }else{
                            echo "<p class='alert alert-danger border-danger my-2 text-center'>Nastala chyba!</p>";
                        }
                    }
                }
            }
            if($podm){
                $sql = "SELECT nazev, adresa, mesto FROM jidelna WHERE id = $jidelna";
                if($jidelny = $db->prepare($sql)){;
                    $jidelny->execute();
                    $jidelny->bind_result($nazev, $adresa, $mesto);
                    if($jidelny->fetch()){
                        echo "<article class='card p-2 mb-2 border-dark bg-light shadow'>";
                        echo "<a class='text-decoration-none text-dark' href='./jidelnicek.php?jidelna=$jidelna'>";
                        echo "<b>$nazev</b>";
                        echo "<p>Města dovozu - ".getMestaDovozu($jidelna)."</p>";
                        echo "<p>Adresa - $mesto $adresa</p>";
                        echo "</a></article>";
                    }else{
                        echo "<p class='alert alert-danger border-danger text-center my-2'>Nepodařilo se načíst jídelníček jídelny s daným id!</p>";
                    }     
                    $jidelny->close();   
                }else{
                    echo "<p class='alert alert-danger border-danger text-center my-2'>Nepodařilo se načíst jídelníček</p>";
                }
                echo "<span>Objednání pro den <b>$den</b></span>";
                $celkem = 0;
                $jidla = array();
                for ($i=1; $i <= 4; $i++) {
                    $num = filter_input(INPUT_POST, "num$i");
                    if($num != 0){
                        $id = filter_input(INPUT_POST, "jidlo$i");
                        $sql_info_jidlo = "SELECT nazev, popis, typ, ob, cena FROM jidlo WHERE id = $id";
                        if($jidlo_info = $db->prepare($sql_info_jidlo)){
                            $jidlo_info->execute();
                            $jidlo_info->bind_result($nazev, $popis, $typ, $ob, $cena);
                            if($jidlo_info->fetch()){
                                 echo "<fieldset>";
                                    echo "<article class='card p-2 mb-2 border-dark bg-light shadow'>
                                    <div class='row no-gutters'>
                                    <div class='col-md-4'>";
                                    if(file_exists("../pic/$id/$ob"))
                                    	echo "<img src='../pic/$id/$ob' class='card-img' alt='jidlo' />";
                                    else
                                        echo "<img src='../pic/generic.png' class='card-img' alt='jidlo' />";
                                    echo "</div>
                                    <div class='col-md-8'>
                                    <div class='card-body'>
                                    ";
                                    echo "<div class='card-title'><h5>$nazev</h5>
                                    <div class='cart-text'><i>$typ</i></div></div>";
                                    echo "<div class='card-text'>$popis</div>";
                                    echo "<div class='card-text float-right'><strong>$cena ,-Kč</strong></div><br>";
                                   
                                    echo "</div></div></div></article>";
                                    echo "</fieldset>";
                                $celkem = $celkem + $cena * $num;
                                array_push($jidla, $id, $num);
                            }else{
                                echo "<p class='alert alert-danger border-danger text-center my-2'>Toto jídlo neexistuje!</p>";
                            }
                            $jidlo_info->close();
                        }
                    }
                }
                if($celkem > 0)
                    echo "<p class='alert alert-info text-center my-2 border-info'>Celkem <strong>$celkem</strong> Kč</p>";
                echo "<form method=\"post\" onsubmit='return checkMesto()' name='obj'>";
                echo "<input class='my-2 form-control' type='hidden' name='jidelna' value='$jidelna'>";
                echo "<input class='my-2 form-control' type='hidden' name='den' value='$den'>";
                echo "<input class='my-2 form-control' type='hidden' name='celkem' value='$celkem'>";
                for ($i=0; $i < count($jidla)/2; $i++) { 
                    echo "<input class='my-2 form-control' type='hidden' name='jidlo".($i+1)."' value='".$jidla[$i*2]."'>";
                    echo "<input class='my-2 form-control' type='hidden' name='num".($i+1)."' value='".$jidla[$i*2+1]."'>";
                }

                if(isset($_SESSION['id'])){
                    $sql_user = "SELECT email, mesto, adresa, telefon FROM user WHERE id = ".$_SESSION['id'];
                    if($user = $db->prepare($sql_user)){
                        $user->execute();
                        $user->bind_result($email, $mesto, $adresa, $telefon);
                        if($user->fetch()){
                            $user->close();
                            if(strpos(getMestaDovozu($jidelna), $mesto) !== false)
                                echo "<input class='my-2 form-control' type='text' name='adresa' value='$adresa' required placeholder='Adresa'>";
                            else
                                echo "<input class='my-2 form-control' type='text' name='adresa' required placeholder='Adresa'>";
                            echo "<select class='my-2 custom-select' name='mesto'>";
                            echo "<option disabled selected value=''>Vyberte mesto ...</option>";
                                $sql = "SELECT mesto FROM mesta_dovozu WHERE jidelna = $jidelna";
                                $mesta = $db->query($sql);
                                if($mesta->num_rows>0){
                                    while($row = $mesta->fetch_assoc()){
                                        if($row['mesto'] == $mesto)
                                            echo "<option value=\"".$row["mesto"]."\" selected>".$row["mesto"] . "</option>";
                                        else
                                            echo "<option value=\"".$row["mesto"]."\">".$row["mesto"] . "</option>";
                                    }
                                }
                                $mesta->close();
                            echo "</select>";
                        }
                    }
                }else{
                    if(isset($email_kon)){
                        echo "<input class='form-control my-2' type='text' name='email' required placeholder='email'>";
                        echo "<input class='form-control my-2' type='text' name='telefon' required placeholder='telefon' value='$telefon'>";
                        echo "<select class='my-2 custom-select' name='mesto'>";
                        echo "<option selected disabled value=''>Vyberte mesto ...</option>";
                            $sql = "SELECT mesto FROM mesta_dovozu WHERE jidelna = $jidelna";
                            $mesta = $db->query($sql);
                            if($mesta->num_rows>0){
                                while($row = $mesta->fetch_assoc()){
                                    if($row['mesto'] == $mestoobj)
                                        echo "<option value=\"".$row['mesto']."\" selected>".$row['mesto'] . "</option>";
                                    else 
                                        echo "<option value=\"".$row["mesto"]."\">".$row["mesto"] . "</option>";
                                }
                                echo "</option>";
                            }
                            $mesta->close();
                        echo "</select>";
                        echo "<input class='form-control my-2' type='text' name='adresa' required value='$adresaobj'>";
                    }else{
                        echo "<input class='form-control my-2' type='text' name='email' required placeholder='email'>";
                        echo "<input class='form-control my-2' type='text' name='telefon' required placeholder='telefon'>";
                        echo "<select class='my-2 custom-select' name='mesto'>";
                        echo "<option disabled selected value=''>Vyberte mesto ...</option>";
                            $sql = "SELECT mesto FROM mesta_dovozu WHERE jidelna = $jidelna";
                            $mesta = $db->query($sql);
                            if($mesta->num_rows>0){
                                while($row = $mesta->fetch_assoc()){
                                        echo "<option value=\"".$row["mesto"]."\">".$row["mesto"] . "</option>";
                                }
                            }
                            $mesta->close();
                        echo "</select>";
                        echo "<input class='form-control my-2' type='text' name='adresa' required>";
                    }
                }
                echo "<input class='btn btn-primary float-right my-3' type='submit' name='submitobj' value='Objednat'>";
                echo "</form>";
            }
            if(isset($email_kon))
                if($email_kon != "")
                    echo "<p class='alert alert-danger border-danger text-center my-2'>Tento email se již používá!</p>";
            $db->close();
?>
        <script>
            function checkMesto(){
                var mesto = document.forms["obj"]["mesto"].value;
                var telefon = Number(document.forms["obj"]["telefon"].value);
                if(mesto == ""){
                    alert("Musíte vybrat město");
                    return false;
                }
                if(telefon < 100000000 || telefon > 999999999){
                    alert("Špatný formát telefonu, korektní - 777586996");
                    return false;
                }
                return true;
            }
        </script>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="mt-4">
        <div class="bg-dark p-2 text-center text-white footer">
            Zer.to IIS Projekt | 2019 FIT VUT
        </div>
	</footer>
	
	<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
        
    <!-- FONT AWESOME -->
        <script src="https://kit.fontawesome.com/9e04c8ca52.js" crossorigin="anonymous"></script>

    </body>
</html>