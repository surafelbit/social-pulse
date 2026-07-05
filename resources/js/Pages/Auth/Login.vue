<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Reactive state for password visibility instead of raw JS
const showPassword = ref(false);

// Reactive state for input focus (used for animated field icons)
const emailFocused = ref(false);
const passwordFocused = ref(false);

// Theme (kept consistent with the rest of the app so colors always match)
const isDark = ref(
    typeof window !== 'undefined'
        ? document.documentElement.classList.contains('dark')
        : false,
);

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('sp-theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('sp-theme', 'light');
    }
};

if (typeof window !== 'undefined') {
    const saved = localStorage.getItem('sp-theme');
    if (saved === 'dark') {
        document.documentElement.classList.add('dark');
        isDark.value = true;
    } else if (saved === 'light') {
        document.documentElement.classList.remove('dark');
        isDark.value = false;
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
        isDark.value = true;
    }
}

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login | Social Pulse" />

    <!-- Full Page Wrapper -->
    <div class="font-body-md min-h-screen flex flex-col transition-colors duration-300" style="background-color: var(--sp-bg); color: var(--sp-text);">

        <!-- TopNavBar -->
        <nav class="w-full top-0 sticky z-50 sp-login-nav" style="border-bottom: 1px solid var(--sp-border); background-color: color-mix(in srgb, var(--sp-bg) 82%, transparent); backdrop-filter: blur(16px);">
            <div class="flex justify-between items-center h-16 px-margin-desktop max-w-container-max mx-auto">
                <Link href="/" class="flex items-center gap-2.5 group cursor-pointer">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#32cd32] shadow-[0_0_16px_rgba(50,205,50,0.7)] transition-transform duration-300 group-hover:scale-125 sp-login-pulse"></span>
                    <span class="font-headline-md text-headline-md font-bold tracking-tight" style="color: var(--sp-text);">
                        Social<span class="sp-login-gradient-text">Pulse</span>
                    </span>
                </Link>
                <div class="hidden md:flex items-center gap-gutter">
                    <button
                        @click="toggleTheme"
                        class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-200 hover:bg-[#32cd32]/10 hover:text-[#32cd32] mr-2"
                        style="color: var(--sp-text-2); border: 1px solid var(--sp-border);"
                        :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    >
                        <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                        </svg>
                    </button>
                    <button
                        class="font-label-sm text-label-sm transition-all duration-300 relative sp-login-navlink"
                        style="color: var(--sp-text-2);"
                    >
                        Help Center
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main Content: Login Canvas -->
        <main class="flex-grow flex items-center justify-center px-margin-mobile py-12 relative overflow-hidden">
            <!-- Kinetic Background Elements -->
            <div class="absolute top-1/4 -left-20 w-96 h-96 rounded-full blur-3xl animate-float-slow" style="background-color: rgba(50,205,50,0.08);"></div>
            <div class="absolute bottom-1/4 -right-20 w-80 h-80 rounded-full blur-3xl animate-float-slower" style="background-color: rgba(76,227,70,0.07);"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[520px] h-[520px] rounded-full blur-3xl pointer-events-none" style="background-color: rgba(50,205,50,0.05);"></div>

            <div
                class="w-full max-w-md rounded-2xl p-base md:p-10 kinetic-shadow transition-all duration-500 relative z-10 overflow-hidden animate-rise-in sp-login-card"
                style="background-color: var(--sp-card); border: 1px solid var(--sp-border); color: var(--sp-text); backdrop-filter: blur(20px);"
            >

                <!-- Signature accent bar -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#32cd32] via-[#4ce346] to-[#32cd32] bg-[length:200%_100%] animate-shimmer"></div>

                <!-- Headline -->
                <header class="mb-10 border-l-4 border-[#32cd32] pl-4">
                    <h1 class="font-headline-lg text-headline-lg mb-2" style="color: var(--sp-text);">Welcome back to the Beat</h1>
                    <p class="font-body-md" style="color: var(--sp-text-2);">Sign in to sync your rhythm with the world.</p>
                </header>

                <!-- Status Message -->
                <div v-if="status" class="mb-4 text-sm font-semibold flex items-center gap-2 sp-login-status" style="color: #32cd32;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ status }}
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label class="font-label-sm text-label-sm block" style="color: var(--sp-text);" for="email">EMAIL ADDRESS</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none transition-colors duration-300"
                                :style="{ color: emailFocused ? '#32cd32' : 'var(--sp-text-3)' }" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                @focus="emailFocused = true"
                                @blur="emailFocused = false"
                                class="w-full h-12 pl-11 pr-4 text-body-md rounded-lg transition-all duration-300 sp-login-input"
                                style="background-color: var(--sp-bg-2); color: var(--sp-text); border: 1px solid var(--sp-border);"
                                placeholder="name@company.com"
                            />
                        </div>
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="font-label-sm text-label-sm block" style="color: var(--sp-text);" for="password">PASSWORD</label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="font-label-sm text-label-sm hover:underline transition-all"
                                style="color: #32cd32;"
                            >
                                Forgot Password?
                            </Link>
                        </div>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none transition-colors duration-300"
                                :style="{ color: passwordFocused ? '#32cd32' : 'var(--sp-text-3)' }" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                @focus="passwordFocused = true"
                                @blur="passwordFocused = false"
                                class="w-full h-12 pl-11 pr-11 text-body-md rounded-lg transition-all duration-300 sp-login-input"
                                style="background-color: var(--sp-bg-2); color: var(--sp-text); border: 1px solid var(--sp-border);"
                                placeholder="••••••••"
                            />
                            <button
                                type="button"
                                @click.prevent="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 hover:text-[#32cd32] focus:outline-none transition-colors duration-300"
                                style="color: var(--sp-text-3);"
                            >
                                <!-- Eye Open Icon (when password is hidden) -->
                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                                <!-- Eye Closed Icon (when password is visible) -->
                                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mt-4">
                        <input
                            id="remember"
                            type="checkbox"
                            v-model="form.remember"
                            class="rounded cursor-pointer sp-login-checkbox"
                            style="border: 1px solid var(--sp-border); accent-color: #32cd32;"
                        >
                        <label for="remember" class="ms-2 font-label-sm text-label-sm cursor-pointer" style="color: var(--sp-text-2);">
                            Remember me
                        </label>
                    </div>

                    <!-- Primary Action -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full h-14 font-headline-md text-headline-md flex items-center justify-center gap-2 kinetic-shadow rounded-lg overflow-hidden relative transition-transform duration-200 group sp-login-submit"
                        :class="[
                            form.processing ? 'opacity-80 pointer-events-none' : 'active:scale-[0.98]'
                        ]"
                    >
                        <span class="absolute inset-0 bg-white/10 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-out"></span>

                        <!-- Loading Spinner (when processing) -->
                        <svg v-if="form.processing" class="animate-spin h-5 w-5 relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <!-- Arrow Right (when idle) -->
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 relative z-10 transition-transform duration-300 group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>

                        <span class="relative z-10">{{ form.processing ? 'Authenticating...' : 'Login' }}</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8 flex items-center">
                    <div class="flex-grow h-px" style="background: linear-gradient(to right, transparent, rgba(50,205,50,0.4));"></div>
                    <span class="px-4 font-label-sm text-label-sm" style="color: var(--sp-text-3); background-color: var(--sp-card);">OR CONTINUE WITH</span>
                    <div class="flex-grow h-px" style="background: linear-gradient(to left, transparent, rgba(50,205,50,0.4));"></div>
                </div>

                <!-- Social Logins -->
                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="h-12 rounded-lg flex items-center justify-center gap-2 font-body-md hover:-translate-y-0.5 transition-all duration-300 sp-login-social"
                        style="border: 1px solid var(--sp-border); color: var(--sp-text);"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#4285F4" d="M23.52 12.27c0-.79-.07-1.54-.2-2.27H12v4.51h6.47c-.28 1.5-1.13 2.77-2.41 3.62v3.01h3.9c2.28-2.1 3.56-5.2 3.56-8.87z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.9-3.01c-1.08.72-2.46 1.15-4.03 1.15-3.1 0-5.73-2.09-6.67-4.9H1.3v3.09C3.26 21.3 7.31 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.33 14.33A7.16 7.16 0 0 1 4.96 12c0-.81.14-1.6.37-2.33V6.58H1.3A11.98 11.98 0 0 0 0 12c0 1.93.46 3.76 1.3 5.42l4.03-3.09z"/>
                            <path fill="#EA4335" d="M12 4.77c1.76 0 3.35.61 4.6 1.8l3.45-3.45C17.94 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.3 6.58l4.03 3.09c.94-2.81 3.57-4.9 6.67-4.9z"/>
                        </svg>
                        Google
                    </button>
                    <button
                        class="h-12 rounded-lg flex items-center justify-center gap-2 font-body-md hover:-translate-y-0.5 transition-all duration-300 sp-login-social"
                        style="border: 1px solid var(--sp-border); color: var(--sp-text);"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.05 12.536c-.02-2.086 1.703-3.086 1.78-3.135-.97-1.42-2.48-1.615-3.02-1.637-1.285-.13-2.508.756-3.158.756-.65 0-1.653-.738-2.72-.718-1.4.02-2.694.813-3.415 2.066-1.457 2.524-.373 6.263 1.047 8.313.694 1.003 1.523 2.13 2.61 2.09 1.048-.042 1.444-.678 2.712-.678 1.267 0 1.622.678 2.732.657 1.13-.02 1.845-1.024 2.535-2.03.8-1.166 1.128-2.293 1.146-2.352-.025-.011-2.198-.844-2.219-3.332z"/>
                            <path d="M15.045 6.096c.577-.7.966-1.674.86-2.646-.83.034-1.834.553-2.43 1.253-.535.62-1.003 1.61-.876 2.56.928.072 1.87-.472 2.446-1.167z"/>
                        </svg>
                        Apple
                    </button>
                </div>

                <!-- Registration Footer -->
                <footer class="mt-10 pt-8 text-center" style="border-top: 1px solid var(--sp-border);">
                    <p class="font-body-md" style="color: var(--sp-text-2);">
                        Don't have an account?
                        <Link :href="route('register')" class="font-bold hover:underline" style="color: #32cd32;">Register</Link>
                    </p>
                </footer>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full py-12 mt-auto" style="background-color: var(--sp-bg-2); border-top: 1px solid var(--sp-border);">
            <div class="flex flex-col md:flex-row justify-between items-center px-margin-desktop max-w-container-max mx-auto gap-base">
                <div class="flex items-center gap-2.5 font-headline-md text-headline-md font-extrabold" style="color: var(--sp-text);">
                    <span class="w-2 h-2 rounded-full bg-[#32cd32]"></span>
                    Social Pulse
                </div>
                <div class="flex gap-gutter my-4 md:my-0">
                    <a class="font-label-sm text-label-sm hover:text-[#32cd32] underline transition-all duration-300" style="color: var(--sp-text-2); text-decoration-color: var(--sp-border);" href="#">Privacy Policy</a>
                    <a class="font-label-sm text-label-sm hover:text-[#32cd32] underline transition-all duration-300" style="color: var(--sp-text-2); text-decoration-color: var(--sp-border);" href="#">Terms of Service</a>
                    <a class="font-label-sm text-label-sm hover:text-[#32cd32] underline transition-all duration-300" style="color: var(--sp-text-2); text-decoration-color: var(--sp-border);" href="#">Help Center</a>
                    <a class="font-label-sm text-label-sm hover:text-[#32cd32] underline transition-all duration-300" style="color: var(--sp-text-2); text-decoration-color: var(--sp-border);" href="#">Careers</a>
                </div>
                <p class="font-label-sm text-label-sm" style="color: var(--sp-text-2);">
                    © 2024 Social Pulse. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    display: inline-block;
    vertical-align: middle;
}
.kinetic-shadow:hover {
    box-shadow: 0px 10px 30px rgba(50, 205, 50, 0.15);
}

