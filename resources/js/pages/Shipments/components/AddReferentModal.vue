<template>
    <Dialog :open="open" @update:open="$emit('update:open', $event)">
        <DialogContent class="sm:max-w-lg">
            <DialogHeader>
                <DialogTitle class="text-heading">Add Referent</DialogTitle>
                <DialogDescription class="text-subheading">
                    Add a
                    <span
                        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium capitalize"
                        :class="
                            scope === 'start'
                                ? 'bg-primary text-primary-foreground'
                                : 'bg-secondary text-secondary-foreground'
                        "
                    >
                        {{ scope }}
                    </span>
                    referent to this shipment.
                </DialogDescription>
            </DialogHeader>

            <!-- Mode toggle -->
            <div
                class="mt-3 flex gap-2 rounded-lg border border-border bg-muted p-1"
            >
                <Button
                    type="button"
                    size="sm"
                    :variant="mode === 'search' ? 'default' : 'ghost'"
                    class="flex-1"
                    @click="mode = 'search'"
                >
                    <SearchIcon class="mr-2 h-4 w-4" />
                    Search existing
                </Button>
                <Button
                    type="button"
                    size="sm"
                    :variant="mode === 'create' ? 'default' : 'ghost'"
                    class="flex-1"
                    @click="mode = 'create'"
                >
                    <UserPlusIcon class="mr-2 h-4 w-4" />
                    Create new
                </Button>
            </div>

            <!-- Error message -->
            <div
                v-if="formError"
                class="rounded-md bg-red-50 p-3 text-sm text-red-600 dark:bg-red-900/20 dark:text-red-400"
            >
                {{ formError }}
            </div>

            <!-- Search mode -->
            <div v-if="mode === 'search'" class="mt-4 space-y-4">
                <div class="relative">
                    <SearchIcon
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                    />
                    <Input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by name or email..."
                        class="pl-10"
                    />
                </div>

                <!-- Loading state -->
                <div
                    v-if="searching"
                    class="flex items-center justify-center py-8"
                >
                    <Loader2Icon
                        class="h-6 w-6 animate-spin text-muted-foreground"
                    />
                </div>

                <!-- Search results -->
                <div
                    v-else-if="searchResults.length > 0"
                    class="max-h-64 space-y-2 overflow-y-auto"
                >
                    <div
                        v-for="referent in searchResults"
                        :key="referent.id"
                        class="flex cursor-pointer items-center justify-between rounded-lg border p-3 transition-colors"
                        :class="[
                            referent.already_attached
                                ? 'cursor-not-allowed bg-muted opacity-50'
                                : selectedReferent?.id === referent.id
                                  ? 'border-primary! bg-primary-foreground!'
                                  : 'border-border hover:border-primary/50 hover:bg-muted/50',
                        ]"
                        @click="selectReferent(referent)"
                    >
                        <div class="flex flex-col">
                            <div class="text-body font-semibold">
                                {{ referent.name }} {{ referent.last_name }}
                            </div>
                            <div class="text-body-small">
                                {{ referent.email || 'No email' }}
                            </div>
                        </div>
                        <div v-if="referent.already_attached">
                            <Badge size="sm"> Already added </Badge>
                        </div>
                        <div v-else-if="selectedReferent?.id === referent.id">
                            <Badge size="sm"> Selected </Badge>
                        </div>
                    </div>
                </div>

                <!-- Empty state -->
                <div
                    v-else-if="!searching"
                    class="py-8 text-center text-muted-foreground"
                >
                    No referents found for this team. Create a new one.
                </div>
            </div>

            <!-- Create mode -->
            <form v-else @submit.prevent="submitForm" class="mt-4 space-y-4">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <Label for="name" class="text-label">Name *</Label>
                        <Input
                            id="name"
                            type="text"
                            placeholder="Name"
                            v-model="form.name"
                            required
                        />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="last_name" class="text-label"
                            >Last Name *</Label
                        >
                        <Input
                            id="last_name"
                            type="text"
                            placeholder="Last Name"
                            v-model="form.last_name"
                            required
                        />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="email" class="text-label">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            placeholder="Email"
                            v-model="form.email"
                        />
                    </div>
                    <div class="flex flex-col gap-1">
                        <Label for="phone" class="text-label">Phone</Label>
                        <Input
                            id="phone"
                            type="tel"
                            placeholder="Phone"
                            v-model="form.phone"
                        />
                    </div>
                </div>
            </form>

            <DialogFooter class="mt-4">
                <Button
                    size="sm"
                    type="button"
                    variant="outline"
                    @click="$emit('update:open', false)"
                >
                    Cancel
                </Button>
                <Button
                    size="sm"
                    type="button"
                    :disabled="
                        loading || (mode === 'search' && !selectedReferent)
                    "
                    @click="submitForm"
                >
                    <Loader2Icon
                        v-if="loading"
                        class="mr-2 h-4 w-4 animate-spin"
                    />
                    {{
                        loading
                            ? 'Saving...'
                            : mode === 'search'
                              ? 'Add Referent'
                              : 'Create & Add'
                    }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import axios from 'axios';
import { Loader2Icon, SearchIcon, UserPlusIcon } from 'lucide-vue-next';
import { onUnmounted, reactive, ref, watch } from 'vue';

const props = defineProps<{
    open: boolean;
    shipmentId: number;
    scope: 'start' | 'end';
}>();

const emit = defineEmits<{
    'update:open': [value: boolean];
    saved: [];
}>();

const mode = ref<'search' | 'create'>('search');
const searchQuery = ref('');
const searchResults = ref<any[]>([]);
const searching = ref(false);
const selectedReferent = ref<any>(null);
const formError = ref('');
const loading = ref(false);

const form = reactive({
    name: '',
    last_name: '',
    email: '',
    phone: '',
});

const resetModal = () => {
    mode.value = 'search';
    searchQuery.value = '';
    searchResults.value = [];
    selectedReferent.value = null;
    formError.value = '';
    form.name = '';
    form.last_name = '';
    form.email = '';
    form.phone = '';
};

// Load referents when modal opens
watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) {
            resetModal();
            searchReferents('');
        }
    },
);

