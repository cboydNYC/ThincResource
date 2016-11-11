
@extends('layouts.app')

@section('content')

    <div class="wrapper-header ">
        <div class=" container">
            <div class="row">
                <div class="col-md-5">
                    <h2 style="margin-left:10px;">Master Staff Assignments</h2>
                </div>
{{--                <div class="col-md-7" style="margin-top:25px;text-align:right;font-size:12px;">
                    Color Legend:&nbsp;&nbsp;&nbsp;
                    @foreach($deadline_types as $type)
                        <img style="display:inline; margin-top:-5px; height:20px; width:20px; background-color:{{ $type->color }};" />&nbsp;{{ $type->deadline_type }}&nbsp;&nbsp;&nbsp;
                    @endforeach
                </div>--}}
            </div>
        </div>
    </div>

    <div class="container">

        <div class="row">

            <!-- main start -->
            <!-- ================ -->
            <div class="main col-md-12">
                <div class="sbox animated ">

                    <div class="sbox-content">

                        <div style="padding:10px; background:#fff;">
                            <div id='calendar'>
                                <table border="1" style="margin:5px; padding:5px;">
                                    <?php $grp = ""; ?>
                                    <tr><th>
                                        Staff Member
                                    </th>
                                    </tr>
                                    @foreach ($staff as $person)
                                        @if($person->group_name != $grp)
                                                <th>{{ strtoupper ($person->group_name) }}</th>
                                        @endif
                                        <?php $grp = $person->group_name; ?>
                                    <tr>
                                        <th>{{ $person->first_name }}</th>
                                        <td>&nbsp;</td>
                                    </tr>
                                        @endforeach
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- main end -->

        </div>

        <div class="container" style="margin-bottom:100px;">
            <div class="row">


            </div>
        </div>
    </div>


@endsection
