<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Providers\AppServiceProvider;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Models\TicketSupport;
use Mail;
use App\Mail\CustomerEmail;

class TicketController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'cus_name' => ['required','min:2'],
            'cus_problem' => ['required','min:10'],
            'cus_email' => ['required','email:rfc,dns'],
            'cus_mobile' => ['required','min:10'],
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
            $ticket = new Ticket;
            $ticket->customer_name = $request->get('cus_name');
            $ticket->customer_email = $request->get('cus_email');
            $ticket->customer_mobile = $request->get('cus_mobile');
            $ticket->customer_problem = $request->get('cus_problem');
            $ticket->support_link_ref = Str::random(20);
            $ticket->ticket_status =AppServiceProvider::TICKET_STATUS[0];
            $ticket->save();

            $link_email = url('/ticket-status/'.$ticket->support_link_ref);

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
            $response_ar['link'] = '<a target="_blank" href="'.$link_email.'">'.$link_email.'</a>';
            $response_ar['title'] = AppServiceProvider::TICKET_OK_TITLE;
            return response()->json($response_ar);

        } catch (\Throwable $th) {

            $response_ar['status'] = false;
            $response_ar['info'] = $th->getMessage();
            $response_ar['title'] = AppServiceProvider::ERROR_TITLE;
            return response()->json($response_ar);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request , string $token)
    {
        $validator = Validator::make($request->all(),[
            'token' => ['required']
        ],[],$this->attributes());

        $ticket = Ticket::where([
            'support_link_ref' => $token,
        ])->first();
        if(empty($ticket)){
            abort(404,'go back to home');
        }

        return view('ticket', [
            'ticket' => $ticket,
        ]);
    }

     /**
     * Display the specified resource.
     */
    public function show_relate_tickets(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_token' => ['required']
        ],[],$this->attributes());

        $relate_tickets = TicketSupport::where([
            'ticket_id' => $request->get('id_token'),
        ])->orderBy('id' , 'desc')
        ->get();
        if(empty($relate_tickets)){
            return [];
        }

        return view('ticket_relate', [
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
            $ticket_support->reply_by = 'CUSTOMER';
            $ticket_support->save();

            $ticket = Ticket::find($request->get('id_token'));
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
            $response_ar['title'] = AppServiceProvider::REPLY_OK_TITLE;
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
