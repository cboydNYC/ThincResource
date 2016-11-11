<script type="text/javascript" src="{{ asset('sximo/js/plugins/jquery-1.12.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('sximo/js/plugins/fullcalendar/lib/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('sximo/js/plugins/fullcalendar/fullcalendar/fullcalendar.min.js') }}"></script>

@extends('layouts.app')

@section('content')

    <div class="wrapper-header ">
        <div class=" container">
            <div class="row">
                <div class="col-md-5">
                    <h2 style="margin-left:10px;">{{ $first_name }}'<?php if(substr($first_name, -1) != "s") { echo "s"; } ?> Assignment&nbsp;Calendar</h2>
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
                            <div id='calendar'></div>
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

    <script>

        $(document).ready(function () {

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultDate: '{{ date("Y-m-d")}}',
                selectable: false,
                selectHelper: true,

                eventClick: function (calEvent, jsEvent, view) {
                    var id = calEvent.id;
                    SximoModal('{!! url("staff/calendar/showModal/".$id) !!}', '' + calEvent.title);
                },
                editable: false,
                events: {
                    url: '{{ url("staff/schedule/".$id) }}',
                    error: function () {
                        $('#script-warning').show();
                    }
                }
            });

        });

    </script>
    <style>
        #script-warning {
            display: none;
            background: #eee;
            border-bottom: 1px solid #ddd;
            padding: 0 10px;
            line-height: 40px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            color: red;
        }

        .fc-event-inner {
            color: #fff;
            padding: 1px 4px;
        }

        #loading {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
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
            h2 {
                font-size:14px;
            }
            .icon {
                -webkit-print-color-adjust: exact;
            }
            nav, .navbar-default, .fc-header-left, .fc-header-right, .footer {
                display: none !important;
                visibility: hidden;
            }
            .navbar-default {
                width:0px;
            }
            #page-wrapper > div:first-of-type {
                display: none !important;
                visibility: hidden;
            }
        }

        @media print and (color) {
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endsection
