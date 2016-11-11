<data>
@foreach ($staff as $person)
        <?php
        $sql = "SELECT tb_staff_group_assignments.id, tb_staff.id, CONCAT(tb_staff.first_name, ' ', LEFT(tb_staff.last_name, 1)) as `name_text`, tb_projects.short_name as `project_short_name`, tb_staff_groups.short_name as `short_group_name`,tb_staff_groups.group_name as `group_name`, tb_staff_group_assignments.hours_per_week, tb_staff_group_assignments.start as start_date, tb_staff_group_assignments.end as end_date, DATEDIFF(tb_staff_group_assignments.end,tb_staff_group_assignments.start) as duration, tb_projects.color, tb_staff_group_assignments.mark_as_out, tb_staff_group_assignments.mark_as_travel
FROM tb_staff_group_assignments
LEFT JOIN tb_staff ON tb_staff_group_assignments.staff_id = tb_staff.id
LEFT JOIN tb_projects ON tb_staff_group_assignments.project_id = tb_projects.id
LEFT JOIN tb_staff_groups ON tb_staff_group_assignments.group_id = tb_staff_groups.id
WHERE tb_staff_group_assignments.staff_id AND tb_staff_group_assignments.staff_id= ".$person->id." AND ((curdate() BETWEEN tb_staff_group_assignments.start AND tb_staff_group_assignments.end) OR (tb_staff_group_assignments.end = '0000-00-00') )
        ORDER BY 'tb_staff.first_name', 'tb_staff.last_name'";
        $result = \DB::select($sql);
        $results['rows'] =  $result;
        $results['total'] = count($result);

        ?>
    <task id="{{ $person->id }}">

    <start_date>
            <![CDATA[ {{ $result[0]->start_date }} 00:00:00 ]]>
            </start_date>
            <duration><?php if ($result->duration) { ?><![CDATA[ {{ $result->duration }} ]]><?php } else { ?>365<?php } ?></duration>
            <text>{{ $result->project_short_name }}{{ $result->short_group_name !== NULL ? "- ".$result->short_group_name: "" }} ({{ $result->hours_per_week }})</text>
            <progress></progress>
            <parent></parent>


</task>
@endforeach
</data>
