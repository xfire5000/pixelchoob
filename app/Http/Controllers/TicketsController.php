<?php

namespace App\Http\Controllers;

use App\Models\ListCase;
use Coderflex\LaravelTicket\Models\Message;
use Coderflex\LaravelTicket\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! isset($_GET['lid'])) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        $ticket = Ticket::where('list_case_id', $_GET['lid'])->first();
        if ($ticket instanceof Ticket) {
            if (isset($_GET['s'])) {
                $messages = $ticket->messages()->with('user')->where('message', 'like', "%{$_GET['s']}%")
                    ->latest()->paginate(10);
            } else {
                $ticket->messages()->whereNot('user_id', auth()->id())->whereColumn('created_at', 'updated_at')->touch();
                $messages = $ticket->messages()->with('user')->latest()->paginate(10);
            }
        } else {
            $messages = null;
        }

        return response($messages);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['message' => 'required', 'list_case_id' => 'required']);
        $ticket = Ticket::where('list_case_id', (int) $request->input('list_case_id'))->first();
        if (is_null($ticket)) {
            $listCase = ListCase::find((int) $request->input('list_case_id'));
            $ticket = Ticket::create([
                'title' => $listCase->title,
                'user_id' => auth()->id(),
                'priority' => 'medium',
                'list_case_id' => $listCase->id,
                'assigned_to' => auth()->id() == $listCase->user_id ? $listCase->author_id : $listCase->user_id,
            ]);
        }
        $message = Message::create([
            'message' => $request->input('message'),
            'user_id' => auth()->id(),
            'ticket_id' => $ticket->id,
        ]);
        $message['user'] = auth()->user();

        return response($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
