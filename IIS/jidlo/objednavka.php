<?php 
session_start();
include '../functions.php';
if(!isset($_SESSION['id']) && isset($_GET['obj'])){
    ?><script>
        window.location = "../index.php";
    </script><?php
}
if(isset($_POST['subridic'])){
    $db = dbconnect();
    $ridic = filter_input(INPUT_POST, "ridic", FILTER_SANITIZE_NUMBER_INT);
    $obj = filter_input(INPUT_POST, "obj", FILTER_SANITIZE_NUMBER_INT);
    $sql_updt = "UPDATE objednavka SET ridic = ?, stav = 'Potvrzeno' WHERE id = $obj";
    var_dump($sql_updt);
    if($updt = $db->prepare($sql_updt)){
        $updt->bind_param("i", $ridic);
        $updt->execute();
        $updt->close();
    }
    $db->close();
    ?><script>
        window.location = "../index.php";
    </script><?php
}
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
        <title>Objednávka | Jidelna IS</title>
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
            <div class="col col-md-10">
                <div class="card shadow-lg border-dark">
                <h5 class="card-header">Objednávka</h5>
                    <div class="card-body">
                <?php 
                    if(isset($_GET['obj'])){
                        if(filter_input(INPUT_GET, "obj", FILTER_VALIDATE_INT))
                            $obj = filter_input(INPUT_GET, "obj", FILTER_SANITIZE_NUMBER_INT);
                        else
                            echo "<p class='alert alert-danger border-danger text-center my-2'>Špatné číslo objednávky!</p>";
                    }else if(isset($_GET['kod'])){
                        if(filter_input(INPUT_GET, "kod", FILTER_VALIDATE_INT))
                            $kod = filter_input(INPUT_GET, "kod", FILTER_SANITIZE_NUMBER_INT);
                        else
                            echo "<p class='alert alert-danger border-danger text-center my-2'>Špatný kód objednávky!</p>";
                    }else{
                        echo "<form class='form-inline' method='post' name='fkod' onsubmit=\"return checkKod()\">";
                        echo "<div class='mx-auto'>";
                        echo "<input type='text' class='form-control ' placeholder='Zadajte objednávku' name='kod' required />";
                        
                        echo "<input type='submit' class='btn btn-primary ml-2' name='submitkod' value='Hledej'>";
                        echo "</div>";
                        echo "</form>";
                    }
                        
                    if(isset($_POST['submitkod'])){
                        ?><script>
                            window.location = "<?php echo "./objednavka.php?kod=".$_POST['kod'];?>";
                        </script><?php
                    }

                    if(isset($obj)){
                        $db = dbconnect();            
                        $userId = $_SESSION['id'];
                        $sql_kontrola = "SELECT id FROM objednavka WHERE id = $obj AND user = $userId";
                        $kontrola = $db->query($sql_kontrola);
                        if($kontrola->num_rows>0 || $_SESSION['prava'] <= 3){
                            $kontrola->close();
                            $db->close();             
                            loadObj($obj, 0);
                        }else{
                            echo "<p class='alert alert-danger border-danger text-center my-2'>Nemáte právo zobrazovat tuto objednávku!</p>";
                        }
                    }else if(isset($kod)){
                        loadObj(0, $kod);
                    }

                    function loadObj($obj, $kod){
                        $db = dbconnect();
                        if($kod == 0)
                            $sql_obj = "SELECT id, user, stav, ridic, cena, cas_objednani, den_dodani, mesto, adresa, jidelna FROM objednavka WHERE id = $obj";
                        else
                            $sql_obj = "SELECT id, user, stav, ridic, cena, cas_objednani, den_dodani, mesto, adresa, jidelna FROM objednavka WHERE kod = $kod";
                        if($obje = $db->prepare($sql_obj)){
                            $obje->execute();
                            $obje->bind_result($obj, $user, $stav, $ridic, $cena, $cas_objednani, $den_dodani, $mestoobj, $adresaobj, $jidelna);
                            if($obje->fetch()){
                                $obje->close();
                                $sql = "SELECT nazev, adresa, mesto FROM jidelna WHERE id = $jidelna AND stav = 1";
                                if($jidelny = $db->prepare($sql)){
                                    $jidelny->execute();
                                    $jidelny->bind_result($nazev, $adresa, $mesto);
                                    if($jidelny->fetch()){
                                        echo "<article class='card p-4 mb-2 border-dark bg-light shadow-lg'>";
                                        echo "<a class='text-decoration-none text-dark' href='./jidelnicek.php?jidelna=$jidelna'>";
                                        echo "<b>$nazev</b>";
                                        echo "<p>Města dovozu - ".getMestaDovozu($jidelna)."</p>";
                                        echo "<p>Adresa - $mesto $adresa</p>";
                                        echo "</a></article>";
                                    }else{
                                        echo "<p class='alert alert-danger border-danger text-center my-2'>Nepodařilo se načíst jídelnu!</p>";
                                    }     
                                    $jidelny->close();   
                                }else{
                                    echo "<p class='alert alert-danger border-danger text-center my-2'>Nepodařilo se načíst jídelnu!</p>";
                                }
                                echo "<p>Objednaná jídla : celkem za <strong>$cena</strong> Kč</p>";
                                $sql_obj_jidl = "SELECT jidlo, pocet FROM objednana_jidla WHERE objednavka = $obj";
                                $obj_jidla = $db->query($sql_obj_jidl);
                                if($obj_jidla->num_rows>0){
                                    echo "<ol>";
                                    while($row = $obj_jidla->fetch_assoc()){
                                        $sql_jidlo_info = "SELECT nazev FROM jidlo WHERE id = ".$row['jidlo'];
                                        $jidlo_info = $db->prepare($sql_jidlo_info);
                                        $jidlo_info->execute();
                                        $jidlo_info->bind_result($nazev);
                                        if($jidlo_info->fetch()){
                                            echo "<li>$nazev -- ".$row['pocet']." Ks</li>";
                                        }
                                        $jidlo_info->close();
                                    }
                                    echo "</ol>";
                                }
                                $obj_jidla->close();
                                echo "<table class='table table-responsive table-hover mt-4'>
                                <thead>
                                    <tr>
                                        <th scope='col'>Stav</th>
                                        <th scope='col'>Rozvoz</th>
                                        <th scope='col'>Čas objednání</th>
                                        <th scope='col'>Den dodání</th>
                                        <th scope='col'>Adresa</th>
                                    </tr>
                                </thead><tr>
                                <tbody>";
                                echo "<td>$stav</td>";
                                $sql_ridic = "SELECT jmeno, prijmeni FROM user WHERE id = $ridic";
                                if($ridic = $db->prepare($sql_ridic)){
                                    $ridic->execute();
                                    $ridic->bind_result($jmeno, $prijmeni);
                                    if($ridic->fetch()){
                                        echo "<td>$jmeno $prijmeni</td>";
                                    }
                                }else
					echo "<td>-</td>";
                                echo "<td>$cas_objednani</td>";
                                echo "<td>" . dateDTH($den_dodani)."</td>";
                                echo "<td>$mestoobj $adresaobj</td>";
                                echo" </tr></tbody></table>
                                
                                
                                ";
                            }else{
                                if($kod == 0){
                                    echo "<p class='alert alert-danger border-danger text-center my-2'>Objednávka s tímto číslem neexistuje!</p>";
                                    ?><script>
                                        var home = setTimeout(Home, 3000, "..account/moje_objednavky.php", home);
                                    </script><?php
                                }else{
                                    echo "<p class='alert alert-danger border-danger text-center my-2'>Objednávka s tímto kódem neexistuje!</p>";
                                    ?><script>
                                        var home = setTimeout(Home, 3000, "./objednavka.php", home);
                                    </script><?php   
                                }                 
                            }
                        }
                        $db->close();
                    }
		        if(isset($_SESSION['prava']))
                    if($_SESSION['prava'] <= 2 && isset($_GET['obj'])){
                        $db = dbconnect();
                        $sql = "SELECT id FROM user WHERE prava = 3";                
                        echo "<form class='form-inline mt-3' method='post' name='fridic' action='./objednavka.php' onsubmit='return checkRidic()'>";
                        echo "<input class='form-control' type='hidden' name='obj' value='$obj'>";
                        echo "<div class='input-group'><div class='input-group-prepend'><label class='input-group-text' id='ridic'>Řidič</label></div>";
                        echo "<select class='custom-select' id='ridic' name='ridic'><option disabled selected value=''>Vyberte ...</option>";
                        if($ridici = $db->query($sql)){
                            if($ridici->num_rows > 0){
                                $ridic = 0;
                                while($row = $ridici->fetch_assoc()){
                                    $ridic = $row['id'];
                                    $sql2 = "SELECT mesto FROM objednavka WHERE stav = 'Potvrzeno' AND ridic = $ridic GROUP BY mesto";
                                    if($mesta = $db->query($sql2)){
                                        if($mesta->num_rows > 0){
                                            $first = true;
                                            while($grp = $mesta->fetch_assoc()){
                                                if($first){
                                                    $first = false;
                                                    $sql_ridic = "SELECT jmeno, prijmeni FROM user WHERE id = $ridic";
                                                    if($ridicdb = $db->prepare($sql_ridic)){
                                                        $ridicdb->execute();
                                                        $ridicdb->bind_result($jmeno, $prijmeni);
                                                        if($ridicdb->fetch()){
                                                            echo "<option value='$ridic'>$jmeno $prijmeni - ".$grp['mesto'] . "</option>";
                                                        }
                                                        $ridicdb->close();
                                                    }
                                                }else{
                                                    echo ", ".$grp['mesto'];
                                                }
                                            }
                                        }else{
                                            $sql_ridic = "SELECT jmeno, prijmeni FROM user WHERE id = $ridic";
                                            if($ridicdb = $db->prepare($sql_ridic)){
                                                $ridicdb->execute();
                                                $ridicdb->bind_result($jmeno, $prijmeni);
                                                if($ridicdb->fetch()){
                                                    echo "<option value='$ridic'>$jmeno $prijmeni" . "</option>";
                                                }
                                                $ridicdb->close();
                                            }
                                        }
                                        $mesta->close();
                                    }
                                    
                                }
                            }
                            $ridici->close();
                        }
                        echo "</select></div>";
                        echo "<input class='btn btn-primary float-right ml-2' type='submit' name='subridic' value='Určit řidiče'>";
                        echo "</form>";
                    }
                
                    ?>
                    <script>
                        function checkKod(){
                            var kod = Number(document.forms['fkod']['kod'].value);
                            if(Number.isInteger(kod)){
                                if(kod < 100000000 || kod > 999999999){
                                    alert("Špatný formát kódu");
                                    return false;
                                }
                                return true;
                            }
                            alert("Musíte zadat číslo");
                            return false;
                        }

                        function checkRidic(){
                            var ridic = document.forms['fridic']['ridic'].value;
                            if(ridic == ""){
                                alert("Musíte vybrat řidiče");
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
