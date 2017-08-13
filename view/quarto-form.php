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
            <div class='item' style='max-width: 400px;'>
                <label for="nome">Nome</label><br/>
                <input required type="text" name="nome" value="<?php print htmlentities($quarto->getNome()) ?>"/>
            </div>
            
            <div class='item' style='max-width: 800px;'>
                <label for="descricao">Descrição</label><br/>
                <textarea rows='3' type="text" name="descricao"><?php print $quarto->getDescricao() ?></textarea>
            </div>

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