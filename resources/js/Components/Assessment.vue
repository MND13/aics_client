<template>
    <v-card flat>

        <v-card-text>

            <GISComponent :dialog_data="data" :user-data="userData"></GISComponent>

        </v-card-text>

    </v-card>
</template>

<script>
import userMixin from '../Mixin/userMixin';
import GISComponent from './GISComponent.vue';

export default {
    components: { GISComponent },
    mixins: [userMixin],
    props: ["uuid"],
    data() {
        return {
            data: {},
        }
    },
    methods: {
        getAssistanceData() {
            // console.log({ "assistance": this.$route.params.uuid });
            axios.get(route("assistances.show", { "assistance": this.$route.params.uuid }),)
                .then(response => {
                    //console.log(response.data);
                    this.data = response.data;
                })
        }
    },
    mounted() {
        this.getAssistanceData();
    }

}
</script>

<style></style>