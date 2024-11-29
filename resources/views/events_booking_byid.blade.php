<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Event Booking!</title>
  </head>
  <body>
<div class="container">
    <div class="row">
        <div class="col-md-2 mt-5">
           
            <div> <a href="{{route('frontend.events')}}">Events</a></div>
            <div> <a href="{{route('frontend.mybookinglist')}}">MyBookihgList</a></div>
            <div><a href="{{route('frontend.userLogout')}}">Logout</a></div>
           
            
        </div>
        <div class="col-md-10">
          <div class="container mt-5">
            <h2 class="mb-4">Event Booking</h2>
        
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        
            <!-- Booking Form -->
            <form action="{{route('frontend.store')}}" method="POST">
                @csrf
            
                <div class="mb-3">
                    <label for="event_id" class="form-label">Event</label>
                    <!-- Display event details as read-only -->
                    <input 
                        type="text" 
                        class="form-control" 
                        id="event_details" 
                        value="{{ $event->name }} ({{ $event->date }} at {{ $event->time }})" 
                        readonly
                    >
                    <!-- Hidden input to pass the event ID -->
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                </div>
            
                <div class="mb-3">
                    <label for="seats" class="form-label">Number of Seats</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="seats" 
                        name="seats" 
                        min="1" 
                        max="{{ $event->available_seats }}" 
                        placeholder="Enter number of seats" 
                        required
                    >
                    <small class="text-muted">Available seats: {{ $event->available_seats }}</small>
                </div>
            
                <button type="submit" class="btn btn-primary">Book Now</button>
            </form>
            
        </div>
        </div>
    </div>
</div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