/* ── Guaranteed-readable inputs (fixes the white-on-white issue) ── */
.sp-login-input {
    caret-color: #32cd32;
}
.sp-login-input::placeholder {
    color: var(--sp-text-3);
    opacity: 0.7;
}
.sp-login-input:focus {
    outline: none;
    border-color: #32cd32 !important;
    box-shadow: 0 0 0 3px rgba(50, 205, 50, 0.15);
    background-color: var(--sp-bg-2);
}
.sp-login-input:hover {
    border-color: rgba(76, 227, 70, 0.5) !important;
}
/* Neutralize browser/autofill styles that can force white text on white bg */
.sp-login-input:-webkit-autofill {
    -webkit-text-fill-color: var(--sp-text);
    box-shadow: 0 0 0 1000px var(--sp-bg-2) inset;
    caret-color: #32cd32;
}

.sp-login-checkbox {
    background-color: var(--sp-bg-2);
}

.sp-login-social:hover {
    background-color: rgba(50, 205, 50, 0.06);
    border-color: rgba(76, 227, 70, 0.5) !important;
}

.sp-login-navlink::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 0;
    height: 1px;
    background: #32cd32;
    transition: width 0.3s ease;
}
.sp-login-navlink:hover {
    color: var(--sp-text) !important;
}
.sp-login-navlink:hover::after {
    width: 100%;
}

