<template>
<a>
    <div class="participant" v-bind:style="{ background: color }">
        <div class="icon-contener">
            <div class="icon" v-bind:style="{ backgroundImage: `url(${icon})`}"></div>
        </div>
        <div class="sep"></div>
        <div class="text">
            {{ fullname }}
        </div>
        
    </div>
</a>
</template>


<script>
export default {
    name: 'ParticipantCard',
    props : {
        icon : {
            default : 'help',
            type: String
        },
        fullname : {
            default : 'name firstname',
            type: String
        }
    }
}
</script>

<style lang="scss">
.participant{
    @apply w-32 h-36 p-1;
    @apply flex flex-col;
    @apply border-2 rounded-lg;
    border-color: #2f3240;
    background-color: #242736;

    &:hover{
        @apply cursor-pointer transform  scale-105 shadow-2xl ;
    }

    .icon-contener{
        @apply w-full h-full;
        max-height: 30%;
        .icon{
            @apply h-12 w-12 bg-no-repeat bg-contain rounded-lg;
            margin: auto;
        }
    }

    .sep{
        min-height: 10%
    }

    .text{
        @apply overflow-hidden text-center;
        min-height: 40%;
        max-height: 60%;
        margin: auto;
        overflow-wrap: break-word;
        color: #93959c;
    }
}
</style>