import { cloneDeep, isEmpty } from "lodash"

export default {
    data() {
        return {

        }
    },
    methods: {

    },
    mounted() {
        const { requiresAuth, requiresRoles } = this.$route.meta;
        if(requiresAuth){
            if(!requiresRoles.includes(this.userData.role)){
                this.$router.push({ path: '/' })
            }
        }
    }
}