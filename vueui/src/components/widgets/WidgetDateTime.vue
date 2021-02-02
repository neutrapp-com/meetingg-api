<template>
    <div class="widget">
        <h2>12:02</h2>
        <h6>{{ updateDateWidget }}</h6>
    </div>
</template>

<script>
export default {
    name : 'WidgetDateTime',
    computed : {
        updateDateWidget(){
            return (new Date()).toString();
        }
    }
}
</script>

<style scoped lang="scss">
.widget {
    @apply w-1/2 h-40 p-10 rounded-2xl text-white;

    h2 {
        @apply text-6xl font-bold p-0 m-0 mb-2 mt-5;
    }

    h6 {
        @apply text-2xl p-0 m-0 mt-2 font-medium;
    }

    background-color: #446373;
    background-image: url('../../assets/images/widgetexample.png');
    background-size: auto 100%;
    background-position: right;
    background-repeat: no-repeat;
}
</style>