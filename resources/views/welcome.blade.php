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
        <div class="card">
        <div class="card-body row">
                <div class="col-sm-12 col-md-5 text-center d-flex align-items-center justify-content-center">
                    <div class="">
                    <h2>{{ config('app.name', 'Laravel') }}</h2>
                    <p class="lead mb-5 text-info font-weight-bold">Support
        agents get in contact with the ticket owner to help resolve their issues.
                    </p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <form method="POST" id="ticket-form" action="">
                        @csrf
                        <div class="form-group">
                            <label for="cus_name">Customer Name</label>
                            <input  placeholder="Enter Customer Name" type="text" name="cus_name" id="cus_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="cus_problem">Problem Description</label>
                            
                            <div id="cus_problemfm"></div>
                            <input  style="outline: none;
    box-shadow: none;
    height: 0px;
    width: 0px !important;" readonly class="form-control-plaintext border-0" type="text" name="cus_problem" id="cus_problem">
                        </div>
                        <div class="form-group">
                            <label for="cus_email">Email Address</label>
                            <input  type="email" placeholder="Enter Email Address" id="cus_email" name="cus_email" class="form-control col-sm-12 col-md-6">
                        </div>
                        <div class="form-group">
                            <label for="cus_mobile">Phone Number</label>
                            <input type="text" placeholder="Enter Phone Number" id="cus_mobile" name="cus_mobile" class="form-control col-sm-12 col-md-6">
                        </div>
                        <div class="form-group">
                            <input type="submit" id="btnsub" class="btn btn-primary btn-lg btn-block" value="Send message">
                        </div>
                        <div class="form-group">
                            <p class="text-left font-weight-bold text-success" id="infomsg"></p>
                        </div>
                    </form>
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
            
            $('#cus_problemfm').summernote({
                placeholder: 'Enter Problem Description',
                height: 200,
                callbacks: {
                    onBlur: function() {
                        $('#cus_problem').val($('#cus_problemfm').summernote('code'));
                    }
                }
            });

            $("#"+ticket_form).validate({
                rules: {
                    cus_name: {
                        required: true,
                        minlength : 2,
                        lettersonly: true
                    },
                    cus_email: {
                        required: true,
                    },
                    cus_mobile: {
                        required: true,
                        digits: true,
                        minlength: 10
                    },
                    cus_problem: {
                        required: true,
                        minlength : 10,
                    },
                },
                messages: {
                    cus_name: {
                        required: "Customer Name Required",
                        minlength: "Enter at least {0} characters"
                    },
                    cus_email: {
                        required: "Customer Email Required",
                    },
                    cus_mobile: {
                        required: "Customer Phone Number Required",
                    },
                    cus_problem: {
                        required: "Reply Required",
                        minlength: "Enter at least {0} characters"
                    }
                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want send this message",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, send it!',
                        reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#cus_problemh').val($('#cus_problemfm').summernote('code'));
                                submitTicket();
                            }
                        });
                }
            });
        });

        function submitTicket(){
            $("#btnsub").LoadingOverlay("show");
            $.ajax({
                method: "POST",
                url: BASE_URL+"/submit-ticket",
                data: $('#'+ticket_form).serialize(),
                dataType: "json",
                success: function(response){
                    if(response.status){
                        $('#infomsg').html('Ticket URL : '+response.link);
                        Swal.fire({
                            position: 'top-end',
                            toast : true,
                            icon: 'success',
                            title: response.title,
                            showConfirmButton: false,
                            timer: 4000
                        });

                        $('#'+ticket_form).trigger("reset");
                        $('#cus_problemfm').summernote('reset');
                    }else{
                        Swal.fire({
                            position: 'top-end',
                            toast : true,
                            icon: 'error',
                            title: response.title,
                            showConfirmButton: false,
                            timer: 4000
                        });
                    }
                    $("#btnsub").LoadingOverlay("hide");
                },error: function(response){
                    $("#btnsub").LoadingOverlay("hide");
                }
            });
        }

        var ticket_form = "ticket-form";
    </script>
    </body>
</html>
