<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-5">
        <header>
            <h2 class="text-base font-bold" style="color: var(--sp-text);">Delete Account</h2>
            <p class="mt-1 text-sm" style="color: var(--sp-text-2);">
                Once deleted, all your data will be permanently removed. This cannot be undone.
            </p>
        </header>

        <button
            @click="confirmUserDeletion"
            id="delete-account-btn"
            class="flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-200 border-2 border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
            </svg>
            Delete Account
        </button>

        <!-- Confirmation Modal (inline) -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200"
                enter-from-class="opacity-0"
                leave-active-class="transition duration-200"
                leave-to-class="opacity-0"
            >
                <div v-if="confirmingUserDeletion" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
                    <Transition
                        enter-active-class="transition duration-200"
                        enter-from-class="opacity-0 scale-95"
                        leave-active-class="transition duration-150"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div v-if="confirmingUserDeletion" class="w-full max-w-md rounded-2xl p-6 shadow-2xl" style="background-color: var(--sp-card); border: 1px solid rgba(239,68,68,0.3);">
                            <!-- Red accent bar -->
                            <div class="h-0.5 w-full rounded-full bg-gradient-to-r from-red-500 to-red-700 mb-5"></div>

                            <div class="flex items-start gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-red-500/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-base" style="color: var(--sp-text);">Delete your account?</h3>
                                    <p class="text-sm mt-1" style="color: var(--sp-text-2);">
                                        This is permanent. Enter your password to confirm.
                                    </p>
                                </div>
                            </div>

                            <!-- Password input -->
                            <div class="mb-5">
                                <input
                                    id="delete-password"
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type="password"
                                    class="sp-input"
                                    placeholder="Enter your password"
                                    @keyup.enter="deleteUser"
                                />
                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center justify-end gap-3">
                                <button
                                    @click="closeModal"
                                    type="button"
                                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 hover:opacity-80"
                                    style="background-color: var(--sp-bg-2); color: var(--sp-text-2); border: 1px solid var(--sp-border);"
                                >
                                    Cancel
                                </button>
                                <button
                                    @click="deleteUser"
                                    :disabled="form.processing"
                                    type="button"
                                    class="px-5 py-2 rounded-full text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition-all duration-200 disabled:opacity-50"
                                >
                                    {{ form.processing ? 'Deleting…' : 'Yes, Delete' }}
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>
