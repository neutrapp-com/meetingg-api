<template>
<div class="meeting-id" v-bind:style="{ background: color }">
    <div class="icon">
        <div class="text-icon">
            <p>Id</p>
        </div>
    </div>
    <div class="text">
        <p>Meeting id:</p>
        <h1>{{ numberId }}</h1>
    </div>
</div>
</template>


<script>
export default {
    name: 'MeetingId',
    props : {
        numberId : {
            default : 'XXX XXX XXXX',
            type: String
        }
    }
}
</script>

<style lang="scss">
.meeting-id{
    @apply flex;
    min-width: 30rem; /* can provoked some problems, remove if you need */
    .icon{
        @apply flex;
        width: 20%;

        .text-icon{
            @apply h-12 w-12 flex m-auto;
            @apply border-2 rounded-lg;
            border-color: #2f3240;

            p{
                @apply m-auto;
                color: #7d7e87;
            }
        }
    }

    .text{
        @apply flex flex-col text-center;
        /*justify-content: space-around;*/
        margin: auto;
        width: 80%;

        p{
            @apply text-lg;
            color: #7d7e87;
        }

        h1{
            @apply text-5xl;
            font-weight: bold;
            color: #dcdcdf;
        }
    }
}
</style>