<?php 
session_start();
include '../functions.php';
if(isset($_SESSION['jmeno']) && isset($_SESSION['prava']) && isset($_GET['jidelna'])){
    if($_SESSION['prava'] > 2){
        ?><script>
            window.location = "../index.php";
        </script><?php
    }
}else{
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
        <title>Provozovna | Jidelna IS</title>
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
                <h5 class="card-header">Správa provozovny</h5>
                    <div class="card-body">

        <?php 
        if($_SESSION['prava'] == 1)
            echo "<a class='mb-2 badge badge-light' href=\"../admin/jidelny.php\">Všechny jídelny</a><br>";
        else
            echo "<a class='mb-2 badge badge-light' href=\"./moje_jidelny.php\">Moje jídelny</a><br>";


            $db = dbconnect();
            if($jidelna_id = filter_input(INPUT_GET, "jidelna", FILTER_VALIDATE_INT)){
                $sql = "SELECT nazev, mesto, adresa, operator, stav FROM jidelna WHERE id = $jidelna_id";
                $user = $db->prepare($sql);
                $user->execute();
                $user->bind_result($nazev, $mesto, $adresa, $operator, $stav);
                $userdata = array();
                if($user->fetch()){
                    $userdata = array("nazev" => $nazev, "mesto" => $mesto, "adresa" => $adresa, "operator" => $operator, "stav" => $stav);
                    $user->close();
                }
                if($operator == $_SESSION['id'] || $_SESSION['prava'] == 1){
                    echo "
                    <form class='clearfix' method=\"post\" action=\"\" enctype=\"multipart/form-data\">
                    <div class='form-row'>
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='nazev'>Název</label>
                                </div>
                                <input class='form-control' id='nazev' type=\"text\" value=\"$nazev\" name=\"nazev\" required=\"required\">                        
                            </div>
                        </div>        
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='adresa'>Adresa</label>
                                </div>
                                <input class='form-control' id='adresa' type=\"text\" value=\"$adresa\" name=\"adresa\" required=\"required\">
                            </div>
                        </div>
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='mesto'>Mesto</label>
                                </div>
                                <select class='custom-select' id='mesto' name=\"mesto\">
                                    <option selected disabled value=''>Vyberte ...</option>";
                                
                                    $sql_mesta = "SELECT nazev FROM mesta";
                                    $mesta = $db->query($sql_mesta);
                                    if ($mesta->num_rows > 0) {
                                        while ($row = $mesta->fetch_assoc()) {
                                            if($row['nazev'] == $mesto)
                                                echo "<option value=\"".$row["nazev"]."\" selected>".$row["nazev"] . "</option>";
                                            else
                                                echo "<option value=\"".$row["nazev"]."\">".$row["nazev"] . "</option>";
                                        }
                                    }
                                    $mesta->close();
                        echo "</select>
                            </div>
                        </div>
                    </div>
		    ";
                            if($_SESSION['prava'] == 1){
                                echo "
                                <div class='form-row'>
				    <div class='col-md-4'>
					<div class='input-group'>
					    <div class='input-group-prepend'>
						<label class='input-group-text' id='prava'>Operátor</label>
					    </div>
                                <select class='custom-select' id='prava' name=\"operator\">";
                                echo "<option selected disabled value=''>Vyberte ...</option>";
                                $sql_op = "SELECT id, jmeno, prijmeni FROM user WHERE prava = 2";
                                $operators = $db->query($sql_op);
                                if ($operators->num_rows > 0) {
                                    while ($row = $operators->fetch_assoc()) {
                                        if($row['id'] == $operator)
                                            echo "<option value=\"".$row["id"]."\" selected>".$row["jmeno"]." ".$row['prijmeni'] . "</option>";
                                        else
                                            echo "<option value=\"".$row["id"]."\">".$row["jmeno"]." ".$row['prijmeni'] . "</option>";                
                                    }
                                }
                                $operators->close();
                                echo "</select>
                                </div>
                            </div>
                           
                                ";
                            }else{
                                
                                echo "<input class='form-control' type=\"text\" value=\"".getOpName($operator)."\" readonly>";
                                echo "</div></div>";
                            }
                            echo "
                            <div class='col col-md-4'>
                                <div class='input-group'>
                                    <div class='input-group-prepend'>
                                        <label class='input-group-text' id='pripravena'>Připravena</label>
                                    </div>
                                    <select class='custom-select' id='pripravena' name=\"stav\">";
                            if($stav == 0){
                                echo "<option value=\"0\" selected>Ne</option>";
                                echo "<option value=\"1\">Ano</option>";
                            }else{
                                echo "<option value=\"0\">Ne</option>";
                                echo "<option value=\"1\" selected>Ano</option>";
                            }
                            echo "</select>
                                </div>
                            </div>
                        </div>
                            ";

                        echo "<input class='btn btn-primary float-right' type=\"submit\" name=\"submit\" value=\"Upravit jídelnu\">";    
                        echo "
                    </form>";

                    echo "<hr>";

                    $mesta_dovozu = getMestaDovozu($jidelna_id);
                    
                    echo "<form class='mt-3 form-inline' method=\"post\" action=\"\" enctype=\"multipart/form-data\">";
                  
                    echo "<label id='mst'>Přidání města k městů dovozu</label>";
                    echo "<select id='mst' class='mx-2 custom-select' name=\"mesta\">";
                    echo "<option disabled value='' selected>Vyberte ...</option>";
                        $sql_mesta = "SELECT nazev FROM mesta";
                        $mesta = $db->query($sql_mesta);
                        if ($mesta->num_rows > 0) {
                            while ($row = $mesta->fetch_assoc()) {
                                if(!(strpos($mesta_dovozu, $row['nazev']) !== false))
                                    echo "<option value=\"".$row["nazev"]."\">".$row["nazev"] . "</option>";
                            }
                        }
                        $mesta->close();
                    echo "</select>";    
                    echo "<input class='btn btn-primary float-right' type=\"submit\" name=\"submit_mesto\" value=\"Přidat město\">";
               
                    echo "</form>";
                    echo "<p>Města dovozu : $mesta_dovozu </p>";                
                }else{
                    echo "<p class='alert alert-danger border-danger text-center my-2'>Jídelna s tímto id neexistuje!</p>";
                }

            }else{
                ?><script>
                    window.location = "../index.php";
                </script><?php 
            }
            if(isset($_POST['submit'])){
                $nazev = filter_input(INPUT_POST, "nazev", FILTER_SANITIZE_STRING);
                $mesto = filter_input(INPUT_POST, "mesto", FILTER_SANITIZE_STRING);
                $adresa = filter_input(INPUT_POST, "adresa", FILTER_SANITIZE_STRING);
                $stav = filter_input(INPUT_POST, "stav");
                if($_SESSION['prava'] == 1)
                    $operator = filter_input(INPUT_POST, "operator");
                if($stav == 1 || $stav == 0){
                    $sql_update = "UPDATE jidelna SET nazev = ?, mesto = ?, adresa = ?, operator = ?, stav = ? WHERE id = $jidelna_id";
                    if($updt = $db->prepare($sql_update)){
                        $updt->bind_param("sssii", $nazev, $mesto, $adresa, $operator, $stav);
                        $updt->execute();
                        $updt->close();
                        ?>
                        <script>
                            window.location = "./jidelna.php?jidelna=<?php echo $jidelna_id; ?>&message=success";
                        </script>
                        <?php
                    }
                }
            }
            
            if(isset($_POST['submit_mesto'])){
                $mesta = filter_input(INPUT_POST, "mesta", FILTER_SANITIZE_STRING);  
                $sql = "INSERT INTO mesta_dovozu (mesto, jidelna) VALUES (?, ?)";
                if($insrt = $db->prepare($sql)){
                    $insrt->bind_param("si", $mesta, $jidelna_id);
                    $insrt->execute();
                    $insrt->close();
                    ?>
                    <script>
                        window.location = "./jidelna.php?jidelna=<?php echo $jidelna_id; ?>&message=mesto_success";
                    </script>
                    <?php
                }
            }
            $db->close();
        ?>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <?php
                if(isset($_GET['message'])){
                    $message = filter_input(INPUT_GET, "message", FILTER_SANITIZE_STRING);
                    if($message == "success")
                        echo "<p class='alert alert-success border-success text-center my-2'>Upravení jídelny bylo úspěšné!</p>";
                    if($message == "mesto_success")
                        echo "<p class='alert alert-success border-success text-center my-2'>Město dovozu bylo úspěšně vloženo!</p>";
                }
            ?>
        </section>
    </main>
    

    <footer class="mt-4 bg-info">
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
