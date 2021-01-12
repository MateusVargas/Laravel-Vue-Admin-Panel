<template>
   <v-layout wrap>
       <v-flex v-for="item in blogs" :key="item.id">
           <v-card>
           	<v-card-title>{{item.title}}</v-card-title>
           	<v-card-subtitle>{{item.status}}</v-card-subtitle>
            <v-card-text>{{item.content}}</v-card-text>
            <v-card-actions>
		      <v-btn
		        color="deep-purple lighten-2"
		        text
		      >
		        Update
		      </v-btn>
		      <v-btn
		        color="deep-purple lighten-2"
		        text
		      >
		        Delete
		      </v-btn>
		    </v-card-actions>
          </v-card>
          <br/>
          <br/>
       </v-flex>
   </v-layout>
</template>

<script>
	export default {
		data: () => ({
			blogs: []
		}),
		created(){
			axios.get('/api/blogs').then(resp=>{
				this.blogs = resp.data.data
				console.log(resp.data.data)
			})
		}
	}
</script>

<style scoped>

</style>