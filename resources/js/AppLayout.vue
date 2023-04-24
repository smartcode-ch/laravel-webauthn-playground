<template>
    <div class="border-round shadow-3 p-4 bg-white" style="min-height: 10rem">
        <slot />
    </div>
</template>

<script setup>

</script>

<style lang="css">

@import url(https://fonts.bunny.net/css?family=abeezee:400);

body {
    height: 100vh;
    padding: 0;
    margin: 0;
    background-color: ghostwhite;
    font-family: 'ABeeZee', sans-serif;
    font-weight: 400;
}

#app {
    width: 100%;
    margin-top: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
