<?php include('topo.html'); ?>
        <h2>Usuários</h2>
        <div class='item' id='grid' style='max-width: 800px;'>
        <table class="users" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style='width:50px;'>Id</th>
                    <th>Name</th>
                    <th style='width:150px;'>Email</th>
                    <th>Senha</th>
                    <th style='width:50px;'colspan=2>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($usuarios as $usuario){ ?>
                <tr>
                    <td><?php print htmlentities($usuario->getId()); ?></td>
                    <td><?php print htmlentities($usuario->getNome()); ?></td>
                    <td><?php print htmlentities($usuario->getEmail()); ?></td>
                    <td><?php print htmlentities($usuario->getHash()); ?></td>
                    <td style='width:25px;'><a href="index.php?op=Usuarios&ac=excluir&id=<?php print $usuario->getId();?>">Excluir</a></td>
                    <td style='width:25px;'><a href="index.php?op=Usuarios&ac=editar&id=<?php print $usuario->getId();?>">Editar</a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        </div>
<?php include('rodape.html'); ?>