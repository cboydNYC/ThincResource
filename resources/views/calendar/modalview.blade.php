<?php
use App\Library\MyHelpers;
?>
<script>
    $(".modal-header").css( "background-color", '{{ $row->color }}' );
</script>

<div class="page-content row">

    <div class="page-content-wrapper">

        <div class="sbox ">

            <div class="sbox-content">

                <table class="table table-striped table-bordered">
                    <tbody>

                    <tr>
                        <td width='30%' class='label-view text-right'>Client</td>
                        <td>{{ $row->client }} </td>
                    </tr>

                    <tr>
                        <td width='30%' class='label-view text-right'>Project</td>
                        <td>{{ $row->project }} </td>
                    </tr>

                    <tr>
                        <td width='30%' class='label-view text-right'>Milestone Title</td>
                        <td>{{ $row->title }} </td>
                    </tr>

                    <tr>
                        <td width='30%' class='label-view text-right'>Description</td>
                        <td>{{ $row->description }} </td>
                    </tr>

                    <tr>
                        <td width='30%' class='label-view text-right'>Status</td>
                        <td>{{ $row->status }} </td>
                    </tr>

                    <tr>
                        <td width='30%' class='label-view text-right'>Priority</td>
                        <td>{{ $row->priority }} </td>
                    </tr>

                    <tr>
                        <td width='30%' class='label-view text-right'>{{ $row->end_date != "0000-00-00" ?  "Start" : "Date" }}</td>
                        <td>{{ MyHelpers::formatDate($row->start_date) }} </td>

                    </tr>
                    @if ($row->end_date != "0000-00-00")
                        <tr>
                            <td width='30%' class='label-view text-right'>End</td>
                            <td>{{ $row->end_date != "0000-00-00" ?  MyHelpers::formatDate($row->end_date) : "" }} </td>

                        </tr>
                    @endif
                    @if($people)
                        <tr>
                            <td width='30%' class='label-view text-right'>People Involved</td>
                            <td>
                                @foreach ($people as $person)
                                  {{ $person->first_name }} {{ $person->last_name }}<br />
                                @endforeach
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>
