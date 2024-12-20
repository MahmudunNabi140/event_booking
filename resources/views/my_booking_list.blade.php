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
          <table class="table table-primary">
            <thead>
              <tr>
                <th scope="col">SL</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">Location</th>
                <th scope="col">Available Seats</th>
                <th scope="col">Book Seets</th>
                <th scope="col">Price</th>
              </tr>
            </thead>
            <tbody>
              @foreach ( $events as $key =>$event )
              <tr>
                <th scope="row">{{$key+1}}</th>
                <td>{{$event['event']->name}}</td>
                <td>{{$event['event']->description}}</td>
                <td>{{$event['event']->date}}</td>
                <td>{{$event['event']->time}}</td>
                <td>{{$event['event']->location}}</td>
                <td>{{$event['event']->available_seats}}</td>
                <td>{{$event->seats}}</td>
                <td>{{$event['event']->price}}</td>


              </tr>
              @endforeach
           
        
            </tbody>
          </table>
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