.sp-login-gradient-text {
    background: linear-gradient(135deg, #32cd32 0%, #4ce346 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.sp-login-pulse {
    animation: sp-login-pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes sp-login-pulse-ring {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.12); }
}

@keyframes sp-login-status-in {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}
.sp-login-status {
    animation: sp-login-status-in 0.4s ease both;
}

/* Primary submit button, matching the app's green gradient */
.sp-login-submit {
    background: linear-gradient(135deg, #32cd32 0%, #28a828 100%);
    color: #06120a;
}
.sp-login-submit:hover:not(:disabled) {
    box-shadow: 0 10px 30px rgba(50, 205, 50, 0.3);
}

/* Login card lift on hover for a bit of life */
.sp-login-card {
    transition: box-shadow 0.4s ease, border-color 0.4s ease;
}
.sp-login-card:hover {
    border-color: rgba(50, 205, 50, 0.25);
}

/* Entrance animation for the login card */
@keyframes rise-in {
    from {
        opacity: 0;
        transform: translateY(16px);
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
    50% { transform: translateY(-20px) translateX(10px); }
}
@keyframes float-slower {
    0%, 100% { transform: translateY(0) translateX(0); }
    50% { transform: translateY(18px) translateX(-14px); }
}
.animate-float-slow {
    animation: float-slow 9s ease-in-out infinite;
}
.animate-float-slower {
    animation: float-slower 11s ease-in-out infinite;
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
    .animate-shimmer,
    .sp-login-pulse,
    .sp-login-status {
        animation: none !important;
    }
}
</style>