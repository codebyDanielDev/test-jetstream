<template>
    <ActionSection>
        <template #title>
            Conectar con WhatsApp
        </template>

        <template #description>
            Conecte con WhatsApp para el envío de notificaciones.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400" v-if="!isConnected">
                Conecte su cuenta de WhatsApp para recibir notificaciones directamente en su aplicación.
            </div>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400" v-else>
                Actualmente conectado a WhatsApp. Si desconecta su cuenta, no podrá enviar mensajes de WhatsApp ni emitir notificaciones.
            </div>

            <div class="mt-5">
                <PrimaryButton v-if="!isConnected" @click="connectWhatsApp" class="text-white bg-red-600 hover:bg-red-700">
                    Conectar
                </PrimaryButton>
                <DangerButton v-if="isConnected" @click="disconnectWhatsApp" class="text-white bg-gray-600 hover:bg-gray-700">
                    Desconectar
                </DangerButton>
            </div>

            <DialogModal :show="showModal" @close="closeModal">
                <template #title>
                    Conectar con WhatsApp
                </template>

                <template #content>
                    <p class="text-gray-600 dark:text-gray-400">
                        Para conectar su cuenta de WhatsApp, escanee el código QR a continuación usando la aplicación de WhatsApp en su teléfono.
                    </p>
                    <div class="flex justify-center mt-4">
                        <div v-if="loading" class="flex items-center justify-center w-48 h-48 border-2 border-red-600">
                            <svg class="w-8 h-8 text-gray-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <img v-if="!loading && qrCode" :src="qrCode" alt="Código QR de WhatsApp" class="w-48 h-48 border-2 border-red-600">
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal" class="mr-2 text-gray-800 bg-gray-200 hover:bg-gray-300">
                        Cancelar
                    </SecondaryButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import apiClient from 'axios';
import ActionSection from '@/Components/ActionSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const showModal = ref(false);
const isConnected = ref(false);
const qrCode = ref(null);
const loading = ref(false);
let pollInterval = null;

const connectWhatsApp = async () => {
    try {
        loading.value = true;
        const response = await apiClient.post('/sessions/add', { sessionId: 'user@example.com' });
        if (response.data.qr) {
            qrCode.value = response.data.qr;
            showModal.value = true;
            loading.value = false;
            pollSessionStatus();
        }
    } catch (error) {
        console.error('Error connecting to WhatsApp:', error);
        loading.value = false;
    }
};

const disconnectWhatsApp = async () => {
    try {
        await apiClient.delete('/sessions/delete');
        isConnected.value = false;
    } catch (error) {
        console.error('Error disconnecting from WhatsApp:', error);
    }
};

const closeModal = () => {
    showModal.value = false;
};

const checkSessionStatus = async () => {
    try {
        const response = await apiClient.get('/sessions/status');
        if (response.data.status === 'AUTHENTICATED') {
            isConnected.value = true;
        } else {
            isConnected.value = false;
        }
    } catch (error) {
        console.error('Error checking session status:', error);
        isConnected.value = false;
    }
};

const pollSessionStatus = () => {
    pollInterval = setInterval(async () => {
        await checkSessionStatus();
        if (isConnected.value) {
            clearInterval(pollInterval);
            closeModal();
        }
    }, 3000);
};

onMounted(() => {
    checkSessionStatus();
    pollInterval = setInterval(checkSessionStatus, 5000); // Poll every 5 seconds
});

onUnmounted(() => {
    clearInterval(pollInterval);
});
</script>

<style scoped>
/* Agrega tus estilos personalizados aquí */
</style>
