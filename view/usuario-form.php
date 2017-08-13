<?php include('topo.html'); ?>
        <?php
        if ($errors) {
            print '<ul class="errors">';
            foreach ( $errors as $field => $error ) {
                print '<li>'.htmlentities($error).'</li>';
            }
            print '</ul>';
        }
        ?>
        <h2><?php print htmlentities($title) ?></h2>
        <form method="POST" action="">
            <div class='item' style='max-width: 430px;'>
                <label for="nome">Nome</label><br/>
                <input required autofocus type="text" name="nome" value="<?php print htmlentities($usuario->getNome()) ?>"/>
            </div>
            <div class='item' style='max-width: 430px;'>
                <label for="email">Email</label><br/>
                <input required type="text" name="email" value="<?php print htmlentities($usuario->getEmail()) ?>"/>
            </div>
            <?php if($usuario->getId() != NULL){ ?>
            <div class='item' style='max-width: 430px;'>
                <label for="oldpassword">Senha antiga</label><br/>
                <input required type="password" name="oldpassword" value="<?php print htmlentities("")?>" />
            </div>
            <?php } ?> 
            <div class='item' style='max-width: 430px;'>
                <label for="password">Senha</label><br/>
                <input required type="password" name="password" value="<?php print htmlentities("")?>" />
            </div>
            <div class='item' style='max-width: 430px;'>
                <label for="passwordconf">Confirme a senha</label><br/>
                <input required type="password" name="passwordconf" value="<?php print htmlentities("")?>" />
            </div>
            <br/>
            <input type="hidden" name="form-submitted" value="1" />
            <div class='button-panel'>
                <input type="submit" value="Gravar"/>
                <input type="reset" value="Limpar"/>

                <div style='background:#b0bec5;' title='Campo sem validação'></div>
                <div style='background:#ffab91;' title='Campo Invalido'></div>
                <div style='background:#a5d6a7;' title='Campo correto'></div>
            </div>
        </form>
<?php include('rodape.html'); ?>