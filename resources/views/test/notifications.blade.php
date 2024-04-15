<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('323996d4cfab0016889a', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('notification-channel');
        channel.bind('notification-event', function (data) {
            var div = document.getElementById('notifications_list');
            div.innerHTML += '<p>'+JSON.stringify(data)+'</p>';
            console.log(JSON.stringify(data));
            console.log(data.notifiable_id);
        });
    </script>
</head>
<body>
<h1 style="background: #000000">
    <span style="color: red">&emsp;ID : {{auth()->user()->id}}</span>
    <span style="color: white">&emsp; {{auth()->user()->email}}</span>
    <span style="color: #0b5ed7">&emsp; {{auth()->user()->profile_type->name}}</span>
</h1>
<div id="notifications_list">

</div>
</body>
