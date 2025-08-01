<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

// CORREÇÃO: O prop 'aprs' agora é um Objeto de paginação, não mais um simples Array.
const props = defineProps({
    aprs: Object,
});

// Variáveis reativas para controlar o modal de confirmação
const confirmingAprDeletion = ref(false);
const aprToDelete = ref(null);

// Formulário Inertia para a exclusão
const form = useForm({});

// Função para abrir o modal e guardar a APR a ser excluída
const confirmDeletion = (apr) => {
    aprToDelete.value = apr;
    confirmingAprDeletion.value = true;
};

// Função para fechar o modal
const closeModal = () => {
    confirmingAprDeletion.value = false;
    aprToDelete.value = null;
};

// Função que efetivamente envia a requisição de exclusão
const deleteApr = () => {
    form.delete(route('aprs.destroy', aprToDelete.value.id), {
        onSuccess: () => closeModal(),
    });
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('pt-BR', options);
};
</script>

<template>
    <Head title="APR's" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Análises Preliminares de Risco (APR)</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <!-- Botão para criar nova APR -->
                        <div class="mb-4">
                            <Link :href="route('aprs.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Criar Nova APR
                            </Link>
                        </div>

                        <!-- Tabela para listar as APRs -->
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Nº APR</th>
                                        <th scope="col" class="px-6 py-3">Atividade</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Data de Início</th>
                                        <th scope="col" class="px-6 py-3">
                                            <span class="sr-only">Ações</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- CORREÇÃO: Checa se aprs.data (a lista de itens) está vazia -->
                                    <tr v-if="aprs.data.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Nenhuma APR encontrada.
                                        </td>
                                    </tr>
                                    <!-- CORREÇÃO: Itera sobre aprs.data, que contém a lista de itens da página atual -->
                                    <tr v-for="apr in aprs.data" :key="apr.id" class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ apr.numero_apr }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ apr.atividade_descricao }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                  :class="{
                                                    'bg-green-100 text-green-800': apr.status === 'Aprovada',
                                                    'bg-yellow-100 text-yellow-800': apr.status === 'Em Elaboração',
                                                    'bg-red-100 text-red-800': apr.status === 'Encerrada'
                                                  }">
                                                {{ apr.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ formatDate(apr.inicio_at) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <Link :href="route('aprs.show', apr.id)" class="font-medium text-green-600 hover:underline">Ver <br></Link>
                                            <Link :href="route('aprs.edit', apr.id)" class="font-medium text-blue-600 hover:underline"> Editar<br></Link>
                                            <!--<a :href="route('aprs.pdf', apr.id)" target="_blank" class="font-medium text-purple-600 hover:underline">PDF</a> -->
                                            <!--<a :href="route('aprs.excel', apr.id)" class="font-medium text-teal-600 hover:underline">Excel</a> -->
                                            <button @click="confirmDeletion(apr)" class="font-medium text-red-600 hover:underline">Excluir</button>                                  
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- NOVO: Links de Paginação -->
                        <div v-if="aprs.links.length > 3" class="mt-4 flex justify-between items-center">
                            <div class="flex space-x-1">
                                <Link
                                    v-for="(link, index) in aprs.links"
                                    :key="index"
                                    :href="link.url"
                                    v-html="link.label"
                                    class="px-3 py-2 text-sm leading-4 rounded-md"
                                    :class="{ 'bg-blue-500 text-white': link.active, 'text-gray-700 hover:bg-gray-200': !link.active }"
                                    preserve-scroll
                                />
                            </div>
                            <div class="text-sm text-gray-500">
                                Mostrando {{ aprs.from }} a {{ aprs.to }} de {{ aprs.total }} resultados
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

         <!-- Modal de Confirmação de Exclusão -->
            <Modal :show="confirmingAprDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Tem a certeza de que deseja excluir esta APR?
                    </h2>
                    <p class="mt-1 text-sm text-gray-600" v-if="aprToDelete">
                        Está prestes a excluir permanentemente a APR nº <strong>{{ aprToDelete.numero_apr }}</strong>. Esta ação não pode ser desfeita.
                    </p>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
                        <DangerButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="deleteApr">
                            Excluir APR
                        </DangerButton>
                    </div>
                </div>
            </Modal>
    </AuthenticatedLayout>
</template>