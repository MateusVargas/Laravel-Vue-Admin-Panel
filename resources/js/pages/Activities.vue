<template>
	<div>
      	<h3 class="text-center">Activities</h3>
			<v-timeline>
				<v-timeline-item
				v-for="(activity, i) in activities"
				:key="i"
				small
				>
				<template v-slot:opposite>
					<span v-text="activity.created_at"></span>
				</template>
				<div class="py-4">
					<div>
					{{activity.causer.name}} {{activity.description}} {{activity.properties.attributes.name}}
					</div>
				</div>
				</v-timeline-item>
			</v-timeline>
  	</div>
</template>

<script>
  export default {
    data: () => ({
      activities: []
    }),

    created(){
    	axios.get('/api/activities').then(resp=>{
    		this.activities = resp.data
    	})
    }
  }
</script>

<style scoped>

</style>