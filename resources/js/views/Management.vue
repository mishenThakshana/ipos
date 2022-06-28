<template>
    <Navbar :class="[modalVisibility ? 'blur-md' : '']" />
    <div :class="['container']">
        <div class="row">
            <Container
                :title="!editMode ? 'Add new product' : 'Edit product'"
                icon="far fa-cube"
                :class="modalVisibility ? 'blur-md' : ''"
            >
                <div>
                    <p
                        v-if="productState.returned_errors.product_name"
                        class="text-red-700"
                        v-text="
                            String(productState.returned_errors.product_name)
                        "
                    ></p>
                    <input
                        v-model="productState.product_name"
                        ref="focusProductName"
                        class="bg-gray-800 text-gray-200 my-1 p-1 w-64 border border-1 border-indigo-500 outline-none"
                        type="text"
                        placeholder="Product name"
                        autofocus
                    />
                </div>
                <div>
                    <p
                        v-if="productState.returned_errors.product_cost"
                        class="text-red-700"
                        v-text="
                            String(productState.returned_errors.product_cost)
                        "
                    ></p>
                    <input
                        v-model="productState.product_cost"
                        @keyup="calculateMarkup"
                        class="bg-gray-800 text-gray-200 my-1 p-1 w-48 border border-1 border-indigo-500 outline-none"
                        type="number"
                        placeholder="Cost (Rs.)"
                    />
                </div>

                <div>
                    <p
                        v-if="productState.returned_errors.product_markup"
                        class="text-red-700"
                        v-text="
                            String(productState.returned_errors.product_markup)
                        "
                    ></p>
                    <input
                        v-model="productState.product_markup"
                        @keyup="calculateMarkup"
                        class="bg-gray-800 text-gray-200 my-1 p-1 w-48 border border-1 border-indigo-500 outline-none"
                        type="number"
                        :placeholder="productState.markup_type"
                    />
                    <button
                        @click="
                            () => {
                                productState.markup_type = 'Markup by price';
                                calculateMarkup();
                            }
                        "
                        :class="[
                            'mx-2 h-8 w-10 text-gray-200 border border-1 border-indigo-500',
                            productState.markup_type == 'Markup by price'
                                ? 'bg-indigo-500'
                                : '',
                        ]"
                        type="button"
                    >
                        <i class="fas fa-dollar-sign"></i>
                    </button>
                    <button
                        @click="
                            () => {
                                productState.markup_type =
                                    'Markup by percentage';
                                calculateMarkup();
                            }
                        "
                        :class="[
                            'h-8 w-10 text-gray-200 border border-1 border-indigo-500',
                            productState.markup_type == 'Markup by percentage'
                                ? 'bg-indigo-500'
                                : '',
                        ]"
                        type="button"
                    >
                        <i class="fas fa-percentage"></i>
                    </button>
                </div>

                <div>
                    <p
                        v-if="productState.returned_errors.product_selling"
                        class="text-red-700"
                        v-text="
                            String(productState.returned_errors.product_selling)
                        "
                    ></p>
                    <input
                        v-model="productState.product_selling"
                        class="bg-gray-800 text-gray-200 my-1 p-1 w-48 border border-1 border-indigo-500 outline-none"
                        type="number"
                        placeholder="Selling price (Rs.)"
                        disabled
                    />
                </div>

                <div>
                    <p
                        v-if="productState.returned_errors.product_stock"
                        class="text-red-700"
                        v-text="
                            String(productState.returned_errors.product_stock)
                        "
                    ></p>
                    <input
                        v-model="productState.product_stock"
                        class="bg-gray-800 text-gray-200 my-1 p-1 w-48 border border-1 border-indigo-500 outline-none"
                        type="text"
                        placeholder="Stock"
                    />
                </div>

                <div v-if="!editMode">
                    <button
                        ref="submitBtn"
                        @click="addProduct"
                        class="bg-green-600 p-2 shadow-2xl text-white hover:bg-green-500 transition-colors mt-2 rounded"
                    >
                        <i class="fas fa-plus-circle"></i> Add new product
                    </button>
                </div>
                <div v-if="editMode">
                    <button
                        ref="submitBtn"
                        @click="updateProduct"
                        class="bg-indigo-500 p-2 shadow-2xl text-white hover:bg-indigo-400 transition-colors mt-2 rounded"
                    >
                        <i class="fas fa-edit"></i> Update product
                    </button>
                    <button
                        ref="closeBtn"
                        @click="clearFields"
                        class="bg-gray-800 mx-2 p-2 shadow-2xl text-white border border-1 border-indigo-500 transition-colors mt-2 rounded"
                    >
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </Container>

            <!--All products listing  -->
            <div :class="['col-lg-8', modalVisibility ? 'blur-md' : '']">
                <div
                    class="w-full bg-gray-800 mt-4 mb-4 rounded shadow-2xl pt-2"
                >
                    <div
                        class="w-full h-8 text-center border-b-4 border-indigo-500 text-white text-lg pb-9 font-bold"
                    >
                        <i class="far fa-cube"></i> All products
                    </div>
                    <div class="container p-4">
                        <!-- Search Component -->
                        <div class="row mb-2">
                            <div class="col-11">
                                <input
                                    v-model="filterKeyword"
                                    @keyup="filterProducts(filterKeyword)"
                                    type="text"
                                    class="bg-transparent border-2 w-full text-white focus: outline-none border-none"
                                    placeholder="Search product"
                                    autofocus
                                />
                            </div>

                            <div class="col-1 text-left">
                                <i class="far fa-search text-gray-500"></i>
                            </div>
                        </div>
                        <!-- End Search Component -->
                        <div
                            v-for="product in productHandler.filteredProducts"
                            :key="product.id"
                            class="row border-2 border-indigo-500 rounded shadow-2xl h-20 p-2 my-4"
                        >
                            <div class="col-8">
                                <h1 class="text-white text-xl">
                                    {{ product.name }} - Rs.{{
                                        product.selling
                                    }}
                                </h1>
                                <h4
                                    class="bg-blue-500 text-white w-36 pl-1 mt-1 text-center"
                                >
                                    {{ product.stock }} in stock
                                </h4>
                            </div>
                            <div class="col-4 text-right">
                                <button
                                    @click="getAxiosProduct(product.id)"
                                    class="bg-green-600 p-2 shadow-2xl text-white hover:bg-green-500 transition-colors mt-2 rounded"
                                >
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button
                                    @click="
                                        () => {
                                            productHandler.deleteProductId =
                                                product.id;
                                            modalVisibility = true;
                                        }
                                    "
                                    class="ml-1 bg-red-700 p-2 shadow-2xl text-white hover:bg-red-600 transition-colors mt-2 rounded"
                                >
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--All products listing  -->
            <!-- Modal Component -->
            <Modal
                v-if="modalVisibility"
                title="Are you sure to delete?"
                class="z-10"
            >
                <button
                    @click="AxiosDeleteProduct"
                    class="mt-6 w-24 bg-green-600 p-2 text-white shadow-2xl transition-colors hover:bg-green-500"
                >
                    Yes
                </button>
                <button
                    @click="modalVisibility = false"
                    class="mt-6 w-24 bg-blue-500 p-2 text-white shadow-2xl transition-colors ml-1 hover:bg-blue-400"
                >
                    No
                </button>
            </Modal>
            <!-- End Modal Component -->
        </div>
    </div>
