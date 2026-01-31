<template>
    <Dialog :open="open" @update:open="$emit('update:open', $event)">
        <DialogContent class="sm:max-w-md">
            <DialogHeader class="gap-1">
                <DialogTitle class="text-heading">Referent Details</DialogTitle>
                <DialogDescription class="text-subheading">
                    View the details of this referent.
                </DialogDescription>
            </DialogHeader>
            <div v-if="referent" class="space-y-4 py-4">
                <div
                    class="w-full max-w-full divide-y rounded-xl border border-border"
                >
                    <div class="grid grid-cols-3 items-center gap-4 px-4 py-3">
                        <div class="text-label">Name</div>
                        <div class="text-body col-span-2">
                            {{ referent.name }} {{ referent.last_name }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4 px-4 py-3">
                        <div class="text-label">Email</div>
                        <div class="text-body col-span-2">
                            {{ referent.email || '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4 px-4 py-3">
                        <div class="text-label">Phone</div>
                        <div class="text-body col-span-2">
                            {{ referent.phone || '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 items-center gap-4 px-4 py-3">
                        <div class="text-label">Scope</div>
                        <div class="text-body col-span-2">
                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                                :class="
                                    referent.pivot?.scope === 'start'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-secondary text-secondary-foreground'
                                "
                            >
                                {{ referent.pivot?.scope }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button
                    size="sm"
                    type="button"
                    variant="outline"
                    @click="$emit('update:open', false)"
                >
                    Close
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
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
import type { Referent } from '@/types';

defineProps<{
    open: boolean;
    referent: Referent | null;
}>();

defineEmits<{
    'update:open': [value: boolean];
}>();
</script>
