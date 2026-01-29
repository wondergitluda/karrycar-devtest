<template>
    <Head title="Shipments" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-2xl leading-tight font-semibold">
                Shipment #{{ shipment.id }}: {{ shipment.from }} ->
                {{ shipment.to }}
            </h1>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h2 class="mb-2 text-xl font-semibold">
                        General Information
                    </h2>
                    <p><strong>ID:</strong> {{ shipment.id }}</p>
                    <p><strong>TEAM:</strong> {{ shipment.team.name }}</p>
                    <p><strong>From:</strong> {{ shipment.from }}</p>
                    <p><strong>To:</strong> {{ shipment.to }}</p>
                </div>
            </div>

            <hr />

            <template
                v-for="(section, index) in referentSections"
                :key="section.scope"
            >
                <hr v-if="index > 0" />

                <div>
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-xl font-semibold">
                            {{ section.title }}
                        </h2>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="openModal(section.scope)"
                        >
                            <PlusIcon class="mr-1 h-4 w-4" />
                            Add
                        </Button>
                    </div>
                    <table
                        v-if="section.referents.length > 0"
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                    >
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Email
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                >
                                    Phone
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900"
                        >
                            <tr
                                v-for="referent in section.referents"
                                :key="referent.id"
                            >
                                <td
                                    class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                                >
                                    {{ referent.name }} {{ referent.last_name }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                                >
                                    {{ referent.email }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                                >
                                    {{ referent.phone }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                        No {{ section.scope }} referents.
                    </p>
                </div>
            </template>
        </div>

        <!-- Modal for adding referent -->
        <Dialog v-model:open="isModalOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Add Referent</DialogTitle>
                    <DialogDescription>
                        Add a new referent to this shipment.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div class="grid gap-2">
                        <Label for="scope">Scope</Label>
                        <Input
                            id="scope"
                            type="text"
                            :model-value="form.scope"
                            disabled
                            class="bg-gray-100 dark:bg-gray-800"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            type="text"
                            placeholder="Name"
                            v-model="form.name"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="last_name">Last Name</Label>
                        <Input
                            id="last_name"
                            type="text"
                            placeholder="Last Name"
                            v-model="form.last_name"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            placeholder="Email"
                            v-model="form.email"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="phone">Phone</Label>
                        <Input
                            id="phone"
                            type="tel"
                            placeholder="Phone"
                            v-model="form.phone"
                            required
                        />
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="isModalOpen = false"
                        >
                            Cancel
                        </Button>
                        <Button type="submit" :disabled="loading">
                            {{ loading ? 'Saving...' : 'Save' }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Shipment } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { PlusIcon } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<{
    shipment: Shipment;
}>();

const referentSections = computed(() => [
    {
        title: 'Start Referents',
        scope: 'start' as const,
        referents: props.shipment.start_referents,
    },
    {
        title: 'End Referents',
        scope: 'end' as const,
        referents: props.shipment.end_referents,
    },
]);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Shipments',
        href: '/shipments',
    },
    {
        title: props.shipment.from + ' -> ' + props.shipment.to,
        href: '',
    },
];

const isModalOpen = ref(false);
const loading = ref(false);

const form = reactive({
    name: '',
    last_name: '',
    email: '',
    phone: '',
    scope: '' as 'start' | 'end' | '',
});

const openModal = (scope: 'start' | 'end') => {
    form.scope = scope;
    form.name = '';
    form.last_name = '';
    form.email = '';
    form.phone = '';
    isModalOpen.value = true;
};

const submitForm = async () => {
    loading.value = true;
    try {
        await axios.post(`/shipments/${props.shipment.id}/addReferent`, form);
        isModalOpen.value = false;
        window.location.reload();
    } catch (error) {
        console.error('Error saving referent:', error);
    } finally {
        loading.value = false;
    }
};
</script>
