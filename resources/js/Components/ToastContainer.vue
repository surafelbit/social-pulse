<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const toasts = ref([]);

const handleShowToast = (e) => {
    const { message, type } = e.detail;
    const id = Date.now();
    toasts.value.push({ id, message, type });
    
    // Auto-remove toast after 4 seconds
    setTimeout(() => {
        removeToast(id);
    }, 4000);
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter(t => t.id !== id);
};

onMounted(() => {
    window.addEventListener('show-toast', handleShowToast);
});

onUnmounted(() => {
    window.removeEventListener('show-toast', handleShowToast);
});
</script>

<template>
    <div class="fixed bottom-5 right-5 z-[9999] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        <TransitionGroup name="toast">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="pointer-events-auto flex items-center justify-between p-4 rounded-2xl border shadow-xl backdrop-blur-xl animate-rise-in"
                :class="[
                    toast.type === 'error'
                        ? 'border-red-500/20 bg-red-950/90 text-red-400'
                        : 'border-[#32cd32]/25 bg-black/90 text-white'
                ]"
                style="border-width: 1px;"
            >
                <div class="flex items-center gap-2.5">
                    <!-- Icon -->
                    <span v-if="toast.type === 'error'" class="text-red-500 text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </span>
                    <span v-else class="text-[#32cd32] text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                    <span class="text-xs font-semibold tracking-wide">{{ toast.message }}</span>
                </div>
                <button
                    @click="removeToast(toast.id)"
                    class="ml-4 text-xs opacity-50 hover:opacity-100 transition-opacity"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-enter-from {
    opacity: 0;
    transform: translateY(20px) scale(0.9);
}
.toast-leave-to {
    opacity: 0;
    transform: translateY(-20px) scale(0.9);
}
</style>
