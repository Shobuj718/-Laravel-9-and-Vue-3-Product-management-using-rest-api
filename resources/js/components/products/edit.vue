<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <h3 class="card-header">Update Product</h3>
                    <div class="card-body">
                        <form @submit.prevent="addProduct">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" required v-model="product.name">
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" class="form-control" required v-model="product.price">
                            </div>
                            <label>Detail</label>
                            <div class="form-group">
                                <textarea name="detail" v-model="product.detail" required id="" cols="130" rows="3"></textarea>
                                <!-- <input type="text" class="form-control" v-model="product.detail"> -->
                            </div>
                            <div class="form-group">
                                <VueMultiselect 
                                    v-model="product.category" 
                                    :options="categories" 
                                    :multiple="true"
                                    :close-on-select="true"
                                    label="name"
                                    placeholder="Select category"
                                    track-by="name"
                                    />
                            </div>

                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="form-control" id="form-control"
                                        ref="file" @change="handleFileObject()">
                                        
                            </div>

                            <!-- <div class="form-group">
                                <tr v-for="(q, index) in product.attachments" :key="q.id">
                                    <input
                                        id="path"
                                        :ref="`attachment_${index}`"
                                        type="file"
                                        name="path"
                                        class="form-control"
                                        aria-label="path"
                                        accept=".jpg,.png,.jpeg,image/png,image/jpg,image/jpeg"
                                    />
                                        <span
                                        style="cursor: pointer;color:red"
                                        @click="removeAttachmentRow(index)"
                                        > Remove
                                        </span>
                                    
                                </tr>

                                <div class="btn btn-success">
                                    <span class="button-add" @click="addAttachmentRow()">
                                        Add Image
                                    </span>
                                </div>
                                
                            </div> -->
                            <br>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
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
                product_image : null,
                product_image_name: null,
                
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
                // if(this.form.category){
                //     this.form.category = this.form.category.map((item) => item.id)
                // }
                this.product.category = this.product.category.map((item) => item.id)
                axios.put(`/api/products/${this.$route.params.id}`, this.product)
                    .then(response => {
                        this.$toast.success(`Product updated success`);
                        this.$router.push({ name: 'products' })
                    })
                    .catch((err) => {
                        // console.log(err.response.data.message)
                        this.$toast.error(err.message);
                     })
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
