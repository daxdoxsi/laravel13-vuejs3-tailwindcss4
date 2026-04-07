<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { dashboard } from '@/routes';
import contact from '@/routes/contact';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import Toast from '@/components/ui/toast/Toast.vue';
import { computed, ref, watch, onMounted } from 'vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Panel de Control',
                href: dashboard(),
            },
        ],
    },
});

const page = usePage();
const status = computed(() => (page.props as any).status as string | null);
const showToast = ref(false);

onMounted(() => {
    if (status.value === 'mensaje-contacto-enviado') {
        showToast.value = true;
    }
});

watch(status, (newStatus) => {
    if (newStatus === 'mensaje-contacto-enviado') {
        showToast.value = true;
    }
});

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    description: '',
});

const submit = () => {
    form.post(contact.store().url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Panel de Control" />

    <Toast
        :show="showToast"
        message="¡Gracias! Tu mensaje ha sido enviado con éxito."
        @close="showToast = false"
    />

    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
        </div>
        <div
            class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-6 md:min-h-min dark:border-sidebar-border"
        >
            <div class="mx-auto max-w-2xl">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold tracking-tight">Formulario de Contacto</h2>
                    <p class="text-muted-foreground text-sm">
                        Déjanos tus datos y nos pondremos en contacto contigo lo antes posible.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="first_name">Nombre</Label>
                            <Input
                                id="first_name"
                                type="text"
                                v-model="form.first_name"
                                required
                                autofocus
                                placeholder="Tu nombre"
                            />
                            <InputError :message="form.errors.first_name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="last_name">Apellidos</Label>
                            <Input
                                id="last_name"
                                type="text"
                                v-model="form.last_name"
                                required
                                placeholder="Tus apellidos"
                            />
                            <InputError :message="form.errors.last_name" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="email">Correo Electrónico</Label>
                            <Input
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                placeholder="ejemplo@correo.com"
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="space-y-2">
                            <Label for="phone">Teléfono</Label>
                            <Input
                                id="phone"
                                type="tel"
                                v-model="form.phone"
                                placeholder="+34 000 000 000"
                            />
                            <InputError :message="form.errors.phone" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="description">Razón del Contacto / Descripción</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            required
                            class="border-input ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex min-h-[120px] w-full rounded-md border bg-transparent px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Cuéntanos más sobre el motivo de tu contacto..."
                        ></textarea>
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="flex items-center justify-end">
                        <Button :disabled="form.processing" type="submit">
                            {{ form.processing ? 'Enviando...' : 'Enviar Mensaje' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
