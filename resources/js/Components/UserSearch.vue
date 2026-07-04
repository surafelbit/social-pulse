<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const searchQuery = ref('');
const results = ref([]);
const showDropdown = ref(false);
const containerRef = ref(null);
const isLoading = ref(false);

let timeout = null;

const handleInput = () => {
    if (timeout) clearTimeout(timeout);
    
    if (searchQuery.value.trim().length === 0) {
        results.value = [];
        isLoading.value = false;
        return;
    }
    
    isLoading.value = true;
    timeout = setTimeout(async () => {
        try {
            const response = await axios.get(route('users.search'), {
                params: { query: searchQuery.value }
            });
            results.value = response.data;
        } catch (error) {
            console.error('Search failed:', error);
        } finally {
            isLoading.value = false;
        }
    }, 300); // 300ms debounce
};

const closeDropdown = (e) => {
    if (containerRef.value && !containerRef.value.contains(e.target)) {
        showDropdown.value = false;
    }
};

const handleSelect = () => {
    searchQuery.value = '';
    results.value = [];
    showDropdown.value = false;
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>

<template>
    <div ref="containerRef" class="relative w-full max-w-xs md:max-w-sm">
        <div class="relative">
            <input
                v-model="searchQuery"
                @input="handleInput"
                @focus="showDropdown = true"
                placeholder="Search users..."
                class="sp-input text-xs py-2 pl-9 pr-8 rounded-full w-full"
                style="background-color: var(--sp-bg-2);"
            />
            <span class="absolute left-3 top-2.5 text-xs text-[var(--sp-text-3)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </span>
            <span v-if="isLoading" class="absolute right-3 top-2.5 text-xs text-[#32cd32]">
                <svg class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </div>

        <!-- Dropdown menu -->
        <div
            v-if="showDropdown && (results.length > 0 || searchQuery.trim().length > 0)"
            class="absolute top-full left-0 right-0 mt-2 rounded-2xl p-2 z-50 border shadow-xl sp-glass-card animate-rise-in"
            style="background-color: var(--sp-card); border-color: var(--sp-border);"
        >
            <div v-if="results.length === 0 && !isLoading" class="p-4 text-center text-xs" style="color: var(--sp-text-3);">
                No users found
            </div>
            <ul v-else class="space-y-1">
                <li v-for="user in results" :key="user.id">
                    <Link
                        :href="route('profile.show', user.username)"
                        @click="handleSelect"
                        class="flex items-center gap-3 p-2 rounded-xl hover:bg-[#32cd32]/10 hover:text-[#32cd32] transition-colors"
                    >
                        <div class="w-8 h-8 sp-avatar text-[10px]">
                            {{ user.name?.charAt(0) ?? '?' }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold" style="color: var(--sp-text);">{{ user.name }}</p>
                            <p class="text-[10px]" style="color: var(--sp-text-3);">@{{ user.username }}</p>
                        </div>
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>
