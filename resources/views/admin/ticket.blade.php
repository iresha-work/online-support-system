<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="">
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

                                <div class="form-group">
                                    
                                    <div id="cus_problem_ry"></div>
                                    <input style="outline: none;
    box-shadow: none;
    height: 0px;
    width: 0px !important;" readonly class="form-control-plaintext border-0" type="text" name="cus_problemh" id="cus_problemh">
                                </div>
                                <div class="form-group">
                                    <select required class="form-control" name="cus_status" id="cus_status">
                                        <option value="">Choose Status</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Process">Process</option>
                                        <option value="Solved">Solved</option>
                                        <option value="Closed">Closed</option>
                                    </select> 
                                </div>
                                <div class="form-group">
                                    <input id="btnrpl" style="background-color: #007bff;" type="submit" class="btn btn-primary btn-lg btn-block" value="Send message">
                                </div>

                                
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function () {
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

        $('#ticket-list').DataTable({
             processing: true,
             serverSide: true,
             ajax: "{{route('tickets.data')}}",
             columns: [
                 { data: 'customer_name' },
                 { data: 'customer_email' },
                 { data: 'customer_mobile' },
                 { data: 'ticket_status' },
                 { data: 'created_at' },
                 { data: 'replies' },
                 { data: 'id' },
             ]
         });
    });

    get_relate_tickets();

    function submitTicketReply(){
        $("#btnrpl").LoadingOverlay("show");
        $.ajax({
            method: "POST",
            url: BASE_URL+"/admin-submit-ticket-reply",
            data: $('#'+ticket_form).serialize(),
            dataType: "json",
            success: function(response){
                if(response.status){
                    Swal.fire({
                        position: 'top-end',
                        toast : true,
                        icon: 'success',
                        title: response.title,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    window.location.reload();
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
    function get_relate_tickets(){
        $("#relate_tickets").LoadingOverlay("show");
        $.ajax({
            method: "GET",
            url: BASE_URL+"/admin-get-relate-tickets",
            data: {
                id_token : $('#id_token').val()
            },
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
    var ticket_form = "ticket-form";
</script>