</template>

<script setup>
import Container from "../components/Container.vue";
import useUserStore from "../stores/user";
import Navbar from "../components/Navbar.vue";
import Modal from "../components/Modal.vue";
import { reactive, ref } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";

const toast = useToast();

const userStore = useUserStore();

//Filter products by keyword
const filterKeyword = ref("");

//Reference Submit Button
const submitBtn = ref();

//Reference Update cancel button
const closeBtn = ref();

//Reference Product Name
const focusProductName = ref();

//Edit mode on/off
const editMode = ref(false);

//config axios header token
const config = {
    headers: { Authorization: `Bearer ${userStore.userToken}` },
};

const productState = reactive({
    product_id: "",
    product_name: "",
    product_cost: "",
    product_markup: "",
    markup_type: "Markup by price",
    product_selling: "",
    product_stock: "",
    returned_errors: [],
});

const productHandler = reactive({
    products: "",
    filteredProducts: "",
    deleteProductId: "",
});

const modalVisibility = ref(false);

//Filter products
const filterProducts = (filterKeyword) => {
    productHandler.filteredProducts = [];
    const returnedResult = productHandler.products.filter(
        (element /**,index */) => {
            return element.name
                .toLowerCase()
                .includes(filterKeyword.toLowerCase());
        }
    );

    returnedResult.forEach((item) => {
        productHandler.filteredProducts.push(item);
    });
};

