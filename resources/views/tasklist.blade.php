@extends('layouts.vueapp')
@section('title')
Laravel Vue2App
@endsection
@section('css')
    <style>
        .red {
            background-color: red;
        }
        .blue {
            background-color: blue;
        }
        li {
            position: relative;
            padding-bottom: 10px;
        }
        li button.pull-left {
            position: absolute;
            left:-2rem;
            top:0;
        }
    </style>
@append
@section('body')
    <div id="root">
        <h1>Geigers Vue2 Task List Demo</h1>
        <input type="text" id="inputTask" v-model="newTask" @keyup.enter="addTask"/>
        <button class="btn btn-large btn-info" @click="addTask">Add Task</button>
        <h2>All Tasks</h2>
        <task-list :tasks="tasks" @delete="deleteTask"></task-list>

        <h2>Incomplete Tasks</h2>
        <incomplete-task-list :incomtasks="incomTasks" @complete="completeTask"></incomplete-task-list>

        <h2>Complete Tasks</h2>
        <complete-task-list :comtasks="comTasks" @incomplete="incompleteTask"></complete-task-list>
    </div>
@endsection
@section('js')
    <script>
        Vue.component('task-list', {
           template: `
            <ul>
                <li v-for="(task, index) in tasks" :task="task" :key="index">
                    @{{ task.descr }}
                    <button class="btn btn-sm btn-danger pull-left" @click="deleteTask(task)">X</button>
                </li>
            </ul>
            `,
            mounted() {
             //  console.log(this.$children);
            },
            props: {
               tasks: Array
            },
            methods: {
                deleteTask(task) {
                    this.$emit('delete', task);
                }
            }
        });

        Vue.component('incomplete-task-list', {
            template: `
                <ul>
                    <li v-for="(incomtask, index) in incomtasks"
                    :incomtask="incomtask"
                    :key="index">
                        @{{ incomtask.descr }}
                        <button  class="btn btn-sm btn-primary pull-left"
                        @click="completeTask(incomtask)"><i class="far fa-square"></i></button>
                    </li>
                </ul>
                `,
            props: {
                incomtasks: Array
            },
            methods: {
                completeTask(incomtask) {
                    this.$emit('complete', incomtask);
                }
            }
        });

        Vue.component('complete-task-list', {
            template: `
                <ul>
                    <li v-for="(comtask, index) in comtasks" :comtask="comtask" :key="index">
                        @{{ comtask.descr }}
                        <button  class="btn btn-sm btn-success pull-left"
                        @click="incompleteTask(comtask)"><i class="far fa-check-square"></i></button>
                    </li>
                </ul>
                `,
            props: {
                comtasks: Array
            },
            methods: {
                incompleteTask(comtask) {
                    this.$emit('incomplete', comtask);
                }
            }
        });

        new Vue({
            el: '#root',
            data: {
                tasks: [],
                errors: [],
                newTask: '',
            },
            mounted() {
                // make ajax request
                this.getData();
            },
            methods: {
                getData() {
                    axios.get('/taskdata').then(response => this.tasks = response.data);
                },
                addTask() {
                    var newTaskArray = {descr: this.newTask, completed: false};
                    req = axios.post('/task', newTaskArray)
                    //    .then(response => alert(response.data.message))
                        .catch(error => this.errors = error.response.data);
                    this.newTask = '';
                    req.then(this.getData);
                },
                completeTask(task) {
                    task.completed = true;
                    this.updateTask(task);
                },
                incompleteTask(task) {
                    task.completed = false;
                    this.updateTask(task);
                },
                updateTask(task) {
                    req = axios({
                        method: 'PATCH',
                        url: '/task/'+task.id+'/',
                        data: task
                    })
                    //    .then(this.getData())
                        .catch(error => this.errors = error.response.data);
                    req.then(this.getData);
                },
                deleteTask(task) {
                    req = axios({
                        method: 'DELETE',
                        url: '/task/'+task.id+'/',
                        data: task
                    })
                    //    .then(response => alert(response.data.message))
                        .catch(error => this.errors = error.response.data);
                    req.then(this.getData);
                }
            },
            computed: {
                incomTasks() {
                    return this.tasks.filter(task => ! task.completed);
                },
                comTasks() {
                    return this.tasks.filter(task => task.completed);
                }
            }
        });
    </script>
@append
