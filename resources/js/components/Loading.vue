<template>
    <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
            <span class="sr-only">Espere...</span>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        view:{
            type: Boolean,
            required: true
        }
    }
}
</script>
