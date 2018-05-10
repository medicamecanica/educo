<?php
$project0 = new Project($db);
require_once DOL_DOCUMENT_ROOT . '/projet/class/task.class.php';

$i=0;
foreach ($objects as $project):
    if ($project->element == 'project'):
        $task = new Task($object->db);
        ?>
        <tr>
            <td><?php echo "Cronograma" ?></td> 
            <td><?php echo $project->getNomUrl(1,'',1) ?></td>
            <td></td>
            <td align="center"><?php echo dol_print_date($project->datec) ?></td>
            <td align="right"><?php echo $project->getLibStatut() ?></td>
            <td align="right">
                <a href="<?php $i++;
                echo $_SERVER['PHP_SELF'] . "?id=$object->id&projectid=$project->id&action=delete_project"
                ?>">
                       <?php
                       echo img_delete()
                       ?>
                </a>
            </td>
            <?php
            //print tasks
            $tasks = $task->getTasksArray(0, 0, $project->id);
            if (count($task) > 0) {

                print '<tr><td colspan="4"></td><td colspan="3">';
                print '<div class="div-table-responsive-no-min">';
                print '<table class="noborder allwidth">';
                print '<tr class="liste_titre">';

                print '<td  colspan="2" align="center">' . $langs->trans("Tasks") .'  '.get_date_range($project->date_start,$project->date_end,'',$langs,0). '</td>';
                ?>
                <td align="right">
                    <a href="<?php
                    $backtourl = urlencode($_SERVER['PHP_SELF'] . "?id=$object->id");
                    // "http://localhost/dolibarr-6/htdocs/projet/tasks.php?id=2&action=create&backtopage=%2Fdolibarr-6%2Fhtdocs%2Fprojet%2Ftasks.php%3Fid%3D2"
                    echo dol_buildpath("projet/tasks.php?id=$project->id&2&action=create&backtopage=" . $backtourl, 1)
                    ?>">
                           <?php
                           echo img_edit_add($langs->trans('AddTask'))
                           . img_object($langs->trans('AddTask'), 'task')
                           ?>
                    </a>
                </td>
                <?php
                print '</tr>';
                ?>
            </tr>

            <?php
            foreach ($tasks as $t) {
                echo '<tr>';
                echo '<td>';
                echo $t->getNomUrl(1, 'withproject','task' , 1);
                echo '<td>';
                echo get_date_range($t->date_start,$t->date_end,'',$langs,0);
                echo '</td>';
                echo '<tr>';
            }
            print '</table>';
            print '</div>';
            print '</tr></td>';
        }
        ?>

    <?php endif; ?>
<?php endforeach; ?>
