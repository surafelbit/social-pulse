<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-base font-bold" style="color: var(--sp-text);">Update Password</h2>
            <p class="mt-1 text-sm" style="color: var(--sp-text-2);">
                Ensure your account is using a strong, unique password.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-5">
            <!-- Current Password -->
            <div class="space-y-1.5">
                <label for="current_password" class="text-xs font-bold uppercase tracking-widest" style="color: var(--sp-text-2);">Current Password</label>
                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="sp-input"
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError :message="form.errors.current_password" class="mt-1" />
            </div>

            <!-- New Password -->
            <div class="space-y-1.5">
                <label for="password" class="text-xs font-bold uppercase tracking-widest" style="color: var(--sp-text-2);">New Password</label>
                <input
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="sp-input"
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <!-- Strength bar -->
                <div class="flex gap-1" v-if="form.password">
                    <span v-for="i in 4" :key="i" class="h-1 flex-1 rounded-full transition-colors duration-300"
                        :style="{ backgroundColor: form.password.length >= i * 3 ? '#32cd32' : 'var(--sp-border)' }">
                    </span>
                </div>
                <InputError :message="form.errors.password" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1.5">
                <label for="password_confirmation" class="text-xs font-bold uppercase tracking-widest" style="color: var(--sp-text-2);">Confirm Password</label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="sp-input"
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <InputError :message="form.errors.password_confirmation" class="mt-1" />
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-1">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="sp-btn-primary text-sm px-6 py-2"
                >
                    {{ form.processing ? 'Updating…' : 'Update Password' }}
                </button>

                <Transition enter-active-class="transition duration-200" enter-from-class="opacity-0" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm font-medium text-[#32cd32] flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Updated
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
