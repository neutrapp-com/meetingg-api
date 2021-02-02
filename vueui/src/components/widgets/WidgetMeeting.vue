<template>
    <div class="widgetmeeting" :style="{ background: color }">
        <div class="w-8/12">
            <h2 class="title text-2xl font-bold">
                Design Daily Zoom Meeting
            </h2>
            <p class="datetime">
                <ion-icon name="time-outline"></ion-icon> {{ getStartHour }} - {{ getEndHour }} |  {{ getTimeRemaining }}
            </p>

            <div class="listusers mg-2">
                <ul class="members">
                    <li v-for="member in getMembers" v-bind:key="member.id">
                        <div :style="{backgroundImage: `url(${member.avatar})`}" class="avatar"></div>
                    </li>
                    <li>
                        <div style="background: url()" class="avatar"></div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="w-4/12 ">
            <span class="rounded-xl bg-gray-600 bg-opacity-40 border-opacity-5 px-6 pt-3 pb-1 float-right">
                <ion-icon name="options-outline" class="d-flex text-lg"/>
            </span>
        </div>
    </div>
</template>


<script>
export default {
    props : {
        color : {
            default : '#212534',
            type: String
        },
        startTime : {
            default : (Date.now()/1000 + 3600 * 3),
            type: Number
        },
        endTime : {
            default : (Date.now()/1000  + 3600 * 4),
            type: Number
        },
        members : {
            default : [
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/15-M.jpg' },
                { id: Math.random(), fullname: 'Jhon Doe', avatar: 'https://www.assyst.de/cms/upload/sub/digitalisierung/18-F.jpg' }
            ]
        }
    },
    computed : {
        getStartHour(){
            let startDate = this.toDateTime(this.startTime);
            return startDate.getHours() + ':' + startDate.getMinutes();
        },
        getEndHour(){
            let endDate = this.toDateTime(this.endTime);
            return endDate.getHours() + ':' + endDate.getMinutes();
        },
        getTimeRemaining(){
            let secRem = this.startTime - (Date.now()/1000);
            let rem = this.toDateTime(secRem);
            
            return (secRem > 0 ? `starts in ${rem.getHours()} hours` : `finished ${rem.getHours()} hours ago`);
        },
        getMembers(){
            return this.members.slice(0,5);
        }
    },
    methods : {
        toDateTime(secs) {
            var t = new Date(1970, 0, 1);
            t.setSeconds(secs);
            return t;
        }

    }
}
</script>

<style lang="scss" scoped>
    .widgetmeeting {
        @apply w-1/2 p-10 rounded-2xl text-white flex;



        ion-icon {
            fill: white;
        }

        .datetime {
            @apply  mt-2;
        }

        .members{
            @apply flex mt-4;

            .avatar {
                @apply mr-2;
            }
        }

        .avatar {
            @apply w-12 h-12 rounded-xl;
            background-size: cover;
            background-position: center;
        }
    }
</style>