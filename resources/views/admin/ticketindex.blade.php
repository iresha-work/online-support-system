<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h5 class="card-title">{{ __('Ticket List') }}</h5>
                <div class="form-inline">
                    <div class="form-group col-6 mb-2">
                        <select required class="form-control col-12" name="cus_status" id="cus_status">
                            <option value="">Choose Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Process">Process</option>
                            <option value="Solved">Solved</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <button id="btnreload" type="button" style="background-color: #007bff;" class="btn btn-primary mb-2"><ion-icon name="search-outline"></ion-icon></button>
                    </div>
                </div>
                
                    <table id="ticket-list" class="display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Create At</th>
                                <th>Reference</th>
                                <th>Replies</th>
                                <th class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    var js_data_ticket = '';
    $(document).ready(function () {
        js_data_ticket = $('#ticket-list').DataTable({
             processing: true,
             serverSide: true,
             responsive: true,
             "ajax": {
                url: "{{route('tickets.data')}}",
                data: function(data) {
                    data.cus_status =  $("#cus_status option:selected").val();
                }
            },
             columns: [
                 { data: 'customer_name' },
                 { data: 'customer_email' },
                 { data: 'customer_mobile' },
                 { data: 'ticket_status' },
                 { data: 'created_at' },
                 { data: 'support_link_ref' },
                 { data: 'replies' ,className: "text-right",orderable: false},
                 { data: 'id' , className: "text-center",orderable: false },
             ]
         });


         $(document).on("click","#btnreload",function() {
            js_data_ticket.ajax.reload();
         });
    });

    function get_relate_ticket(pid){
            $.ajax({
                method: "GET",
                url: BASE_URL+"/ticket-data",
                data: {
                    pid : pid
                },
                dataType: "html",
                success: function(response){
                }
            });
        }
</script>
