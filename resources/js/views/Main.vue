<template>
    <Navbar :class="[modalVisibility ? 'blur-md' : '']" />
    <div class="container">
        <div class="row">
            <Container
                title="All Products"
                icon="far fa-cube"
                :class="[modalVisibility ? 'blur-md' : '']"
            >
                <!-- Search Component -->
                <div class="row mb-2">
                    <div class="col-11">
                        <input
                            @keyup="filterProducts(filterKeyword)"
                            v-model="filterKeyword"
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
                <!-- Loading effect -->
                <div ref="loadingEffect" class="row">
                    <div class="p-4 max-w-sm w-full mx-auto">
                        <div class="animate-pulse flex space-x-4">
                            <div
                                class="rounded-full bg-slate-700 h-10 w-10"
                            ></div>
                            <div class="flex-1 space-y-6 py-1">
                                <div class="h-2 bg-slate-700 rounded"></div>
                                <div class="space-y-3">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div
                                            class="h-2 bg-slate-700 rounded col-span-2"
                                        ></div>
                                        <div
                                            class="h-2 bg-slate-700 rounded col-span-1"
                                        ></div>
                                    </div>
                                    <div class="h-2 bg-slate-700 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading effect -->
                <!-- All Products Listing -->
                <div
                    v-for="product in productHandler.filteredProducts"
                    :key="product.id"
                    class="row border-2 border-indigo-500 rounded shadow-2xl h-20 p-2 my-4"
                >
                    <div class="col-8">
                        <h1 class="text-white text-xl">
                            {{ product.name }} - Rs.{{ product.selling }}
                        </h1>
                        <h4
                            class="bg-blue-500 text-white w-36 pl-1 mt-1 text-center"
                        >
                            {{ product.stock }} in stock
                        </h4>
                    </div>
                    <div class="col-4 text-right">
                        <button
                            @click="addProductToSale(product.id)"
                            class="bg-green-600 p-2 shadow-2xl text-white hover:bg-green-500 transition-colors mt-2"
                        >
                            <i class="fas fa-plus-circle"></i> Add
                        </button>
                    </div>
                </div>
                <!-- End All Products Listing -->
            </Container>
            <!-- End all Products container -->
            <Container
                title="Ongoing Sale"
                icon="far fa-rss"
                :class="[modalVisibility ? 'blur-md' : '']"
            >
                <table class="min-w-full">
                    <tbody>
                        <tr
                            v-for="addedProduct in productHandler.addedProducts"
                            :key="addedProduct.id"
                            class="border-b bg-gray-800 boder-gray-900"
                        >
                            <td
                                class="text-sm text-white font-medium px-6 py-4 whitespace-nowrap"
                            >
                                {{ addedProduct.name }}
                            </td>
                            <td
                                class="text-sm text-white font-medium px-6 py-4 whitespace-nowrap"
                            >
                                {{ addedProduct.stock }} x Rs.{{
                                    addedProduct.exactPrice
                                }}
                            </td>

                            <td
                                class="text-sm text-white font-medium px-6 py-4 whitespace-nowrap"
                            >
                                Rs. {{ addedProduct.selling }}
                            </td>
                            <td>
                                <button
                                    @click="
                                        reduceFromAddedProducts(addedProduct.id)
                                    "
                                    class="text-green-600 rounded"
                                >
                                    <i class="fas fa-minus-circle"></i>
                                </button>
                            </td>
                            <td>
                                <button
                                    @click="
                                        () => {
                                            productHandler.deleteProductId =
                                                addedProduct.id;
                                            modalVisibility = true;
                                        }
                                    "
                                    class="text-red-600 rounded ml-1"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </Container>
            <!-- End all Products container -->
            <Container
                title="Amount"
                icon="far fa-shopping-cart"
                :class="[modalVisibility ? 'blur-md' : '']"
            >
                <div class="text-center text-gray-200">
                    <h1 class="text-4xl">Total - {{ productHandler.total }}</h1>
                    <h1 class="text-2xl mt-3 text-gray-200">
                        Sub total - {{ productHandler.subtotal }}
                    </h1>
                    <button
                        @click="
                            () => {
                                productHandler.allowDiscount = true;
                                discountInput.focus();
                            }
                        "
                        class="bg-blue-500 mt-4 w-36 h-10 rounded text-gray-200"
                    >
                        <i class="fas fa-tags"></i> Add a discount
                    </button>
                    <div v-if="productHandler.allowDiscount">
                        <div class="my-2">
                            <button
                                @click="
                                    () => {
                                        productHandler.discountType = 'price';
                                        calculateDiscount();
                                        discountInput.focus();
                                    }
                                "
                                :class="[
                                    'mx-2 border border-1 border-indigo-500 w-6 rounded',
                                    productHandler.discountType == 'price'
                                        ? 'bg-indigo-500'
                                        : '',
                                ]"
                            >
                                <i class="fas fa-dollar-sign"></i>
                            </button>
                            <button
                                @click="
                                    () => {
                                        productHandler.discountType =
                                            'percentage';
                                        calculateDiscount();
                                        discountInput.focus();
                                    }
                                "
                                :class="[
                                    'mx-2 border border-1 border-indigo-500 w-6 rounded',
                                    productHandler.discountType == 'percentage'
                                        ? 'bg-indigo-500'
                                        : '',
                                ]"
                            >
                                <i class="fas fa-percentage"></i>
                            </button>
                        </div>
                        <div>
                            <input
                                ref="discountInput"
                                v-model="productHandler.discount"
                                @keyup="calculateDiscount"
                                class="mt-2 w-20 bg-gray-800 outline-none border border-1 border-indigo-500 text-gray-200"
                                type="text"
                            />
                            <button
                                @click="productHandler.allowDiscount = false"
                                class="mx-2 bg-red-700 w-6"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </Container>
            <!-- End total container container -->
            <!-- Delete Modal Component -->
            <Modal
                v-if="modalVisibility"
                title="Are you sure to delete?"
                class="flex z-10 h-screen fixed w-full justify-center items-start"
            >
                <button
                    @click="removeFromAdded"
                    class="mt-6 w-24 bg-green-600 p-2 text-white shadow-2xl transition-colors hover:bg-green-500"
                >
                    Yes
                </button>
                <button
                    @click="
                        () => {
                            modalVisibility = false;
                            productHandler.deleteProductId = null;
                        }
                    "
                    class="mt-6 w-24 bg-blue-500 p-2 text-white shadow-2xl transition-colors ml-1 hover:bg-blue-400"
                >
                    No
                </button>
            </Modal>
            <!-- End Delete Modal Component -->
        </div>
    </div>
