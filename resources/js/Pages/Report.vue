<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios'
import { computed, ref, watch, watchEffect } from 'vue';

const projects = ref([])
const project_select = ref(1)
const card_select = ref()

const getProjects = () => {
    axios.get('projects').then(response => {
        projects.value = response.data.projects
    }).catch(error => {
        console.log(error)
    })
}

const cards = computed(() => {
    if(projects.value.length > 0){
        let index = projects.value.findIndex(item => item.id == project_select.value)
        return projects.value[index].cards
    }
    
})
const project_current = computed(() => {
    if(projects.value.length > 0){
        let index = projects.value.findIndex(item => item.id == project_select.value)
        return projects.value[index]
    }
    
})

// при изменении проекта обнуляем карточки
watch(project_select, (newValue, oldValue) => {
    card_select.value = null
})
watchEffect([getProjects])
</script>

<template>
    <Head title="Dashboard2" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <label for="small" class="block mb-2 text-sm font-medium text-gray-900">Выберите проект</label>
                <select v-model="project_select" id="default" class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"> 
                    <option :value="project.id" v-for="project in projects"> 
                            {{ project.name }}
                        </option>
                </select>

                <div v-if="project_select">
                    <label for="small" class="block mb-2 text-sm font-medium text-gray-900">Выберите карточку</label>
                    <select v-model="card_select" id="default" class="bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option :value="card" v-for="card in cards"> 
                                {{ card.name }}
                            </option>
                    </select>
                    <ul v-if="card_select">
                        <li class="pb-3 sm:pb-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate ">
                                        На проект: <span class="text-xl font-medium">{{ project_current.name }}</span> 
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                                    {{ project_current.time }}
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul v-if="card_select">
                        По задачам:
                        <li class="pb-3 sm:pb-4" v-for="task in card_select.tasks">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate ">
                                        {{ task.text }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-900 ">
                                    {{ task.time }}
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
