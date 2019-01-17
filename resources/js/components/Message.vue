<template>
    <div>
        <li :class="className">
            <slot></slot>
            <span class="time">{{ time }}</span>
        </li>
        <small class="badge float-right" :class="badgeClass">{{ user.name }}</small>
    </div>
</template>

<script>
    export default {
        props: [
            'color',
            'user',
            'time'
        ],
        computed: {
            className() {
                return 'list-group-item-'+this.color;
            },
            badgeClass() {
                return 'badge-'+this.color;
            }
        },
        mounted() {
           // console.log('Message Component mounted.')
        }
    }
</script>

<style>
    li .time {
        color: #000;
        font-size: 0.75rem;
        float: right;
    }
</style>