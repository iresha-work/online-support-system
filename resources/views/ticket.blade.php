<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- include summernote css/js -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

        <style>
            .error{
                color:red !important;
            }
        </style>
        <script>
            var BASE_URL = '{{url('/')}}';
        </script>
    </head>
    <body class="antialiased">
    <section class="content">
            <div class="col-sm-12 col-md-12 text-center d-flex align-items-center justify-content-center">
                <div class="">
                <h2>{{ config('app.name', 'Laravel') }}</h2>
                <p class="lead mb-5 text-info font-weight-bold">Support
                    agents get in contact with the ticket owner to help resolve their issues.
                </p>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="card">
                        <div class="card-header">
                            #Ticket <a href="">{{$ticket->support_link_ref}} </a>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <input value="{{$ticket->ticket_status}}" readonly  type="text" class="form-control-plaintext">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Customer Name</label>
                                <div class="col-sm-8">
                                    <input value="{{$ticket->customer_name}}" readonly  type="text" class="form-control-plaintext">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="cus_email">Email Address</label>
                                <div class="col-sm-8">
                                    <input value="{{$ticket->customer_email}}" readonly  type="text" class="form-control-plaintext ">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="cus_mobile">Phone Number</label>
                                <div class="col-sm-8">
                                    <input value="{{$ticket->customer_mobile}}" readonly  type="text" class="form-control-plaintext">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-form-label" for="cus_problem">Problem Description</label>
                                <div class="col-12" id="cus_problem">{!!$ticket->customer_problem!!}</div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <p class="font-weight-bold">Ticket History & Submit Reply</p>
                            </div>
                            
                            <form method="POST" id="ticket-form" action="">
                            @csrf
                            <input type="hidden" value="{{$ticket->id}}" name="id_token" id="id_token">
                            <div class="card-body">
                                <div id="relate_tickets" style="max-height: 15rem;
    overflow: auto;"></div>
@if ($ticket->ticket_status != "Closed")
                                <div class="form-group">
                                    
                                    <div id="cus_problem_ry"></div>
                                    <input style="outline: none;
    box-shadow: none;
    height: 0px;
    width: 0px !important;" readonly class="form-control-plaintext border-0" type="text" name="cus_problemh" id="cus_problemh">
                                </div>
                                <div class="form-group">
                                    <input id="btnrpl" type="submit" class="btn btn-primary btn-lg btn-block" value="Send message">
                                </div>
                            </div>
                            @else
                            
                            @endif
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
    </section>
     <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <script
  src="https://code.jquery.com/jquery-3.7.0.min.js"
  integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- https://summernote.org/getting-started/ -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <!-- https://jqueryvalidation.org/ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
   
    <!-- https://sweetalert2.github.io/#download -->
    <script  src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
    <!-- custom script  -->
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.validator.addMethod("lettersonly", function(value, element) 
            {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
            }, "Contains only letters");
            
            $('#cus_problem').summernote({
                placeholder: 'Enter Problem Description',
                height: 200,
            });
            $('#cus_problem').summernote('disable');
            $('#cus_problem_ry').summernote({
                placeholder: 'Enter Reply',
                height: 140,
                callbacks: {
                    onBlur: function() {
                        $('#cus_problemh').val($('#cus_problem_ry').summernote('code'));
                    }
                } 
            });

            $("#"+ticket_form).validate({
                rules: {
                    cus_problemh: {
                        required: true,
                        minlength : 10,
                    },
                },
                messages: {
                    cus_problemh: {
                        required: "Reply Required",
                        minlength: "Enter at least {0} characters"
                    }
                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want send this reply",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, send it!',
                        reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                submitTicketReply();
                            }
                        });
                }
            });
        });

        function submitTicketReply(){
            $("#btnrpl").LoadingOverlay("show");
            $.ajax({
                method: "POST",
                url: BASE_URL+"/submit-ticket-reply",
                data: $('#'+ticket_form).serialize(),
                dataType: "json",
                success: function(response){
                    if(response.status){
                        get_relate_tickets();
                        Swal.fire({
                            position: 'top-end',
                            toast : true,
                            icon: 'success',
                            title: response.title,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        $('#'+ticket_form).trigger("reset");
                        $('#cus_problem_ry').summernote('reset');
                        $('#cus_problemh').val('');
                        
                    }else{
                        Swal.fire({
                            position: 'top-end',
                            toast : true,
                            icon: 'error',
                            title: response.title,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                    $("#btnrpl").LoadingOverlay("hide");
                },
                error: function(response){
                    $("#btnrpl").LoadingOverlay("hide");
                }
            });
        }
        var ticket_form = "ticket-form";
        get_relate_tickets();
        function get_relate_tickets(){
            $("#relate_tickets").LoadingOverlay("show");
            $.ajax({
                method: "GET",
                url: BASE_URL+"/get-relate-tickets",
                data: $('#'+ticket_form).serialize(),
                dataType: "html",
                success: function(response){
                    $('#relate_tickets').html(response);
                    $("#relate_tickets").LoadingOverlay("hide");
                },
                error: function(response){
                    $("#relate_tickets").LoadingOverlay("hide");
                }
            });
        }
        
    </script>
    </body>
</html>
