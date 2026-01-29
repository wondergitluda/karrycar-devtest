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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h2 class="mb-2 text-xl font-semibold">Start Referents</h2>
                    <ul class="list-inside list-disc">
                        <li
                            v-for="referent in shipment.start_referents"
                            :key="referent.id"
                        >
                            {{ referent.name }} {{ referent.last_name }}
                            <br />
                            ({{ referent.email }})
                        </li>
                    </ul>
                </div>
                <div>
                    <ReferentForm :shipment-id="shipment.id" scope="start" />
                </div>
            </div>

            <hr />

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h2 class="mb-2 text-xl font-semibold">End Referents</h2>
                    <ul class="list-inside list-disc">
                        <li
                            v-for="referent in shipment.end_referents"
                            :key="referent.id"
                        >
                            {{ referent.name }} {{ referent.last_name }}
                            <br />
                            ({{ referent.email }})
                        </li>
                    </ul>
                </div>
                <div>
                    <ReferentForm :shipment-id="shipment.id" scope="end" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ReferentForm from '@/components/ReferentForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type Shipment } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    shipment: Shipment;
}>();

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
</script>
