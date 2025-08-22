<template>
    <Layout>
        <PageHeader :title="titleHeader || titlePluralize"/>
        <BCard no-body>
            <!-- Componente de filtro -->
            <Filter
                :session="session"
                :title="title"
                :url="url"
                :filter="filter"
                :title-pluralize="titlePluralize"
                @new-view="changeViewType"
                @update-show-card="updateShowCard"
                @filter-applied="loadList"
                :endpoint="endpoint"
            />

            <!-- Visualização em tabela -->
            <BCardBody>
                <div class="table-responsive table-card">
                    <table
                        id="table"
                        class="table align-middle mb-0 table-striped table-hover"
                    >
                        <thead>
                        <tr>
                            <!--                            Gerar as colunas do cabeçalho das tables-->
                            <th
                                v-for="(thead, index) in table"
                                :key="'th-table-list' + index"
                                :class="{'text-center' : (index > 0 && table[0].column !== 'check' && table[0].column !== 'ID') || (index > 1 && ((table[0].column === 'check' || table[0].column === 'ID')))}"
                                :style="`width: ${thead.column === 'check' || thead.column === 'ID' ? '1%' : 'auto'}`"
                                class="text-nowrap"
                            >
                                <input
                                    v-if="thead.column === 'check'"
                                    id="selectAll"
                                    type="checkbox"
                                    class="form-check-input"
                                    @change="selectAll"
                                >
                                <span v-else>{{ thead.column }}</span>
                                <span style="white-space: nowrap">
                                    <i
                                        v-if="thead.order"
                                        class="las la-arrow-down pointer"
                                        :class="thead.order === order_by && order ==='asc' ? 'active' : ''"
                                        @click="newOrder(thead.order, 'asc')"
                                    />
                                    <i
                                        v-if="thead.order"
                                        class="las la-arrow-up pointer"
                                        :class="thead.order === order_by && order ==='desc' ? 'active' : ''"
                                        @click="newOrder(thead.order, 'desc')"
                                    />
                                </span>
                            </th>
                        </tr>
                        </thead>

                        <!--                        Se encontrar resultados da api gerar as linhas-->
                        <tbody
                            v-if="listAll && listAll.length > 0"
                            style="overflow-y: hidden"
                        >
                        <tr
                            v-for="line in listAll"
                            :id="'line' + line.id"
                            :key="'tr-table-list' + line.id"
                        >
                            <td
                                v-for="(k, tdIndex) in keys"
                                :key="'td-table-list' + tdIndex"
                                :class="{'text-center' : (tdIndex > 0 && table[0].column !== 'check' && table[0].column !== 'ID') || (tdIndex > 1 && ((table[0].column === 'check' || table[0].column === 'ID'))),
                                        'text-nowrap': k === 'actions'
                            }"
                            >
                                <slot
                                    :name="k"
                                    :value="line"
                                >
                                    {{ k.indexOf('.') > -1 ? getNestedValue(line, k) : (line[k] || 'N/A') }}
                                </slot>
                            </td>
                        </tr>
                        </tbody>

                        <!--                        Se não mensagem de nada encontrado-->
                        <tbody v-else-if="listAll.length === 0">
                        <tr>
                            <td
                                :colspan="Object.keys(table).length"
                                class="text-center"
                            >
                                Nenhum resultado
                                encontrado
                            </td>
                        </tr>
                        </tbody>
                    </table>

<!--                    <span-->
<!--                        id="spinnerTable"-->
<!--                        class="spinner spinner-border flex-shrink-0 mx-auto"-->
<!--                        role="status"-->
<!--                    >-->
<!--                        <span class="visually-hidden"/>-->
<!--                    </span>-->
                </div>
            </BCardBody>


            <BCardFooter class="border-top-0">
                <div class="align-items-center mt-xl-3 justify-content-start d-lg-flex">
                    <div class="align-items-center d-flex text-muted mb-2 mb-lg-0">
                        <div class="me-2">
                            <span>Exibir</span>
                        </div>
                        <div class="col-auto">
                            <select
                                id="limitFilter"
                                class="form-control form-select"
                                v-model="limit"
                                @change="setLimit"
                            >
                                <option :value="25">
                                    25
                                </option>
                                <option :value="50">
                                    50
                                </option>
                                <option :value="100">
                                    100
                                </option>
                                <option :value="250">
                                    250
                                </option>
                            </select>
                        </div>
                        <div class="ms-2">
                            <span> registros</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 me-lg-auto ms-lg-4 mb-2 mb-lg-0">
                        <div
                            v-if="start >= 0"
                            class="text-muted"
                        >
                            Exibindo de
                            <span class="fw-semibold">{{ start }}</span>
                            a
                            <span
                                class="fw-semibold"
                            >{{
                                    partial
                             }}
                            </span>
                            de
                            <span class="fw-semibold">{{ total }}</span>
                            resultados
                        </div>
                    </div>


                    <Pagination
                        ref="paginationComponent"
                        :page="page"
                        :total-pages="pages"
                        :session="props.session"
                        @load-list="loadList"
                    />
                </div>
            </BCardFooter>
        </BCard>
    </Layout>
