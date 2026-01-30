<template>
    <Head title="Referents" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6"
        >
            <header class="flex items-center justify-between px-0 py-3">
                <div class="flex flex-col">
                    <h1 class="text-3xl leading-tight font-semibold">
                        Referents
                    </h1>
                    <p class="px-0 text-gray-600 dark:text-gray-400">
                        List of all referents in the system.
                    </p>
                </div>
            </header>
            <!-- Table of referents -->
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
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-900 uppercase dark:text-gray-400"
                            >
                                ID
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                NAME
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                EMAIL
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            >
                                PHONE
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
                                SHIPMENTS
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-2 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                            ></th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900"
                    >
                        <tr
                            v-for="referent in referents.data"
                            :key="referent.id"
                        >
                            <td
                                class="px-6 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ referent.id }}
                            </td>
                            <td
                                class="px-6 py-3 text-sm font-semibold whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ referent.name }} {{ referent.last_name }}
                            </td>
                            <td
                                class="px-6 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ referent.email }}
                            </td>
                            <td
                                class="px-6 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ referent.phone }}
                            </td>
                            <td
                                class="px-6 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ referent.team.name }}
                            </td>
                            <td
                                class="px-6 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ referent.shipments_count }}
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-center">
                                    <!-- Not required functions -->
                                    <!-- <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="sm">
                                                <MoreHorizontalIcon
                                                    class="h-4 w-4"
                                                />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem
                                                @click="viewReferent(referent)"
                                            >
                                                <EyeIcon class="mr-2 h-4 w-4" />
                                                View
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem
                                                class="text-red-600 focus:bg-red-100 focus:text-red-700 dark:text-red-400 dark:focus:bg-red-900/20 dark:focus:text-red-300"
                                                @click="
                                                    openDeleteModal(referent)
                                                "
                                            >
                                                <Trash2Icon
                                                    class="mr-2 h-4 w-4"
                                                />
                                                Delete
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu> -->
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
                                <Pagination :links="referents.links" />
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
import { Head, router } from '@inertiajs/vue3';

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

const viewReferent = (referent: Referent) => {
    router.visit(`/referents/${referent.id}/show`);
};
</script>