</template>

<script setup>
import useUserStore from "../stores/user";
import Container from "../components/Container.vue";
import Navbar from "../components/Navbar.vue";
import Modal from "../components/Modal.vue";
import { reactive, ref } from "vue";

const userStore = useUserStore();

const productHandler = reactive({
    products: [],
    filteredProducts: [],
    addedProducts: [],
    deleteProductId: null,
    subtotal: 0,
    total: 0,
    allowDiscount: false,
    discount: 0,
    discountType: "price",
});

const filterKeyword = ref("");

const loadingEffect = ref();

const modalVisibility = ref(false);

//Reference discount input
const discountInput = ref();

//config axios header token
const config = {
    headers: { Authorization: `Bearer ${userStore.userToken}` },
};

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

//Add product to ongoing sale
const addProductToSale = (productId) => {
    const findInAddedProducts = productHandler.addedProducts.find(
        (element) => productId === element.id
    );

    const findInProducts = productHandler.products.find(
        (element) => productId === element.id
    );

    if (findInAddedProducts) {
        if (findInProducts.stock < 0 || findInProducts.stock == 0) {
            alert("No stock left");
        } else {
            findInProducts.stock -= 1;
            findInAddedProducts.stock += 1;
            findInAddedProducts.selling =
                findInAddedProducts.stock * findInProducts.selling;
        }
    } else {
        if (findInProducts.stock > 0) {
            findInProducts.stock -= 1;
            const clonnedArray = JSON.parse(JSON.stringify(findInProducts));
            clonnedArray.stock = 1;
            clonnedArray.exactPrice = findInProducts.selling;
            productHandler.addedProducts.push(clonnedArray);
        } else {
            alert("No stock left");
        }
    }

    calculateTotal();
};

//Reduce from added products
const reduceFromAddedProducts = (productId) => {
    const findInAddedProducts = productHandler.addedProducts.find(
        (element) => productId === element.id
    );
    const findInProducts = productHandler.products.find(
        (element) => productId === element.id
    );
    const indexOfAddedProduct = productHandler.addedProducts.findIndex(
        (element) => {
            return productId === element.id;
        }
    );
    if (findInAddedProducts.stock === 1) {
        productHandler.addedProducts.splice(indexOfAddedProduct, 1);
        findInProducts.stock += 1;
    } else {
        findInAddedProducts.stock -= 1;
        findInAddedProducts.selling =
            findInAddedProducts.selling - findInAddedProducts.exactPrice;
        findInProducts.stock += 1;
    }

    calculateTotal();
};

//Remove product from ongoing sale
const removeFromAdded = () => {
    const productId = productHandler.deleteProductId;
    const findInAddedProducts = productHandler.addedProducts.find(
        (element) => productId === element.id
    );

    const findInProducts = productHandler.products.find(
        (element) => productId === element.id
    );

    const indexOfAddedProduct = productHandler.addedProducts.findIndex(
        (element) => {
            return productId === element.id;
        }
    );

    findInProducts.stock = findInProducts.stock + findInAddedProducts.stock;

    productHandler.addedProducts.splice(indexOfAddedProduct, 1);
    modalVisibility.value = false;
    productHandler.deleteProductId = null;

    calculateTotal();
};

//Calculate total
const calculateTotal = () => {
    let total = 0;
    productHandler.addedProducts.forEach((element) => {
        total = total + element.selling;
    });

    productHandler.total == 0 || productHandler.addedProducts.length < 1
        ? (productHandler.discount = 0)
        : false;

    productHandler.subtotal = total;
    productHandler.total = total;
    calculateDiscount();
};

//Calculate discount
const calculateDiscount = () => {
    if (productHandler.discountType == "price") {
        productHandler.total =
            productHandler.subtotal - productHandler.discount;
    } else {
        let discount =
            productHandler.subtotal * (productHandler.discount / 100);
        productHandler.total = productHandler.subtotal - discount;
    }
};

//Axios getting all products
const axiosGetProducts = async () => {
    await axios.get("api/products", config).then((response) => {
        productHandler.products = response.data;
        productHandler.filteredProducts = productHandler.products;
        loadingEffect.value.style.display = "none";
    });
};

axiosGetProducts();
</script>
