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
    username: '', // Añade el campo username
    phone_number: '',
    country_code: '',
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
const handlePhoneNumberInput = (event) => {
    const value = event.target.value;
    event.target.value = value.replace(/\D/g, ''); // Reemplaza todo lo que no sea dígito
    form.phone_number = event.target.value;
};
const handleUsernameInput = (event) => {
    const value = event.target.value;
    // Reemplaza caracteres no permitidos con una expresión regular que coincida con alpha_dash
    event.target.value = value.replace(/[^a-zA-Z0-9_-]/g, '');
    form.username = event.target.value;
};
</script>

<template>

    <Head :title="t('auth.register.title')" />


    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>
        <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white">
            Create your Account
        </h1>
        <p class="text-sm font-light text-gray-500 dark:text-gray-300">
            Start your website in seconds. Already have an account? <a href="#"
                class="font-medium text-primary-600 hover:underline dark:text-primary-500">Login here</a>.
        </p> <br>
        <form @submit.prevent="submit">
            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <InputLabel for="name" :value="t('auth.register.name')" />
                    <TextInput id="name" v-model="form.name" type="text" class="block w-full mt-1" required autofocus
                        autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="username" :value="t('auth.register.username')" />
                    <TextInput id="username" v-model="form.username" type="text" class="block w-full mt-1" required
                        autocomplete="off" @input="handleUsernameInput" />
                    <InputError class="mt-2" :message="form.errors.username" />
                </div>

                <div>
                    <InputLabel for="email" :value="t('auth.register.email')" />
                    <TextInput id="email" v-model="form.email" type="email" class="block w-full mt-1" required
                        autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div>
                    <InputLabel for="country_code" :value="t('auth.register.country_code')"
                        class="block mb-2 text-sm font-bold text-gray-700" />
                    <v-select :options="countryCodes" v-model="selectedCountry" label="name"
                        :placeholder="t('auth.register.country_code_placeholder')" @search="fetchCountryCodes"
                        :loading="isLoading"
                        class="block w-full mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <InputError class="mt-2 text-red-600" :message="form.errors.country_code" />
                </div>







            </div>


            <div class="mt-4">
                <InputLabel for="phone_number" :value="t('auth.register.phone_number')" />
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 18">
                            <path
                                d="M18 13.446a3.02 3.02 0 0 0-.946-1.985l-1.4-1.4a3.054 3.054 0 0 0-4.218 0l-.7.7a.983.983 0 0 1-1.39 0l-2.1-2.1a.983.983 0 0 1 0-1.389l.7-.7a2.98 2.98 0 0 0 0-4.217l-1.4-1.4a2.824 2.824 0 0 0-4.218 0c-3.619 3.619-3 8.229 1.752 12.979C6.785 16.639 9.45 18 11.912 18a7.175 7.175 0 0 0 5.139-2.325A2.9 2.9 0 0 0 18 13.446Z" />
                        </svg>
                    </div>

                    <TextInput id="phone_number" v-model="form.phone_number" type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required autocomplete="tel" @input="handlePhoneNumberInput" />
                </div>
                <InputError class="mt-2" :message="form.errors.phone_number" />
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Select a phone number that matches the format. sin el codigo de pais
                </p>
            </div>

            <div class="mt-4">
                <InputLabel for="password" :value="t('auth.register.password')" />
                <TextInput id="password" v-model="form.password" type="password" class="block w-full mt-1" required
                    autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" :value="t('auth.register.password_confirmation')" />
                <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                    class="block w-full mt-1" required autocomplete="new-password" />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center">
                <div class="w-full h-0.5 bg-gray-200 dark:bg-gray-700"></div>
                <div class="px-5 text-center text-gray-500 dark:text-gray-400">or</div>
                <div class="w-full h-0.5 bg-gray-200 dark:bg-gray-700"></div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">


                <a href="#"
                    class="w-full inline-flex items-center justify-center py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_13183_10121)">
                            <path
                                d="M20.3081 10.2303C20.3081 9.55056 20.253 8.86711 20.1354 8.19836H10.7031V12.0492H16.1046C15.8804 13.2911 15.1602 14.3898 14.1057 15.0879V17.5866H17.3282C19.2205 15.8449 20.3081 13.2728 20.3081 10.2303Z"
                                fill="#3F83F8" />
                            <path
                                d="M10.7019 20.0006C13.3989 20.0006 15.6734 19.1151 17.3306 17.5865L14.1081 15.0879C13.2115 15.6979 12.0541 16.0433 10.7056 16.0433C8.09669 16.0433 5.88468 14.2832 5.091 11.9169H1.76562V14.4927C3.46322 17.8695 6.92087 20.0006 10.7019 20.0006V20.0006Z"
                                fill="#34A853" />
                            <path
                                d="M5.08857 11.9169C4.66969 10.6749 4.66969 9.33008 5.08857 8.08811V5.51233H1.76688C0.348541 8.33798 0.348541 11.667 1.76688 14.4927L5.08857 11.9169V11.9169Z"
                                fill="#FBBC04" />
                            <path
                                d="M10.7019 3.95805C12.1276 3.936 13.5055 4.47247 14.538 5.45722L17.393 2.60218C15.5852 0.904587 13.1858 -0.0287217 10.7019 0.000673888C6.92087 0.000673888 3.46322 2.13185 1.76562 5.51234L5.08732 8.08813C5.87733 5.71811 8.09302 3.95805 10.7019 3.95805V3.95805Z"
                                fill="#EA4335" />
                        </g>
                        <defs>
                            <clipPath id="clip0_13183_10121">
                                <rect width="20" height="20" fill="white" transform="translate(0.5)" />
                            </clipPath>
                        </defs>
                    </svg>
                    Sign up with Google
                </a>
                <a href="#"
                    class="w-full inline-flex items-center justify-center py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 mr-2 text-gray-900 dark:text-white" viewBox="0 0 21 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_13183_29163)">
                            <path
                                d="M18.6574 15.5863C18.3549 16.2851 17.9969 16.9283 17.5821 17.5196C17.0167 18.3257 16.5537 18.8838 16.1969 19.1936C15.6439 19.7022 15.0513 19.9627 14.4168 19.9775C13.9612 19.9775 13.4119 19.8479 12.7724 19.585C12.1308 19.3232 11.5412 19.1936 11.0021 19.1936C10.4366 19.1936 9.83024 19.3232 9.18162 19.585C8.53201 19.8479 8.00869 19.985 7.60858 19.9985C7.00008 20.0245 6.39356 19.7566 5.78814 19.1936C5.40174 18.8566 4.91842 18.2788 4.33942 17.4603C3.71821 16.5863 3.20749 15.5727 2.80738 14.4172C2.37887 13.1691 2.16406 11.9605 2.16406 10.7904C2.16406 9.45009 2.45368 8.29407 3.03379 7.32534C3.4897 6.54721 4.09622 5.9334 4.85533 5.4828C5.61445 5.03219 6.43467 4.80257 7.31797 4.78788C7.80129 4.78788 8.4351 4.93738 9.22273 5.2312C10.0081 5.52601 10.5124 5.67551 10.7335 5.67551C10.8988 5.67551 11.4591 5.5007 12.4088 5.15219C13.3069 4.82899 14.0649 4.69517 14.6859 4.74788C16.3685 4.88368 17.6327 5.54699 18.4734 6.74202C16.9685 7.65384 16.2241 8.93097 16.2389 10.5693C16.2525 11.8454 16.7154 12.9074 17.6253 13.7506C18.0376 14.1419 18.4981 14.4444 19.0104 14.6592C18.8993 14.9814 18.7821 15.29 18.6574 15.5863V15.5863ZM14.7982 0.400358C14.7982 1.40059 14.4328 2.3345 13.7044 3.19892C12.8254 4.22654 11.7623 4.82035 10.6093 4.72665C10.5947 4.60665 10.5861 4.48036 10.5861 4.34765C10.5861 3.38743 11.0041 2.3598 11.7465 1.51958C12.1171 1.09416 12.5884 0.740434 13.16 0.458257C13.7304 0.18029 14.2698 0.0265683 14.7772 0.000244141C14.7921 0.133959 14.7982 0.267682 14.7982 0.400345V0.400358Z"
                                fill="currentColor" />
                        </g>
                        <defs>
                            <clipPath id="clip0_13183_29163">
                                <rect width="20" height="20" fill="white" transform="translate(0.5)" />
                            </clipPath>
                        </defs>
                    </svg>
                    Sign up with Apple
                </a>

            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
                        <div class="ms-2">
                            {{ t('auth.register.terms') }} <a target="_blank" :href="route('terms.show')"
                                class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{
                                    t('auth.register.terms_of_service') }}</a> {{ t('auth.register.and') }} <a
                                target="_blank" :href="route('policy.show')"
                                class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{
                                    t('auth.register.privacy_policy') }}</a>
                        </div>
                    </div>
                </InputLabel>
                <InputError class="mt-2" :message="form.errors.terms" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')"
                    class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
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
