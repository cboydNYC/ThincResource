@extends('layouts.app')

@section('content')
<div class="page-content row">

    <div class="modal fade printable autoprint" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-default" style='float: right;' data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle"></span></button>
                    <a href="#" class="btn btn-default" style='float: right;'> <span class="glyphicon glyphicon-print"></span> </a>
                    {{--<a href="#" class="btn btn-default" style='float: right;'> <span class="glyphicon glyphicon-trash"></span> </a>
                    <a href="#" class="btn btn-default" style='float: right;'> <span class="glyphicon glyphicon-pencil"></span> </a>--}}
                    <div style="margin-bottom:15px;">
                    @if(file_exists(public_path().'/sximo/images/'.CNF_LOGO) && CNF_LOGO !='')
                        <img src="{{ asset('sximo/images/'.CNF_LOGO)}}" alt="{{ CNF_APPNAME }}" />
                    @else
                        <img src="{{ asset('sximo/images/logo.png')}}" alt="{{ CNF_APPNAME }}"  />
                    @endif
                    </div>
                    <h4 class="modal-title">{{ $pageTitle }}</h4>
                    <h5 class="modal-subtitle">{{ $pageNote }}</h5>
                </div>

                <!-- text input -->
                <!-- Begin Content -->
                <?php $curr_month = ""; ?>
                <div class="page-content-wrapper m-t modal-body">
                    <table border="0" style="margin-bottom:25px;" >
                    @foreach ($deadlines AS $deadline)
                        @if($curr_month != $deadline->start_month)
                            <tr><td colspan="4"><h3 class="month">{{ $deadline->start_month }}</h3></td></tr>
                        @endif
                        <tr valign="top">
                            <td class="start_date" nowrap="nowrap">{{ date('M d', strtotime($deadline->start_date)) }}
                                @if($deadline->end_date!="0000-00-00")
                                    &nbsp;-&nbsp;{{  date('M d', strtotime($deadline->end_date)) }}
                                @endif
                            </td>
                            <td style="text-align:center; width:50px;"><div class="icon" style="height:35px; width:35px; border-radius: 50%; margin:3px; padding:10px 3px 3px 3px; color: #FFF !important; text-align:center; font-size:12px; font-weight:700; background-color:{{ $deadline->color }} !important; -webkit-print-color-adjust: exact;">{{ $deadline->short_name }}</div>
                            <td class="title">{{ $deadline->title }}&nbsp;
                                @if($deadline->description)
                                <br />
                            <small>{{ $deadline->description }}&nbsp;</small>
                                @endif
                        <?php
                            //print_r($deadline);
                            $p = "";
                                $participants = \DB::select( \DB::raw("SELECT tb_project_milestone_staff.user_id, tb_staff.first_name, tb_staff.last_name FROM tb_project_milestone_staff INNER JOIN tb_staff ON tb_project_milestone_staff.user_id=tb_staff.id WHERE tb_project_milestone_staff.milestone_id = ".$deadline->id) );
                            if ( count($participants) ) {
                                foreach($participants as $participant) {
                                    $p .= ($p !="" ? ", " : "");
                                    $p .= $participant->first_name . " " . substr($participant->last_name,0,1);
                                }

                            }
                            echo("<div class='participants'>".$p."</div>");
                            $curr_month = $deadline->start_month; ?></td>
                        </tr>
                    @endforeach
                        </table>
                </div>
                <!-- End Content -->
            </div>

        </div>



</div>

<style>
    body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
    }
    .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
        font-family: inherit;
        font-weight: 500;
        line-height: 1.1;
        color: inherit;
    }
    h3.month {
        font-weight:800;
    }
    .title {
        font-weight:600;
        margin:0px;
        padding:0px;

    }
    .title > small {
        font-weight:400;
        margin:0px;
        padding:0px;
    }
    .participants {
        font-weight:400;
        font-style: italic;
        font-size:12px;
        color: #333;
        margin:0px;
        padding:0px 0px 15px 0px;
    }
    td {
        padding: 5px;
        margin: 5px;
    }

    @media (min-width: 768px) {
        .modal-dialog {
            width: 800px;
            margin: 30px auto;
        }
        .modal-content {
            -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
        }

    }
    @media print {
        body {
            background-color: white !important;
            -webkit-print-color-adjust: exact;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
        }
        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
        }
        body.modalprinter * {
            visibility: hidden;
        }

        body.modalprinter .modal-dialog.focused {
            position: absolute;
            padding: 0px;
            margin: 0px;
            left: 0px;
            top: 0px;
        }

        body.modalprinter .modal-dialog.focused .modal-content {
            border-width: 0;
        }

        body.modalprinter .modal-dialog.focused .modal-content .modal-header img,
        body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title,
        body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-subtitle,
        body.modalprinter .modal-dialog.focused .modal-content .modal-body,
        body.modalprinter .modal-dialog.focused .modal-content .modal-body * {
            visibility: visible;
        }

        body.modalprinter .modal-dialog.focused .modal-content .modal-header,
        body.modalprinter .modal-dialog.focused .modal-content .modal-body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            padding: 0px;
        }

        body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title {
            margin-bottom: 20px;
        }

        .icon {
            -webkit-print-color-adjust: exact;
        }
    }
    @media print and (color) {
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@if(!empty($error_code) && $error_code == 5)
    <script>
    $(function() {
        $('#myModal').modal('show');
    });
    </script>
@endif

<script>
$(document).ready(function(){
    $('.modal.printable').on('shown.bs.modal', function () {
        $('.modal-dialog', this).addClass('focused');
        $('body').addClass('modalprinter');

        if ($(this).hasClass('autoprint')) {
            window.print();
        }
    }).on('hidden.bs.modal', function () {
        $('.modal-dialog', this).removeClass('focused');
        $('body').removeClass('modalprinter');
    });
});	
</script>

@endsection