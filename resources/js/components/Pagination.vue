<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { PaginationLink } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

interface Props {
    links: PaginationLink[];
}

defineProps<Props>();

function getLabel(label: string): string {
    if (label.includes('Previous')) return '';
    if (label.includes('Next')) return '';
    return label;
}
</script>

<template>
    <nav
        v-if="links.length > 3"
        class="flex items-center justify-center gap-1"
        aria-label="Pagination"
    >
        <template v-for="(link, index) in links" :key="index">
            <!-- Previous button -->
            <Button
                v-if="index === 0"
                :as="Link"
                :href="link.url ?? ''"
                :disabled="!link.url"
                variant="outline"
                size="icon"
                class="h-9 w-9"
                :class="{ 'pointer-events-none opacity-50': !link.url }"
                :preserve-scroll="true"
            >
                <ChevronLeft class="h-4 w-4" />
            </Button>

            <!-- Page numbers -->
            <Button
                v-else-if="index > 0 && index < links.length - 1"
                :as="Link"
                :href="link.url ?? ''"
                :variant="link.active ? 'default' : 'outline'"
                size="icon"
                class="h-9 w-9"
                :class="{ 'pointer-events-none': link.active }"
                :preserve-scroll="true"
            >
                {{ getLabel(link.label) }}
            </Button>

            <!-- Next button -->
            <Button
                v-else-if="index === links.length - 1"
                :as="Link"
                :href="link.url ?? ''"
                :disabled="!link.url"
                variant="outline"
                size="icon"
                class="h-9 w-9"
                :class="{ 'pointer-events-none opacity-50': !link.url }"
                :preserve-scroll="true"
            >
                <ChevronRight class="h-4 w-4" />
            </Button>
        </template>
    </nav>
</template>
