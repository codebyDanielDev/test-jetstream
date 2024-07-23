<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

import AddSessionWhatsapp from '@/Pages/Profile/Partials/AddSessionWhatsapp.vue';
import ListGetSessionWhatsapp from '@/Pages/Profile/Partials/ListGetSessionWhatsapp.vue';

import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import { Dialog, DialogPanel, Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
import { Bars3Icon } from '@heroicons/vue/20/solid';
import {
    BellIcon,
    CreditCardIcon,
    CubeIcon,
    FingerPrintIcon,
    UserCircleIcon,
    UsersIcon,
    XMarkIcon,
    UserIcon,      // Asegúrate de que estos iconos están correctamente importados
    Cog6ToothIcon, // Cambiado de CogIcon
    LifebuoyIcon,  // Cambiado de SupportIcon
    ChartBarSquareIcon, // Cambiado de ChartBarIcon
    PuzzlePieceIcon,    // Cambiado de PuzzleIcon
    CommandLineIcon     // Cambiado de CodeIcon
} from '@heroicons/vue/24/outline';

const secondaryNavigation = [
    { name: 'General', href: '#', icon: UserCircleIcon, current: true },
    { name: 'Profile', href: '#', icon: UserIcon, current: false },
    { name: 'Security', href: '#', icon: FingerPrintIcon, current: false },
    { name: 'Notifications', href: '#', icon: BellIcon, current: false },
    { name: 'Plan', href: '#', icon: CubeIcon, current: false },
    { name: 'Billing', href: '#', icon: CreditCardIcon, current: false },
    { name: 'Support', href: '#', icon: LifebuoyIcon, current: false },
    { name: 'Activity', href: '#', icon: ChartBarSquareIcon, current: false },
    { name: 'Settings', href: '#', icon: Cog6ToothIcon, current: false },
    { name: 'Integrations', href: '#', icon: PuzzlePieceIcon, current: false },
    { name: 'API', href: '#', icon: CommandLineIcon, current: false },
];



const automaticTimezoneEnabled = ref(true)



const { t } = useI18n();

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout :title="t('profile.title')">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ t('profile.title') }}
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">

            </div>
        </div>


        <div class="pt-16 mx-auto max-w-7xl lg:flex lg:gap-x-16 lg:px-8">
            <h1 class="sr-only">General Settings</h1>

            <aside
                class="flex py-4 overflow-x-auto border-b border-gray-900/5 lg:block lg:w-64 lg:flex-none lg:border-0 lg:py-20">
                <nav class="flex-none px-4 sm:px-6 lg:px-0">
                    <ul role="list" class="flex gap-x-3 gap-y-1 whitespace-nowrap lg:flex-col">
                        <li v-for="item in secondaryNavigation" :key="item.name">
                            <a :href="item.href"
                                :class="[item.current ? 'bg-gray-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600', 'group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm font-semibold leading-6']">
                                <component :is="item.icon"
                                    :class="[item.current ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600', 'h-6 w-6 shrink-0']"
                                    aria-hidden="true" />
                                {{ item.name }}
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <main class="px-4 py-16 sm:px-6 lg:flex-auto lg:px-0 lg:py-20">
                <div class="max-w-2xl mx-auto space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none">
                    <div>
                        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                            <UpdateProfileInformationForm :user="$page.props.auth.user" />

                            <SectionBorder />

                        </div>

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <AddSessionWhatsapp class="mt-10 sm:mt-0" />
                    <SectionBorder />
                </template>

                <ListGetSessionWhatsapp :sessions="sessions" class="mt-10 sm:mt-0" />
                <SectionBorder />

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm class="mt-10 sm:mt-0" />
                    <SectionBorder />
                </div>

                <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                    <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication"
                        class="mt-10 sm:mt-0" />
                    <SectionBorder />
                </div>

                <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <SectionBorder />
                    <DeleteUserForm class="mt-10 sm:mt-0" />
                </template>

                        <h2 class="text-base font-semibold leading-7 text-gray-900">Profile</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">This information will be displayed publicly so
                            be
                            careful what you share.</p>

                        <dl class="mt-6 space-y-6 text-sm leading-6 border-t border-gray-200 divide-y divide-gray-100">
                            <div class="pt-6 sm:flex">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">Full name</dt>
                                <dd class="flex justify-between mt-1 gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">Tom Cook</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>
                            <div class="pt-6 sm:flex">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">Email address</dt>
                                <dd class="flex justify-between mt-1 gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">tom.cook@example.com</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>
                            <div class="pt-6 sm:flex">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">Title</dt>
                                <dd class="flex justify-between mt-1 gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">Human Resources Manager</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Bank accounts</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Connect bank accounts to your account.</p>

                        <ul role="list"
                            class="mt-6 text-sm leading-6 border-t border-gray-200 divide-y divide-gray-100">
                            <li class="flex justify-between py-6 gap-x-6">
                                <div class="font-medium text-gray-900">TD Canada Trust</div>
                                <button type="button"
                                    class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                            </li>
                            <li class="flex justify-between py-6 gap-x-6">
                                <div class="font-medium text-gray-900">Royal Bank of Canada</div>
                                <button type="button"
                                    class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                            </li>
                        </ul>

                        <div class="flex pt-6 border-t border-gray-100">
                            <button type="button"
                                class="text-sm font-semibold leading-6 text-indigo-600 hover:text-indigo-500"><span
                                    aria-hidden="true">+</span> Add another bank</button>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Integrations</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Connect applications to your account.</p>

                        <ul role="list"
                            class="mt-6 text-sm leading-6 border-t border-gray-200 divide-y divide-gray-100">
                            <li class="flex justify-between py-6 gap-x-6">
                                <div class="font-medium text-gray-900">QuickBooks</div>
                                <button type="button"
                                    class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                            </li>
                        </ul>

                        <div class="flex pt-6 border-t border-gray-100">
                            <button type="button"
                                class="text-sm font-semibold leading-6 text-indigo-600 hover:text-indigo-500"><span
                                    aria-hidden="true">+</span> Add another application</button>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Language and dates</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500">Choose what language and date format to use
                            throughout
                            your account.</p>

                        <dl class="mt-6 space-y-6 text-sm leading-6 border-t border-gray-200 divide-y divide-gray-100">
                            <div class="pt-6 sm:flex">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">Language</dt>
                                <dd class="flex justify-between mt-1 gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">English</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>
                            <div class="pt-6 sm:flex">
                                <dt class="font-medium text-gray-900 sm:w-64 sm:flex-none sm:pr-6">Date format</dt>
                                <dd class="flex justify-between mt-1 gap-x-6 sm:mt-0 sm:flex-auto">
                                    <div class="text-gray-900">DD-MM-YYYY</div>
                                    <button type="button"
                                        class="font-semibold text-indigo-600 hover:text-indigo-500">Update</button>
                                </dd>
                            </div>
                            <SwitchGroup as="div" class="flex pt-6">
                                <SwitchLabel as="dt" class="flex-none pr-6 font-medium text-gray-900 sm:w-64" passive>
                                    Automatic
                                    timezone</SwitchLabel>
                                <dd class="flex items-center justify-end flex-auto">
                                    <Switch v-model="automaticTimezoneEnabled"
                                        :class="[automaticTimezoneEnabled ? 'bg-indigo-600' : 'bg-gray-200', 'flex w-8 cursor-pointer rounded-full p-px ring-1 ring-inset ring-gray-900/5 transition-colors duration-200 ease-in-out focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">
                                        <span aria-hidden="true"
                                            :class="[automaticTimezoneEnabled ? 'translate-x-3.5' : 'translate-x-0', 'h-4 w-4 transform rounded-full bg-white shadow-sm ring-1 ring-gray-900/5 transition duration-200 ease-in-out']" />
                                    </Switch>
                                </dd>
                            </SwitchGroup>
                        </dl>
                    </div>
                </div>
            </main>
        </div>
    </AppLayout>
</template>
