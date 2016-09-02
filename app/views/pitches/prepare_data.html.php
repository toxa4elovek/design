<table>
    <thead>
        <tr>
            <td>id</td>
            <td>category_id</td>
            <td>user_id</td>
            <td>price</td>
            <td>Кол-во доп. опций</td>
            <td>ideas_count</td>
            <td>количество оценок</td>
            <td>количество комментариев</td>
            <td>флаг возврата</td>
            <td>длительность проекта</td>
        </tr>
    </thead>
    <?php foreach($projects as $project):?>
    <tr>
        <td><?= $project->id?></td>
        <td><?= $project->category_id?></td>
        <td><?= $project->user_id?></td>
        <td><?= $project->price?></td>
        <td><?= $project->addonsCount?></td>
        <td><?= $project->ideas_count?></td>
        <td><?= $project->ratingNum?></td>
        <td><?= $project->commentsNum?></td>
        <td><?= $project->refund?></td>
        <td><?= $project->days?></td>
    </tr>
    <?php endforeach;?>
</table>