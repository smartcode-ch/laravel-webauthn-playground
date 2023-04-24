<template>
    <div class="w-20rem">
        <form @submit.prevent="register">

            <div class="flex flex-column gap-1 pb-3">
                <label for="email">E-Mail</label>
                <InputText id="email" type="email" name="email" required v-model="form.email" autofocus />
            </div>

            <div class="flex flex-column gap-1 pb-3">
                <label for="name">Name</label>
                <InputText id="name" name="name" required v-model="form.name" />
            </div>

            <div class="flex justify-content-between align-items-center">
                <Link href="/login" class="text-sm text-gray-600">Login</Link>
                <Button type="submit">Register</Button>
            </div>

        </form>

    </div>
</template>

<script setup>
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import axios from 'axios'
import {arrayBufferToBase64, checkSupport, recursiveBase64StrToArrayBuffer} from '../helpers.js'
import {Link, router} from '@inertiajs/vue3'
import {onMounted, reactive} from 'vue'

const form = reactive({
  email: '',
  name: '',
})

onMounted(() => checkSupport())

async function register() {

  // Get create args from server.
  let response = await axios.post('/register/create', form)
  const createArgs = response.data;

  // replace binary base64 data with ArrayBuffer. a other way to do this
  // is the reviver function of JSON.parse()
  recursiveBase64StrToArrayBuffer(createArgs);

  // create credentials
  const cred = await navigator.credentials.create(createArgs);

  // check auth on server side
  router.post('/register/process', {
    transports: cred.response.getTransports  ? cred.response.getTransports() : null,
    clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
    attestationObject: cred.response.attestationObject ? arrayBufferToBase64(cred.response.attestationObject) : null
  });

}

</script>
