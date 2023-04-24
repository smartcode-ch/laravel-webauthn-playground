<template>
    <div class="w-20rem">
        <form @submit.prevent="login">

            <div class="flex flex-column gap-1 pb-3">
                <label for="email">E-Mail</label>
                <InputText id="email" name="email" required type="email" v-model="form.email" :class="{'p-invalid' : errors.email}" autofocus/>
                <small v-if="errors.email" class="p-error block mt-1" v-text="errors.email[0]"/>
            </div>

            <div class="flex justify-content-between align-items-center">
                <Link href="/register" class="text-sm text-gray-600">Register</Link>
                <Button type="submit">Login</Button>
            </div>

        </form>

    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import axios from 'axios'
import {arrayBufferToBase64, checkSupport, recursiveBase64StrToArrayBuffer} from '../helpers.js'
import {router} from '@inertiajs/vue3'
import {onMounted, reactive, ref} from 'vue'

const props = defineProps({
  user : Object
})

const form = reactive({
  email: props.user?.email,
})

let errors = ref({})

onMounted(() => checkSupport())

async function login() {

  try {
    errors.value = {};

    let response = await axios.post('/login/create', form);

    // Get check args
    const getArgs = await response.data;

    // replace binary base64 data with ArrayBuffer. a other way to do this
    // is the reviver function of JSON.parse()
    recursiveBase64StrToArrayBuffer(getArgs);

    // check credentials with hardware
    const credentials = await navigator.credentials.get(getArgs);

    // send to server
    router.post('/login/process', {
      credentialId: credentials.rawId ? arrayBufferToBase64(credentials.rawId) : null,
      clientDataJSON: credentials.response.clientDataJSON  ? arrayBufferToBase64(credentials.response.clientDataJSON) : null,
      authenticatorData: credentials.response.authenticatorData ? arrayBufferToBase64(credentials.response.authenticatorData) : null,
      signature: credentials.response.signature ? arrayBufferToBase64(credentials.response.signature) : null,
      userHandle: credentials.response.userHandle ? arrayBufferToBase64(credentials.response.userHandle) : null
    });

  } catch (e) {

    if (e.response.status === 422) {
      console.log('Login.vue 70 =>', e.response.data.errors.email)
      errors.value = e.response.data.errors;
    } else {
      throw e;
    }
  }

}

</script>
