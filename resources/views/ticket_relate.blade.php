@forelse ($relate_tickets as $relate_ticket)
<div class="alert alert-{{$relate_ticket->reply_by == 'ADMIN' ? 'info' : 'success'}}" role="alert">
    <h6 class="alert-heading">{{$relate_ticket->reply_by == 'ADMIN' ? 'Admin' : 'Customer'}} | {{$relate_ticket->created_at}}</h6>
    <p>{!!$relate_ticket->instructions!!}</p>
    <p class="mb-0 text-primary font-weight-bold">{{$relate_ticket->ticket_status}}</p>
</div>
@empty
    <div class="alert alert-danger" role="alert">
    No History Activity
    </div>
@endforelse