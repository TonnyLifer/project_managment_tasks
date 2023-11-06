<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios'
import { ref, watch, watchEffect } from 'vue';

const projects = ref([])
const new_project_view = ref(false)
const new_project = ref(null)
const select_project = ref({id: null, name: null})

const getProjects = () => {
    axios.get('projects').then(response => {
        projects.value = response.data.projects
    }).catch(error => {
        console.log(error)
    })
}

const create_project = () => {
    if(new_project.value){
        axios.post('projects/create', {
            name: new_project.value
        }).then(response => {
            new_project.value = null
            new_project_view.value = false
            if(response.data.message){
                getProjects()
            }
        }).catch(error => {
            console.log(error)
        })
    }
}

const update_select_view = item => {
    select_project.value.id = item.id
    select_project.value.name = item.name
}

const delete_project = id => {
    axios.delete('projects/destroy/'+id).then(response => {
        getProjects()
    }).catch(error => {
        console.log(error)
    })
}

const update_project = item => {
    axios.patch('projects/update/'+item.id,{
        name: select_project.value.name
    }).then(response => {
        select_project.value.id = null
        getProjects()
    }).catch(error => {
        console.log(error)
    })
}

watchEffect([getProjects])
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="grid grid-cols-6">
            <div class="col-span-12 md:col-span-1" v-for="(item, key) in projects" :key="key">
                <div class="relative mt-6 flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md m-3">
                    <div class="p-6" v-if="select_project?.id != item.id">
                        <h5 class="mb-2 block font-sans text-xl font-semibold leading-snug tracking-normal text-blue-gray-900 antialiased">
                            {{ item.name }}
                        </h5>
                    </div>
                    <div class="p-6" v-if="select_project?.id == item.id">
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            id="username" 
                            type="text" 
                            v-model="select_project.name"
                            placeholder="Изменить проект"
                            >
                    </div>
                    <div class="p-6 pt-0 grid grid-cols-2 gap-4" v-if="select_project?.id != item.id">
                        <a class="!font-medium !text-blue-gray-900 !transition-colors hover:!text-pink-500" :href="route('project', { id: item.id })" :active="route().current('project')">
                            <button
                                class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-pink-500 transition-all hover:bg-pink-500/10 active:bg-pink-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                                data-ripple-dark="true"
                                
                            >
                                Просмотр
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    aria-hidden="true"
                                    class="h-4 w-4"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"
                                    ></path>
                                </svg>
                            </button>
                        </a>
                        <a class="!font-medium !text-blue-gray-900 !transition-colors hover:!text-pink-500">
                            <button
                                @click="update_select_view(item)"
                                class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-blue-500 transition-all hover:bg-blue-500/10 active:bg-blue-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                                data-ripple-dark="true"
                            >
                                Изменить
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"> <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/> </svg>
                            </button>
                        </a>
                    </div>
                    <div class="pl-3 pb-6 grid grid-cols-4 gap-12" v-else>
                        <a class="!font-medium !text-red-gray-900 !transition-colors hover:!text-red-500">
                            <button
                                @click="delete_project(item.id)"
                                class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-red-500 transition-all hover:bg-red-500/10 active:bg-red-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                                data-ripple-dark="true"
                            >
                                Удалить
                            </button>
                        </a>
                        <a class="!font-medium !text-blue-gray-900 !transition-colors hover:!text-pink-500">
                            <button
                                @click="select_project.id = null"
                                class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-blue-500 transition-all hover:bg-blue-500/10 active:bg-blue-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                                data-ripple-dark="true"
                            >
                                Закрыть
                            </button>
                        </a>
                        <a class="!font-medium !text-blue-gray-900 !transition-colors hover:!text-pink-500">
                            <button
                                @click="update_project(item)"
                                class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-green-500 transition-all hover:bg-green-500/10 active:bg-green-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                                data-ripple-dark="true"
                            >
                                Сохранить
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-span-12 md:col-span-1">
                <div class="relative mt-6 flex flex-col rounded-xl bg-white bg-clip-border text-gray-700 shadow-md m-3">
                    <div class="p-6">
                        <h5 v-if="!new_project_view" class="mb-2 block font-sans text-xl font-semibold leading-snug tracking-normal text-blue-gray-900 antialiased">
                            Создать новый проект
                        </h5>
                        <input v-else v-model="new_project" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Новый проект">
                    </div>
                    <div class="p-6 pt-0">
                        <a class="!font-medium !text-blue-gray-900 !transition-colors hover:!text-indigo-500 grid grid-cols-2 gap-4">
                            <button
                                class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-indigo-500 transition-all hover:bg-indigo-500/10 active:bg-indigo-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                                data-ripple-dark="true"
                                @click="new_project_view ? create_project() : new_project_view = true"
                            >
                                Создать
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"> <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/> </svg>
                            </button>

                            <button
                                class="flex select-none items-center gap-2 rounded-lg py-2 px-4 text-center align-middle font-sans text-xs font-bold uppercase text-red-500 transition-all hover:bg-red-500/10 active:bg-red-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                type="button"
                                data-ripple-dark="true"
                                v-if="new_project_view"
                                @click="new_project_view = false"
                            >
                                Закрыть
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"> <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/> </svg>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
