<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OpenAI ChatGPT</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background: #f0f0f0;
            display: grid;
            place-content: center;
            height: 100vh;
            height: 100dvh;
        }

        main {
            width: 400px;
            max-width: 100%;
            height: 70vh;

            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 8px;
            margin-bottom: 16px;

            overflow-y: auto;
        }

        ul {
            display: flex;
            flex-direction: column;
            list-style: none;
            padding: 0px;
        }

        .message {
            display: flex;
            flex-direction: column;
            margin: 4px 0;
            padding: 4px 0;

            > span {
                width: 36px;
                height: 36px;
                font-size: 12px;
                font-weight: 500;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 99px;

            }
            > p {
                border-radius: 4px;
                padding: 4px 8px;
                margin-top: 6px;
            }

            &.user {
                align-self: flex-end;
                align-items: flex-end;
                span, p {
                    background: rgb(198, 255, 220);
                }
            }

            &.bot {
                align-self: flex-start;
                span, p {
                    background: rgb(219, 236, 255);
                }
            }

        }

        form {
            display: flex;
            input {
                border-radius: 99px;
                flex-grow: 1;
                padding: 8px;
                margin-right: 8px;
                border: 1px solid #ccc;
            }
            button {
                background: #09f;
                border: 0;
                color: white;
                border-radius: 99px;
                cursor: pointer;
                padding: 8px;
                transition: background .3s ease;

                &[disabled] {
                    background: #ccc;
                    opacity: .6;
                    pointer-events: none;
                }

                &:hover {
                    background: rgb(0,104,173);
                }
            }
        }

    </style>
</head>
<body>

    <header>
        <h1>Amancio O.</h1>
        <h3>Tu frutero de confianza</h3>
    </header>

    <main class="container">
        <ul>
            <li class="message bot">
                <span>A.O.</span>
                <p>Hola! <br/> ¿ En que puedo ayudarte ?</p>
            </li>
        </ul>
    </main>

    <form method="POST">
        <input id="message" name="message" placeholder="Ask me..." />
        <button type="submit">Send</button>
    </form>

    <!-- component -->
    <template id="message-template">
        <li class="message">
            <span></span>
            <p></p>
        </li>
    </template>

    <script type="module">
        const $ = el => document.querySelector(el);

        const form = $('form');
        const input = $('form input');
        const template = $('#message-template');
        const messages = $('ul');
        const main = $('main');
        const button = $('button');

        const csrf = "{{ csrf_token() }}";

        const BOT = 'A.O.';
        const USER = 'Tú';

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const userMessage = input.value.trim();
            if (userMessage != '') {

                input.value = '';

                button.setAttribute('disabled', true);

                addMessage(userMessage, 'user');

                fetch('{{ route("ai")}}', {
                    headers: {
                        "Content-Type": "application/json"
                    },
                    method: "POST",
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        message: userMessage})
                })
                .then((r)=> {
                    return r.json();
                })
                .then((d) => {
                    addMessage(d.respuesta, 'bot');
                    button.removeAttribute('disabled');
                })
                .catch((e) => {console.log(e)});

            }
        });

        // sender = bot | user
        function addMessage(message, sender) {
            // Clonar el template
            const cloneTemplate = template.content.cloneNode(true);
            const newMessage = cloneTemplate.querySelector('.message');

            const who = newMessage.querySelector('span');
            const text = newMessage.querySelector('p');

            text.textContent = message;
            who.textContent = sender == 'bot' ? BOT : USER;
            newMessage.classList.add(sender);

            messages.appendChild(newMessage);

            // Actualizar el scroll
            main.scrollTop = main.scrollHeight;
        }
    </script>
</body>
</html>
