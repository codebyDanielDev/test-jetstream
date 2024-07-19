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
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

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
            {{ t('profile.sessions_whatsapp.title') }}
        </template>

        <template #description>
            {{ t('profile.sessions_whatsapp.description') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                {{ t('profile.sessions_whatsapp.content') }}
            </div>

            <div v-if="sessions.length > 0" class="mt-5 space-y-4">
                <div v-for="(session, i) in sessions" :key="i"
                    class="flex items-center p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                    <div class="flex-1 ml-3">
                        <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ session.id }}
                        </div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ t('profile.sessions_whatsapp.status') }}:
                            <span v-if="session.status === 'AUTHENTICATED'"
                                class="font-semibold text-green-600">{{ t('profile.sessions_whatsapp.connected') }}</span>
                            <span v-else class="font-semibold text-blue-600">{{ t('profile.sessions_whatsapp.connected') }}</span>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <img src="https://static.whatsapp.net/rsrc.php/yZ/r/JvsnINJ2CZv.svg" alt="">
                        <!-- FALTA AGREGAR ESTO -->
                        <!-- <PrimaryButton @click="disconnectSession(session.id)"
                            class="text-white bg-red-600 hover:bg-red-700">
                            {{ t('profile.sessions_whatsapp.disconnect') }}
                        </PrimaryButton> -->
                    </div>
                </div>
            </div>

            <div v-else class="mt-5">
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ t('profile.sessions_whatsapp.no_sessions') }}</p>
            </div>

            <div class="flex items-center mt-5">
                <PrimaryButton @click="confirmLogout">
                    {{ t('profile.sessions_whatsapp.log_out_other_browser_sessions') }}
                </PrimaryButton>

                <ActionMessage :on="form.recentlySuccessful" class="ml-3">
                    {{ t('profile.logout_other_browser_sessions.done') }}
                </ActionMessage>
            </div>

            <!-- Log Out Other Devices Confirmation Modal -->
            <DialogModal :show="confirmingLogout" @close="closeModal">
                <template #title>
                    {{ t('profile.sessions_whatsapp.log_out_other_browser_sessions') }}
                </template>

                <template #content>
                    {{ t('profile.logout_other_browser_sessions.enter_password') }}

                    <div class="mt-4">
                        <TextInput ref="passwordInput" v-model="form.password" type="password" class="block w-3/4 mt-1"
                            placeholder="{{ t('profile.logout_other_browser_sessions.password') }}" autocomplete="current-password"
                            @keyup.enter="logoutOtherBrowserSessions" />

                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal">
                        {{ t('profile.logout_other_browser_sessions.cancel') }}
                    </SecondaryButton>

                    <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        @click="logoutOtherBrowserSessions">
                        {{ t('profile.sessions_whatsapp.log_out_other_browser_sessions') }}
                    </PrimaryButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>

<style scoped>
/* Agrega tus estilos personalizados aquí */
</style>
