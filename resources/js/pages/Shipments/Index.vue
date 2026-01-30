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
                                class="table-header-cell text-label"
                            >
                                ID
                            </th>
                            <th
                                scope="col"
                                class="table-header-cell text-label"
                            >
                                TEAM
                            </th>
                            <th
                                scope="col"
                                class="table-header-cell text-label"
                            >
                                From
                            </th>
                            <th
                                scope="col"
                                class="table-header-cell text-label"
                            >
                                To
                            </th>
                            <th
                                scope="col"
                                class="table-header-cell text-label"
                            >
                                Referents (s/e)
                            </th>
                            <th
                                scope="col"
                                class="table-header-cell text-label"
                            ></th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900"
                    >
                        <tr
                            v-for="shipment in shipments.data"
                            :key="shipment.id"
                        >
                            <td class="text-body table-cell">
                                {{ shipment.id }}
                            </td>
                            <td
                                class="text-body text-underline table-cell font-medium"
                            >
                                <a @click="viewShipment(shipment)">
                                    {{ shipment.team.name }}
                                </a>
                            </td>
                            <td class="text-body table-cell">
                                {{ shipment.from }}
                            </td>
                            <td class="text-body table-cell">
                                {{ shipment.to }}
                            </td>
                            <td class="text-body table-cell">
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
import { Head, router } from '@inertiajs/vue3';
import { EyeIcon, MoreHorizontalIcon } from 'lucide-vue-next';
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

const viewShipment = (shipment: Shipment) => {
    router.visit(`/shipments/${shipment.id}`);
};
</script>
