<template>
  <div>
    <p>{{data}}</p>
  </div>
</template>

<script setup>
    const config = useRuntimeConfig();
    console.log(config.public.url);
    const {data} = await useFetch(config.public.url + '/check');
</script>
