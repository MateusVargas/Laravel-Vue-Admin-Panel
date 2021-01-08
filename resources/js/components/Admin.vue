<template>
    <div id="app">
      <v-app id="inspire">
        <v-navigation-drawer
          v-model="drawer"
          app
          clipped
          fixed
        >
          <v-list dense>
            <v-list-item exact to="/admin">
              <v-list-item-action>
                <v-icon>dashboard</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Dashboard</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-list-group no-action>
              <v-list-item slot="activator">
                <v-list-item-action>
                  <v-icon>account_circle</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>User Management</v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item to='/admin/users'>
                <v-list-item-action>
                  <v-icon>account_circle</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>Users</v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item to='/admin/roles'>
                <v-list-item-action>
                  <v-icon>account_circle</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>Roles</v-list-item-title>
                </v-list-item-content>
              </v-list-item>

              <v-list-item to='/admin/permissions'>
                <v-list-item-action>
                  <v-icon>account_circle</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title>Permissions</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-group>

            <v-list-item to="/admin/activities">
              <v-list-item-action>
                <v-icon>settings</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Activities</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-list-item to='/admin/settings'>
              <v-list-item-action>
                <v-icon>settings</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Settings</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>
            
        <v-app-bar
          app
          clipped-left
        >
          <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
          <v-toolbar-title>Laravel Vue Admin
          </v-toolbar-title>
          <v-spacer></v-spacer>

          <v-menu
            bottom
            origin="center center"
            transition="scale-transition"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                v-bind="attrs"
                v-on="on"
                @click="markAsRead"
              >
                <v-badge
                  color="red"
                  overlap
                >
                  <span slot="badge">{{unreadNotifications.length}}</span>
                  <v-icon>notifications</v-icon>
                </v-badge>
              </v-btn>
            </template>

            <v-list>
              <v-list-item
                v-for="notification in allNotifications" :key="notification.id">
                  <v-list-item-title>{{ notification.data.createdUser.name }} has just registered
                   </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

          <v-menu
            bottom
            origin="center center"
            transition="scale-transition"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                icon
                v-bind="attrs"
                v-on="on"
              >
                <v-icon>account_circle</v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item>
                  <v-list-item-title>{{ user.name }}
                  </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-app-bar>
    
        <v-main>
          <v-container class="fill-height" fluid>
            <v-row
              align="center"
              justify="center"
            >
              <v-col class="shrink">
                <router-view></router-view>
              </v-col>
            </v-row>
          </v-container>
        </v-main>
    
        <v-footer app>
          <span>&copy; {{ new Date().getFullYear() }}</span>
        </v-footer>
      </v-app>
    </div>
</template>

<script>
    export default {
        data:()=>({
            drawer: null,
            items: [
              { title: 'Click Me' },
              { title: 'Click Me' },
              { title: 'Click Me' },
              { title: 'Click Me 2' },
            ],
            allNotifications: []
        }),
        props: ["user"],
        methods:{
            logout(){
              console.log('Component mounted.')
            },
            markAsRead(){
              axios.get('/mark-all-read/'+this.user.id).then(resp=>console.log('done'))
            }
        },
        computed:{
          unreadNotifications(){
            return this.allNotifications.filter(notification => {
              return notification.read_at == null
            })
          }
        },
        created(){
          this.allNotifications = window.user.user.notifications

          Echo.private("App.User." + this.user.id).notification(notification => {
            this.allNotifications.push(notification.notification)
          });
        }
    }
</script>

<style>
  .v-application a{
    text-decoration:none;
  }
  .v-list-group__items{
    width: 100%;
  }
  .v-list-group {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
</style>