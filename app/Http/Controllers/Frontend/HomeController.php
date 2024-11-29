<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
        
        return view('welcome');
    }
    public function home()
    {
        
        return view('home');
    }
    public function events()
    {
        $events=Event::all();
        return view('event',compact('events'));
    }
    public function eventsBookingbyid($id)
    {

        $event=Event::find($id);
        return view('events_booking_byid',compact('event'));
    }


    public function store(Request $request)
    {
        $user_id = auth()->id();
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seats' => 'required|integer|min:1',
        ]);

        // Check seat availability
        $event = Event::findOrFail($request->event_id);
        if ($request->seats > $event->available_seats) {
            return back()->withErrors(['seats' => 'Not enough seats available.']);
        }

        // Save the booking
        Booking::create([
            'event_id' => $request->event_id,
            'user_id' => $user_id,
            'seats' => $request->seats,
        ]);

        $event->decrement('available_seats', $request->seats);

        return redirect()->route('frontend.mybookinglist')->with('success', 'Booking successful!');
    }

    public function myBookingList()
    {
        $user_id = auth()->id();
        if ($user_id) {
            $events = Booking::with(['user', 'event'])
            ->where('user_id', $user_id)
            ->get();
            return view('my_booking_list',compact('events'));
        }
       
    }



    public function Userlogin(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    $user = User::where('email', $request->email)->first();
    if ($user) {
        if (Hash::check($request->password, $user->password)) {
            Auth::guard('web')->login($user);
    
            $request->session()->regenerate();
    
            return redirect()->route('frontend.home');
        } else {
            dd('Password mismatch', $request->password, $user->password);
        }
    }
    
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    function userLogout()
    {

        auth()->logout();

        session()->flush('message', "Successfully Logged Out.");

        return redirect()->route('login');
    }


}
