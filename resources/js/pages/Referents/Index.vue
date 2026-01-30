<template>
    <Head title="Referents" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl"
        >
            <h1
                class="p-6 pb-3 text-3xl leading-tight font-semibold tracking-tighter"
            >
                Referents
            </h1>
            <!-- Table of referents -->
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
                            NAME
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            EMAIL
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >
                            PHONE
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
                            SHIPMENTS
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
                    <tr v-for="referent in referents.data" :key="referent.id">
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ referent.id }}
                        </td>
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
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ referent.team.name }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            {{ referent.shipments_count }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                        >
                            <a
                                :href="`/referents/${referent.id}/show`"
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600"
                            >
                                <SquarePenIcon class="h-4 w-4" />
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <Pagination :links="referents.links" />
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
    type Referent,
} from '@/types';
import { Head } from '@inertiajs/vue3';
import { SquarePenIcon } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Referents',
        href: '',
    },
];

const props = defineProps<{
    referents: PaginatedData<Referent>;
}>();
</script>
