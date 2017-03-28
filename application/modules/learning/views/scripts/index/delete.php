<?php
        $all=$this->rs;
 
	foreach ($all as $rs){?>
        <tr>
            <td><?php echo $rs["cat_id"] ;?></td>
        </tr>
    <?php }

    ?>