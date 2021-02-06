<template>
<a>
    <div class="textbutton" v-bind:style="{ background: color, color: fontColor }">
        <p>{{ text }}</p>
    </div>
</a>
</template>


<script>
export default {
    name: 'TextButton',
    props : {
        text : {
            default : 'Your Text Here',
            type: String
        },
        color : {
            default : '#232634',
            type: String
        },
        fontColor : {
            default : '#97989e',
            type: String
        }
    }
}
</script>

<style lang="scss">
.textbutton{
    @apply h-12 px-6 py-3 rounded-lg;
    @apply border-2 rounded-lg;
    border-color: #2f3240;

    &:hover{
        @apply cursor-pointer transform  scale-105 shadow-2xl ;
    }
}
</style>