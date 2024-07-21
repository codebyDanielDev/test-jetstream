<template>
    <div class="flex flex-col items-center min-h-screen p-6 bg-gray-100 dark:bg-gray-900 sm:justify-center sm:p-0">
        <div class="flex justify-center w-full mb-8">
            <slot name="logo" />
        </div>

        <div class="w-full place-self-center lg:col-span-6">
            <div class="p-6 mx-auto bg-white rounded-lg shadow dark:bg-gray-800 sm:max-w-xl sm:p-8">
                <div href="#" class="inline-flex items-center mb-4 text-xl font-semibold text-gray-900 dark:text-white">

                </div>
                <slot />
            </div>
        </div>
    </div>
</template>

<style>
/* Considera agregar aquí estilos específicos que Tailwind no pueda manejar directamente */
</style>
