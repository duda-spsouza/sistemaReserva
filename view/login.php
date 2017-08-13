<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1' />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" crossorigin="anonymous">
        <link href='css/stylesheet.css' rel='stylesheet' type='text/css'>
        <title>Gerenciador de Reservas</title>

    </head>
    <body>
        <?php
        if ($errors) {
            print '<ul class="errors">';
            foreach ( $errors as $field => $error ) {
                print '<li>'.htmlentities($error).'</li>';
            }
            print '</ul>';
        }
        ?>
        <div class="wrapper">
        <form class="form-signin" method="POST" action="">       
          <h2 class="form-signin-heading">Login</h2>
          <input type="text" class="form-control" name="email" placeholder="Email" required="" autofocus="" />
          <input type="password" class="form-control" name="password" placeholder="Senha" required=""/>      
          <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>   
          <input type="hidden" name="form-submitted" value="1" />
        </form>
      </div>
    </body>
</html>
