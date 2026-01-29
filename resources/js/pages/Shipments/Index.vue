<template>
    <Head title="Shipments" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-2xl leading-tight font-semibold">Shipments</h1>
            <!-- Table of shipments -->
            <table
                class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
            >
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            ID
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            TEAM
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            From
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            To
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            Start Referents
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            End Referents
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            Edit
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900"
                >
                    <tr v-for="shipment in shipments.data" :key="shipment.id">
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ shipment.id }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ shipment.team.name }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ shipment.from }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ shipment.to }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ shipment.start_referents.length }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ shipment.end_referents.length }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            <a
                                :href="`/shipments/${shipment.id}/show`"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600"
                            >
                                <SquarePenIcon class="h-4 w-4" />
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <Pagination :links="shipments.links" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import {
    type BreadcrumbItem,
    type PaginatedData,
    type Shipment,
} from '@/types';
import { Head } from '@inertiajs/vue3';
import { SquarePenIcon } from 'lucide-vue-next';

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

const props = defineProps<{
    shipments: PaginatedData<Shipment>;
}>();
</script>