</template>

<script setup>
import {endLoadTable, getUrl, loadTable} from "@/composables/functions";
import {setSessions} from "@/composables/setSessions";
import {onMounted, ref, defineProps, defineEmits} from "vue";

import Layout from "@/components/layouts/main.vue";
import Pagination from "@/components/base/Pagination.vue";
import PageHeader from "@/components/layouts/page-header.vue";
import Filter from "@/components/base/Filter.vue";

const props = defineProps({
    service: {
        required: true,
        type: Object
    },
    session: {
        required: true,
        type: String
    },
    endpoint: {
        required: true,
        type: String
    },
    url: {
        required: true,
        type: String
    },
    title: {
        required: true,
        type: String,
    },
    titleHeader: {
        required: false,
        type: String,
        default: ''
    },
    titlePluralize: {
        required: true,
        type: String
    },
    table: {
        required: true,
        type: Array
    },
    keys: {
        required: true,
        type: Array
    },
    filter: {
        required: true,
        type: Array,
    },
})

const emits = defineEmits(['update-show-card']);
const apiStore = {};

const order = ref(null);
const order_by = ref(null);

const listAll = ref([])
const start = ref(0)
const partial = ref(0)
const total = ref(0)
const page = ref(0)
const pages = ref(0)
const limit = ref(25)

function setOrders() {
    const objData = JSON.parse(localStorage.getItem(props.session))

    if (!objData) {
        setTimeout(() => {
            setOrders();
        }, 300)
    } else {
        order.value = objData.params.order;
        order_by.value = objData.params.order_by;
    }
}

function getNestedValue(obj, path) {
    return path
        .split('.')
        .reduce((acc, key) => (acc && acc[key] !== undefined ? acc[key] : undefined), obj) || 'N/A';
}

function newOrder(new_order_by, new_order) {
    let obj = JSON.parse(localStorage.getItem(props.session))

    obj.params.order = new_order;
    order.value = new_order;

    obj.params.order_by = new_order_by;
    order_by.value = new_order_by;

    localStorage.setItem(props.session, JSON.stringify(obj));

    loadList()
}

// Busca dos dados da tabela na store
async function loadList() {
    if (props.session) {
        setSessions(props.session);
        const data = await props.service.index(props.session);

        total.value = data.count;
        pages.value = data.pages;
        page.value = data.page;
        limit.value = parseInt(data.per_page);
        start.value = total.value === 0 ? 0 : ((page.value - 1) * limit.value) + 1;
        partial.value = total.value === 0 ? 0 : ((page.value - 1) * limit.value) + data.data.length;
        listAll.value = data.data;

        endLoadTable();
    } else {
        setTimeout(() => {
            loadList();
        }, 300)
    }
}

// Settar novo valor de limit na localStorage e chamando nova busca na store
function setLimit() {
    let obj = JSON.parse(localStorage.getItem(props.session));
    obj.params.page = 1;
    obj.params.limit = limit.value;
    localStorage.setItem(props.session, JSON.stringify(obj));
    loadTable();

    loadList()

    const elemento = document.getElementById('limitFilter');
    const altura = elemento.offsetHeight;
    window.scrollTo(0, altura + 50);
}

function selectAll() {
    // Seleciona o elemento <tbody>
    const tbody = document.querySelector("tbody");
    const check = document.getElementById('selectAll').checked;

// Seleciona todos os checkboxes dentro do <tbody>
    const checkboxes = tbody.querySelectorAll('input[type="checkbox"]');

// Itera sobre os checkboxes e faz algo com cada um deles (exemplo: logar no console)
    checkboxes.forEach(checkbox => {
        checkbox.checked = check; // Retorna se está marcado ou não
    });

    emits('update-show-card', check)
}


onMounted(() => {
    loadList();
    setOrders();
})

</script>

<style>

th i {
    opacity: .4 !important;
}

th i.active {
    opacity: 1 !important;
}

.icon-list {
    border: solid 1px #C6C6C6;
    border-radius: 5px;
}


</style>