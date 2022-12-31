<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <h3 class="card-header">Create Product</h3>
                    <div class="card-body">
                        <form @submit.prevent="addProduct" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" required v-model="form.name">
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" class="form-control" required v-model="form.price">
                            </div>
                            <label>Detail</label>
                            <div class="form-group">
                                <textarea name="detail" v-model="form.detail" required id="" cols="50" rows="3"></textarea>
                                <!-- <input type="text" class="form-control" v-model="form.detail"> -->
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <VueMultiselect 
                                    v-model="form.category" 
                                    :options="categories" 
                                    :multiple="true"
                                    :close-on-select="true"
                                    label="name"
                                    placeholder="Select category"
                                    track-by="name"
                                    required="true"
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

                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>
 
<script>
    import { mapActions } from "vuex";
    //   import { file } from '@babel/types';
    import VueMultiselect  from 'vue-multiselect';
    export default {
        components: { VueMultiselect  },
        data() {
            return {
                product: {
                    attachments: [],
                },
                form :{},
                category: null,
                categories: [],
                product_image : null,
                product_image_name: null,
                file: '',
                
            }
        },
        created(){
            axios.get('/api/categories')
            .then(response => {
                this.categories = response.data.data;
                
            })
        },
        methods: {
            handleFileObject() {
                
                this.product_image = this.$refs.file.files[0]
                this.product_image_name = this.product_image.name
            },
            onChange(e) {
                this.file = e.target.files[0];
            },
            addProduct(e) {
                console.log(this.form)
                if(this.form.category){
                    this.form.category = this.form.category.map((item) => item.id)
                }
                // let form = new FormData()
                // form.append('product_image', this.product_image)
                //  _.each(this.form, (value, key) => {
                //     form.append(key, value)
                // })
                // axios.post('/api/products', this.form,{
                //     headers: {
                //         'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
                //     }
                // })

                
                axios.post('/api/products', this.form)
                    .then(response => {
                        this.$toast.success(`Product created success`);
                        this.$router.push({ name: 'products' })
                        })
                    .catch((err) => {
                        this.form.category = null
                        // console.log(err.response.data.message)
                        this.$toast.error(err.response.data.message);
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
                        this.$router.push({ name: "login" });
                    });
                },
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>


<link rel="stylesheet" href="`https://unpkg.com/vue-multiselect@2.1.6/dist/vue-multiselect.min.css`">
