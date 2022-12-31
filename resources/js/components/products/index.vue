<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <h3 class="navbar-brand">Shobuj DEV</h3>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown"
                    aria-controls="navbarNavDropdown"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        <li v-if="user.name" class="nav-item">
                            <router-link
                                :to="{ name: 'dashboard' }"
                                class="nav-link"
                                >Home
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link
                                :to="{ name: 'products' }"
                                class="nav-link"
                                >Products
                            </router-link>
                        </li>
                        <li v-if="user.name" class="nav-item">
                            <router-link
                                :to="{ name: 'products.create' }"
                                class="nav-link"
                                >Products Create
                            </router-link>
                        </li>
                    </ul>
                    <div v-if="user.name" class="d-flex">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle"
                                    href="#"
                                    id="navbarDropdownMenuLink"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    {{ user.name }}
                                </a>
                                <div
                                    class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="navbarDropdownMenuLink"
                                >
                                    <a
                                        class="dropdown-item"
                                        href="javascript:void(0)"
                                        @click="logout"
                                        >Logout</a
                                    >
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-else>
                       
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <router-link to="login" class="nav-link"
                            >Login</router-link
                        >
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <h2 class="card-header">Products List</h2>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Detail</th>
                                    <!-- <th>Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="product in products"
                                    :key="product.id"
                                >
                                    <td>{{ product.id }}</td>
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.price }}</td>
                                    <td>
                                        <span
                                            v-for="category in product.categories"
                                            :key="category.id"
                                        >
                                            <span class="text-primary">
                                                {{ category.name ?? "" }}</span
                                            >
                                        </span>
                                    </td>
                                    <td>{{ product.detail }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <router-link
                                                :to="{
                                                    name: 'products.show',
                                                    params: { id: product.id },
                                                }"
                                                class="btn btn-success"
                                                >Show</router-link
                                            >
                                            <router-link
                                                v-if="user"
                                                :to="{
                                                    name: 'products.edit',
                                                    params: { id: product.id },
                                                }"
                                                class="btn btn-primary"
                                                >Edit</router-link
                                            >
                                            <button
                                                v-if="user"
                                                class="btn btn-danger"
                                                @click="
                                                    deleteProduct(product.id)
                                                "
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
    name: "product-page-list",
    data() {
        return {
            user: this.$store.state.auth.user,
            products: [],
        };
    },
    created() {
        axios.get("/api/products").then((response) => {
            console.log("response.data");
            console.log(response.data);
            this.products = response.data.data;
        });
    },
    methods: {
        deleteProduct(id) {
            axios
                .delete(`/api/products/${id}`)
                .then((response) => {
                    this.$toast.success(`Product deleted success`);
                    let i = this.products.map((data) => data.id).indexOf(id);
                    this.products.splice(i, 1);
                })
                .catch((err) => {
                    // console.log(err.response.data.message)
                    this.$toast.error(err.response.data.message);
                });
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
    },
};
</script>
