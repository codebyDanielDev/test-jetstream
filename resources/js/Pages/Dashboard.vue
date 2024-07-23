<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

const message = ref('');

const broadcastEvent = async () => {
    await axios.get('/broadcast-test');
};

onMounted(() => {
    window.Echo.private('test-channel')
        .listen('TestEvent', (e) => {
            message.value = e.message;
        });
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <button @click="broadcastEvent" class="px-4 py-2 text-white bg-blue-500 rounded">
                            Emitir Evento
                        </button>
                        <p v-if="message">{{ message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
