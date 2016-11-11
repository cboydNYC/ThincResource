@extends('layouts.app')

@section('content')
    <div class="page-content row">

        <div class="modal fade printable autoprint" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn btn-default" style='float: right;' data-dismiss="modal"><span
                                    class="glyphicon glyphicon-remove-circle"></span></button>
                        <a href="#" class="btn btn-default" style='float: right;'> <span
                                    class="glyphicon glyphicon-print"></span> </a>
                        {{--<a href="#" class="btn btn-default" style='float: right;'> <span class="glyphicon glyphicon-trash"></span> </a>
                        <a href="#" class="btn btn-default" style='float: right;'> <span class="glyphicon glyphicon-pencil"></span> </a>--}}
                        <div style="margin-bottom:15px;">
                            @if(file_exists(public_path().'/sximo/images/'.CNF_LOGO) && CNF_LOGO !='')
                                <img src="{{ asset('sximo/images/'.CNF_LOGO)}}" alt="{{ CNF_APPNAME }}"/>
                            @else
                                <img src="{{ asset('sximo/images/logo.png')}}" alt="{{ CNF_APPNAME }}"/>
                            @endif
                        </div>
                        <h4 class="modal-title">{{ $pageTitle }}</h4>
                        <h5 class="modal-subtitle">{{ $pageNote }}</h5>
                    </div>

                    <!-- text input -->
                    <!-- Begin Content -->

                    <div class="page-content-wrapper m-t modal-body">

                        <table class="table table-header-rotated">
                            <thead>
                            <tr>
                                <!-- write column headers-->
                                <th></th>
                                @foreach ($people AS $person)
                                    <th class="rotate">
                                        <div><span>{{ $person->name }}</span></div>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <!-- write rows-->
                            @foreach ($projects AS $project)
                                <?php
                                $assignments = \DB::select(\DB::raw("SELECT staff_id, role_id FROM tb_project_staffing WHERE project_id = " . $project->id . " AND isactive=1"));
                                $ass = json_decode(json_encode($assignments), True);
                                ?>
                                <tr>
                                    <th class="row-header" id="p{{ $project->id }}" nowrap="nowrap">{{ $project->name }}</th>
                                    @foreach ($people AS $person)
                                        <td id="s{{ $person->id }}"><?php
                                            foreach ($ass as $key => $val) {
                                                //echo $val['staff_id'];
                                                if($val['staff_id'] === $person->id ) {
                                                    echo "&#10004;";
                                                }
                                            }

                                            ?>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
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
                font-weight: 800;
            }

            .title {
                font-weight: 600;
                margin: 0px;
                padding: 0px;

            }

            .title > small {
                font-weight: 400;
                margin: 0px;
                padding: 0px;
            }

            .participants {
                font-weight: 400;
                font-style: italic;
                font-size: 12px;
                color: #333;
                margin: 0px;
                padding: 0px 0px 15px 0px;
            }

            .table-header-rotated {
                border-collapse: collapse;
                width: 95%;
            }

            .table-header-rotated td {
                width: 15px;
                height:20px;
                text-align: center;
                padding: 10px 5px;
                border: 1px solid #ccc;
                color: green;
                font-weight:800;
                font-size:18px;
            }
            .table-header-rotated th.rotate {
                height: 94px;
                white-space: nowrap;
            }

            .table-header-rotated th.rotate > div {
                -webkit-transform: translate(25px, 51px) rotate(315deg);
                transform: translate(25px, 51px) rotate(315deg);
                width: 30px;
            }

            .table-header-rotated th.rotate > div > span {
                border-bottom: 1px solid #ccc;
                padding: 5px 10px;
            }

            .table-header-rotated th.row-header {
                padding: 0 10px;
                border-bottom: 1px solid #ccc;
            }

            th.project-header {
                padding: 5px 25px 5px 5px;
                margin: 5px;
            }

            @media (min-width: 768px) {
                .modal-dialog {
                    width: 1024px;
                    margin: 30px auto;
                }

                .modal-content {
                    -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
                    box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
                }

            }

            @media print {
                @page { size: landscape; }
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
                $(function () {
                    $('#myModal').modal('show');
                });
            </script>
        @endif

        <script>
            $(document).ready(function () {
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