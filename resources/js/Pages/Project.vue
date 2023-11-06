<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent } from 'vue'
import { VueDraggableNext } from 'vue-draggable-next'
import axios from 'axios'
export default defineComponent({
    components: {
        draggable: VueDraggableNext,
        AuthenticatedLayout: AuthenticatedLayout,
        Head: Head,
    },
    props: {
        project_id: {
            type: String
        }
    },
    data() {
        return {
            enabled: true,
            project: null,
            dragging: false,
            new_task_view: false,
            task_text: null,
            new_card_view: false,
            card_name: null
        }
    },
    methods: {
        log(event) {
            console.log(event)
        },
        getCards(){
            axios.get('projects/'+this.project_id).then(response => {
                this.project = response.data.project
            }).catch(error => {
                console.log(error)
            })
        },
        new_task_select(card_id){
            this.new_task_view = card_id
        },
        create_task(card_id){
            axios.post('tasks/create',{
                text: this.task_text,
                card_id: card_id
            }).then(response => {
                this.new_task_view = false
                this.getCards()
            }).catch(error => {
                console.log(error)
            })
        },
        completed_task(task_id){
            axios.patch('tasks/completed/'+task_id).then(response => {
                this.getCards()
            }).catch(error => {
                console.log(error)
            })
        },
        deleted_task(task_id){
            axios.delete('tasks/destroy/'+task_id).then(response => {
                this.getCards()
            }).catch(error => {
                console.log(error)
            })
        },
        start_time_task(task_id, time_status){
            axios.post('tasks/time/'+task_id,{
                time_status: !time_status
            }).then(response => {
                this.getCards()
            }).catch(error => {
                console.log(error)
            })
        },
        create_card(){
            if(this.card_name){
                axios.post('cards/create',{
                    project_id: this.project_id,
                    name: this.card_name
                }).then(response => {
                    this.new_card_view = false
                    this.getCards()
                }).catch(error => {
                    console.log(error)
                })
            }
        }
    },
    created(){
        this.getCards()
    },
    watch:{
        new_task_view(newVal, oldVal){
            this.task_text = null
        }
    }
})
</script>
<template>
    <Head title="Project" />
    
    <AuthenticatedLayout>
        <template #header>
                <h1 class="text-xl font-medium">{{project?.name}}</h1>
        </template>
        <div class="grid grid-cols-5">
            <div class="col-span-12 md:col-span-1" v-for="(card, key) in project?.cards" :key="key">
                <div class="max-w-lg m-3 bg-white p-3 rounded-xl shadow shadow-slate-300">
                    <div class="flex flex-row justify-between items-center">
                        <div>
                            <h1 class="text-xl font-medium">{{ card.name }}</h1>
                        </div>
                    </div>

                    <div id="tasks" class="my-1">
                        <draggable class="dragArea list-group w-full" :list="card?.tasks" @change="log">
                            <div
                                v-for="task in card?.tasks"
                                :key="task.id"
                            >
                                <div id="task" class="flex justify-between items-center border-b border-slate-200 py-3 px-2 border-l-4  border-l-transparent">
                                    <div class="inline-flex items-center space-x-2" v-if="task.status == 1">
                                        <div>
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                viewBox="0 0 24 24" 
                                                stroke-width="1.5" 
                                                stroke="currentColor" 
                                                class="w-6 h-6 text-slate-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>                          
                                        </div>
                                        <div class="text-slate-500 line-through">{{ task.text }}</div>
                                    </div>

                                    <div class="inline-flex items-center space-x-2" v-else>
                                        <div >
                                            <svg 
                                                @click="completed_task(task.id)" 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                viewBox="0 0 24 24" 
                                                stroke-width="1.5" 
                                                stroke="currentColor" 
                                                class="w-6 h-6 text-slate-500 hover:text-indigo-600 hover:cursor-pointer">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>

                                        </div>
                                        <div>{{ task.text }}</div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <svg 
                                            @click="start_time_task(task.id, task.time_status)" 
                                            title="Начать отчёт"
                                            v-if="task.time_status == 0 && task.status == 0"
                                            xmlns="http://www.w3.org/2000/svg" 
                                            width="16" 
                                            height="16" 
                                            fill="currentColor" 
                                            class="bi bi-clock w-4 h-4 text-slate-500 hover:text-slate-700 hover:cursor-pointer" 
                                            viewBox="0 0 16 16"> 
                                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/> <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/> 
                                        </svg>
                                        <svg 
                                            @click="start_time_task(task.id, task.time_status)" 
                                            title="Время считается"
                                            v-if="task.time_status == 1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16" 
                                            fill="currentColor" 
                                            class="bi bi-stopwatch-fill text-slate-500 hover:text-slate-700 hover:cursor-pointer" 
                                            viewBox="0 0 16 16"> <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0 5.29-11.584.531.531 0 0 0 .013-.012l.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1 0-.707.707l.354.354-.354.354a.717.717 0 0 0-.012.012A6.973 6.973 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1h-3zm2 5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0z"/> </svg>

                                        <svg @click="deleted_task(task.id)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-500 hover:text-slate-700 hover:cursor-pointer">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>                      
                                    </div>
                                </div>
                            </div>
                            
                        </draggable>

                        <!-- кнопка создания новой задачи -->
                        <div id="task" class="flex justify-center items-center pt-3" v-if="new_task_view != card.id">
                            <button 
                                @click="new_task_select(card.id)"  
                                type="button" 
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Создать задачу
                            </button>
                        
                        </div>

                        <!-- если активировано создани по id -->
                        <div id="task" class="justify-center items-center pt-3" v-if="new_task_view == card.id">
                            <textarea id="message" rows="2" v-model="task_text" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Новая задача..."></textarea>
                        
                            <div id="task" class="flex justify-around items-center pt-3">
                                <button 
                                    @click="new_task_view = false" 
                                    type="button" 
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                >
                                    Закрыть
                                </button>
                                <button 
                                    @click="create_task(card.id)" 
                                    type="button" 
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                >
                                    Создать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 md:col-span-1">
                <div class="max-w-lg m-3 bg-white p-3 rounded-xl shadow shadow-slate-300">
                    <div class="flex flex-row justify-between items-center" v-if="!new_card_view">
                        <div>
                            <h1 class="text-xl font-medium">Создать карточку</h1>
                        </div>
                        <div @click="new_card_view = true" class="inline-flex space-x-2 items-center">
                            <a href="#" class="p-2 border border-slate-200 rounded-md inline-flex space-x-1 items-center text-indigo-200 hover:text-white bg-indigo-600 hover:bg-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"> <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/> </svg>                  
                            </a>
                        </div>
                    </div>
                    <div  v-if="new_card_view">
                        <div>
                            <textarea 
                                id="message" 
                                rows="2"
                                 v-model="card_name" 
                                 class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                                 placeholder="Новая карточка..."
                            ></textarea>
                        </div>
                        <div id="task" class="flex justify-around items-center pt-3">
                            <button 
                                @click="new_card_view = false" 
                                type="button" 
                                class="px-3 py-2 text-xs font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                            >
                                Закрыть
                            </button>
                            <button 
                                @click="create_card" 
                                type="button" 
                                class="px-3 py-2 text-xs font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                            >
                                Создать
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>