// Debounced search
let searchTimeout: ReturnType<typeof setTimeout> | null = null;

watch(searchQuery, (newQuery) => {
    if (searchTimeout) clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        searchReferents(newQuery);
    }, 300);
});

onUnmounted(() => {
    if (searchTimeout) clearTimeout(searchTimeout);
});

const searchReferents = async (query: string) => {
    searching.value = true;
    try {
        const response = await axios.get(
            `/shipments/${props.shipmentId}/referents`,
            {
                params: { query, scope: props.scope },
            },
        );
        searchResults.value = response.data;
    } catch (error) {
        console.error('Error searching referents:', error);
    } finally {
        searching.value = false;
    }
};

const selectReferent = (referent: any) => {
    if (referent.already_attached) return;
    selectedReferent.value = referent;
};

const submitForm = async () => {
    loading.value = true;
    formError.value = '';

    try {
        if (mode.value === 'search') {
            if (!selectedReferent.value) {
                formError.value = 'Please select a referent.';
                loading.value = false;
                return;
            }

            await axios.post(`/shipments/${props.shipmentId}/referents`, {
                mode: 'existing',
                referent_id: selectedReferent.value.id,
                scope: props.scope,
            });
        } else {
            await axios.post(`/shipments/${props.shipmentId}/referents`, {
                mode: 'create',
                scope: props.scope,
                ...form,
            });
        }

        emit('update:open', false);
        emit('saved');
    } catch (error: any) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            formError.value = Object.values(errors).flat().join(' ');
        } else {
            formError.value = 'An error occurred. Please try again.';
        }
        console.error('Error saving referent:', error);
    } finally {
        loading.value = false;
    }
};
</script>
