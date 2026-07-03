<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-6">
            <h2 class="text-base font-bold" style="color: var(--sp-text);">Profile Information</h2>
            <p class="mt-1 text-sm" style="color: var(--sp-text-2);">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-5">
            <!-- Name -->
            <div class="space-y-1.5">
                <label for="name" class="text-xs font-bold uppercase tracking-widest" style="color: var(--sp-text-2);">Full Name</label>
                <input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    class="sp-input"
                    placeholder="Your name"
                />
                <InputError class="mt-1" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-bold uppercase tracking-widest" style="color: var(--sp-text-2);">Email Address</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="sp-input"
                    placeholder="your@email.com"
                />
                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <!-- Email verification notice -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="rounded-lg p-3 text-sm" style="background-color: rgba(50,205,50,0.08); border: 1px solid rgba(50,205,50,0.2);">
                <p style="color: var(--sp-text);">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="text-[#32cd32] font-bold underline hover:no-underline ml-1"
                    >
                        Re-send verification email
                    </Link>
                </p>
                <p v-show="status === 'verification-link-sent'" class="mt-1 font-medium text-[#32cd32]">
                    A new verification link has been sent.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-1">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="sp-btn-primary text-sm px-6 py-2"
                >
                    {{ form.processing ? 'Saving…' : 'Save Changes' }}
                </button>

                <Transition enter-active-class="transition duration-200" enter-from-class="opacity-0 translate-y-1" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm font-medium text-[#32cd32] flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Saved
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
