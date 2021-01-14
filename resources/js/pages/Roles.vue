<template>
 <div>
  <v-toolbar dark color="grey-lighten">
    <v-toolbar-title>Roles</v-toolbar-title>
    <v-divider
          class="mx-4"
          inset
          vertical
    ></v-divider>
    <v-spacer></v-spacer>
    <v-dialog v-model="dialog" max-width="700px">
          
  <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >New Role
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
                      label="Role name"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-select
                      v-model="editedItem.permissions" :items="allPermissions"
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

  <v-data-table
    :headers="headers"
    :items="tableData"
    class="elevation-1"
  >
    <template v-slot:item="{ item }">
    <tr>
    <td>{{ item.name }}</td>
                <td style="width: 40%" v-if="item.permissions">
                    <v-chip
                            small
                            v-for="(permission,index) in item.permissions"
                            color="primary" text-color="white"
                            :key="index"
                    >
                        {{permission.name}}
                    </v-chip>
                </td>
                <td class="justify-center layout px-0">
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
                </td>
              </tr>
     </template>
     <template slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
 </div>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      dialogDelete: false,
      headers: [
        { text: 'Name', value: 'name' },
        { text: 'Permissions', value: 'created_at' },
        { text: 'Actions', value: 'name', sortable: false },
      ],
      tableData: [],
      editedIndex: -1,
      allPermissions: [],
      editedItem: {
        name: '',
        permissions: [],
      },
      defaultItem: {
        name: '',
        permissions: [],
      },
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'New Role' : 'Edit Role'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    created () {
      this.initialize()
    },

    methods: {
      initialize () {
        axios.get('/api/roles').then(resp=>
        resp.data.data ? this.tableData = resp.data.data : this.tableData = []
        )
        axios.get('/api/permissions').then(resp=>
        resp.data.data ? this.allPermissions = resp.data.data : this.allPermissions = []
        )
      },

      editItem (item) {
        this.editedIndex = this.tableData.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      deleteItem (item) {
        let role = this.tableData.indexOf(item)
        if(confirm('Apagar este item?')){
          axios.delete('/api/roles/'+item.id).then(resp=>this.tableData.splice(role,1))
        }
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      save () {
        if (this.editedIndex > -1) {
          Object.assign(this.tableData[this.editedIndex], this.editedItem)
          axios.put(`/api/roles/${this.editedItem.id}`,this.editedItem).then(resp => alert(resp.data.message))
        } else {
          this.tableData.push(this.editedItem)
          axios.post('/api/roles/',this.editedItem).then(resp => alert(resp.data.message))
        }
        this.close()
      },
    },
  }
</script>

