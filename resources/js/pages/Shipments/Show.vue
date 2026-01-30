<template>
    <Head title="Shipments" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6"
        >
            <!-- Header -->
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

            <!-- General Information -->
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <header class="mb-4 flex flex-col">
                        <h2 class="text-heading">General Information</h2>
                        <p class="text-subheading">
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
                            <div class="text-label">ID</div>
                            <div class="text-body col-span-2">
                                {{ shipment.id }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-3 items-center gap-4 px-4 py-3"
                        >
                            <div class="text-label">TEAM</div>
                            <div class="text-body col-span-2">
                                {{ shipment.team.name || '-' }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-3 items-center gap-4 px-4 py-3"
                        >
                            <div class="text-label">FROM</div>
                            <div class="text-body col-span-2">
                                {{ shipment.from || '-' }}
                            </div>
                        </div>
                        <div
                            class="grid grid-cols-3 items-center gap-4 px-4 py-3"
                        >
                            <div class="text-label">TO</div>
                            <div class="text-body col-span-2">
                                {{ shipment.to || '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4" />

            <!-- Referents panels -->
            <template
                v-for="(section, index) in referentSections"
                :key="section.scope"
            >
                <div class="block" :class="{ 'mt-8': index > 0 }">
                    <div class="mb-4 flex items-center justify-between">
                        <header>
                            <h2 class="text-heading">
                                {{ section.title }}
                            </h2>
                            <p class="text-subheading">
                                Manage the referents associated with this
                                shipment below.
                            </p>
                        </header>
                        <Button size="sm" @click="openAddModal(section.scope)">
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
                                        class="table-header-cell text-label"
                                    >
                                        ID
                                    </th>
                                    <th
                                        scope="col"
                                        class="table-header-cell text-label"
                                    >
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="table-header-cell text-label"
                                    >
                                        Email
                                    </th>
                                    <th
                                        scope="col"
                                        class="table-header-cell text-label"
                                    >
                                        Phone
                                    </th>
                                    <th scope="col" class="table-header-cell">
                                        &nbsp;
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
                                    <td class="text-body table-cell">
                                        {{ referent.id }}
                                    </td>
                                    <td
                                        class="text-body text-underline table-cell font-medium"
                                    >
                                        <a @click="openViewModal(referent)">
                                            {{ referent.name }}
                                            {{ referent.last_name }}
                                        </a>
                                    </td>
                                    <td class="text-body table-cell">
                                        {{ referent.email }}
                                    </td>
                                    <td class="text-body table-cell">
                                        {{ referent.phone }}
                                    </td>
                                    <td class="table-cell">
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
                    <p v-else class="text-subheading">
                        No {{ section.scope }} referents.
                    </p>
                </div>
            </template>
        </div>

        <!-- Modals -->
        <AddReferentModal
            v-model:open="isAddModalOpen"
            :shipment-id="shipment.id"
            :scope="currentScope"
            @saved="reloadPage"
        />

        <ViewReferentModal
            v-model:open="isViewModalOpen"
            :referent="selectedReferent"
        />

        <DeleteReferentModal
            v-model:open="isDeleteModalOpen"
            :shipment-id="shipment.id"
            :referent="selectedReferent"
            @deleted="reloadPage"
        />
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
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
import {
    EyeIcon,
    MoreHorizontalIcon,
    PlusIcon,
    Trash2Icon,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AddReferentModal from './components/AddReferentModal.vue';
import DeleteReferentModal from './components/DeleteReferentModal.vue';
import ViewReferentModal from './components/ViewReferentModal.vue';

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

// Modal state
const isAddModalOpen = ref(false);
const isViewModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const currentScope = ref<'start' | 'end'>('start');
const selectedReferent = ref<any>(null);

const openAddModal = (scope: 'start' | 'end') => {
    currentScope.value = scope;
    isAddModalOpen.value = true;
};

const openViewModal = (referent: any) => {
    selectedReferent.value = referent;
    isViewModalOpen.value = true;
};

const openDeleteModal = (referent: any) => {
    selectedReferent.value = referent;
    isDeleteModalOpen.value = true;
};

const reloadPage = () => {
    window.location.reload();
};
</script>
