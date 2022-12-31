<template>
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <h3 class="navbar-brand">Shobuj</h3>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        <li v-if="user" class="nav-item">
                            <router-link :to="{name:'dashboard'}" class="nav-link">Home </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name:'products'}" class="nav-link">Products </router-link>
                        </li>
                    </ul>
                    <div v-if="user" class="d-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ user.name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <h3 class="card-header">Product show</h3>
                    <div class="card-body">

<section style="background-color: #eee;">
    <div class="row justify-content-center mb-3">
      <div class="col-md-12 col-xl-10">
        <div class="card shadow-0 border rounded-3">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                <div class="bg-image hover-zoom ripple rounded ripple-surface">
                  <!-- <img src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/E-commerce/Products/img%20(4).webp"
                    class="w-100" /> -->
                     <img  v-for="image in product.images" :key="image.id" :src="'/storage/'+image.name" class="w-100">
                  <a href="#!">
                    <div class="hover-overlay">
                      <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                    </div>
                  </a>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-6">
                <h5>{{ product.name }}</h5>
                <div v-if="product.category.length > 0" class="d-flex flex-row">
                  <div class="text-primary mb-1 me-2">
                      <span>Category</span>
                  </div>
                </div>
                <div v-for="data in product.category" :key="data.id" class="mt-1 mb-0 text-muted small">
                    <span class="text-primary"> • </span>
                  <span>{{ data.name ?? '' }}</span>
                  
                </div>
                
                <p class=" mb-4 mb-md-0">
                  {{ product.detail }}
                </p>
              </div>
              <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                <div class="d-flex flex-row align-items-center mb-1">
                  <h4 class="mb-1 me-1">Tk.{{ product.price }}</h4>
                  <!-- <span class="text-danger"><s>$20.99</s></span> -->
                </div>
                <!-- <h6 class="text-success">Free shipping</h6>
                <div class="d-flex flex-column mt-4">
                  <button class="btn btn-primary btn-sm" type="button">Details</button>
                  <button class="btn btn-outline-primary btn-sm mt-2" type="button">
                    Add to wishlist
                  </button>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>
 
<script>
    import { mapActions } from "vuex";
    import VueMultiselect  from 'vue-multiselect';
    export default {
        components: { VueMultiselect  },
        data() {
            return {
              user:this.$store.state.auth.user,
                product: {
                    attachments: [],
                    category: null,
                },
                categories: [],
                
            }
        },
        created(){
            axios.get('/api/categories')
            .then(response => {
                this.categories = response.data.data;
                
            }),
            axios.get('/api/products/'  + this.$route.params.id)
            .then(response => {
                this.product = response.data.data;
                console.log(response.data.data.categories)
                this.product.category = response.data.data.categories
                
            })
        },
        methods: {
            addProduct() {
                this.product.category = this.product.category.map((item) => item.id)
                axios.put(`/api/products/${this.$route.params.id}`, this.product)
                    .then(response => (
                        this.$router.push({ name: 'products' })
                    ))
                    .catch(err => console.log(err))
                    .finally(() => this.loading = false)
            },

            addAttachmentRow() {
                const obj = {
                    path: '',
                }
                this.product.attachments.push(obj)
            },
            removeAttachmentRow(index) {
                this.product.attachments.splice(index, 1)
            },

            ...mapActions({
                    signOut: "auth/logout",
                }),
                async logout() {
                    await axios.post("/api/auth/logout").then(({ data }) => {
                        this.signOut();
                        this.$toast.success('User logout success');
                        this.$router.push({ name: "login" });
                    });
                },
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>


<link rel="stylesheet" href="`https://unpkg.com/vue-multiselect@2.1.6/dist/vue-multiselect.min.css`">
