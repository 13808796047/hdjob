<?php if(!defined("PATH_HD"))exit;?>
<table class="table-form">
    <?php if(is_array($fields)):?><?php  foreach($fields as $field){ ?>
    <tr>
        <th><?php echo $field['title'];?></th>
        <td><?php echo $field['html'];?><span><?php echo $field['field_tips'];?></span></td>
    </tr>
    <?php }?><?php endif;?>
</table>