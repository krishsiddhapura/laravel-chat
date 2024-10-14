<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat Room</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
<section class="msger">
    <header class="msger-header">
        <div class="msger-header-title">
            <i class="fas fa-comment-alt"></i> Slack Light
        </div>
        <div class="msger-header-options">
            <span><i class="fas fa-cog"></i></span>
        </div>
    </header>

    <main class="msger-chat"></main>

    <form class="msger-inputarea" id="messageForm">
        <input type="text" id="msgInput" class="msger-input" placeholder="Enter your message...">
        <button type="submit" class="msger-send-btn" id="send">Send</button>
    </form>
</section>
<script src="{{asset('assets/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script>
    $(document).ready(function (){
        // WebSocket client connection with token
        const socket = new WebSocket('ws://192.168.100.146:5252?token={{auth()->guard('web')->user()->remember_token}}');

        $("#messageForm").validate({
            submitHandler: function (form,e){
                e.preventDefault();
                let message = $("#msgInput").val();
                $(".msger-chat").append(`<div class="msg right-msg">
                                        <div class="msg-img" style="background-image: url({{asset('assets/images/avtar/female/'.rand(1,10).'.jpg')}})" ></div>
                                        <div class="msg-bubble">
                                            <div class="msg-info">
                                                <div class="msg-info-name">Sajad</div>
                                                <div class="msg-info-time">12:46</div>
                                            </div>
                                            <div class="msg-text">`+message+`</div>
                                        </div>
                                    </div>`);
                const data = {
                    type: "broadcast",
                    message: message,
                };
                socket.send(JSON.stringify(data));

                var chatContainer = $('.msger-chat');
                chatContainer.scrollTop(chatContainer.prop('scrollHeight'));

                $("#messageForm").trigger('reset');
            }
        });


        // ON CONNECTION OPEN
        socket.addEventListener('open', (event) => {
            console.log('WebSocket connection opened');
        });

        // ON MESSAGE EVENT
        socket.addEventListener('message', (event) => {
            data = $.parseJSON(event.data);
            message = data.message;
            $(".msger-chat").append(`<div class="msg left-msg">
                                        <div class="msg-img" style="background-image: url({{asset('assets/images/avtar/male/'.rand(1,10).'.jpg')}})" ></div>
                                        <div class="msg-bubble">
                                            <div class="msg-info">
                                                <div class="msg-info-name">BOT</div>
                                                <div class="msg-info-time">12:45</div>
                                            </div>
                                            <div class="msg-text">`+message+`</div>
                                        </div>
                                    </div>`);

            var chatContainer = $('.msger-chat');
            chatContainer.scrollTop(chatContainer.prop('scrollHeight'));
        });

        // CONNECTION CLOSE
        socket.addEventListener('close', (event) => {
            console.log('WebSocket connection closed');
        });
    });
</script>
</body>
</html>
