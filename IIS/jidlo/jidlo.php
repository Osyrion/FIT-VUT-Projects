<?php 
session_start();
include '../functions.php';
if(isset($_POST['jidelna']))
    if(filter_input(INPUT_POST, "jidelna", FILTER_VALIDATE_INT))
        $jidelna = filter_input(INPUT_POST, "jidelna");
    else{
        ?><script>
            window.location = "../index.php";
        </script><?php
    }
else{
    ?><script>
        window.location = "../index.php";
    </script><?php
}
if(isset($_POST['den']))
    $den = filter_input(INPUT_POST, "den", FILTER_SANITIZE_STRING);
else
    $den = date('Y-m-d');
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
        <title>Jídlo | Jidelna IS</title>
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
                <h5 class="card-header">Jídlo</h5>
                    <div class="card-body">
        <?php 
            $db = dbconnect();
            $sql = "SELECT nazev, adresa, mesto FROM jidelna WHERE id = $jidelna AND stav = 1";
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
                echo "<p class='alert alert-danger text-center border-danger my-2'>Nepodařilo se načíst jídelníček!</p>";
            }
            $sql = "SELECT id FROM nabidka WHERE jidelna = $jidelna AND den = '$den' AND stav LIKE 'Otevřeno'";
            if($jidelny = $db->prepare($sql)){
                $jidelny->execute();
                $jidelny->bind_result($id);
                if($jidelny->fetch()){
                    $jidelny->close();                       
                    $sql_jidla_v_nabidce = "SELECT jidlo FROM jidla_v_nabidce WHERE nabidka = $id";
                    $jidla_v_nabidce = $db->query($sql_jidla_v_nabidce);
                    if($jidla_v_nabidce->num_rows > 0){
                        $num = 1;
                        echo "<form action=\"objednat.php\" method=\"post\" onsubmit=\"return checkNum()\" name=\"jidlo\">";
                        echo "<input type='hidden' name='jidelna' value='$jidelna'>";
                        echo "<input type='hidden' name='den' value='$den'>";
                        
                        while($row = $jidla_v_nabidce->fetch_assoc()){
                            $sql_info_jidlo = "SELECT nazev, popis, typ, ob, cena FROM jidlo WHERE id = ".$row['jidlo'];
                            if($jidlo_info = $db->prepare($sql_info_jidlo)){
                                $jidlo_info->execute();
                                $jidlo_info->bind_result($nazev, $popis, $typ, $ob, $cena);
                                if($jidlo_info->fetch()){
                                    echo "<fieldset>";
                                    echo "<article class='card p-2 mb-2 border-dark bg-light shadow'>
                                    <div class='row no-gutters'>
                                    <div class='col-md-4'>";
                                    if(file_exists("../pic/".$row['jidlo']."/$ob"))
                                    	echo "<img src='../pic/".$row['jidlo']."/$ob' class='card-img' alt='jidlo' />";
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
                                    echo "<div class='form-inline'>";
                                    echo "<input class='form-control' type=\"hidden\" name=\"jidlo$num\" value=\"".$row['jidlo']."\">";
                                    echo "<input class='form-control mr-2 mt-2' type=\"number\" name=\"num$num\" min=\"0\" max=\"4\" value=\"0\">0-4ks";
                                    echo "</div>";
                                    echo "</div></div></div></article>";
                                    echo "</fieldset>";
                                    $num++;
                                }else{
                                    echo "<p class='alert alert-danger border-danger text-center my-2'>Toto jídlo neexistuje</p>";
                                }
                                $jidlo_info->close();
                            }
                        }
                        
                        echo "<input class='btn btn-primary float-right' type=\"submit\" name=\"submit\" value=\"Objednat\">";
                        echo "</form>";
                    }else{
                        echo "<p class='alert alert-danger border-danger text-center my-2'>V nabídce nejsou žádná jídla!</p>";
                    }
                    $jidla_v_nabidce->close();
                }else{
                    echo "<p class='alert alert-danger border-danger text-center my-2'>V tento den nelze objednávat jídlo!</p>";
                }     
            }else{
                echo "<p class='alert alert-danger border-danger text-center my-2'>Nepodařilo se načíst jídelníček!</p>";
                echo "<p id=\"asd\"></p>";
            }
            $db->close();
        ?>
        <script>
            function checkNum(){
                var n1 = document.forms["jidlo"]["num1"].value;
                var n2 = document.forms["jidlo"]["num2"].value;
                var n3 = document.forms["jidlo"]["num3"].value;
                var n4 = document.forms["jidlo"]["num4"].value;
                if(n1 == 0 && n2 == 0 && n3 == 0 && n4 == 0){
                    alert("Musí být vybráno více než 0 kusů!");
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
