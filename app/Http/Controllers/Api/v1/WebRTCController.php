<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\CreateDialogOfferEvent;
use App\Events\DialogOfferAcceptedEvent;
use App\Events\DialogOfferRejectedEvent;
use App\Events\WebRtcAnswerEvent;
use App\Events\WebRtcCandidateEvent;
use App\Events\WebRtcOfferEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptDialogOfferRequest;
use App\Http\Requests\RejectDialogOfferRequest;
use App\Http\Requests\SendDialogOfferRequest;
use App\Http\Requests\WebRtcAnswer;
use App\Http\Requests\WebRtcCandidate;
use App\Http\Requests\WebRtcOffer;
use App\User;
use Illuminate\Support\Facades\Log;

class WebRTCController extends Controller
{
    public function sendDialogOffer(SendDialogOfferRequest $request)
    {
        $addresseeUser = User::where('login', $request['login'])->first();
        broadcast(new CreateDialogOfferEvent($addresseeUser->id, auth()->user()));
        return response()->json(['message' => 'Запрос отправлен']);
    }

    public function acceptDialogOffer(AcceptDialogOfferRequest $request)
    {
        $addresseeUser = User::where('login', $request['login'])->first();
        broadcast(new DialogOfferAcceptedEvent($addresseeUser->id, auth()->user()));
        return response()->json(['message' => 'Запрос принят']);
    }

    public function rejectDialogOffer(RejectDialogOfferRequest $request)
    {
        $addresseeUser = User::where('login', $request['login'])->first();
        broadcast(new DialogOfferRejectedEvent($addresseeUser->id));
        return response()->json(['message' => 'Запрос отклонен']);
    }

    public function webRtcOffer(WebRtcOffer $request)
    {
        broadcast(new WebRtcOfferEvent($request['toUserId'], $request['offer']));
    }

    public function webRtcAnswer(WebRtcAnswer $request)
    {
        broadcast(new WebRtcAnswerEvent($request['toUserId'], $request['answer']));
    }

    public function webRtcCandidate(WebRtcCandidate $request)
    {
        broadcast(new WebRtcCandidateEvent($request['toUserId'], $request['candidate']));
    }
}
