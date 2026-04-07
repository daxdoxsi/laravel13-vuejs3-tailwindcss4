<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';

const props = defineProps<{
    show: boolean;
    message: string;
    duration?: number;
}>();

const emit = defineEmits(['close']);

const visible = ref(false);

const startTimer = () => {
    setTimeout(() => {
        visible.value = false;
        emit('close');
    }, props.duration || 3000);
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        visible.value = true;
        startTimer();
    }
}, { immediate: true });

onMounted(() => {
    if (props.show) {
        visible.value = true;
        startTimer();
    }
});
</script>

<template>
    <Transition
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="visible" class="pointer-events-auto fixed right-4 bottom-4 z-50 w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black/5 dark:bg-neutral-900 dark:ring-white/10">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ message }}</p>
                    </div>
                    <div class="ml-4 flex shrink-0">
                        <button type="button" @click="visible = false" class="inline-flex rounded-md bg-transparent text-neutral-400 hover:text-neutral-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none">
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
