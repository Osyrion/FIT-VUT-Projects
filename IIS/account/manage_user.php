<?php 
session_start();
include '../functions.php';
if(isset($_SESSION['jmeno']) && isset($_SESSION['prava']) && isset($_GET['user'])){
    if(!($_SESSION['prava'] == 1 || $_SESSION['id'] == $_GET['user'])){
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
        <title>Užívatelia | Jidelna IS</title>
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
                    <h5 class="card-header">Správa účtu</h5>
                    <div class="card-body">
                    
        <?php 
            if($_SESSION['prava'] == 1)
                echo "<a class='mb-2 badge badge-light' href=\"../admin/accounts.php\">Všechny uživatelé</a><br>";
            $db = dbconnect();
            $user_id = filter_input(INPUT_GET, "user", FILTER_SANITIZE_NUMBER_INT);           
            
            
            if(isset($_POST['submit'])){
                $jmeno = filter_input(INPUT_POST, "jmeno", FILTER_SANITIZE_STRING);
                $prijmeni = filter_input(INPUT_POST, "prijmeni", FILTER_SANITIZE_STRING);
                $mesto = filter_input(INPUT_POST, "mesto", FILTER_SANITIZE_STRING);
                $adresa = filter_input(INPUT_POST, "adresa", FILTER_SANITIZE_STRING);
                $telefon = filter_input(INPUT_POST, "telefon", FILTER_SANITIZE_STRING);
                if($_SESSION['prava'] == 1)
                    $prava = filter_input(INPUT_POST, "prava", FILTER_SANITIZE_STRING);
                if($email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
                    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
                    if($mesto == "")
                        $mesto = NULL;
                    if($adresa == "")
                        $adresa = NULL;
                    if($telefon == "")
                        $telefon = NULL;
                    if($_SESSION['prava'] == 1)
                        $sql_update = "UPDATE user SET jmeno = ?, prijmeni = ?, email = ?, mesto = ?, adresa = ?, telefon = ?, prava = ? WHERE id = $user_id";
                    else
                        $sql_update = "UPDATE user SET jmeno = ?, prijmeni = ?, email = ?, mesto = ?, adresa = ?, telefon = ? WHERE id = $user_id";
                    if($updt = $db->prepare($sql_update)){
                        if($_SESSION['prava'] == 1)
                            $updt->bind_param("sssssii", $jmeno, $prijmeni, $email, $mesto, $adresa, $telefon, $prava);
                        else
                            $updt->bind_param("sssssi", $jmeno, $prijmeni, $email, $mesto, $adresa, $telefon);
                        $updt->execute();
                        $updt->close();
                        if($_SESSION['prava'] == 1){
                            echo "<p class='alert alert-success border-success text-center my-2'>Úprava uživatele byla úspěšná!</p>";
                            ?>
                            <script>
                                var refresh = setTimeout(Home, 3000, "../admin/accounts.php", refresh);
                            </script>
                            <?php
                        }else{
                           ?>
                        <script>
                            window.location = "./user.php?user=<?php echo $user_id; ?>&message=success";                        
                        </script>
                        <?php
                        }
                    }

                }else{
                    echo "<p class='my-2 alert alert-danger text-center border-danger'>Špatný formát emailu!</p>";
                }
            }
            if(isset($_POST['subdel'])){
                $sql = "DELETE FROM user WHERE id = $user_id";
                if($try_del = $db->prepare($sql)){
                    $try_del->execute();
                    if($try_del->affected_rows > 0){
                        ?><script>
                        var refresh = setTimeout(Home, 3000, "../admin/accounts.php", refresh); /*../account/login.php?action=odhlasit*/ 
                        </script><?php
                    }
                    $try_del->close();
                }
                $sql = "UPDATE user SET jmeno = null, prijmeni = null, password = null, mesto = null, adresa = null, prava = 5 WHERE id = $user_id";
                if($set_pleb = $db->prepare($sql)){
                    $set_pleb->execute();
                    if($set_pleb->affected_rows > 0){
                        ?><script>
                        var refresh = setTimeout(Home, 3000, "../admin/accounts.php", refresh);
                        </script><?php
                    }
                    $set_pleb->close();
                }
            }
            
            $sql = "SELECT jmeno, prijmeni, email, mesto, adresa, telefon, prava FROM user WHERE id = $user_id";
            $user = $db->prepare($sql);
            $user->execute();
            $user->bind_result($jmeno, $prijmeni, $email, $mesto, $adresa, $telefon, $prava);
            if($user->fetch()){
                $user->close();
            }
            if($jmeno != NULL){
                echo"
                <form class='clearfix' method=\"post\" action=\"\" enctype=\"multipart/form-data\" name=\"userform\" onsubmit=\"return checkInput()\">
                    <div class='form-row'>
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='jmeno'>Jméno</label>
                                </div>
                                <input class='form-control' id='jmeno' type=\"text\" value=\"$jmeno\" name=\"jmeno\" required=\"required\">
                            </div>
                        </div>        
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='prijmeni'>Příjmení</label>
                                </div>
                                <input class='form-control' id='prijmeni' type=\"text\" value=\"$prijmeni\" name=\"prijmeni\" required=\"required\">
                            </div>
                        </div>
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='email'>Email</label>
                                </div>
                                <input class='form-control' id='email' type=\"text\" value=\"$email\" name=\"email\" required=\"required\">
                            </div>
                        </div>
                    </div>

                    <div class='form-row'>
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
                        echo "
                                </select>
                            </div>
                        </div>        
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='adresa'>Adresa</label>
                                </div>
                                <input class='form-control' id='adresa' placeholder='Ulica a číslo' type=\"text\" value=\"$adresa\" name=\"adresa\">
                            </div>
                        </div>
                        <div class='col-md-4 mb-3'>
                            <div class='input-group'>
                                <div class='input-group-prepend'>
                                    <label class='input-group-text' id='telefon'>Telefón</label>
                                </div>
                                <input class='form-control' id='telefon' type=\"text\" value=\"$telefon\" name=\"telefon\" placeholder='Formát - 666555444'>
                            </div>
                        </div>
                    </div> ";
                            if($_SESSION['prava'] == 1)
                            echo "<div class='form-row'>
					<div class='col col-md-2 mb-3'>
					    <div class='input-group'>
						<div class='input-group-prepend'>
						    <label class='input-group-text' id='prava'>Práva</label>
						</div><input class='form-control' id='prava' type=\"number\" min='1' max='4' value=\"$prava\" name=\"prava\" required=\"required\">
					    </div>
					</div>
				    </div>";
                    

                   echo "<input class='btn btn-primary float-right' type=\"submit\" name=\"submit\" value=\"Upravit uživatele\">
                </form>
                

                <form class='' onsubmit='return confirm(\"Opravdu chcete smazat účet?\")' action='manage_user.php?user=$user_id' method='post'>
                    <input class='btn btn-danger float-right' type=\"submit\" name=\"subdel\" value=\"Odstranit účet\">
                </form>";

            }else{
                echo "<p class='alert alert-danger text-center border-danger my-2'>Uživatel s tímto id neexistuje!</p>";
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
                echo "<p class='alert alert-success text-center border-success my-2'>Upravení uživatele bylo úspěšné!</p>";
            }
        ?>
        <script>
            function checkInput(){
                var tel = document.forms["userform"]["telefon"].value;
                if(tel == "")
                    return true;
                var telefon = Number(document.forms["userform"]["telefon"].value);
                if(telefon < 100000000 || telefon > 999999999){
                    alert("<p class='alert alert-danger text-center border-danger my-2'>Špatný formát telefonu, korektní - 777586996</p>");
                    return false;
                }
                return true;
            }
        </script>
    </section>    
    </main>
    

    <footer>
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
