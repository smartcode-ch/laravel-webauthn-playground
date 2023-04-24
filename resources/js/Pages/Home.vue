<template>
    <div class="w-30rem">
        <div class="flex flex-column gap-1 pb-2">
            Yuo are logged in as
        </div>

        <div class="flex flex-column gap-1 pb-3 text-4xl">
            {{ user.name }}
        </div>

        <div class="flex">
            <Button type="submit" class="block" @click="router.get('/logout')">Logout</Button>
        </div>
    </div>
</template>
<script setup>
import Button from 'primevue/button'
import {router} from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
})
</script>
