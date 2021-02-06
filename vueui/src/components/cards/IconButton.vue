<template>
<a>
    <div class="iconbutton" v-bind:style="{ background: color }">
        <div class="icon" v-bind:style="{ backgroundImage: `url(${icon})`}">
            
        </div>
    </div>
</a>
</template>


<script>
export default {
    name: 'IconButton',
    props : {
        icon : {
            default : 'help',
            type: String
        },
        color : {
            default : '#232634',
            type: String
        }
    }
}
</script>

<style lang="scss">
.iconbutton{
    @apply h-12 w-12 p-2 rounded-lg;
    @apply border-2 rounded-lg;
    border-color: #2f3240;

    &:hover{
        @apply cursor-pointer transform  scale-105 shadow-2xl ;
    }

    .icon{
        @apply w-full h-full bg-no-repeat bg-contain;
    }
}
</style>