//calculate markup
const calculateMarkup = () => {
    if (productState.markup_type == "Markup by price") {
        productState.product_selling =
            productState.product_cost + productState.product_markup;
    } else {
        productState.product_selling =
            productState.product_cost +
            (productState.product_cost * productState.product_markup) / 100;
    }
};

//clearing input fields
const clearFields = () => {
    editMode.value = false;
    productState.product_id =
        productState.product_name =
        productState.product_cost =
        productState.product_markup =
        productState.product_selling =
        productState.product_stock =
            "";
    productState.returned_errors = [];
};

//Axios requests
const addProduct = async () => {
    submitBtn.value.disabled = true;
    submitBtn.value.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i> Please wait`;
    await axios
        .post("api/products", productState, config)
        .then(() => {
            clearFields();
            toast.success("Product Added Successfully", {
                timeout: 3000,
            });
        })
        .catch((error) => {
            productState.returned_errors = error.response.data.errors;
        });

    axiosGetProducts();
    submitBtn.value.disabled = false;
    submitBtn.value.innerHTML = `<i class="fas fa-plus-circle"></i> Add new product`;
    focusProductName.value.focus();
};

//Axios getting all products
const axiosGetProducts = () => {
    axios.get("api/products", config).then((response) => {
        productHandler.products = response.data;
        productHandler.filteredProducts = productHandler.products;
    });
};

//Axios get product data
const getAxiosProduct = async ($id) => {
    await axios.get(`api/products/${$id}`, config).then((response) => {
        productState.product_id = response.data.id;
        productState.product_name = response.data.name;
        productState.product_cost = response.data.cost;
        productState.product_selling = response.data.selling;
        productState.product_stock = response.data.stock;

        if (productState.markup_type == "Markup by price") {
            productState.product_markup =
                productState.product_selling - productState.product_cost;
        } else {
            const markup =
                productState.product_selling - productState.product_cost;
            productState.product_markup =
                (markup / productState.product_cost) * 100;
        }
    });

    editMode.value = true;
};

//Axios update product
const updateProduct = async () => {
    const productId = productState.product_id;

    submitBtn.value.disabled = true;
    closeBtn.value.disabled = true;
    submitBtn.value.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i> Please wait`;

    await axios
        .put(`api/products/${productId}`, productState, config)
        .then(() => {
            toast.success("Product Updated Successfully", {
                timeout: 3000,
            });
            clearFields();
            submitBtn.value.disabled = false;
            closeBtn.value.disabled = false;
            submitBtn.value.innerHTML = `<i class="fas fa-edit"></i> Update product`;
            editMode.value = false;
            axiosGetProducts();
            focusProductName.value.focus();
        })
        .catch((error) => {
            productState.returned_errors = error.response.data.errors;
            submitBtn.value.disabled = false;
            closeBtn.value.disabled = false;
            submitBtn.value.innerHTML = `<i class="fas fa-edit"></i> Update product`;
            focusProductName.value.focus();
        });
};

//Axios delete product
const AxiosDeleteProduct = () => {
    axios
        .delete(`api/products/${productHandler.deleteProductId}`, config)
        .then(() => {
            productHandler.deleteProductId = false;
            modalVisibility.value = false;
            toast.success("Product Deleted Successfully", {
                timeout: 3000,
            });
            axiosGetProducts();
        });
};

axiosGetProducts();
</script>
