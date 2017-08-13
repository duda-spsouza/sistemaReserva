<?php include('topo.html'); ?>
        <h2>Salas</h2>
        <div class='item' id='grid' style='max-width: 800px;'>
        <table class="users" border="0" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style='width:50px;'>Id</th>
                    <th>Sala</th>
                    <th>Descrição</th>
                    <th style='width:50px;'colspan=2>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($quartos as $room){ ?>
                <tr>
                    <td><?php print htmlentities($room->getId()); ?></td>
                    <td><?php print htmlentities($room->getNome()); ?></td>
                    <td><?php print $room->getDescricao(); ?></td>
                    <td style='width:25px;'><a href="index.php?op=Quartos&ac=excluir&id=<?php print $room->getId();?>">Excluir</a></td>
                    <td style='width:25px;'><a href="index.php?op=Quartos&ac=editar&id=<?php print $room->getId();?>">Editar</a></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        </div>
<?php include('rodape.html'); ?>