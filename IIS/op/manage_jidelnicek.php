<?php 
session_start();
include '../functions.php';
if(isset($_GET['jidelna']))
    if(filter_input(INPUT_GET, "jidelna", FILTER_VALIDATE_INT))
        $jidelna = filter_input(INPUT_GET, "jidelna");
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
if(isset($_GET['den']))
    $den = filter_input(INPUT_GET, "den", FILTER_SANITIZE_STRING);
else
    $den = date('Y-m-d');
    
if(isset($_POST['submitjidlo'])){
    $db = dbconnect();
    $sql_sel = "SELECT id FROM nabidka WHERE jidelna = $jidelna AND den = '$den'";
    if($sel = $db->prepare($sql_sel)){
        $sel->execute();
        $sel->bind_result($id);
        $sel->fetch();
        $sel->close();
    }
    $jidla = array(filter_input(INPUT_POST, "jidlo1"), filter_input(INPUT_POST, "jidlo2"), filter_input(INPUT_POST, "jidlo3"), filter_input(INPUT_POST, "jidlo4"));
    $sql = "INSERT INTO jidla_v_nabidce (nabidka, jidlo) VALUES (?, ?)";
    $sql_del = "DELETE FROM jidla_v_nabidce WHERE nabidka = $id";
    if($del = $db->prepare($sql_del)){
        $del->execute();
        $del->close();
        for ($i=0; $i < 4 ; $i++) {
            if($insrt_jidlo = $db->prepare($sql)){
                $insrt_jidlo->bind_param("ii", $id, $jidla[$i]);
                $insrt_jidlo->execute();
                $insrt_jidlo->close();
            }
        }
    }
    $db->close();
    ?><script>
        window.location = "<?php echo "./manage_jidelnicek.php?jidelna=$jidelna&den=$den&message=succ"?>";
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
        <title>Upraviť jidelnicek | Jidelna IS</title>
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
                <h5 class="card-header">Upraviť jídelníček</h5>
                    <div class="card-body">



        <?php 
            $db = dbconnect();
            $sql = "SELECT nazev, adresa, id FROM jidelna WHERE id = $jidelna AND stav = 1";
            if($jidelny = $db->prepare($sql)){;
                $jidelny->execute();
                $jidelny->bind_result($nazev, $adresa, $id);
                if($jidelny->fetch()){
                    echo "<article class='card p-4 mb-2 border-dark bg-light shadow-lg'>";
                    echo "<a class='text-decoration-none text-dark' href='../jidlo/jidelnicek.php?jidelna=$jidelna'>";
                    echo "<b>$nazev</b>";
                    echo "<p>Města dovozu - ".getMestaDovozu($jidelna)."</p>";
                    echo "<p>Adresa - $id $adresa</p>";
                    echo "</a></article>";
                }else{
                    echo "<p class='alert alert-danger border-danger text-center my-2'>Nepodařilo se načíst jídelníček jídelny s daným id!</p>";
                }     
                $jidelny->close();   
            }else{
                echo "<p class='alert alert-danger border-danger text-center my-2'>Nepodařilo se načíst jídelníček!</p>";
            }
        ?>
            <form method="get" action="manage_jidelnicek.php" class="form-inline">
                <a class="badge badge-light mr-2" href="./manage_jidelnicek.php?jidelna=<?php echo $jidelna;?>&den=<?php echo date('Y-m-d', strtotime($den . ' -1 day'));?>">Předchozí den</a>
                <input type="hidden" name="jidelna" value="<?php echo $jidelna;?>" >
                <input class="form-control" type="date" name="den"  value="<?php echo $den;?>">
                <a class="badge badge-light ml-2" href="./manage_jidelnicek.php?jidelna=<?php echo $jidelna;?>&den=<?php echo date('Y-m-d', strtotime($den . ' +1 day'));?>">Následující den</a>
                <input type="submit" value="Změnit den" class="btn btn-primary ml-4" />
            </form>        
        
        <?php
            $dnes = date('Y-m-d', strtotime("+1 day"));
            if($dnes >= $den){
                echo "<p class='alert alert-danger border-danger text-center my-2'>Na tento den již nelze upravovat jídelníček!</p>";
            }else{
                echo "<form class='clearfix' name='jid' method='post' action='' onsubmit='return checkInputs()'>";
                echo "<span>Vložte 4 rozdílne jídla</span>";
                $sql = "SELECT id FROM nabidka WHERE jidelna = $jidelna AND den = '$den'";
                if($jidelny = $db->prepare($sql)){
                    $jidelny->execute();
                    $jidelny->bind_result($id);
                    if($jidelny->fetch()){
                        $jidelny->close();                       
                        $sql_jidla_v_nabidce = "SELECT jidlo FROM jidla_v_nabidce WHERE nabidka = $id";
                        $jidla_v_nabidce = $db->query($sql_jidla_v_nabidce);
                        $i = 1;
                        if($jidla_v_nabidce->num_rows > 0){
                            while($row = $jidla_v_nabidce->fetch_assoc()){
                                $sql_info_jidlo = "SELECT nazev FROM jidlo WHERE id = ".$row['jidlo'];
                                if($jidlo_info = $db->prepare($sql_info_jidlo)){
                                    $jidlo_info->execute();
                                    $jidlo_info->bind_result($nazev);
                                    if(!$jidlo_info->fetch())
                                        echo "<p class='alert alert-danger border-danger text-center my-2'>Toto jídlo neexistuje!</p>";
                                    $jidlo_info->close();
                                   
                                    echo "<select class='my-2 custom-select' name='jidlo$i'>";
                                    echo "<option selected disabled value=''>Vyberte ...</option>";
                                        $sql = "SELECT id, nazev FROM jidlo WHERE typ LIKE 'hlavni'";
                                        if($i == 4)
                                            $sql = "SELECT id, nazev FROM jidlo WHERE typ LIKE 'polevka'";
                                        $jidla = $db->query($sql);
                                        if($jidla->num_rows>0){
                                            while($row = $jidla->fetch_assoc()){
                                                if($row['nazev'] == $nazev)
                                                    echo "<option value=\"".$row["id"]."\" selected>".$row["nazev"] . "</option>";
                                                else
                                                    echo "<option value=\"".$row["id"]."\">".$row["nazev"] . "</option>";
                                            }
                                        }
                                        $jidla->close();
                                    echo "</select>";
                                    $i++;                                    
                                }
                            }
                        }else{
                            for ($i=1; $i <= 4; $i++) { 
                               
                                echo "<select class='custom-select my-2' name='jidlo$i'>";
                                echo "<option selected disabled value=''>Vyberte ...</option>";
                                $sql = "SELECT id, nazev FROM jidlo WHERE typ LIKE 'hlavni'";
                                if($i == 4)
                                $sql = "SELECT id, nazev FROM jidlo WHERE typ LIKE 'polevka'";
                                $jidla = $db->query($sql);
                                if($jidla->num_rows>0){
                                    while($row = $jidla->fetch_assoc()){
                                            echo "<option value=\"".$row["id"]."\">".$row["nazev"] . "</option>";
                                    }
                                }
                                $jidla->close();
                                echo "</select>";
                            }
                        }
                        $jidla_v_nabidce->close();
                    }else{
                        $sql = "INSERT INTO nabidka (jidelna, den, stav) VALUES (?, ?, 'Otevřeno')";
                        if($new_nabidka = $db->prepare($sql)){
                            $new_nabidka->bind_param("is", $jidelna, $den);
                            $new_nabidka->execute();
                            $new_nabidka->close();
                            ?><script>
                                window.location = "./manage_jidelnicek.php?jidelna=<?php echo $jidelna; ?>&den=<?php echo $den; ?>";
                            </script><?php
                        }
                    }   
                }
                echo "<input class='btn btn-primary float-right' type='submit' name='submitjidlo' value='Vložit jídla'>";
                echo "</form>"; 
            }


            $db->close();
        ?>
        <script>
            function checkInputs(){
                var i1 = document.forms['jid']['jidlo1'].value;
                var i2 = document.forms['jid']['jidlo2'].value;
                var i3 = document.forms['jid']['jidlo3'].value;
                var i4 = document.forms['jid']['jidlo4'].value;
                if(i1 == "" || i2 == "" || i3 == "" || i4 == ""){
                    alert("Musíš vybrat všechny 4 jídla");
                    return false;
                }
                if(i1 == i2 || i1 == i3 || i2 == i3){
                    alert("Každé jídlo musí být jiné");
                    return false;
                }
                return true;
            }
        </script>

                    <section class="clearfix">
                        <?php
                            if(isset($_GET['message'])){
                                echo "<p class='alert alert-success text-center my-2 border-success'>Jídla byla úspěšně přidána do jídelníčku!</p>";
                            }
                        ?>
                    </section>

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