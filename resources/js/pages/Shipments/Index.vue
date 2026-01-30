<template>
    <Head title="Shipments" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6"
        >
            <header class="flex items-center justify-between px-0 py-3">
                <div class="flex flex-col">
                    <h1 class="text-3xl leading-tight font-semibold">
                        Shipments
                    </h1>
                    <p class="px-0 text-gray-600 dark:text-gray-400">
                        List of all shipments in the system.
                    </p>
                </div>
            </header>
            <div
                class="overflow-hidden rounded-lg border border-border dark:border-gray-700"
            >
                <!-- Table of shipments -->
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                >
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-semibold tracking-wider text-gray-900 uppercase dark:text-gray-400"
                            >
                                ID
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                TEAM
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                From
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                To
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                Referents (s/e)
                            </th>
                            <th scope="col" class="px-6 py-2"></th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900"
                    >
                        <tr
                            v-for="shipment in shipments.data"
                            :key="shipment.id"
                        >
                            <td
                                class="truncate px-6 py-3 text-sm whitespace-nowrap text-primary dark:text-gray-100"
                            >
                                {{ shipment.id }}
                            </td>
                            <td
                                class="truncate px-6 py-3 text-sm font-medium whitespace-nowrap text-primary dark:text-gray-100"
                            >
                                {{ shipment.team.name }}
                            </td>
                            <td
                                class="truncate px-6 py-3 text-sm whitespace-nowrap text-primary dark:text-gray-100"
                            >
                                {{ shipment.from }}
                            </td>
                            <td
                                class="truncate px-6 py-3 text-sm whitespace-nowrap text-primary dark:text-gray-100"
                            >
                                {{ shipment.to }}
                            </td>
                            <td
                                class="truncate px-6 py-3 text-sm whitespace-nowrap text-primary dark:text-gray-100"
                            >
                                {{ shipment.start_referents.length }} /
                                {{ shipment.end_referents.length }}
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-center">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="sm">
                                                <MoreHorizontalIcon
                                                    class="h-4 w-4"
                                                />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem
                                                @click="viewShipment(shipment)"
                                            >
                                                <EyeIcon class="mr-2 h-4 w-4" />
                                                View
                                            </DropdownMenuItem>
                                            <!-- Not required function -->
                                            <!-- <DropdownMenuItem
                                                class="text-red-600 focus:bg-red-100 focus:text-red-700 dark:text-red-400 dark:focus:bg-red-900/20 dark:focus:text-red-300"
                                                @click="
                                                    openDeleteModal(shipment)
                                                "
                                            >
                                                <Trash2Icon
                                                    class="mr-2 h-4 w-4"
                                                />
                                                Delete
                                            </DropdownMenuItem> -->
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td
                                colspan="6"
                                class="bg-gray-50 px-6 py-4 dark:bg-gray-800"
                            >
                                <Pagination :links="shipments.links" />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Function not actually required -->
        <!-- Modal for deleting shipment -->
        <!-- <Dialog v-model:open="isDeleteModalOpen">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Delete Shipment</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this shipment? This
                        action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <div v-if="shipmentToDelete" class="py-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>ID:</strong> {{ shipmentToDelete.id }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Route:</strong> {{ shipmentToDelete.from }} ->
                        {{ shipmentToDelete.to }}
                    </p>
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
                        @click="deleteShipment"
                    >
                        {{ deleting ? 'Deleting...' : 'Delete' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog> -->
    </AppLayout>
</template>

<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import {
    type BreadcrumbItem,
    type PaginatedData,
    type Shipment,
} from '@/types';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { EyeIcon, MoreHorizontalIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Shipments',
        href: '',
    },
];

defineProps<{
    shipments: PaginatedData<Shipment>;
}>();

const isDeleteModalOpen = ref(false);
const deleting = ref(false);
const shipmentToDelete = ref<Shipment | null>(null);

const viewShipment = (shipment: Shipment) => {
    router.visit(`/shipments/${shipment.id}/show`);
};

const openDeleteModal = (shipment: Shipment) => {
    shipmentToDelete.value = shipment;
    isDeleteModalOpen.value = true;
};

const deleteShipment = async () => {
    if (!shipmentToDelete.value) return;

    deleting.value = true;
    try {
        await axios.delete(`/shipments/${shipmentToDelete.value.id}`);
        isDeleteModalOpen.value = false;
        window.location.reload();
    } catch (error) {
        console.error('Error deleting shipment:', error);
    } finally {
        deleting.value = false;
    }
};
</script>
