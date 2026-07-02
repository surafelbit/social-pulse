<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false, // Added for the terms checkbox
});

// Reactive state for password visibility
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register | Social Pulse" />

    <div class="bg-background text-on-background min-h-screen flex flex-col selection:bg-primary-container selection:text-on-primary-container">

        <!-- TopNavBar -->
        <nav class="fixed top-0 w-full z-50 bg-surface/80 dark:bg-surface/80 backdrop-blur-xl border-b border-white/10 shadow-sm">
            <div class="flex justify-between items-center px-margin-desktop py-4 max-w-container-max mx-auto">
                <div class="flex items-center gap-8">
                    <Link href="/" class="flex items-center gap-2.5 font-headline-md text-headline-md font-bold text-primary dark:text-primary hanken group">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_14px_rgba(50,205,50,0.65)] transition-transform duration-300 group-hover:scale-125"></span>
                        Social Pulse
                    </Link>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="#" class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors hover:scale-105 duration-200 relative after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-px after:w-0 after:bg-[#32cd32] after:transition-all after:duration-300 hover:after:w-full">Features</a>
                        <a href="#" class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors hover:scale-105 duration-200 relative after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-px after:w-0 after:bg-[#32cd32] after:transition-all after:duration-300 hover:after:w-full">Community</a>
                        <a href="#" class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors hover:scale-105 duration-200 relative after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-px after:w-0 after:bg-[#32cd32] after:transition-all after:duration-300 hover:after:w-full">Live</a>
                        <a href="#" class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors hover:scale-105 duration-200 relative after:content-[''] after:absolute after:left-0 after:-bottom-1 after:h-px after:w-0 after:bg-[#32cd32] after:transition-all after:duration-300 hover:after:w-full">About</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <Link :href="route('login')" class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors active:scale-95">
                        Login
                    </Link>
                    <Link :href="route('register')" class="bg-primary-container text-on-primary-container px-6 py-2 rounded-xl font-bold hover:scale-105 transition-transform duration-200 active:scale-95 shadow-[0_4px_16px_rgba(50,205,50,0.25)]">
                        Register
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Content: Registration Canvas -->
        <main class="flex-grow flex items-center justify-center pt-24 pb-12 px-margin-mobile md:px-0 relative overflow-hidden">
            <!-- Ambient Background Elements -->
            <div class="absolute top-1/4 -right-20 w-96 h-96 bg-primary-fixed-dim/10 rounded-full blur-[100px] -z-10 animate-float-slow"></div>
            <div class="absolute bottom-1/4 -left-20 w-96 h-96 bg-tertiary-fixed-dim/10 rounded-full blur-[100px] -z-10 animate-float-slower"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[560px] h-[560px] rounded-full bg-primary-fixed-dim/5 blur-[100px] -z-10 pointer-events-none"></div>

            <div class="w-full max-w-[480px] animate-rise-in">
                <!-- Header Section -->
                <div class="text-center mb-10">
                    <h1 class="font-headline-lg text-headline-lg text-on-surface mb-2 hanken">Join the Beat</h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant">Create your account to start sharing real moments.</p>
                </div>

                <!-- Registration Card -->
                <div class="bg-surface-container-lowest border border-outline-variant p-8 md:p-10 rounded-xl lime-shadow relative z-10 overflow-hidden">

                    <!-- Signature accent bar -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#32cd32] via-[#7ee881] to-[#32cd32] bg-[length:200%_100%] animate-shimmer"></div>

                    <form @submit.prevent="submit" class="space-y-6">

                        <!-- Name Field -->
                        <div class="space-y-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block uppercase tracking-wider" for="name">Full Name</label>
                            <div class="relative group">
                                <input
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 font-body-md focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary hover:border-[#32cd32]/50 transition-all"
                                    placeholder="Alex Johnson"
                                />
                                <!-- User SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-outline-variant group-focus-within:text-[#32cd32] transition-colors pointer-events-none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label class="font-label-sm text-label-sm text-on-surface-variant block uppercase tracking-wider" for="email">Email Address</label>
                            <div class="relative group">
                                <input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                    class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 font-body-md focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary hover:border-[#32cd32]/50 transition-all"
                                    placeholder="alex@pulse.com"
                                />
                                <!-- Envelope SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-outline-variant group-focus-within:text-[#32cd32] transition-colors pointer-events-none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Password Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div class="space-y-2">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block uppercase tracking-wider" for="password">Password</label>
                                <div class="relative group">
                                    <input
                                        id="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        v-model="form.password"
                                        required
                                        autocomplete="new-password"
                                        class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 font-body-md focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary hover:border-[#32cd32]/50 transition-all pr-10"
                                        placeholder="••••••••"
                                    />
                                    <button type="button" @click.prevent="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline-variant hover:text-[#32cd32] focus:outline-none transition-colors">
                                        <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                    </button>
                                </div>
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label class="font-label-sm text-label-sm text-on-surface-variant block uppercase tracking-wider" for="password_confirmation">Confirm</label>
                                <div class="relative group">
                                    <input
                                        id="password_confirmation"
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        v-model="form.password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        class="w-full bg-white border border-outline-variant rounded-lg px-4 py-3 font-body-md focus:outline-none focus:ring-2 focus:ring-primary-container focus:border-primary hover:border-[#32cd32]/50 transition-all pr-10"
                                        placeholder="••••••••"
                                    />
                                    <button type="button" @click.prevent="showConfirmPassword = !showConfirmPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline-variant hover:text-[#32cd32] focus:outline-none transition-colors">
                                        <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                    </button>
                                </div>
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>
                        </div>

                        <!-- Password strength hint bar -->
                        <div class="flex gap-1.5 -mt-3" v-if="form.password">
                            <span class="h-1 flex-1 rounded-full transition-colors duration-300" :class="form.password.length >= 1 ? 'bg-[#32cd32]' : 'bg-outline-variant'"></span>
                            <span class="h-1 flex-1 rounded-full transition-colors duration-300" :class="form.password.length >= 6 ? 'bg-[#32cd32]' : 'bg-outline-variant'"></span>
                            <span class="h-1 flex-1 rounded-full transition-colors duration-300" :class="form.password.length >= 10 ? 'bg-[#32cd32]' : 'bg-outline-variant'"></span>
                            <span class="h-1 flex-1 rounded-full transition-colors duration-300" :class="form.password.length >= 14 ? 'bg-[#32cd32]' : 'bg-outline-variant'"></span>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="flex items-start gap-3 pt-2">
                            <input
                                id="terms"
                                type="checkbox"
                                v-model="form.terms"
                                required
                                class="mt-1 w-4 h-4 rounded border-outline-variant text-[#32cd32] focus:ring-primary-container cursor-pointer"
                            />
                            <label class="font-body-md text-body-md text-on-surface-variant" for="terms">
                                I agree to the <a class="text-primary hover:underline font-semibold" href="#">Terms of Service</a> and <a class="text-primary hover:underline font-semibold" href="#">Privacy Policy</a>.
                            </label>
                        </div>

                        <!-- Primary Action -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-primary-container text-on-primary-container hanken font-bold text-headline-md py-4 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary-container/20 mt-4 relative overflow-hidden group"
                            :class="form.processing ? 'opacity-80 pointer-events-none' : 'hover:scale-[1.02] active:scale-95'"
                        >
                            <span class="absolute inset-0 bg-white/10 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-out"></span>

                            <svg v-if="form.processing" class="animate-spin h-6 w-6 relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="relative z-10">{{ form.processing ? 'Registering...' : 'Register' }}</span>
                        </button>
                    </form>

                    <!-- Footer Link -->
                    <div class="mt-8 pt-8 border-t border-outline-variant/30 text-center">
                        <p class="font-body-md text-body-md text-on-surface-variant">
                            Already registered?
                            <Link :href="route('login')" class="text-primary font-bold hover:underline ml-1">Login to your account</Link>
                        </p>
                    </div>
                </div>

                <!-- Social Provider Disclaimer -->
                <div class="mt-8 flex flex-col items-center gap-4">
                    <div class="flex items-center gap-4 w-full">
                        <div class="h-px flex-grow bg-gradient-to-r from-transparent to-[#32cd32]/40"></div>
                        <span class="font-label-sm text-label-sm text-outline uppercase">Or join with</span>
                        <div class="h-px flex-grow bg-gradient-to-l from-transparent to-[#32cd32]/40"></div>
                    </div>
                    <div class="flex gap-4">
                        <button class="w-14 h-14 border border-outline-variant rounded-xl flex items-center justify-center hover:bg-surface-container hover:border-[#32cd32]/60 hover:-translate-y-0.5 transition-all duration-300">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#4285F4" d="M23.52 12.27c0-.79-.07-1.54-.2-2.27H12v4.51h6.47c-.28 1.5-1.13 2.77-2.41 3.62v3.01h3.9c2.28-2.1 3.56-5.2 3.56-8.87z"/>
                                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.9-3.01c-1.08.72-2.46 1.15-4.03 1.15-3.1 0-5.73-2.09-6.67-4.9H1.3v3.09C3.26 21.3 7.31 24 12 24z"/>
                                <path fill="#FBBC05" d="M5.33 14.33A7.16 7.16 0 0 1 4.96 12c0-.81.14-1.6.37-2.33V6.58H1.3A11.98 11.98 0 0 0 0 12c0 1.93.46 3.76 1.3 5.42l4.03-3.09z"/>
                                <path fill="#EA4335" d="M12 4.77c1.76 0 3.35.61 4.6 1.8l3.45-3.45C17.94 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.3 6.58l4.03 3.09c.94-2.81 3.57-4.9 6.67-4.9z"/>
                            </svg>
                        </button>
                        <button class="w-14 h-14 border border-outline-variant rounded-xl flex items-center justify-center hover:bg-surface-container hover:border-[#32cd32]/60 hover:-translate-y-0.5 transition-all duration-300">
                            <svg class="w-6 h-6 text-on-surface" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.05 12.536c-.02-2.086 1.703-3.086 1.78-3.135-.97-1.42-2.48-1.615-3.02-1.637-1.285-.13-2.508.756-3.158.756-.65 0-1.653-.738-2.72-.718-1.4.02-2.694.813-3.415 2.066-1.457 2.524-.373 6.263 1.047 8.313.694 1.003 1.523 2.13 2.61 2.09 1.048-.042 1.444-.678 2.712-.678 1.267 0 1.622.678 2.732.657 1.13-.02 1.845-1.024 2.535-2.03.8-1.166 1.128-2.293 1.146-2.352-.025-.011-2.198-.844-2.219-3.332z"/>
                                <path d="M15.045 6.096c.577-.7.966-1.674.86-2.646-.83.034-1.834.553-2.43 1.253-.535.62-1.003 1.61-.876 2.56.928.072 1.87-.472 2.446-1.167z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full py-stack-xl bg-surface-container-lowest dark:bg-surface-container-lowest border-t border-white/5 mt-auto">
            <div class="flex flex-col md:flex-row justify-between items-center px-margin-desktop space-y-stack-md md:space-y-0 max-w-container-max mx-auto">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <span class="flex items-center gap-2 font-headline-md text-headline-md font-bold text-primary dark:text-primary hanken">
                        <span class="w-2 h-2 rounded-full bg-[#32cd32]"></span>
                        Social Pulse
                    </span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant dark:text-on-surface-variant text-sm">© 2024 Social Pulse. All rights reserved.</span>
                </div>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-[#32cd32] transition-colors hover:underline text-sm">Privacy Policy</a>
                    <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-[#32cd32] transition-colors hover:underline text-sm">Terms of Service</a>
                    <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-[#32cd32] transition-colors hover:underline text-sm">Help Center</a>
                    <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-[#32cd32] transition-colors hover:underline text-sm">Careers</a>
                    <a href="#" class="font-body-sm text-body-sm text-on-surface-variant hover:text-[#32cd32] transition-colors hover:underline text-sm">Contact</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
body {
    font-family: 'Inter', sans-serif;
    background-color: #f9f9f9;
}
.hanken {
    font-family: 'Hanken Grotesk', sans-serif;
}
.lime-shadow {
    box-shadow: 0px 10px 30px rgba(50, 205, 50, 0.1);
}
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: #e2e2e2;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
    background: #32cd32;
}

/* Entrance animation for the registration card */
@keyframes rise-in {
    from {
        opacity: 0;
        transform: translateY(18px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-rise-in {
    animation: rise-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
}

/* Ambient floating blobs */
@keyframes float-slow {
    0%, 100% { transform: translateY(0) translateX(0); }
    50% { transform: translateY(-22px) translateX(-12px); }
}
@keyframes float-slower {
    0%, 100% { transform: translateY(0) translateX(0); }
    50% { transform: translateY(20px) translateX(14px); }
}
.animate-float-slow {
    animation: float-slow 10s ease-in-out infinite;
}
.animate-float-slower {
    animation: float-slower 12s ease-in-out infinite;
}

/* Shimmer accent bar */
@keyframes shimmer {
    0% { background-position: 0% 50%; }
    100% { background-position: 200% 50%; }
}
.animate-shimmer {
    animation: shimmer 3s linear infinite;
}

/* Respect reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
    .animate-rise-in,
    .animate-float-slow,
    .animate-float-slower,
    .animate-shimmer {
        animation: none !important;
    }
}
</style>