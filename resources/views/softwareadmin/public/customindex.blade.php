<div class="container m-t">
    <h4>Software</h4>
    <div class="table-responsive" style="width:65%;">
        <table class="table table-striped table-bordered" style="width:100%;">
            <thead>
            <tr>
                @foreach ($subtableGrid as $t)
                    @if($t['view'] =='1')
                        <?php $limited = isset($t['limited']) ? $t['limited'] : ''; ?>
                        @if(SiteHelpers::filterColumn($limited ))
                            <th>{{ $t['label'] }}</th>
                        @endif
                    @endif
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach ($rowData as $row)
                <tr>
                    @foreach ($subtableGrid as $field)
                        <?php print_r($field); ?>
                        @if($field['view'] == '1')
                            <?php $limited = isset($field['limited']) ? $field['limited'] : ''; ?>
                            @if(SiteHelpers::filterColumn($limited ))
                                <td>
                                    @php
                                    echo $field;
                                        switch ($field) {
                                            case "proficiency":
                                                //code to be executed
                                                break;
                                            case "learn":
                                                //code to be executed
                                                break;
                                            default:
                                                echo SiteHelpers::formatRows($row->{$field['field']},$field,$row);
                                        }
                                    @endphp


                                </td>
                            @endif
                        @endif
                    @endforeach
                </tr>
            @endforeach

            </tbody>

        </table>
    </div>
    <div class="text-center" style="width:50%;"> {!! $pagination->render() !!}</div>


</div> 