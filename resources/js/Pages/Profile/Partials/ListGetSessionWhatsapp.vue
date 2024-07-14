<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const sessions = ref([]);
const form = useForm({
    password: '',
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const confirmLogout = () => {
    confirmingLogout.value = true;
    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;
    form.reset();
};

// Fetch WhatsApp sessions
const fetchSessions = async () => {
    try {
        const response = await axios.get(route('whatsapp-sessions.list'));
        if (response.data.success) {
            sessions.value = response.data.sessions;
        }
    } catch (error) {
        console.error('Error fetching sessions:', error);
    }
};

onMounted(() => {
    fetchSessions();
});
</script>

<template>
    <ActionSection>
        <template #title>
            Sesiones de WhatsApp
        </template>

        <template #description>
            Gestiona tus sesiones de WhatsApp conectadas.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Si es necesario, puedes cerrar sesión en todas tus demás sesiones de WhatsApp en todos tus dispositivos.
                Algunas de tus sesiones recientes están listadas a continuación; sin embargo, esta lista puede no ser
                exhaustiva. Si sientes que tu cuenta ha sido comprometida, también deberías actualizar tu contraseña.
            </div>

            <div v-if="sessions.length > 0" class="mt-5 space-y-4">
                <div v-for="(session, i) in sessions" :key="i"
                    class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <div class="flex-1 ml-3">
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ session.id }}
                        </div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Estado:
                            <span v-if="session.status === 'AUTHENTICATED'"
                                class="font-semibold text-green-600">Autenticado</span>
                            <span v-else class="font-semibold text-blue-600">Conectado</span>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <img src="https://static.whatsapp.net/rsrc.php/yZ/r/JvsnINJ2CZv.svg" alt="">
                        <!-- FALTA AGREGAR ESTO -->
                        <!-- <PrimaryButton @click="disconnectSession(session.id)"
                            class="text-white bg-red-600 hover:bg-red-700">
                            Desconectar
                        </PrimaryButton> -->
                    </div>
                </div>
            </div>

            <div v-else class="mt-5">
                <p class="text-sm text-gray-600 dark:text-gray-400">No hay sesiones activas de WhatsApp.</p>
            </div>

            <div class="flex items-center mt-5">
                <PrimaryButton @click="confirmLogout">
                    Cerrar todas las sesiones
                </PrimaryButton>

                <ActionMessage :on="form.recentlySuccessful" class="ml-3">
                    Listo.
                </ActionMessage>
            </div>

            <!-- Log Out Other Devices Confirmation Modal -->
            <DialogModal :show="confirmingLogout" @close="closeModal">
                <template #title>
                    Cerrar sesión en otros dispositivos
                </template>

                <template #content>
                    Por favor, introduce tu contraseña para confirmar que deseas cerrar sesión en tus otros
                    dispositivos.

                    <div class="mt-4">
                        <TextInput ref="passwordInput" v-model="form.password" type="password" class="block w-3/4 mt-1"
                            placeholder="Contraseña" autocomplete="current-password"
                            @keyup.enter="logoutOtherBrowserSessions" />

                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal">
                        Cancelar
                    </SecondaryButton>

                    <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        @click="logoutOtherBrowserSessions">
                        Cerrar sesión en otros dispositivos
                    </PrimaryButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>

<style scoped>
/* Agrega tus estilos personalizados aquí */
</style>
