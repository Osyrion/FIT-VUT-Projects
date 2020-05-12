<?php 
session_start();
include '../functions.php';
if(isset($_SESSION['jmeno'])){
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
        <title>Registrace | Jidelna IS</title>
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
                            <li class='nav-item'>
                                <a class='nav-link' href='../account/register.php'><button class='btn btn-outline-info'>Registrace</button></a>
                            </li>
                            <li class='nav-item'>
                                <a class='nav-link' href='../account/login.php'><button class='btn btn-outline-warning'>Přihlášení</button></a>
                            </li>
                        </ul>
                </div>
            <div>
        </nav>
    <main class="container">
        <section class="row justify-content-md-center">
            <div class="col col-md-6 mt-sm-3">
                <div class="card shadow-lg border-dark">
                <h5 class="card-header">Registrace</h5>
                    <div class="card-body">
        
            <?php 
            if(isset($_POST['submit'])){
                $jmeno = filter_input(INPUT_POST, "jmeno", FILTER_SANITIZE_STRING);
                $prijmeni = filter_input(INPUT_POST, "prijmeni", FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
                $pass1 = filter_input(INPUT_POST, "pass1");
                $pass2 = filter_input(INPUT_POST, "pass2");
                $email_kon = emailExists($email);
                $email = $email_kon > 0 ? "" : $email;
            ?>

            <!-- REGISTRATION FORM -->
            <form method="post" action="" enctype="multipart/form-data">
              <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputName">Jméno</label>
                    <input type="text" class="form-control" id="inputName" placeholder="Zadajte jméno" name="jmeno" required="required" value="<?php echo $jmeno;?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputSurname">Příjmení</label>
                    <input type="text" class="form-control" id="inputSurname" placeholder="Zadajte příjmení" name="prijmeni" required="required" value="<?php echo $prijmeni;?>">
                </div>
              </div>
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="Zadajte email" name="email" required="required" value="<?php echo $email;?>">
                </div>
                <div class="form-group">
                    <label for="inputPassword1">Heslo</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="Zadajte heslo" name="pass1" required="required" />
                </div>
                <div class="form-group">
                    <label for="inputPassword2">Potvrzení hesla</label>
                    <input type="password" class="form-control" id="inputPassword2" placeholder="Zadejte heslo znovu" name="pass2" required="required" />
                </div>                
                    <input type="submit" class="btn btn-primary float-right" name="submit" value="Registrovat" />
            </form>

            <?php }else{?>
            <!-- REGISTRATION FORM -->
            <form method="post" action="" enctype="multipart/form-data">
              <div class="form-row"> 
                <div class="form-group col-md-6">
                    <label for="inputName">Jméno</label>
                    <input type="text" class="form-control" id="inputName" placeholder="Zadajte jméno" name="jmeno" required="required" />
                </div>
                <div class="form-group col-md-6">
                    <label for="inputSurname">Příjmení</label>
                    <input type="text" class="form-control" id="inputSurname" placeholder="Zadajte příjmení" name="prijmeni" required="required" />
                </div>
              </div>
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="Zadajte email" name="email" required="required" />
                </div>
                <div class="form-group">
                    <label for="inputPassword1">Heslo</label>
                    <input type="password" class="form-control" id="inputPassword" placeholder="Zadajte heslo" name="pass1" required="required" />
                </div>
                <div class="form-group">
                    <label for="inputPassword2">Potvrzení hesla</label>
                    <input type="password" class="form-control" id="inputPassword2" placeholder="Zadejte heslo znovu" name="pass2" required="required" />
                </div>                
                    <input type="submit" class="btn btn-primary float-right" name="submit" value="Registrovat" />
            </form>
                <?php } ?>
                <a href="../index.php" >≪ Návrat</a>
                    </div>
                </div>
                <div class="alert alert-secondary mt-md-2 shadow-lg border-dark" role="alert">
                    Již jsem registrovaný: <a href="login.php" class="badge badge-warning">Přihlášení</a>
                </div>
            
    
	<?php
        
        if(isset($_POST['submit'])){
            if($pass1!==$pass2){
                echo "
                <p class='alert alert-danger border-danger text-center'>Hesla se neshodují!</p>        
                ";
            }else{
                add($email, $pass1, $jmeno, $prijmeni);
            }
        }
                
        function add($email, $password, $jmeno, $prijmeni){           
            if(!($email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))){
                echo "<p class='alert alert-danger border-danger text-center'>Špatný formát emailu!</p>";
            }else{
                $email_kon = emailExists($email);
                $db = dbconnect();                                    
                if($email_kon == 0){
                    $pass = password_hash($password, PASSWORD_DEFAULT);
                    $avatar = "/avatar/avatar1.jpg";
                    $sql1 = "INSERT INTO user(jmeno, prijmeni, email, password) VALUES (?, ?, ?, ?)";
                    if($val = $db->prepare($sql1)){
                        $val->bind_param("ssss", $jmeno, $prijmeni, $email, $pass);
                        $val->execute();
                        $val->close();
                        echo "<p class='alert alert-success border-success text-center'>Registrace úspěšná!</p>";
                        ?><script>
                            var home = setTimeout(Home, 5000, "login.php", home);
                        </script><?php
                    }else{
                        echo "<p class='alert alert-danger text-center border-danger'>Registrace se nezdařila!</p>";
                    }
                }else{
                    $sql = "SELECT jmeno FROM user WHERE id = $email_kon";
                    if($ucheck = $db->prepare($sql)){
                        $ucheck->execute();
                        $ucheck->bind_result($jmeno_emp);
                        if($ucheck->fetch()){
                            $ucheck->close();
                            if($jmeno_emp == ""){
                                $sql_insrt = "UPDATE user SET jmeno = ?, prijmeni = ?, password = ?, prava = 4 WHERE id = $email_kon";
                                if($insrt = $db->prepare($sql_insrt)){
                                    $pass = password_hash($password, PASSWORD_DEFAULT);
                                    $insrt->bind_param("sss", $jmeno, $prijmeni, $pass);
                                    $insrt->execute();
                                    $insrt->close();
                                    echo "<p class='alert alert-success border-success text-center'>Registrace úspěšná!</p>";
                                    ?><script>
                                        var home = setTimeout(Home, 5000, "login.php", home);
                                    </script><?php
                                }
                            }else{
                                echo "<p class='alert alert-danger border-danger text-center'>Tento email se již používá!</p>";
                            }
                        }
                    }
                }
                $db->close();                
            }
        }
        ?>
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