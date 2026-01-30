<template>
    <Head title="Shipments" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6"
        >
            <header class="flex items-center justify-between px-0 py-3">
                <div class="flex flex-col">
                    <h1 class="text-3xl leading-tight font-semibold">
                        Shipment #{{ shipment.id }}
                    </h1>
                    <p class="px-0 text-gray-600 dark:text-gray-400">
                        {{ shipment.from }} ->
                        {{ shipment.to }}
                    </p>
                </div>
            </header>
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <header class="mb-4 flex flex-col">
                        <h2 class="text-xl font-semibold">
                            General Information
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Manage the referents associated with this shipment
                            below.
                        </p>
                    </header>
                    <div
                        class="w-fit max-w-full min-w-lg divide-y rounded-xl border border-border"
                    >
                        <div
                            class="grid grid-cols-3 items-center gap-4 px-4 py-3"
                        >
                            <div
                                class="text-xs font-semibold tracking-wider text-primary uppercase"
                            >
                                ID
                            </div>
                            <div
                                class="col-span-2 text-sm font-medium text-primary dark:text-gray-100"
                            >
                                {{ shipment.id }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-3 items-center gap-4 px-4 py-3"
                        >
                            <div
                                class="text-xs font-semibold tracking-wider text-primary uppercase"
                            >
                                TEAM
                            </div>
                            <div
                                class="col-span-2 flex items-center justify-between gap-2 text-sm font-medium text-primary dark:text-gray-100"
                            >
                                {{ shipment.team.name || '-' }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-3 items-center gap-4 px-4 py-3"
                        >
                            <div
                                class="text-xs font-semibold tracking-wider text-primary uppercase"
                            >
                                FROM
                            </div>
                            <div
                                class="col-span-2 flex items-center gap-1 text-sm font-medium text-primary dark:text-gray-100"
                            >
                                <Button
                                    as-child
                                    variant="ghost"
                                    size="icon"
                                    class="h-6 w-6"
                                >
                                    <span>
                                        <MapPinIcon class="h-4 w-4" />
                                    </span>
                                </Button>
                                {{ shipment.from || '-' }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-3 items-center gap-4 px-4 py-3"
                        >
                            <div
                                class="text-xs font-semibold tracking-wider text-primary uppercase"
                            >
                                TO
                            </div>
                            <div
                                class="col-span-2 flex items-center gap-2 text-sm font-medium text-primary dark:text-gray-100"
                            >
                                <Button
                                    as-child
                                    variant="ghost"
                                    size="icon"
                                    class="h-6 w-6"
                                >
                                    <span>
                                        <MapPinIcon class="h-4 w-4" />
                                    </span>
                                </Button>
                                {{ shipment.to || '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mb-4" />
            <template
                v-for="(section, index) in referentSections"
                :key="section.scope"
            >
                <div class="block" :class="{ 'mt-8': index > 0 }">
                    <div class="mb-4 flex items-center justify-between">
                        <header>
                            <h2 class="text-2xl font-semibold">
                                {{ section.title }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Manage the referents associated with this
                                shipment below.
                            </p>
                        </header>
                        <Button size="sm" @click="openModal(section.scope)">
                            <PlusIcon class="h-4 w-4" />
                            Add referent
                        </Button>
                    </div>
                    <div
                        v-if="section.referents.length > 0"
                        class="overflow-hidden rounded-lg border border-border dark:border-gray-700"
                    >
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                        >
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-2 text-left text-xs font-semibold tracking-wider text-gray-900 uppercase dark:text-gray-400"
                                    >
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-2 text-left text-xs font-semibold tracking-wider text-gray-900 uppercase dark:text-gray-400"
                                    >
                                        Email
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-2 text-left text-xs font-semibold tracking-wider text-gray-900 uppercase dark:text-gray-400"
                                    >
                                        Phone
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-2 text-right text-xs font-semibold tracking-wider text-gray-900 uppercase dark:text-gray-400"
                                    ></th>
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
                                        class="truncate px-6 py-1 text-sm font-medium whitespace-nowrap text-primary dark:text-gray-100"
                                    >
                                        {{ referent.name }}
                                        {{ referent.last_name }}
                                    </td>
                                    <td
                                        class="truncate px-6 py-1 text-sm whitespace-nowrap text-primary dark:text-gray-100"
                                    >
                                        {{ referent.email }}
                                    </td>
                                    <td
                                        class="truncate px-6 py-1 text-sm whitespace-nowrap text-primary dark:text-gray-100"
                                    >
                                        {{ referent.phone }}
                                    </td>
                                    <td
                                        class="px-6 py-1 text-right text-sm whitespace-nowrap"
                                    >
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button
                                                    variant="ghost"
                                                    size="sm"
                                                >
                                                    <MoreHorizontalIcon
                                                        class="h-4 w-4"
                                                    />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuItem
                                                    @click="
                                                        openViewModal(referent)
                                                    "
                                                >
                                                    <EyeIcon
                                                        class="mr-1 h-4 w-4"
                                                    />
                                                    View
                                                </DropdownMenuItem>
                                                <DropdownMenuSeparator />
                                                <DropdownMenuItem
                                                    class="text-red-600 focus:bg-red-100 focus:text-red-700 dark:text-red-400 dark:focus:bg-red-900/20 dark:focus:text-red-300"
                                                    @click="
                                                        openDeleteModal(
                                                            referent,
                                                        )
                                                    "
                                                >
                                                    <Trash2Icon
                                                        class="mr-1 h-4 w-4"
                                                    />
                                                    Delete
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                        No {{ section.scope }} referents.
                    </p>
                </div>
            </template>
        </div>
        <!-- Modal for adding referent -->
        <!-- <Dialog v-model:open="isModalOpen">
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
        </Dialog> -->
        <!-- Modal for viewing referent -->
        <Dialog v-model:open="isViewModalOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Referent Details</DialogTitle>
                    <DialogDescription>
                        View the details of this referent.
                    </DialogDescription>
                </DialogHeader>
                <div v-if="referentToView" class="space-y-4 py-4">
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Name
                        </div>
                        <div
                            class="col-span-2 text-sm font-medium text-primary dark:text-gray-100"
                        >
                            {{ referentToView.name }}
                            {{ referentToView.last_name }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Email
                        </div>
                        <div
                            class="col-span-2 text-sm font-medium text-primary dark:text-gray-100"
                        >
                            {{ referentToView.email || '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Phone
                        </div>
                        <div
                            class="col-span-2 text-sm font-medium text-primary dark:text-gray-100"
                        >
                            {{ referentToView.phone || '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Scope
                        </div>
                        <div class="col-span-2">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                                :class="
                                    referentToView.pivot?.scope === 'start'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'text-secodary-foreground bg-secondary'
                                "
                            >
                                {{ referentToView.pivot?.scope }}
                            </span>
                        </div>
                    </div>
                </div>
                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="isViewModalOpen = false"
                    >
                        Close
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
        <!-- Modal for deleting referent -->
        <Dialog v-model:open="isDeleteModalOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete Referent</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to remove this referent from the
                        shipment? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <div v-if="referentToDelete" class="space-y-4 py-4">
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Name
                        </div>
                        <div
                            class="col-span-2 text-sm font-medium text-primary dark:text-gray-100"
                        >
                            {{ referentToDelete.name }}
                            {{ referentToDelete.last_name }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Email
                        </div>
                        <div
                            class="col-span-2 text-sm font-medium text-primary dark:text-gray-100"
                        >
                            {{ referentToDelete.email || '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Phone
                        </div>
                        <div
                            class="col-span-2 text-sm font-medium text-primary dark:text-gray-100"
                        >
                            {{ referentToDelete.phone || '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4">
                        <div
                            class="text-xs font-semibold tracking-wider text-gray-400 uppercase"
                        >
                            Scope
                        </div>
                        <div class="col-span-2">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                                :class="
                                    referentToDelete.pivot?.scope === 'start'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'text-secodary-foreground bg-secondary'
                                "
                            >
                                {{ referentToDelete.pivot?.scope }}
                            </span>
                        </div>
                    </div>
                </div>
                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="isDeleteModalOpen = false"
                    >
                        Cancel
                    </Button>
                    <Button
                        type="button"
                        variant="destructive"
                        :disabled="deleting"
                        @click="deleteReferent"
                    >
                        {{ deleting ? 'Deleting...' : 'Delete' }}
                    </Button>
                </DialogFooter>
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
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Shipment } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import {
    EyeIcon,
    MapPinIcon,
    MoreHorizontalIcon,
    PlusIcon,
    Trash2Icon,
} from 'lucide-vue-next';
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
const isDeleteModalOpen = ref(false);
const deleting = ref(false);
const referentToDelete = ref<any>(null);
const isViewModalOpen = ref(false);
const referentToView = ref<any>(null);

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

const openViewModal = (referent: any) => {
    referentToView.value = referent;
    isViewModalOpen.value = true;
};

const openDeleteModal = (referent: any) => {
    referentToDelete.value = referent;
    isDeleteModalOpen.value = true;
};

const deleteReferent = async () => {
    if (!referentToDelete.value?.pivot?.id) return;

    deleting.value = true;
    try {
        await axios.delete(
            `/shipments/${props.shipment.id}/removeReferent/${referentToDelete.value.pivot.id}`,
        );
        isDeleteModalOpen.value = false;
        window.location.reload();
    } catch (error) {
        console.error('Error deleting referent:', error);
    } finally {
        deleting.value = false;
    }
};
</script>
