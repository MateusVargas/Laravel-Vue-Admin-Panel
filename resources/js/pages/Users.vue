<template>
  <v-data-table
    :headers="headers"
    :items="tableData"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Users</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              New User
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>
  

    <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="Name"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.email"
                      label="E-mail"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.password"
                      :append-icon="showPassword ? 'eye' : 'eye-off'"
                      :rules="[rules.required,rules.min]"
                      label="Password"
                      :type="showPassword ? 'text' : 'password'"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.confirm_password"
                      label="Confirm Password"
                      :append-icon="showPasswordConfirm ? 'md-eye' : 'eye-off'"
                      :rules="[rules.required,rules.min]"
                      :type="showPasswordConfirm ? 'text' : 'password'"
                    ></v-text-field>
                  </v-col>

                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
        <h3>Roles</h3>
                    <v-select
                      v-model="editedItem.role"
          :items="allRoles"
                      label="Roles"
          item-text="name"
          return-object
          chips
                    ></v-select>
                  </v-col>
     
      <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-select
                      v-model="editedItem.permissions"
          :items="allPermissions"
                      label="Permissions"
          item-text="name"
          return-object
          multiple
          chips
                    ></v-select>
                  </v-col>

                </v-row>
              </v-container>
            </v-card-text>



            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                color="blue darken-1"
                @click="save"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        edit
      </v-icon>
      <v-icon
        small
        @click="deleteItem(item)"
      >
        delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
</template>


<script>
  export default {
    data: () => ({
      dialog: false,
      headers: [
        { text: 'Username', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Role', value: 'role.name' },
        { text: 'Created', value: 'created_at' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      tableData: [],
      editedIndex: -1,
      allRoles: [],
      allPermissions: [],
      editedItem: {
        name: '',
        email: '',
        role: [],
        permissions: [],
        created_at: '',
      },
      defaultItem: {
        name: '',
        email: '',
        role: [],
        permissions: [],
        created_at: '',
      },
      showPassword: false,
      showPasswordConfirm: false,
      password: 'Password',
      rules: {
        required: value => !!value || 'Required.',
        min: value => value ? value.length >= 4 || 'Min 4 characters' : ''
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New User' : 'Edit User'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      }
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
        axios.get('/api/users').then(resp=>this.tableData = resp.data.data)
        axios.get('/api/roles').then(resp => this.allRoles = resp.data.data)
  axios.get('/api/permissions').then(resp =>this.allPermissions = resp.data.data)

      },

      editItem (item) {
        this.editedIndex = this.tableData.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        let user = this.tableData.indexOf(item)
        if(confirm('Apagar este item?')){
          axios.delete('/api/users/'+item.id).then(resp=>this.tableData.splice(user,1))
        }
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        },300)
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.tableData[this.editedIndex], this.editedItem)
          axios.put(`/api/users/${this.editedItem.id}`,this.editedItem).then(resp => alert(resp.data.message))
        } else {
          this.tableData.push(this.editedItem)
          axios.post('/api/users/',this.editedItem).then(resp => alert(resp.data.message))
        }
        this.close()
      },
    },
  }
</script>