<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({
    name: '',
    email: '',
    phone_number: '',
    country_code: '' ,
    password: '',
    password_confirmation: '',
    terms: false,
});

const defaultOption = { dial_code: '', name: t('auth.register.country_code_placeholder') };
const countryCodes = ref([defaultOption]);
const isLoading = ref(false);
const selectedCountry = ref(null);

const fetchCountryCodes = async (search = '') => {
    isLoading.value = true;
    try {
        const response = await axios.get('/components/country-codes', {
            params: { search }
        });
        countryCodes.value = [defaultOption, ...response.data.data];
    } catch (error) {
        console.error('Error fetching country codes:', error);
    } finally {
        isLoading.value = false;
    }
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

onMounted(() => fetchCountryCodes());

watch(selectedCountry, (newVal) => {
    form.country_code = newVal ? newVal.dial_code : '';
});
</script>

<template>
    <Head :title="t('auth.register.title')" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" :value="t('auth.register.name')" />
                <TextInput id="name" v-model="form.name" type="text" class="block w-full mt-1" required autofocus autocomplete="name" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" :value="t('auth.register.email')" />
                <TextInput id="email" v-model="form.email" type="email" class="block w-full mt-1" required autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="country_code" :value="t('auth.register.country_code')" />
                <v-select :options="countryCodes" v-model="selectedCountry" label="name" :placeholder="t('auth.register.country_code_placeholder')" @search="fetchCountryCodes" :loading="isLoading" />
                <InputError class="mt-2" :message="form.errors.country_code" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone_number" :value="t('auth.register.phone_number')" />
                <TextInput id="phone_number" v-model="form.phone_number" type="text" class="block w-full mt-1" required autocomplete="tel" />
                <InputError class="mt-2" :message="form.errors.phone_number" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" :value="t('auth.register.password')" />
                <TextInput id="password" v-model="form.password" type="password" class="block w-full mt-1" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" :value="t('auth.register.password_confirmation')" />
                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password" class="block w-full mt-1" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                        <div class="ms-2">
                            {{ t('auth.register.terms') }} <a target="_blank" :href="route('terms.show')" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ t('auth.register.terms_of_service') }}</a> {{ t('auth.register.and') }} <a target="_blank" :href="route('policy.show')" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ t('auth.register.privacy_policy') }}</a>
                        </div>
                    </div>
                </InputLabel>
                <InputError class="mt-2" :message="form.errors.terms" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ t('auth.register.already_registered') }}
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ t('auth.register.register_button') }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>

<style>
.vue-select .vs__dropdown-menu {
    background-color: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
}

.vue-select .vs__dropdown-menu li {
    color: #1f2937;
}

.vue-select .vs__dropdown-menu li:hover {
    background-color: #e5e7eb;
}
</style>
