<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket;
use App\Models\TicketSupport;
use App\Providers\AppServiceProvider;
use Mail;
use App\Mail\CustomerEmail;

class AdminTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  view('admin.ticketindex');
    }

    /**
     * Display a listing of the resource.
     */
    public function getTickets(Request $request){

        $ar_search_cols = ["customer_name" , "customer_email" , "customer_mobile" , "ticket_status","created_at" ,"support_link_ref"];
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $cus_status = $request->get('cus_status');
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Ticket::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Ticket::select('count(*) as allcount')->where(function($query) use ($ar_search_cols , $searchValue , $cus_status) {
            if($searchValue != ''){
                foreach ($ar_search_cols as $col) {
                    $query->orWhere($col,'like', '%' .$searchValue . '%');
                }
            }
            if($cus_status != ''){
                $query->where('ticket_status',$cus_status);
            }
        })->count();

        // Fetch records
        $tickets = Ticket::where(function($query) use ($ar_search_cols , $searchValue , $cus_status) {
                        if($searchValue != ''){
                            foreach ($ar_search_cols as $col) {
                                $query->orWhere($col,'like', '%' .$searchValue . '%');
                            }
                        }

                        if($cus_status != ''){
                            $query->where('ticket_status',$cus_status);
                        }
                    })->select('*')
                    ->skip($start)
                    ->take($rowperpage)
                    ->orderby($ar_search_cols[$columnIndex] , $columnSortOrder)
                    ->get();

        $data_arr = array();
        foreach($tickets as $ticket){
           $data_arr[] = array(
                "id" => '<a target="_blank" class="detail-ticket text-center text-danger" href="'.url('/admin-ticket-status/'.$ticket->support_link_ref ).'"><ion-icon name="search-outline"></ion-icon></a>',
               "customer_name" => $ticket->customer_name,
               "customer_email" => $ticket->customer_email,
               "customer_mobile" => $ticket->customer_mobile,
               "ticket_status" => $ticket->ticket_status,
               "support_link_ref" => $ticket->support_link_ref ,
               "created_at" => date('Y-m-d H:i:s', strtotime($ticket->created_at)),
               "replies" => $ticket->supports->count(),
           );
        }

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $data_arr
        );

        return response()->json($response); 
     }


    /**
     * Display the specified resource.
     */
    public function showTicket(Request $request , string $token)
    {
        $validator = Validator::make($request->all(),[
            'token' => ['required']
        ],[]);

        $ticket = Ticket::where([
            'support_link_ref' => $token,
        ])->first();
        if(empty($ticket)){
            abort(404,'go back to home');
        }

        return view('admin.ticket', [
            'ticket' => $ticket,
        ]);
    }

     /**
     * Display the specified resource.
     */
    public function showRelateTickets(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_token' => ['required']
        ],[]);

        $relate_tickets = TicketSupport::where([
            'ticket_id' => $request->get('id_token'),
        ])
        ->orderBy('id' , 'desc')
        ->get();

        if(empty($relate_tickets)){
            return [];
        }

        return view('admin.ticket_relate', [
            'relate_tickets' => $relate_tickets,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'cus_problemh' => ['required','min:10'],
            'id_token' => ['required'],
        ],[],$this->attributes());

        $response_ar = [];

        if ($validator->fails()) {

            $errors = $validator->errors();
            $response_ar['status'] = false;
            $response_ar['info'] = $errors->first();
            $response_ar['title'] = AppServiceProvider::VALIDATION_ERROR_TITLE;
            return response()->json($response_ar);    
        }
                
        try {
            $ticket_support = new TicketSupport;
            $ticket_support->ticket_id = $request->get('id_token');
            $ticket_support->instructions = $request->get('cus_problemh');
            $ticket_support->reply_by = 'ADMIN';
            $ticket_support->ticket_status = $request->get('cus_status');
            $ticket_support->save();

            $ticket = Ticket::find($request->get('id_token'));
            $ticket->ticket_status = $request->get('cus_status');
            $ticket->save();

            try {
                $details = [
                    'title' => 'Support agents get in contact with the ticket',
                    'url' => url('/admin-ticket-status/'.$ticket->support_link_ref),
                    'support_link_ref' => $ticket->support_link_ref
                ];
                Mail::to($ticket->customer_email)->send(new CustomerEmail($details));
            } catch (\Throwable $th) {
                
            }

            $response_ar['status'] = true;
            $response_ar['info'] = null;
            $response_ar['title'] = "Reply Submitted";
            return response()->json($response_ar);

        } catch (\Throwable $th) {

            $response_ar['status'] = false;
            $response_ar['info'] = $th->getMessage();
            $response_ar['title'] = AppServiceProvider::ERROR_TITLE;
            return response()->json($response_ar);
       
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'cus_name' => 'Customer Name',
            'cus_problemh' => 'Reply',
            'cus_problem' => 'Problem',
            'cus_mobile' => 'Phone Number',
            'cus_email' => 'Email Address',
            'token' => "Token"
        ];
    }
}
