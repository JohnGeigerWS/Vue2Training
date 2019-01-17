@extends('layouts.vueapp')
@section('title', 'Laravel Chat App')
@section('css')
    <style>
        #chat-box .list-group {
            overflow-y: scroll;
            height: 20rem;
            border: 1px solid black;
        }
        #chat-box input{
            width:90%;
        }
    </style>
@append
@section('body')
    <div id="chat">
        <div class="row">
            <div id="chat-box" class="offset-4 col-4">
                <li class="list-group-item active">Chat Room</li>
                <div class="badge badge-pill badge-primary">@{{ typing }}</div>
                <ul class="list-group" v-chat-scroll>
                    <message
                            v-for="(value, index) in chat.message"
                            :key = value.index
                            :user = chat.user[index]
                            :color = chat.color[index]
                            :time = chat.time[index]
                    >@{{ value }}</message>
                </ul>
                <input type="text"
                       v-model="message"
                       @keyup.enter="send"
                       placeholder="Please Type your Message..."
                />
                <button class="btn btn-sm btn-success" @click="send">Send</button>
            </div>
            <div id="user-list" class="col-4">
                <li class="list-group-item active">Chat Users (@{{ numberOfUsers }})</li>
                <ul class="list-group">
                    <li class="list-group-item"
                        v-for="(user, index) in userlist"
                        :key = user.index
                        v-text="user.user.name"
                    ></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        new Vue({
            el: '#chat',
            data: {
                userlist: [],
                numberOfUsers: 0,
                message: '',
                typing: '',
                chat: {
                    message:[],
                    user:[],
                    color:[],
                    time: []
                },
                errors: [],
            },
            watch: {
                message() {
                    Echo.private('chat')
                        .whisper('typing', {
                            text: this.message,
                            name: '{{ $user->name }}'
                        });
                }
            },
            methods: {
                send() {
                    if (this.message.length != 0) {
                        this.chat.message.push(this.message);
                        this.chat.user.push({name: 'You'});
                        this.chat.color.push('success');
                        this.chat.time.push(this.getTime());
                        res = axios.post('/chat', {
                            message: this.message,
                            chat: this.chat
                        })
                            .then(response => console.log(response))
                            .catch(error => console.log(error));

                        res.then(this.message = '');

                    }
                },
                getTime() {
                    let time = new Date();
                    return time.getHours() + ':' + time.getMinutes();
                },
                getOldMessages() {
                    axios.post('/getOldMessages')
                        .then(response => {
                            console.log('get old messages result');
                            console.log(response);
                            if (response.data !== '') {
                                this.chat = response.data;
                            }
                        })
                        .catch(error => {
                            console.log('get old messages error:');
                            console.log(error);
                        })
                }
            },
            mounted() {
                this.getOldMessages();
                let _this = this;
                Echo.private('chat')
                    .listen('.GeigerChatEvent', (e) => {
                        this.chat.message.push(e.message)
                        this.chat.user.push(e.user)
                        this.chat.color.push('warning')
                        this.chat.time.push(this.getTime())
                        axios.post('/saveToSession', {
                            chat: this.chat
                        })
                            .then(response => {
                                console.log(response);
                            })
                            .catch(error => {
                                console.log('Sate to Session Error:');
                                console.log(error);
                            });
                    })
                    .listenForWhisper('typing', (e) => {
                        if (e.text !== '') {
                            if (e.name !== '') {
                                this.typing = e.name + ' is typing...';
                            } else {
                                this.typing = 'typing...'
                            }
                        } else {
                            this.typing = ''
                        }

                        // remove is typing indicator after 0.9s
                        setTimeout(function () {
                            _this.typing = ''
                        }, 900);
                    });
                Echo.join('chat')
                    .here((users) => {
                        this.numberOfUsers = users.length;
                        this.userlist = users;
                    })
                    .joining((user) => {
                        this.numberOfUsers += 1;
                        this.$toaster.success(user.user.name + ' has joined the chat');
                        this.userlist.push(user);

                    })
                    .leaving((user) => {
                        this.numberOfUsers -= 1;
                        this.$toaster.warning(user.user.name + ' has left the chat');
                        this.userlist = this.userlist.filter(function(value){
                            return value.user.name !== user.user.name;
                        });
                    })
                ;
            },
            computed: {
                // computed stuff
            }
        });
    </script>
@append
