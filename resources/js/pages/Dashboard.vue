<template>
  <v-layout wrap>
    <v-dialog v-model="dialog" max-width="500px">
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          color="primary"
          dark
          class="mb-2 new-blog"
          v-bind="attrs"
          v-on="on"
        >
          +
        </v-btn>
      </template>

      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="12" md="12">
                <v-text-field
                  v-model="editedItem.title"
                  label="Title"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="12">
                <v-textarea
                  clearable
                  clear-icon="close"
                  v-model="editedItem.content"
                  label="Content"
                ></v-textarea>
              </v-col>
              <v-col cols="12" sm="12" md="12"> </v-col>

              <v-col cols="12" sm="12" md="12">
                <h3>Categories</h3>

                <v-select
                  v-model="editedItem.categories"
                  :items="allCategories"
                  label="Categories"
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
          <v-btn color="blue darken-1" @click="close"> Cancel </v-btn>
          <v-btn color="blue darken-1" @click="save"> Save </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-flex v-for="item in blogs" :key="item.id">
      <v-card>
        <v-card-title>{{ item.title }}</v-card-title>
        <v-card-subtitle>{{ item.status }}</v-card-subtitle>
        <v-card-text>{{ item.content }}</v-card-text>
        <v-card-text>
          <v-chip-group
            column
            v-for="category in item.categories"
            :key="category.id"
          >
            <v-chip>{{ category.name }}</v-chip>
          </v-chip-group>
        </v-card-text>
        <v-card-actions>
          <v-btn color="deep-purple lighten-2" text @click="editItem(item)">
            Edit
          </v-btn>
          <v-btn color="deep-purple lighten-2" text @click="deleteItem(item)">
            Delete
          </v-btn>
        </v-card-actions>
      </v-card>
      <br />
      <br />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    blogs: [],
    editedIndex: -1,
    allCategories: [],
    editedItem: {
      title: "",
      content: "",
      categories: [],
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Blog" : "Edit Blog";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  created() {
    axios.get("/api/blogs").then((resp) => {
      this.blogs = resp.data.data;
    });
    axios.get("/api/categories").then((resp) => {
      this.allCategories = resp.data.data;
    });
  },

  methods: {
    editItem(item) {
      this.editedIndex = this.blogs.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      let blog = this.blogs.indexOf(item);
      if (confirm("Apagar este item?")) {
        axios
          .delete("/api/blogs/" + item.id)
          .then((resp) => this.blogs.splice(blog, 1));
      }
    },
    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },
    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.blogs[this.editedIndex], this.editedItem);
        axios
          .put(`/api/blogs/${this.editedItem.id}`, this.editedItem)
          .then((resp) => {
            if (resp.data.data.id) {
              alert("Blog updated");
            }
          });
      } else {
        this.blogs.push(this.editedItem);
        axios.post("/api/blogs/", this.editedItem).then((resp) => {
          if (resp.data.data.id) {
            alert("Blog created");
          }
        });
      }
      this.close();
    },
  },
};
</script>

<style scoped>
.new-blog {
  position: fixed;
  bottom: 30px;
  right: 5px;
  min-width: 20px !important;
}
</style>